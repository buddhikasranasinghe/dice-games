<?php

namespace Domain\User;

use Illuminate\Support\Str;

class SignUpCommand
{
    public string $email;
    public string $password;
    public string $status = 'active';

    public function getRandomName(): string
    {
        return Str::random(5);
    }
}
