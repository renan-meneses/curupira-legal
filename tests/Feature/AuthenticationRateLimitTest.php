<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

uses(RefreshDatabase::class);

it('rate limits repeated invalid login attempts', function (): void {
    User::query()->create(['name' => 'Login', 'email' => 'login@example.com', 'password' => Hash::make('password-password')]);
    RateLimiter::clear('login@example.com|127.0.0.1');

    for ($attempt = 1; $attempt <= 5; $attempt++) {
        $this->from('/login')->post('/login', ['email' => 'login@example.com', 'password' => 'senha-incorreta'])->assertRedirect('/login');
    }

    $this->from('/login')->post('/login', ['email' => 'login@example.com', 'password' => 'senha-incorreta'])->assertStatus(429);
});
