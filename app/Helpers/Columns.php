<?php

namespace App\Helpers;

class Columns
{
    public static function get($model, $selectedColumns)
    {
        $responseColumn = [];

        $AllColumns = $model->getColumns();
        $defaultColumns = $model->getDefaultColumn();

       $selected = isset($selectedColumns) ? $selectedColumns : $defaultColumns;

        foreach ($AllColumns as $column) {
            $responseColumn['AllColumns'][$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $responseColumn['SelectedColumns'][$column] = trans("column." . $column);
            }
        }
        
        return $responseColumn;
    }
}
