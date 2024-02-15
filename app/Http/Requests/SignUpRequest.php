<?php

namespace App\Http\Requests;

use Domain\User\SignUpCommand;

class SignUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'email'
            ],
            'password' => [
                'required',
                'min:6'
            ],
            'password_confirmation' => [
                'required',
                'same:password'
            ]
        ];
    }

    public function command(): SignUpCommand
    {
        $command = new SignUpCommand;
        $command->email = $this->input('username');
        $command->password = $this->input('password');

        return $command;
    }
}
