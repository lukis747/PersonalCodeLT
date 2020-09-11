<?php


namespace Lukjon\PersonalCodeLT;


use Illuminate\Support\Facades\Validator;
use Lukjon\PersonalCodeLT\ValidatorRules\HasCorrectControlSumRule;
use Lukjon\PersonalCodeLT\ValidatorRules\HasCorrectGenderRule;
use Lukjon\PersonalCodeLT\ValidatorRules\HasCorrectLengthRule;

class PersonalCodeLT
{
    private $personalCode;

    /**
     * PersonalCodeLT constructor.
     * @param $personalCode
     */
    public function __construct($personalCode)
    {
        $this->personalCode = array_map('intval', str_split($personalCode));;
    }

    public function validate($personalCode)
    {
        $validator = Validator::make(
            ['personalCode' => $personalCode],
            ['personalCode' => [new HasCorrectLengthRule,new HasCorrectGenderRule,new HasCorrectControlSumRule]]);

        return $validator->fails() ? false : true;
    }
    public function extractInformation()
    {
        return [
            'personalCode' => implode($this->personalCode),
            'gender' => $this->getGender(),
            'birthdate' => $this->getBirthdate(),
            'age' => $this->getAge(),
            'personalCodeCheckResult' => $this->validate(implode($this->personalCode)),
            'personalCodeHasCorrectControlSum' => $this->hasCorrectControlNumber($this->personalCode)
        ];
    }
    public function getGender()
    {
        return ($this->personalCode[0] % 2) == 0 ? 'moteris' : 'vyras';
    }
    public function getAge()
    {
        $today = strtotime(now());
        $birthday = strtotime($this->getBirthdate());

        $diff = abs($today-$birthday);
        $years = floor($diff / (365*60*60*24));

        return $years;
    }
    public function getBirthdate()
    {
        $birthdate = [];

        switch ($this->personalCode[0])
        {
            case 1:
                //men born in 19 century
                $birthdate = [1,8];
                break;
            case 2:
                //women born in 19 century
                $birthdate = [1,8];
                break;
            case 3:
                //men born in 20 century
                $birthdate = [1,9];
                break;
            case 4:
                //women born in 20 century
                $birthdate = [1,9];
                break;
            case 5:
                //men born in 21 century
                $birthdate = [2,0];
                break;
            case 6:
                //women born in 21 century
                $birthdate = [2,0];
                break;
        }
        $dataFromPersonalCode  = array_slice($this->personalCode,1,6);
        $birthdate = array_merge($birthdate,$dataFromPersonalCode);
        $birthdate = strtotime(implode($birthdate));
        $birthdate = date('Y-m-d',$birthdate);
        return $birthdate;

    }
    public static function calculateControlSum($personalCodeNumbersArray):int
    {
        $sum = $personalCodeNumbersArray[0]*1 +
            $personalCodeNumbersArray[1]*2 +
            $personalCodeNumbersArray[2]*3 +
            $personalCodeNumbersArray[3]*4 +
            $personalCodeNumbersArray[4]*5 +
            $personalCodeNumbersArray[5]*6 +
            $personalCodeNumbersArray[6]*7 +
            $personalCodeNumbersArray[7]*8 +
            $personalCodeNumbersArray[8]*9 +
            $personalCodeNumbersArray[9]*1;

        $liekana = $sum % 11;
        if ($liekana != 10)
        {
            return $liekana;
        }else{
            $sum = $personalCodeNumbersArray[0]*3 +
                $personalCodeNumbersArray[1]*4 +
                $personalCodeNumbersArray[2]*5 +
                $personalCodeNumbersArray[3]*6 +
                $personalCodeNumbersArray[4]*7 +
                $personalCodeNumbersArray[5]*8 +
                $personalCodeNumbersArray[6]*9 +
                $personalCodeNumbersArray[7]*1 +
                $personalCodeNumbersArray[8]*2 +
                $personalCodeNumbersArray[9]*3;

            $liekana = $sum % 11;

            if ($liekana != 10)
            {
                return  $liekana;
            }else{
                return 0;
            }
        }
    }

    public function hasCorrectControlNumber($personalCodeNumbersArray)
    {
        return $personalCodeNumbersArray[10] === PersonalCodeLT::calculateControlSum($personalCodeNumbersArray);
    }
    public function getPersonalCode()
    {
        return implode($this->personalCode);
    }
    public function getPersonalCodeArray()
    {
        return $this->personalCode;
    }

}
