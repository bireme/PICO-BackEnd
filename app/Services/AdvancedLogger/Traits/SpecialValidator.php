<?php

namespace PICOExplorer\Services\AdvancedLogger\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use PICOExplorer\Services\AdvancedLogger\Exceptions\SpecialValidationException;
use PICOExplorer\Services\AdvancedLogger\Exceptions\SpecialValidationInternalException;
use Throwable;
use Exception;

trait SpecialValidator
{

    protected function SpecialValidate(array $data, array $ruleCasts, string $ThrowerInfo = null,string $ModelName=null)
    {
        $errorInfo=['data'=>$data,'ruleCasts'=>$ruleCasts];
        if ($ThrowerInfo) {
            $errorInfo['ThrowerInfo'] = $ThrowerInfo;
        }
        if($ModelName){
            $errorInfo['ModelName'] = $ModelName;
        }
        try {
            $rules = $this->GenericValidator($ruleCasts);
        }catch(Throwable $ex){
            throw new SpecialValidationInternalException(['errors'=>$errorInfo], $ex);
        }
        $errorInfo=array_replace($errorInfo,$rules);
        try {
            Validator::validate($data, $rules['rules'], $rules['messages'], $rules['names'] ?? []);
        } catch (ValidationException $ex) {
            $errorInfo['errors'] = $ex->errors();
            throw new SpecialValidationException($errorInfo, $ex);
        }catch(Exception $ex){
            throw new SpecialValidationInternalException(['errors'=>$errorInfo], $ex);
        }catch(Throwable $ex){
            throw new SpecialValidationInternalException(['errors'=>$errorInfo], $ex);
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
