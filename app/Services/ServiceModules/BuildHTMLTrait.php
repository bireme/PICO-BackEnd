<?php

namespace PICOExplorer\Services\ServiceModules;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorWhileBuildingHTML;

trait BuildHTMLTrait
{

    protected function BuildHTML(string $titleid, array $array)
    {
        foreach ($array as $key => $item) {
            foreach ($item as $keytwo => $elementData) {
                if (!(is_string($elementData['value']))) {
                    throw new ErrorWhileBuildingHTML(['title'=>$titleid,'notFound'=>'value','key'=>$key,'keytwo'=>$keytwo,'data'=>$array]);
                }
                if (!(is_string($elementData['title']))) {
                    throw new ErrorWhileBuildingHTML(['title'=>$titleid,'notFound'=>'title','key'=>$key,'keytwo'=>$keytwo,'data'=>$array]);
                }
                if (!(is_bool($elementData['checked']))) {
                    throw new ErrorWhileBuildingHTML(['title'=>$titleid,'notFound'=>'checked','key'=>$key,'keytwo'=>$keytwo,'data'=>$array]);
                }
            }
        }
        $FormData = [
            'titleid' => $titleid,
            'content' => $array,
        ];
        return view('partials.modais.tabcontent.tabcontent')->with($FormData)->render();
    }

    protected function BuildHiddenField(string $formtitle, string $title, $notEncodedvalue)
    {
        $data = [
            'title' => $formtitle.'-'.$title,
            'value' => json_encode($notEncodedvalue),
        ];
        return view('partials.modais.tabcontent.hiddenfield')->with($data)->render();
    }

}
