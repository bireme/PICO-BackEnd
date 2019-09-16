<?php


namespace PICOExplorer\Http\Controllers\PICO;

use PICOExplorer\Facades\PICOServiceFacade;
use PICOExplorer\Models\MainModelsModel;
use PICOExplorer\Services\ServiceModels\PICOServiceModel;
use Request;

interface MainControllerInterface
{

    /**
     * @return PICOServiceModel
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
