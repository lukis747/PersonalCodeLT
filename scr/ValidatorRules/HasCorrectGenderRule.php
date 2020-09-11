<?php


namespace Lukjon\PersonalCodeLT\ValidatorRules;

use Illuminate\Contracts\Validation\Rule;

class HasCorrectGenderRule implements Rule
{

    public function passes($attribute, $value)
    {
        $personalCodeNumbersArray = array_map('intval', str_split($value));
        $allowedGenderNumbers = [1,2,3,4,5,6];
        return in_array($personalCodeNumbersArray[0],$allowedGenderNumbers);
    }

    public function message()
    {
        return "Nurodyta neteisinga asmens lytis";
    }
}
