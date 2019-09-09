<?php

namespace PICOExplorer\Models;

use Jenssegers\Model\Model;

abstract class MainModelsModel extends Model
{

    /**
     * @return array
     */
    abstract public static function requestRules();

    /**
     * @return array
     */
    abstract public static function responseRules();

    /**
     * @return array
     */
    abstract public static function AttributeRules();

    /**
     * @return array
     */
    abstract public function ControllerTestData();

    protected $attributes = [];
    protected $guarded = ['*'];
    protected $locked=false;

    final public function __construct()
    {
        $basevars= ['InitialData','results'];
        $attrs = array_keys($this->AttributeRules());
        $this->attributes = array_unique(array_merge($basevars, $attrs));
        $varData=[];
        foreach($this->attributes as $var){
            $varData[$var]='Unset';
        }
        $this->ForceUpdate($varData);
        parent::__construct();
    }

    final public function AttributeList(){
        return array_keys($this->AttributeRules());
    }

    final public function ForceUpdate(array $data)
    {
        $this->forceFill($data);
    }

    final public function isLocked(){
        return $this->locked;
    }

    final public function lock(){
        $this->locked=true;
    }

}
