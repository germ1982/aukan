<?php

use app\models\Mds_org_contacto;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_deposito;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_persona;
use app\models\Sds_stk_recepcion_item;
use app\models\Sds_stk_movimiento;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$columna1 = '10%';
$columna2 = '25%';
$columna3 = '10%';
$columna4 = '20%';
$columna5 = '20%';
$columna6 = '5%';
$columna7 = '10%';

$template  = "{view}";

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
$usuario = Mds_seg_usuario::findOne($idusuario);
$id_organismo = $usuario->organismo_stock;


return [
    [
        'attribute' => 'fecha_hora',
        'width' => $columna1,
        'label' => 'Fecha',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            //$fc = date_format($fc, 'd/m/Y H:i');
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

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idarticulo',
        'width' => $columna2,
        'label' => 'Articulo',
        'value' => function ($model) {
                $idarticulo = $model->idarticulo;
                if ($idarticulo != null) 
                    {
                        $articulo = Sds_stk_articulo::findOne($idarticulo);
                        return $articulo->descripcion;
                    }
                return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Sds_stk_articulo::findBySql("select idarticulo,descripcion from sds_stk_articulo where idarticulo in(select DISTINCT(idarticulo) from sds_stk_movimiento) and organismo = $id_organismo order by descripcion")->all(),
            'idarticulo','descripcion'
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar articulo...'],


    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo',
        'width' => $columna3,
        'value' => function ($model)
        {
            $aux = '';
            switch($model->tipo)
            {
                case Sds_stk_movimiento::TIPO_INGRESO:{
                    $aux = 'Ingreso';
                    break;
                }
                case Sds_stk_movimiento::TIPO_REUBICACION:{
                    $aux = 'Reubicación';
                    break;
                }
                case Sds_stk_movimiento::TIPO_EGRESO:{
                    $aux = 'Egreso';
                    break;
                }
                case Sds_stk_movimiento::TIPO_CONVERSION:{
                    $aux = 'Conversion';
                    break;
                }   
            }
            return $aux;
        },
        'filter' => ['1' => 'Ingreso', '2' => ' Reubicacion', '3' => ' Egreso', '4' => 'Conversion'],

    ],


    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'origen',
        'width' => $columna4,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'destino',
        /* 'value'=>function($model){
            if($model->tipo==Sds_stk_movimiento::TIPO_EGRESO){
                $persona=Sds_com_persona::findBySql(
                    "SELECT UPPER(p.nombre) nombre, UPPER(p.apellido) apellido 
                    FROM sds_stk_entrega e
                    LEFT JOIN sds_stk_entrega_item ei ON e.identrega=ei.identrega
                    LEFT JOIN sds_com_persona p ON e.idpersona=p.idpersona
                    WHERE ei.identregaitem=".$model->item_entrega
                )->one();
                return $persona->nombre.', --'.$persona->apellido;
            }
            return $model->destino;
        }, */
        'width' => $columna5,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cantidad',
        'width' => $columna6,

    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'organismo',
        'width' => $columna6,
        'visible' => false,
        'value' => function ($model) {
            $deposito_ingreso = '0';
            $deposito_egreso = '0';

            $iddeposito = $model->deposito_ingreso;
            if ($iddeposito != null) 
                {
                    $deposito_ingreso = Sds_stk_deposito::findOne($iddeposito)->idorganismo;   
                }

            $iddeposito = $model->deposito_egreso;
            if ($iddeposito != null) 
                {
                    $deposito_egreso = Sds_stk_deposito::findOne($iddeposito)->idorganismo;
                }
            return "$deposito_ingreso - $deposito_egreso";
        },
        'filter' => [ '' => 'Todos','1' => 'Del Usuario','0' => 'Otros'],
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view} {update} {delete} {conversion} {generarei}',
        'dropdown' => false,
        'width' => $columna7,
        
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
                          'data-confirm-title'=>'esta seguro?',
                          'data-confirm-message'=>'Esta seguro que desea eliminar este movimiento?'], 
        'buttons' => [
                        'update' => function ($url, $model) {
                            $url =  Url::to(['/sds_stk_movimiento/update', 'id' => $model->idmovimiento]);
                            $ban = 1;
                            $ban = $model->tipo == 12 ? $ban : 0;
                            $ban = $model->generado == 0 ? $ban : 0;
                            return $ban == 1 ? Html::a('<i class="glyphicon glyphicon-pencil"></i>',$url,
                            ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip']):'';
                        },
                        'delete' => function ($url, $model) {
                            $url =  Url::to(['/sds_stk_movimiento/delete', 'id' => $model->idmovimiento]);
                            $ban = 1;
                            $ban = $model->tipo == 2 ? $ban : 0;
                            $ban = $model->generado == 0 ? $ban : 0;
                            return $ban == 1 ? Html::a('<span class= "glyphicon glyphicon-trash"></span>',$url,[
                                'role' => 'modal-remote', 'title' => 'Eliminar',
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-request-method' => 'post',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => '¿Está seguro?',
                                'data-confirm-message' => '¿Está seguro de que quiere eliminar este item?'
                            ]):'';
                        },
                        'conversion' => function($url, $model){
                            $url =  Url::to(['/sds_stk_movimiento/conversion_view', 'id' => $model->idmovimiento]);
                            return $model->tipo == Sds_stk_movimiento::TIPO_CONVERSION ? 
                            Html::a('<i class="glyphicon glyphicon-transfer"></i>',$url,
                            ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Ver Conversion', 'data-toggle' => 'tooltip']):'';
                        },
                        'generarei' => function ($url, $model) {
                            $ban = 1;

                            $deposito_destino = Sds_stk_deposito::findOne($model->deposito_ingreso);

                            $ban = ($deposito_destino!=null && $deposito_destino->idresponsable!=null) ? $ban : 0;

                            $ban = ($model->generado==0) ? $ban : 0;

                            $ban = $model->tipo == Sds_stk_movimiento::TIPO_REUBICACION ? $ban : 0;

                            $ri = Sds_stk_recepcion_item::findOne($model->item_recepcion);

                            $ban = ($ri!=null && $ri->identrega==null) ? 0 : $ban;
            
                            $url =  Url::to(['/sds_stk_movimiento/generar_ei', 'idmovimiento'=>$model->idmovimiento]);
            
                            return $ban==1 ? Html::a('<span class= "glyphicon glyphicon-arrow-right"></span>', $url, [
                                'title' => "Generar EI",
                                'role' => 'modal-remote',
                                'data-toggle' => 'tooltip',
                                'data-confirm-title' => '¿Está seguro?',
                                'data-confirm-message' => 'Se va a generar la entrega intermedia, esta de acuerdo?'
                            ]) : '';
                        },
                    ],
    ],

];   