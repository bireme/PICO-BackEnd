<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use PICOExplorer\Services\AdvancedLogger\Exceptions\SpecialValidationException;
use PICOExplorer\Services\AdvancedLogger\Exceptions\ErrorInSpecialValidationService;
use Throwable;
use Exception;

class SpecialValidator
{

    public function SpecialValidate(array $data, array $ruleCasts, string $ThrowerInfo = null, string $ModelName = null)
    {
        $errorInfo=null;
        try {
            $errorInfo = ['data' => $data, 'ruleCasts' => $ruleCasts];
            if ($ThrowerInfo) {
                $errorInfo['ThrowerInfo'] = $ThrowerInfo;
            }
            if ($ModelName) {
                $errorInfo['ModelName'] = $ModelName;
            }
            $rules = $this->GenericValidator($ruleCasts);
            $errorInfo = array_replace($errorInfo, $rules);
        } catch (Throwable $ex) {
            throw new ErrorInSpecialValidationService(['errors' => $errorInfo], $ex);
        }
        try {
            Validator::validate($data, $rules['rules'], $rules['messages'], $rules['names'] ?? []);
        } catch (ValidationException $ex) {
            $errorInfo['errors'] = $ex->errors();
            throw new SpecialValidationException($errorInfo, $ex);
        } catch (Throwable $ex) {
            throw new ErrorInSpecialValidationService(['Error'=>$ex->getMessage(),'errors' => $errorInfo], $ex);
        }
    }

    private function GenericValidator(array $ruleCasts)
    {
        $casts = [];
        $ruleMessages = [];
        foreach ($ruleCasts as $cast => $rule) {
            array_push($casts, [$cast => $rule]);
            array_push($ruleMessages, [$cast => 'Failed: ' . $cast . ' => ' . $rule]);
        }
        return ['rules' => $casts, 'messages' => $ruleMessages];
    }

}
