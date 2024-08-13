<?php

namespace App\Rules;

use App\Models\Specialty;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ActiveSpecialty implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $specialty = Specialty::where('id', $value)->where('is_active', 1)->exists();

        if (!$specialty) {
            $fail('Specialty is not active.');
        }
    }
}
