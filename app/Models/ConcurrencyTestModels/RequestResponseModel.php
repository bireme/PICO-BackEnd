<?php


namespace PICOExplorer\Models\ConcurrencyTestModels;


class RequestResponseModel
{
    private $request = [];
    private $response = "";

    public function __construct(array $request, string $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function getRequest(){
        return $this->request;
    }

    public function CompareResponse(string $currentResponse){
        $percent=null;
        $sim = similar_text ($this->response , $currentResponse, $percent);
        return $percent;
    }

}
