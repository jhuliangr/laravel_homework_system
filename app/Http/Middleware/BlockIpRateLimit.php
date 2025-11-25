<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class BlockIpRateLimit
{
    public function handle(Request $request, Closure $next, $profile = 'default'): Response
    {
        $ip = $request->ip();
        $config = config("rate_limit.{$profile}", config('rate_limit.default'));

        // Verificar whitelist
        if (in_array($ip, $config['whitelist'] ?? [])) {
            return $next($request);
        }

        // Verificar blacklist
        if (in_array($ip, $config['blacklist'] ?? [])) {
            return response()->json([
                'error' => 'Acceso denegado.'
            ], 403);
        }

        $maxAttempts = $config['max_attempts'];
        $decayMinutes = $config['decay_minutes'];
        $blockMinutes = $config['block_minutes'];

        $rateLimitKey = "rate_limit:{$ip}:{$profile}";
        $blockedKey = "blocked_ip:{$ip}:{$profile}";

        if (Cache::has($blockedKey)) {
            return response()->json([
                'error' => 'Demasiadas solicitudes. IP temporalmente bloqueada.',
                'retry_after' => Cache::get($blockedKey) - time()
            ], 429);
        }

        $attempts = Cache::get($rateLimitKey, 0);

        if ($attempts >= $maxAttempts) {
            $blockUntil = time() + ($blockMinutes * 60);
            Cache::put($blockedKey, $blockUntil, $blockMinutes * 60);
            Cache::forget($rateLimitKey);

            // Opcional: Log del bloqueo
            \Log::warning("IP bloqueada por rate limit: {$ip}");

            return response()->json([
                'error' => 'Demasiadas solicitudes. IP bloqueada temporalmente.',
                'retry_after' => $blockMinutes * 60
            ], 429);
        }

        Cache::put($rateLimitKey, $attempts + 1, $decayMinutes * 60);

        $response = $next($request);

        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => max(0, $maxAttempts - ($attempts + 1)),
            'X-RateLimit-Reset' => time() + ($decayMinutes * 60)
        ]);

        return $response;
    }
}