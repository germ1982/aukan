<?php

use app\models\Mds_org_contacto;
use app\models\Sds_com_persona;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\helpers\Html;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

function crear_celda($label, $contenido, $ancho)
{
    echo "
    <div class='col-xs-$ancho'>  
        <h6><b>$label</b></h6>
        <p style='padding: 3px 6px; font-size: 12px; line-height: 1.42857143; color: #555555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px;'>
                $contenido
        </p>
    </div>";
}

return ;   