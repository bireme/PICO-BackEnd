<?php

namespace PICOExplorer\Models\ConcurrencyTestModels;

abstract class ConcurrencyBaseModel
{

    /**
     * @var RequestResponseModel
     */
    private $testUno;
    /**
     * @var RequestResponseModel
     */
    private $testDos;
    /**
     * @var RequestResponseModel
     */
    private $testTres;

    /**
     * @return array
     */
    abstract protected function requestUno();

    /**
     * @return array
     */
    abstract protected function requestDos();

    /**
     * @return array
     */
    abstract protected function requestTres();

    /**
     * @return string
     */
    abstract protected function responseUno();

    /**
     * @return string
     */
    abstract protected function responseDos();

    /**
     * @return string
     */
    abstract protected function responseTres();

    public function __construct()
    {

        $this->testUno = new RequestResponseModel($this->requestUno(),$this->responseUno());
        $this->testDos = new RequestResponseModel($this->requestDos(),$this->responseDos());
        $this->testTres = new RequestResponseModel($this->requestTres(),$this->responseTres());
    }

    public function Test(String $responseUno, String $responseDos, String $responseTres){
        $valor1 = $this->testUno->CompareResponse($responseUno);
        $valor2 = $this->testDos->CompareResponse($responseDos);
        $valor3 = $this->testTres->CompareResponse($responseTres);
        $valorfinal = ($valor1+$valor2+$valor3)/3;
        return $valorfinal;
    }

}
