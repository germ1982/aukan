<?php

use app\models\Sds_cel_factura;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use app\models\Telefonia_vista_integradora;
use kartik\helpers\Html;

// ESTO ES PARA MOSTRAR LAS CUENTAS EXISTENTES EN LA VISTA INTEGRADORA Y PONERLOS EN EL FILTRO DE LAS CUENTAS
    $mysql_cuentas = 'SELECT * from vista_integradora where cuenta >= 1 group by cuenta';

// ESTO ES MOSTRAR LOS AÑOS EXISTENTES EN LA VISTA INTEGRADORA Y PONERLOS EN EL FILTRO DEL LOS AÑOS
    $mysql_anios = 'SELECT * FROM sds_cel_factura Where periodo_anio >=1  group by periodo_anio order by periodo_anio';

    $anios = array();
    //$anios[]='';
    //$anio_actual = date('Y');

    $facturas = Sds_cel_factura::findBySql($mysql_anios)->all();
    foreach($facturas as $factura)
        {
            $anios[$factura->periodo_anio]=$factura->periodo_anio;
        }

    /* for ($i = ($anio_actual-1); $i <= ($anio_actual+1); $i++) {
        $anios[$i]=$i;
    } */

// ESTO ES PARA EL FILTRO DE LA FECHA DE CARGA

    $layoutDate = <<< HTML
        {input1}
        {input2}
        <span class="input-group-addon kv-date-remove">
            <i class="glyphicon glyphicon-remove"></i>
        </span>
    HTML;

// ESTO ES PARA EL FILTRO DE LOS MESES
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
        $meses = array(1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio', 8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre');
// ESTO ES PARA ORGANIZAR EL ANCHO DE LAS COLUMNAS
    $columna1 = '50%';
    $columna2 = '15%';
    $columna3 = '10%';
    $columna4 = '15%';
    $columna5 = '10%';
return [
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idfactura',
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'cuenta',
        'value' => function ($model) {
            $nrolinea = $model->cuenta;
            if ($nrolinea != null) {
                $linea = Telefonia_vista_integradora::findOne($nrolinea);
                return $linea->cuenta;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Telefonia_vista_integradora::findBySql($mysql_cuentas)->all(), 'lineanro', 'cuenta'),

        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'cuenta...'],
        'format' => 'raw',
        'width' => $columna1,
    ],


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
        'width' => $columna2,
        'filter' => $meses,//uso un array para el filter
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'periodo_anio',
        'filter' => $anios,
        'width' =>  $columna3,
        'label' => 'Periodo Año',
    ],
    [
        'attribute' => 'fecha_carga',
        'width' => '15%',
        'label' => 'Fecha de carga',
        'value' => function ($model) {
            $fc = date_create($model->fecha_carga);
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
        ]),
        'width' =>  $columna4,
    ],

    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'observaciones',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{update} {importar} {delete}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
        'buttons' => [

            'importar' => function ($url, $model) {
                $url =  Url::to(['/sds_cel_factura/importar', 'idfactura' => $model->idfactura]);
                return Html::a('<span class= "fas fa-file-upload"></span>', $url, [
                    'title' => "Importar Items de Factura",
                    'role' => 'modal-remote', 
                    'data-pjax' => 0, 
                    //'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
/*             'importar1' => function ($url, $model) {
                $url =  Url::to(['importar_factura_items.php', 'idregistro' => $model->idfactura]);
                //return Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote']);
                return Html::a('<span class= "fa fa-file-upload"></span>', 
                    ['importar_factura_items','idregistro' => $model->idfactura], 
                    [
                    'title' => "Importar Items de Factura",
                    'role' => 'modal-remote', 
                    //'data-pjax' => 0, 
                    //'target' => '_blank',
                    //'data-toggle' => 'tooltip',
                ]);
            }, */
        'width' =>  $columna5,

        ],
    ],

];   