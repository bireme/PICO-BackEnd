<?php


namespace PICOExplorer\Http\Traits;


trait RenderArrayAsStringTrait
{

    protected function TabRepeats(string $sep, int $num)
    {
        $txt = '';
        while ($num > 0) {
            $txt = $txt . $sep;
            $num--;
        }
        return $txt;
    }


    protected function RenderArrayAsString(string $title, $data, $sep = '"\t"')
    {
        if (!($data)) {
            return 'null';
        }
        $txt = $title . ' => ';
        if (is_array($data)) {
            $txt = $this->RenderArrayAsStringLooper($data, 1, $sep);
        } elseif (is_object($data)) {
            $txt = json_encode($data);
        } else {
            $txt = $data;
        }
        return $txt;
    }

    protected function RenderArrayAsStringLooper($data, int $level, string $sep)
    {
        $tab = $this->TabRepeats($sep, $level);
        $txt = '[';
        foreach ($data as $key => $value) {
            if (!($value)) {
                $value = 'null';
            }
            if (is_array($value)) {
                $value = $this->RenderArrayAsStringLooper($value,$level+1,$sep);
            } elseif (is_object($data)) {
                $value = json_encode($value);
            }
            $txt = $txt . PHP_EOL.$tab . $key . ' => ' . $value;
        }
        return $txt .PHP_EOL .']';
    }


}
