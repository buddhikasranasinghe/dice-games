<?php

namespace Domain\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SignUpAction
{
    public function execute(SignUpCommand $command): User
    {
        $user = new User;

        $user->forceFill([
            'id' => Str::uuid(),
            'name' => $command->getRandomName(),
            'email' => $command->email,
            'password' => Hash::make($command->password),
            'status' => $command->status
        ]);

        $user->save();
        
        return $user;
    }
}
