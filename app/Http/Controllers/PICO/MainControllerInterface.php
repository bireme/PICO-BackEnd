<?php


namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Models\MainModels\MainModelsModel;

interface MainControllerInterface
{

    /**
     * @return array
     */
    public static function requestRules();

    /**
     * @return MainModelsModel
     */
    public static function responseRules();

    /**
     * @return MainModelsModel
     */
    public function create();

    public function core(string $JSONEncodedData);

    /**
     * @return array
     */
    public function TestData();

}
