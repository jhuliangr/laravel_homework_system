<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Mollie\Api\Http\Data\Money;
use Mollie\Api\Http\Requests\CreatePaymentRequest;
use Mollie\Laravel\Facades\Mollie;
use Request;

class PremiumUserController extends Controller
{
    public function preparePayment(Request $request)
    {
        $exposed_url = env("APP_EXPOSED_URL", "http://homework.test/");
        $amount_cents = 2 * 100;

        $webhook_url = $exposed_url . "api/mollie/webhook";

        $user = auth()->user();

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'amount_cents' => $amount_cents,
        ]);

        $request = new CreatePaymentRequest(
            description: 'Premium user for ' . auth()->user()->name,
            amount: new Money(currency: 'EUR', value: number_format($amount_cents / 100, 2, '.', '')),
            redirectUrl: $exposed_url . "dashboard",
            webhookUrl: $webhook_url,
            metadata: [
                "order_id" => "# $purchase->id",
                "customer_info" => [
                    'user_id' => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                ]
            ]
        );

        $payment = Mollie::send($request);

        $purchase->mollie_payment_id = $payment->id;
        $purchase->save();

        return redirect($payment->getCheckoutUrl(), 303);
    }
    //
}