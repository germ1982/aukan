<?php

namespace app\helpers;

use yii\helpers\Html;

class AppCheckboxListHelper
{
    /**
     * Renderiza una grilla de checkboxes genérica
     *
     * @param array $items Lista de modelos con PK y descripcion
     * @param string $pkField Nombre del campo PK
     * @param string $descField Nombre del campo descripcion
     * @param string $inputName Nombre del input para el POST
     * @param array $selectedIds IDs ya seleccionados (para edicion)
     * @return string HTML
     */
    public static function render($items, $pkField, $descField, $inputName, $selectedIds = [])
    {
        $html = '<div style="display:flex; flex-wrap:wrap; gap:8px; padding:6px 0; border:1px solid #ccc; border-radius:4px; max-height:200px; overflow-y:auto;">';
        foreach ($items as $item) {
            $id = $item->$pkField;
            $desc = $item->$descField;
            $checked = in_array($id, $selectedIds);
            $html .= '<div style="min-width: 150px; font-size:10px;">';
            $html .= Html::checkbox($inputName . '[]', $checked, [
                'value' => $id,
                'label' => $desc,
                'labelOptions' => ['style' => 
                'font-size:11px; 
                margin-left:5px; 
                font-weight:normal;
                align-items: center;
                '],
            ]);
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
}