<?php

namespace PICOExplorer\Models\MainModels;

use Jenssegers\Model\Model;
use PICOExplorer\Facades\AdvancedLoggerFacade;
use PICOExplorer\Services\AdvancedLogger\Traits\SpecialValidator;

abstract class MainModelsModel extends Model
{

    use SpecialValidator;

    protected $fillable = [
        'InitialData',
    ];

    protected $casts = [];

    protected $attributes = [
        'InitialData',
    ];

    protected $guarded = ['*'];

    protected $rules = [];

    protected $ruleMessages = [];

    protected $attrNames = [];

    protected $FillableVariables = [];

    public function __construct()
    {
        $this->AddToFillable($this->FillableAttributes());
        parent::__construct();
    }

    protected function AddToFillable(array $variables)
    {
        $this->attributes = array_unique(array_merge($this->attributes, $variables));
        $this->fillable = array_unique(array_merge($this->fillable, $variables));
    }

    public function getAttributeInitialData($value)
    {
        return $value;
    }

    public function getResults()
    {
        return $this->offsetGet('Data');
    }

    protected $attrs = [];

    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    abstract protected static function FillableAttributes();

    protected function FillStatic(){
        $attrs=$this::FillableAttributes();
        $this->fillable=array_replace($this->fillable,$attrs);
    }

    final public function setResults(string $referer, array $results, array $responseRules)
    {
        $this->UpdateModel($referer, ['Data' => $results], $responseRules);
    }

    final public function UpdateModel(string $referer, array $data, array $ruleArr)
    {
        AdvancedLoggerFacade::LogTest('UPDATE'.get_class($this).json_encode($data));
        $this->SpecialValidate($data,$ruleArr,$referer,get_class($this));
        AdvancedLoggerFacade::LogTest('SUCESS'.get_class($this));
        $this->forceFill($data);
    }

}
