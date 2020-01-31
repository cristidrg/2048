<?php

namespace App;

class GridHelper
{
    public static function addNewBlock($startingGrid)
    {
        $gridIsFull = true;
        $grid = $startingGrid;
        $rows = [0,1,2,3,4,5];
        $columns = [0,1,2,3,4,5];
        shuffle($rows);
        shuffle($columns);
        
        foreach($rows as $row) {
            foreach($columns as $column) {
                if ($grid[$column][$row]["value"] == 0) {
                    $gridIsFull = false;
                    $grid[$column][$row]["value"] = 1;

                    return [
                        "grid" => $grid,
                        "gridIsFull" => $gridIsFull
                    ];
                }
            }
        }

        return [
            "grid" => $grid,
            "gameOver" => $gameOver
        ];
    }

    public static function switchLeft($startingGrid)
    {
        $grid = $startingGrid;

        for ($row = 0; $row < 6; $row++) {
            for ($column = 0; $column < 6; $column++) {
                if ($grid[$row][$column]["value"] > 0) {
                    $columnCopy = $column - 1;
                    while ($columnCopy > -1) {
                        if ($grid[$row][$columnCopy]["value"] == 0) {
                            $temp = $grid[$row][$columnCopy + 1];
                            $grid[$row][$columnCopy + 1] = $grid[$row][$columnCopy];
                            $grid[$row][$columnCopy] = $temp;
                        } else if ($grid[$row][$columnCopy]["value"] == $grid[$row][$columnCopy + 1]["value"]
                            && $grid[$row][$columnCopy]["canMerge"] && $grid[$row][$columnCopy + 1]["canMerge"]){
                            $grid[$row][$columnCopy]["value"] = $grid[$row][$columnCopy]["value"] * 2;
                            $grid[$row][$columnCopy]["canMerge"] = false;
                            $grid[$row][$columnCopy + 1]["value"] = 0;
                            $grid[$row][$columnCopy + 1]["canMerge"] = false;
                        } else {
                            break;
                        }
                        $columnCopy = $columnCopy - 1;
                    }
                }
            }
        }

        return $grid;
    }

    public static function switchRight($startingGrid)
    {
        $grid = $startingGrid;

        for ($row = 5; $row > -1; $row--) {
            for ($column = 5; $column > -1; $column--) {
                if ($grid[$row][$column]["value"] > 0) {
                    $columnCopy = $column + 1;
                    while ($columnCopy < 6) {
                        if ($grid[$row][$columnCopy]["value"] == 0) {
                            $temp = $grid[$row][$columnCopy - 1];
                            $grid[$row][$columnCopy - 1] = $grid[$row][$columnCopy];
                            $grid[$row][$columnCopy] = $temp;
                        } else if ($grid[$row][$columnCopy]["value"] == $grid[$row][$columnCopy - 1]["value"]
                            && $grid[$row][$columnCopy - 1]["canMerge"] && $grid[$row][$columnCopy]["canMerge"]) {
                            $grid[$row][$columnCopy]["value"] = $grid[$row][$columnCopy]["value"] * 2;
                            $grid[$row][$columnCopy]["canMerge"] = false;
                            $grid[$row][$columnCopy - 1]["canMerge"] = false;
                            $grid[$row][$columnCopy - 1]["value"] = 0;
                        } else {
                            break;
                        }
                        $columnCopy = $columnCopy + 1;
                    }
                }
            }
        }

        return $grid;
    }

    public static function switchBottom($startingGrid)
    {
        $grid = $startingGrid;
        for ($column = 5; $column > -1; $column--) {
            for ($row = 5; $row > -1; $row--) {
                if ($grid[$row][$column]["value"] > 0) {
                    $rowCopy = $row + 1;
                    while ($rowCopy < 6) {
                        if ($grid[$rowCopy][$column]["value"] == 0) {
                            $temp = $grid[$rowCopy - 1][$column];
                            $grid[$rowCopy - 1][$column] = $grid[$rowCopy][$column];
                            $grid[$rowCopy][$column] = $temp;
                        } else if ($grid[$rowCopy][$column]["value"] == $grid[$rowCopy - 1][$column]["value"]
                            && $grid[$rowCopy - 1][$column]["canMerge"] && $grid[$rowCopy][$column]["canMerge"]) {
                            $grid[$rowCopy][$column]["value"] = $grid[$rowCopy][$column]["value"] * 2;
                            $grid[$rowCopy][$column]["canMerge"] = false;
                            $grid[$rowCopy][$column]["canMerge"] = false;
                            $grid[$rowCopy - 1][$column]["value"] = 0;
                        } else {
                            break;
                        }
                        $rowCopy = $rowCopy + 1;
                    }
                }
            }
        }

        return $grid;
    }

    public static function switchTop($startingGrid)
    {
        $grid = $startingGrid;
        for ($column = 0; $column < 6; $column++) {
            for ($row = 0; $row < 6; $row++) {
                if ($grid[$row][$column]["value"] > 0) {
                    $rowCopy = $row - 1;
                    while ($rowCopy > -1) {
                        if ($grid[$rowCopy][$column]["value"] == 0) {
                            $temp = $grid[$rowCopy + 1][$column];
                            $grid[$rowCopy + 1][$column] = $grid[$rowCopy][$column];
                            $grid[$rowCopy][$column] = $temp;
                        } else if ($grid[$rowCopy][$column]["value"] == $grid[$rowCopy + 1][$column]["value"]
                            && $grid[$rowCopy][$column]["canMerge"] && $grid[$rowCopy + 1][$column]["canMerge"]) {
                            $grid[$rowCopy][$column]["value"] = $grid[$rowCopy][$column]["value"] * 2;
                            $grid[$rowCopy][$column]["canMerge"] = false;
                            $grid[$rowCopy + 1][$column]["canMerge"] = false;
                            $grid[$rowCopy + 1][$column]["value"] = 0;
                        } else {
                            break;
                        }
                        $rowCopy = $rowCopy - 1;
                    }
                }
            }
        }

        return $grid;
    }
}
