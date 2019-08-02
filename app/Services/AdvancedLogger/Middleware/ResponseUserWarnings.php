<?php

namespace PICOExplorer\Services\AdvancedLogger\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class ResponseUserWarnings
{

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $warnings = $this->getWarnings();
        if($warnings) {
            $collection = $response->original;
            $collection = $this->buildContent($warnings, $collection);
            $response->setContent($collection);
        }
        return $response;
    }

    protected function buildContent($warnings,$collection){
        if (is_array($collection)) {
            foreach($warnings as $level => $leveldata){
                if(array_key_exists($level,$collection)){
                    foreach($leveldata as $title => $message){
                        if(array_key_exists($title,$leveldata)){
                            $collection[$level][$title]=$collection[$level][$title].PHP_EOL.$message;
                        }else{
                            $collection[$level][$title]=$message;
                        }
                    }
                }else{
                    $collection[$level]=$leveldata;
                }
            }
        } else {
            $collection = [
                'Data' => $collection,
            ];
            $collection = array_merge($collection, $warnings);
        }
        return $collection;
    }

    protected function getWarnings()
    {
        if (Session::has('AdvancedLoggerWarning')) {
            $data = Session::get('AdvancedLoggerWarning');
            Session::pull('AdvancedLoggerWarning');
            Session::save();
            return $data;
        }else{
            return null;
        }
    }

}
