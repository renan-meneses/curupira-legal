<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesUsers;

final class CreateNewUser implements CreatesUsers
{
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:180'],
            'email' => ['required', 'string', 'email:rfc', 'max:180', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:12'],
        ])->validate();

        return User::query()->create([
            'name' => $input['name'],
            'email' => mb_strtolower($input['email']),
            'password' => Hash::make($input['password']),
        ]);
    }
}
