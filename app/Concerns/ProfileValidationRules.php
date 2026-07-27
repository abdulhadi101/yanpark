<?php

namespace App\Concerns;

trait ProfileValidationRules
{
    protected function profileRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }
}
