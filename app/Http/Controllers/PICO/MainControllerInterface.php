<?php


namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\PICOServiceFacade;
use PICOExplorer\Models\MainModelsModel;
use PICOExplorer\Services\ServiceModels\ServiceModel;
use Request;

interface MainControllerInterface
{

    /**
     * @return ServiceModel
     */
    public function ServiceBind();

    public function index(Request $request);

    public function outerBind(array $data);

    public function info();

    /**
     * @return MainModelsModel
     */
    public function getMainModel();

}
