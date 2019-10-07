<?php


namespace PICOExplorer\Http\Traits;


trait CorrectQuerySplitTrait
{

    protected function improveQuerySplit(array $QuerySplit, array $Ops, array $Seps)
    {

        $error = true;
        $previous = null;
        $previousTwo = null;
        while ($error) {
            $error = false;
            $totaldeleteindexes = [];
            foreach ($QuerySplit as $index => $item) {
                if (!($item)) {
                    continue;
                }
                $type = $QuerySplit[$index]['type'];
                $deleteindexes = null;

                if ($previous) {
                    if (!($type === 'keyexplored' || $type === 'keyword' || $type === 'keyrep' || $type === 'keypartial')) {
                        $value = $QuerySplit[$index]['value'] ?? '';
                        $previousValue = $QuerySplit[$previous]['value'] ?? '';
                        $previousTwoValue = null;
                        if ($previousTwo) {
                            $previousTwoValue = $QuerySplit[$previousTwo]['value'] ?? null;
                        }
                        $deleteindexes = $this->CorrectErrors($Ops, $index, $previous, $value, $previousValue, $previousTwo, $previousTwoValue);
                    }
                }
                if ($deleteindexes) {
                    $totaldeleteindexes = array_merge($totaldeleteindexes, $deleteindexes);
                    $previousTwo = null;
                    $previous = null;
                    $error = true;
                } else {
                    if ($previous) {
                        $previousTwo = $previous;
                    }
                    $previous = $index;
                }
            }
            $this->deleteErrors($QuerySplit, $totaldeleteindexes);
            $show = [];
            $this->reBuildWithoutNulls($QuerySplit, $show);
            $this->removeBorders($QuerySplit, $Ops, $error);
        }
        return $QuerySplit;
    }

    private function CorrectErrors(array $Ops, int $index, int $previous, string $value, string $previousValue, int $previousTwo = null, string $previousTwoValue = null)
    {
        if ($previousTwo) {
            if ((in_array($value, $Ops)) && (in_array($previousTwoValue, $Ops)) && ($previousValue === ' ') && ($previousTwoValue !== 'not')) {
                return [$previousTwo, $previous, $index];
            }
            if ($previousTwoValue === '(' && $value === ')') {
                return [$previousTwo, $index];
            }
            if ($previousTwoValue === '(' && $previousValue === ' ' && in_array($value, $Ops) && $value !== 'not') {
                return [$previous, $index];
            }
            if (in_array($previousTwoValue, $Ops) && $previousValue === ' ' && $value === ')') {
                return [$previousTwo, $previous];
            }

            if ($previousTwoValue === '(' && $value === ' ' && in_array($previousValue, $Ops) && $previousValue !== 'not') {
                return [$previous, $index];
            }
            if (in_array($previousValue, $Ops) && $previousTwoValue === ' ' && $value === ')') {
                return [$previousTwo, $previous];
            }
        }
        if ($previousValue === ' ' && $value === ' ') {
            return [$previous];
        }
        if ($previousValue === ' ' && $value === ')') {
            return [$previous];
        }
        if ($previousValue === '(' && $value === ' ') {
            return [$index];
        }
        if ($previousValue === '(' && $value === ')') {
            return [$previous, $index];
        }
        return null;
    }

    private function deleteErrors(array &$QuerySplit, array $totaldeleteindexes)
    {
        if (count($totaldeleteindexes) > 0) {
            rsort($totaldeleteindexes);

            foreach ($totaldeleteindexes as $itemdelete) {
                unset($QuerySplit[$itemdelete]);
            }
        }
    }

    private function reBuildWithoutNulls(array &$QuerySplit, array &$show)
    {
        $newQuerySplit = [];
        foreach ($QuerySplit as $index => $item) {
            if ($item) {
                array_push($show, $item['value']);
                array_push($newQuerySplit, $item);
            }
        }
        $QuerySplit = $newQuerySplit;
    }

    private function removeBorders(array &$QuerySplit, array $Ops, bool &$error)
    {
        $tmperror = true;
        while ($tmperror) {
            $tmperror = false;
            $firstval = $QuerySplit[0]['value'];
            $lastval = $QuerySplit[count($QuerySplit) - 1]['value'];
            if (in_array($lastval, $Ops) || $lastval === ' ') {
                array_pop($QuerySplit);
                $error = true;
                $tmperror = true;
            }
            if (in_array($firstval, $Ops) || $firstval === ' ') {
                array_shift($QuerySplit);
                $error = true;
                $tmperror = true;
            }
        }
    }

}
