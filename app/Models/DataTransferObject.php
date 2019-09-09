<?php

namespace PICOExplorer\Models;

use PICOExplorer\Exceptions\Exceptions\AppError\AtributeWasNotArray;
use PICOExplorer\Exceptions\Exceptions\AppError\AttributeDoesNotExist;
use PICOExplorer\Exceptions\Exceptions\AppError\ModelNameDoesNotExistInDTOBinds;
use PICOExplorer\Exceptions\Exceptions\AppError\InitialDataIsNotSet;
use PICOExplorer\Exceptions\Exceptions\AppError\modelAliasDoesNotExist;
use PICOExplorer\Exceptions\Exceptions\AppError\modelAliasNotSpecified;
use PICOExplorer\Exceptions\Exceptions\AppError\ModelIsAlreadyLockedCantAddResults;
use PICOExplorer\Exceptions\Exceptions\AppError\ResultsIsNotSet;
use PICOExplorer\Exceptions\Exceptions\AppError\RulesNotFoundInsideModelForVariable;
use PICOExplorer\Exceptions\Exceptions\AppError\TheModelIsNotInstanceOfMainModelsModel;
use PICOExplorer\Exceptions\Exceptions\AppError\TheModelIsNull;
use PICOExplorer\Facades\AdvancedLoggerFacade;
use PICOExplorer\Facades\SpecialValidatorFacade;
use PICOExplorer\Models\DataModels\DeCSModel;
use PICOExplorer\Models\DataModels\IntegrationDeCSModel;
use PICOExplorer\Models\DataModels\IntegrationResultsNumberModel;
use PICOExplorer\Models\DataModels\QueryBuildModel;
use PICOExplorer\Models\DataModels\ResultsNumberModel;
use PICOExplorer\Models\DataModels\TreeModel;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;

class DataTransferObject
{

    protected $modelList = [];
    protected $referer;
    protected $modelAlias;
    protected $currentModel;

    public static final function getModel(string $modelName)
    {
        switch ($modelName) {
            case 'DeCS':
                return new DeCSModel();
            case 'IntegrationDeCS':
                return new IntegrationDeCSModel();
            case 'ResultsNumber':
                return new ResultsNumberModel();
            case 'IntegrationResultsNumber':
                return new IntegrationResultsNumberModel();
            case 'QueryBuild':
                return new QueryBuildModel();
            case 'Tree':
                return new TreeModel();
            default:
                throw new ModelNameDoesNotExistInDTOBinds(['modelName' => $modelName]);
                break;
        }
    }

    public function __construct(string $referer, $model)
    {
        $this->referer = $referer;
        $this->SaveModelToList($model, 'main');
    }


    public function getAttr(string $name, string $modelAlias = null)
    {
        $this->TmpModelAlias($modelAlias);
        $this->AttrExists($name);
        return $this->currentModel->getAttribute($name);
    }

    public function getTestData(string $modelAlias = null)
    {
        $this->TmpModelAlias($modelAlias);
        $data = $this->currentModel->ControllerTestData();
        return $data;
    }

    final public function setResults(string $referer, array $results, string $modelAlias = null)
    {
        $this->TmpModelAlias($modelAlias);
        if ($this->currentModel->isLocked()) {
            throw new ModelIsAlreadyLockedCantAddResults(['model' => $this->currentModel, 'referer' => $referer]);
        }
        $rules = $this->currentModel->responseRules();
        $data = ['results' => $results];
        $this->ValidatedLogAndUpdate($referer, $rules, $data);
        $this->currentModel->lock();
    }

    final public function setInitialData(string $referer, array $initialData, string $modelAlias = null)
    {
        $this->TmpModelAlias($modelAlias);
        $rules = $this->currentModel->requestRules();
        $data = ['initialData' => $initialData];
        $this->ValidatedLogAndUpdate($referer, $rules, $data);
    }

    public function getInitialData(string $modelAlias = null,bool $ServiceMonitoring=false)
    {
        $this->TmpModelAlias($modelAlias);
        $data = $this->currentModel->initialData;
        if ($data === 'unset' && (!($ServiceMonitoring))) {
            throw new InitialDataIsNotSet();
        }
        return $data;
    }

    public function getResults(string $modelAlias = null,bool $ServiceMonitoring=false)
    {
        $this->TmpModelAlias($modelAlias);
        $data = $this->currentModel->results;
        if ($data === 'unset' && (!($ServiceMonitoring))) {
            throw new ResultsIsNotSet();
        }
        return $data;
    }

    public function requestRules(string $modelAlias = null)
    {
        $this->TmpModelAlias($modelAlias);
        return $this->currentModel->requestRules();
    }

    public function responseRules(string $modelAlias = null)
    {
        $this->TmpModelAlias($modelAlias);
        return $this->currentModel->responseRules();
    }

    public function PushToAttribute(string $referer, string $attribute, array $datawithkeys, bool $unique = false, string $modelAlias = null)
    {
        $this->TmpModelAlias($modelAlias);
        $previous = $this->getAttr($attribute);
        if (!(is_array($previous))) {
            if (!($previous)) {
                $previous = [];
            } else {
                throw new AtributeWasNotArray(['referer' => $referer, 'model' => get_class($this->currentModel), 'Attribute' => $attribute]);
            }
        }
        if ($unique) {
            foreach ($datawithkeys as $key => $data) {
                $previous[$key] = $data;
            }
            $newdata = $previous;
        } else {
            $newdata = array_merge($previous, $datawithkeys);
        }
        $this->SaveToModel($referer, $newdata,$modelAlias);
    }

    public function SaveToModel(string $referer, array $data, string $modelAlias = null)
    {
        $this->TmpModelAlias($modelAlias);
        $globalrules = $this->currentModel->AttributeRules();
        foreach ($data as $variable => $variabledata) {
            $this->AttrExists($variable);
            $localrules = $rules ?? $globalrules[$variable] ?? null;
            if (!($localrules)) {
                throw new RulesNotFoundInsideModelForVariable(['referer' => $referer, 'variable' => $variable, 'globalrules' => $globalrules ? array_keys($globalrules) : 'NotLoaded', 'localrules' => $localrules]);
            }
            $localdata = [$variable => $variabledata];
            $this->ValidatedLogAndUpdate($referer, $localrules, $localdata);
        }
    }

    final public function getDTOreferer()
    {
        return $this->referer;
    }


    ///////////////////////////
    /// PRIVATE FUNCTIONS
    /////////////////////////

    private function SaveModelToList($model, string $modelAlias)
    {

        if (!($model)) {
            throw new TheModelIsNull(['model' => null, 'controller' => $this->referer]);
        }
        if (!($model instanceof MainModelsModel)) {
            $info = get_class($model) ?? null;
            throw new TheModelIsNotInstanceOfMainModelsModel(['model' => $info, 'controller' => $this->referer]);
        }
        $this->modelList[$modelAlias] = $model;
    }

    private function TmpModelAlias(string $modelAlias = null)
    {
        if (!($modelAlias)) {
            if (count($this->modelList) !== 1) {
                throw new modelAliasNotSpecified(['modelAlias' => $modelAlias]);
            }
            $this->currentModel = $this->modelList['main'];

        } else {
            if (!(array_key_exists($modelAlias, $this->modelList))) {
                throw new modelAliasDoesNotExist(['modelAlias' => $modelAlias]);
            } else {
                $this->currentModel = $this->modelList[$modelAlias];
            }
        }
    }

    private function ValidatedLogAndUpdate(string $referer, array $rules, array $data = null)
    {
        $wasSuccess = false;
        try {
            SpecialValidatorFacade::SpecialValidate($data, $rules, $referer, get_class($this->currentModel));
            $this->currentModel->ForceUpdate($data);
            $wasSuccess = true;
        } catch (DontCatchException $ex) {
            //
        } finally {
            $this->LogSummary($wasSuccess, $data, $rules);
        }
    }

    private function AttrExists(string $Attribute)
    {

        $attrs = $this->currentModel->AttributeList();
        $exists =  array_key_exists($Attribute,$attrs);
        if ($exists) {
            throw new AttributeDoesNotExist(['model' => get_class($this->currentModel), 'Attribute' => $Attribute]);
        }
    }

    private function LogSummary(bool $wasSuccess, array $data, array $rules = null)
    {
        $logdata = [];
        $variable = 'Variable: ' . json_encode(array_keys($data));
        $logdata['Variable'] = $variable;
        if ($wasSuccess) {
            $Success = 'Success';
            $level = 'info';
            $logdata['Success'] = true;
        } else {
            $Success = 'Failed';
            $level = 'Error';
            $logdata['Success'] = false;
            $logdata['data'] = $data;
            $logdata['rules'] = $rules;
        }
        $MainData = ['title' => $Success . ' - '.json_encode(array_keys($data)).' - ' . get_class($this->currentModel),
            'info' => $variable,
            'data' => $logdata,
            'stack' => null,
        ];
        AdvancedLoggerFacade::AdvancedLog('DTO', $level, $MainData);
    }

}
