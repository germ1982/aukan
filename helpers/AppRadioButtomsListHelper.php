<?php

namespace app\helpers;

use yii\helpers\Html;

class AppRadioButtomsListHelper
{
    public static function renderRadio($items, $pkField, $descField, $inputName, $selectedId = null)
    {
        $html = '<div style="display:flex; flex-wrap:wrap; gap:8px; padding:6px 0;border:1px solid #ccc; border-radius:4px;">';
        foreach ($items as $item) {
            $id = $item->$pkField;
            $desc = $item->$descField;
            $checked = $id == $selectedId;
              $html .= '<div style="min-width: 150px; font-size:10px;">';
            $html .= Html::radio($inputName, $checked, [
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
