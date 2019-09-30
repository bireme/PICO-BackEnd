<?php

namespace PICOExplorer\Services\ServiceModels;

trait PICOQueryProcessorTrait
{

    protected function ProcessQuery(string $query)
    {
        $QuerySplitBySeparators = $this->splitQueryWithDelimiters($query);
        $FixedQuoted = $this->RepairQuotes($QuerySplitBySeparators, "'");
        return $FixedQuoted;
    }

    ///////////////////////////////////////////////////////////////////
    //INNER FUNCTIONS
    ///////////////////////////////////////////////////////////////////

    private function splitQueryWithDelimiters(string $query)
    {
        $query = str_replace('  ', ' ', $query);
        $query = str_replace("'", '"', $query);
        $query = strtolower($query);
        $pattern = '/([():])/';
        $QuerySplitBySeparators = preg_split($pattern, $query, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $QuerySplitBySeparators = $this->SplitArrayByOps(' or ',[' ','or',' '],$QuerySplitBySeparators);
        $QuerySplitBySeparators = $this->SplitArrayByOps(' and ',[' ','and',' '], $QuerySplitBySeparators);
        $QuerySplitBySeparators = $this->SplitArrayByOps('not ',['not',' '], $QuerySplitBySeparators);
        return $QuerySplitBySeparators;
    }

    private function SplitArrayByOps(string $delimiter, array $replacedelimiter, array $array)
    {
        $res = [];
        foreach ($array as $obj) {
            if (!($obj)) {
                continue;
            }
            $parts = explode($delimiter, $obj);
            foreach ($parts as $index => $partsobj) {
                array_push($res, $partsobj);
                if ($index < count($parts) - 1) {
                    array_merge($res, $replacedelimiter);
                }
            }
        }
        return $res;
    }


    private function isInRange(int $index, array $FoundQuotes)
    {
        foreach ($FoundQuotes as $array) {
            $min = $array[0];
            $max = $array[1];
            if ($index < $min) {
                continue;
            }
            if ($index > $max) {
                continue;
            }
            if ($index === $max) {
                return 0;
            } else {
                return 1;
            }
        }
        return -1;
    }

    private function RemoveQuotes(array $QuerySplitBySeparators, array $FoundQuotes)
    {
        $index = count($QuerySplitBySeparators);
        while ($index >= 0) {
            $index--;
            $range = $this->isInRange($index, $FoundQuotes);
            if ($range === -1) {
                continue;
            } elseif ($range === 1) {
                $QuerySplitBySeparators[$index] = $QuerySplitBySeparators[$index] . $QuerySplitBySeparators[$index + 1];
                unset($QuerySplitBySeparators[$index + 1]);
            }
        }
        return $QuerySplitBySeparators;
    }

    private function RepairQuotes(array $QuerySplitBySeparators, string $Quote)
    {
        $UniqueQuote = [];
        foreach ($QuerySplitBySeparators as $index => $QuerySplitBySepsElement) {
            if (substr($QuerySplitBySepsElement, 0, 1) === $Quote) {
                array_push($UniqueQuote, [$index]);
            }
            if (substr($QuerySplitBySepsElement, -1) === $Quote) {
                $tmp = $UniqueQuote[count($UniqueQuote) - 1] ?? null;
                if ($tmp) {
                    $num = count($tmp) ?? 0;
                    if ($num === 1) {
                        array_push($UniqueQuote[count($UniqueQuote) - 1], $index);
                    }
                }
            }
            $QuerySplitBySepsElement = str_replace('"', '', $QuerySplitBySepsElement);
        }
        if (count($UniqueQuote) === 0) {
            $QuerySplitBySeparators = $this->RemoveQuotes($QuerySplitBySeparators, $UniqueQuote);
        }
        return $QuerySplitBySeparators;
    }

}
