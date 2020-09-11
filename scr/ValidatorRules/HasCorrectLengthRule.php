<?php


namespace Lukjon\PersonalCodeLT\ValidatorRules;

use Illuminate\Contracts\Validation\Rule;

class HasCorrectLengthRule implements Rule
{

    public function passes($attribute, $value)
    {
        return strlen($value) === 11;
    }

    public function message()
    {
        return "Asmens kodas yra netinkamo ilgio.";
    }
}
