<?php

return ['default' => env('LOG_CHANNEL', 'stack'), 'deprecations' => ['channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'), 'trace' => false], 'channels' => ['stack' => ['driver' => 'stack', 'channels' => ['daily'], 'ignore_exceptions' => false], 'daily' => ['driver' => 'daily', 'path' => storage_path('logs/laravel.log'), 'days' => 14, 'replace_placeholders' => true], 'null' => ['driver' => 'monolog', 'handler' => Monolog\Handler\NullHandler::class], 'emergency' => ['path' => storage_path('logs/laravel.log')]]];
