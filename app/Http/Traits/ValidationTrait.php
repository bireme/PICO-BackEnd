<?php


namespace PICOExplorer\Http\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator as Validator;

trait ValidationTrait
{
    public function ValidateData(array $data, array $rules)
    {
        try {
            Validator::validate($data, $rules);
            return false;
        } catch (ValidationException $ex) {
            return $ex;
        }
    }
}
