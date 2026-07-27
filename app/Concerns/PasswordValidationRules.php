<?php

namespace App\Concerns;

trait PasswordValidationRules
{
    protected function passwordRules(): array
    {
        return ['required', 'string', 'min:8', 'confirmed'];
    }
}
