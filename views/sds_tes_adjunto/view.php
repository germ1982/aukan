<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_tes_adjunto */

function get_mes($mes)
        {
            switch ($mes)
            {
                case "1":
                    $mes = "Enero";
                    break;

                case "2":
                    $mes =  "Febrero";
                    break;

                case "3":
                    $mes =  "Marzo";
                    break;

                case "4":
                    $mes =  "Abril";
                    break;
                case "5":
                    $mes = "Mayo";
                    break;

                case "6":
                    $mes =  "Junio";
                    break;

                case "7":
                    $mes =  "Julio";
                    break;

                case "8":
                    $mes =  "Agosto";
                    break;
                case "9":
                    $mes = "Septiembre";
                    break;

                case "10":
                    $mes =  "Octubre";
                    break;

                case "11":
                    $mes =  "Noviembre";
                    break;

                case "12":
                    $mes =  "Diciembre";
                    break;
            } 
        return $mes;
        }
    function get_tipo($tipo)
        {
            switch ($tipo)
                {
                    case "1":
                        $tipo = "Desempleo";
                        break;
                    case "2":
                        $tipo = "Familia";
                        break;
                    case "3":
                        $tipo = "SST";
                    break;      
                }
            return $tipo;
        }
    function get_pago($pago)
        {
            switch ($pago)
                {
                    case "1":
                        $pago = "Acreditación";
                        break;
                    case "2":
                        $pago = "Cheque";
                        break;    
                }
            return $pago;
        }
?>


<style>
    #base64image {
        display: block;
        border: ridge 1px;
        padding: 8px;
        border-color: #E6E6E6;
        max-width: 40%;
    }

    .campo {
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>
<div class="sds-tes-adjunto-view">
<div class="row">
    <div class="col-md-6">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'carga',
                    'value' => function ($model) {
                        return date_format(date_create($model->carga    ), 'd/m/Y');
                    },
                ],
                [
                    'attribute' => 'periodo_mes',
                    'value' => function ($model) {
                        return get_mes($model->periodo_mes);
                    },
                ],

                'periodo_anio',
                [
                    'attribute' => 'tipo',
                    'value' => function ($model) {
                        return get_tipo($model->tipo);
                    },
                ],
                [
                    'attribute' => 'pago',
                    'value' => function ($model) {
                        return get_pago($model->pago);
                    },
                ],

            ],
        ]) ?>
    </div>
    <div class="col-md-6">
        <?php
                if($model->path) 
                    {
                        echo "<h5><b>Archivo Adjunto:</b></h5>";
                        $ruta = '@web/'.$model->path; 

                        echo Html::a($model->path, Url::to($ruta, true), ['target' => '_blank', 'data-pjax' => "0",  'style' => 'width:80%']); 

                            
                    }
            ?>
    </div>
</div>

</div>
