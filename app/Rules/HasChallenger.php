<?php

namespace App\Rules;

use Closure;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;

class HasChallenger implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $challengers = User::query()->whereKey($value)->get();

        if (!$challengers->count() > 0) {
            $fail('The challenger is not exist.');
        }
    }
}
