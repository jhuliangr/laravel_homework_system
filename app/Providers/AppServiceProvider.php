<?php

namespace App\Providers;

use Google\Exception;
use Illuminate\Support\ServiceProvider;
use Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadGoogleStorageDriver('google');
        $this->loadGoogleStorageDriver('google_admin');
    }
    private function loadGoogleStorageDriver(string $driverName = 'google')
    {
        try {
            Storage::extend($driverName, function ($app, $config) {
                $options = [];

                $client = new \Google\Client();
                $client->setClientId($config['clientId']);
                $client->setClientSecret($config['clientSecret']);
                $client->refreshToken($config['refreshToken']);

                $service = new \Google\Service\Drive($client);
                $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service,  '/', $options);
                $driver = new \League\Flysystem\Filesystem($adapter);

                return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
            });
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
        }
    }
}
