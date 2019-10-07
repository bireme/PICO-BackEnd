<?php

namespace PICOExplorer\Services\ServiceModules;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorWhileBuildingHTML;
use PICOExplorer\Exceptions\Exceptions\AppError\ErrorWhileBuildingHTMLNotBoolean;
use PICOExplorer\Exceptions\Exceptions\AppError\ErrorWhileBuildingHTMLNotString;

trait BuildHTMLTrait
{

    protected function BuildHTML(string $titleid, array $array,string $alternateText=null)
    {
        foreach ($array as $key => $item) {
            foreach ($item as $keytwo => $elementData) {
                if ((($elementData['title'] ?? null)===null) || (($elementData['value'] ?? null)===null) || (($elementData['checked'] ?? null)===null)) {
                    throw new ErrorWhileBuildingHTML(['title' => $titleid,'Error' => 'ElementNotFound', 'dataThatMustHaveTitleAndValue' => $elementData]);
                }
                if (!(is_string($elementData['value']))) {
                    throw new ErrorWhileBuildingHTMLNotString(['title' => $titleid, 'notFormat' => 'value', 'key' => $key, 'keytwo' => $keytwo, 'data' => $array]);
                }
                if (!(is_string($elementData['title']))) {
                    throw new ErrorWhileBuildingHTMLNotString(['title' => $titleid, 'notFormat' => 'title', 'key' => $key, 'keytwo' => $keytwo, 'data' => $array]);
                }
                if (!(is_integer($elementData['checked']) || is_bool($elementData['checked']))) {
                    throw new ErrorWhileBuildingHTMLNotBoolean(['title' => $titleid, 'notFormat' => 'checked', 'key' => $key, 'keytwo' => $keytwo, 'data' => $array]);
                }
            }
        }
        $FormData = [
            'titleid' => $titleid,
            'content' => $array,
            'alternateText' => $alternateText,
        ];
        return view('partials.modais.tabcontent.tabcontent')->with($FormData)->render();
    }

    protected function BuildHiddenField(string $formtitle, string $title, $notEncodedvalue)
    {
        $data = [
            'title' => $formtitle . '-' . $title,
            'value' => json_encode($notEncodedvalue),
        ];
        return view('partials.modais.tabcontent.hiddenfield')->with($data)->render();
    }

}
