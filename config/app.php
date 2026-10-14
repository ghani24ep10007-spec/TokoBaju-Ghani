<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name' => env('APP_NAME', 'Toko Baju Ghani'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', true),

    'url' => env('APP_URL', 'http://localhost:8000'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'Asia/Jakarta',

    'locale' => 'id',

    'fallback_locale' => 'en',

    'faker_locale' => 'id_ID',

    'key' => env('APP_KEY', 'base64:3uJm/QvC5qQ7v1v4J1Y6oE9N+Z8+r2t/fXzK6f2x9lM='),

    'cipher' => 'AES-256-CBC',

    'maintenance' => [
        'driver' => 'file',
    ],

    'providers' => ServiceProvider::defaultProviders()->toArray(),

    'aliases' => Facade::defaultAliases()->toArray(),

];
