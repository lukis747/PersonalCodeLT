<?php


namespace Lukjon\PersonalCodeLT\ValidatorRules;

use Illuminate\Contracts\Validation\Rule;
use Lukjon\PersonalCodeLT\PersonalCodeLT;

class HasCorrectControlSumRule implements Rule
{

    public function passes($attribute, $value)
    {
        $personalCodeNumbersArray = array_map('intval', str_split($value));
        return $personalCodeNumbersArray[10] === PersonalCodeLT::calculateControlSum($personalCodeNumbersArray);
    }

    public function message()
    {
        return "Asmens kodo kontrolinė suma nesutampa.";
    }
}
