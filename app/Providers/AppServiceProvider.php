<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        $iniPath = base_path('.php_bin/php.ini');
        if (file_exists($iniPath)) {
            putenv("PHPRC={$iniPath}");
            $_ENV['PHPRC'] = $iniPath;
            $_SERVER['PHPRC'] = $iniPath;
        }

        if (class_exists(\Illuminate\Foundation\Console\ServeCommand::class)) {
            \Illuminate\Foundation\Console\ServeCommand::$passthroughVariables[] = 'PHPRC';
        }
    }
}
