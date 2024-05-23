<?php
use yii\helpers\Url;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

//GERM: esto siguiente lo robe de por ahi
$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

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

    $tipos = array(1=>'Desempleo',2=>'Familia',3=>'SST');
    $meses = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio', 8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
    $pagos = array(1=>'Acreditacion',2=>'Cheque');
    return [
//---------------------------------------------------------------------------------------------------------------
    [
        'attribute' => 'carga',
        'width' => '20%',
        'value' => function ($model) {
            $fc = date_create($model->carga);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesde',
            'attribute2' => 'fhasta',
            'options' => ['placeholder' => 'Desde'],
            'options2' => ['placeholder' => 'Hasta'],
            'type' => DatePicker::TYPE_RANGE,
            'layout' => $layoutDate,
            'separator' => ' ',
            'readonly' => true,
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'autoclose' => true
            ]
        ])
    ],
//---------------------------------------------------------------------------------------------------------------
    [//Combo Comun para datos a mano
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'periodo_mes',
        'value' => function ($model) {
            $periodo_mes = $model->periodo_mes;
            if ($periodo_mes != null) {
                return get_mes($periodo_mes);
            }
            return "";
        },
        'width' => '20%',
        'filter' => $meses,//uso un array para el filter
    ],
//---------------------------------------------------------------------------------------------------------------
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'periodo_anio',
        'width' => '20%',
        'label' => 'Periodo Año',
    ],
//---------------------------------------------------------------------------------------------------------------
    [//Combo Comun para datos a mano
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo',
        'value' => function ($model) {
            $tipo = $model->tipo;
            if ($tipo != null) 
                {
                    return get_tipo($tipo);
                }
            return "";
        },
        'width' => '20%',
        'filter' => $tipos,//uso un array para el filter
    ],
//---------------------------------------------------------------------------------------------------------------
    [//Combo Comun para datos a mano
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'pago',
        'value' => function ($model) {
            $pago = $model->pago;
            if ($pago != null) 
                {
                    return get_pago($pago);
                }
            return "";
        },
        'width' => '20%',
        'filter' => $pagos,//uso un array para el filter
    ],
//---------------------------------------------------------------------------------------------------------------
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'],  
    ],

];   