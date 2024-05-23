<?php

use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Mds_org_contacto;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use app\models\Sds_stk_articulo;
use app\models\Sds_stk_entrega;
use app\models\Sds_stk_entrega_item;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;
$columna1 = '8%';//fecha_hora
$columna2 = '12%';//idcontacto//responsable
$columna3 = '15%';//idpersona//destinatario
$columna4 = '5%';//acta_original//completa
$columna5 = '20%';//detalle_items
$columna6 = '5%';//ordenes//OC
$columna7 = '13%';//observaciones
$columna8 = '10%';//organizacion_social
$columna9 = '12%';//actions
return [
/*     [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'identrega',

    ], */
    [//fecha_hora
        'attribute' => 'fecha_hora',
        'width' => $columna1,
        'label' => 'Fecha',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
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
    [//idcontacto//responsable
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcontacto',
        'value' => function ($model) {
            $contacto = Mds_org_contacto::findOne($model->idcontacto);
            $persona = Sds_com_persona::findOne($contacto->idpersona);
            if (!($persona == null)) {
                $aux = "$persona->apellido, $persona->nombre";
            } else {
                $aux = "No encontrado";
            }

            return $aux;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_org_contacto::findBySql("select * from mds_org_contacto c 
                join sds_com_persona p on p.idpersona=c.idpersona where c.idcontacto in (SELECT idcontacto from sds_stk_entrega) order by nombre ASC, apellido ASC;")->all(),
            'idcontacto',
            function ($model) {
                return $model->nombre . " " . $model->apellido;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true, 'multiple' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Responsable...'],
        'format' => 'raw',
        'width' => $columna2,
        'label' => 'Responsable',
    ],
    [//idpersona//destinatario
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idpersona',
        'value' => function ($model) {
            $persona = Sds_com_persona::findOne($model->idpersona);
            if (!($persona == null)) {
                $periodo_prueba = $model->fecha_hora < Sds_stk_articulo::PERIODO_PRUEBA ? '(Período Prueba)' : '';
                $aux = "$persona->documento - $persona->apellido, $persona->nombre $periodo_prueba";
            } else {
                $aux = "No encontrado";
            }

            return $aux;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_persona::findBySql("select * from sds_com_persona p where p.idpersona in (SELECT idpersona from sds_stk_entrega) order by nombre ASC, apellido ASC;")->all(),
            'idpersona',
            function ($model) {
                return $model->documento . " - " . $model->nombre . " " . $model->apellido;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Destinatario...'],
        'format' => 'raw',
        'width' => $columna3,
        'label' => 'Destinatario',
    ],
    [//acta_original//completa
        'attribute' => 'acta_original',
        'width' => $columna4,
        'label' => 'Completa',
        'value' => function ($model) {
            if ($model->acta_original) {
                return "Si";
            } else {
                return "No";
            }
        },
        'filter' => ['1' => 'Si', '0' => 'No', '' => 'Todos'],
    ],
    [//detalle_items
        'attribute' => 'detalle_items',
        'width' => $columna5,
        'label' => 'Detalle',
        'format' => 'html',
    ],
    [//ordenes//OC
        'attribute' => 'ordenes',
        'width' => $columna6,
        'label' => 'OC',
        'format' => 'html',
    ],
    /*[
        'attribute' => 'mostrar',
        'format' => 'html',
        'visible' => false,
        'width' => $columna7,
    ], */
    [//observaciones
        'attribute' => 'observaciones',
        'width' => $columna7,
    ],
        /* [//organizacion_social
            'attribute' => 'organizacion_social',
            'width' => $columna8,
            'label' => 'Org. Social',
            'value' => function ($model) {return $model->organizacion_social ? Sds_com_configuracion::findOne($model->organizacion_social)->descripcion:'';},
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(
                Sds_com_configuracion::find()->where("idconfiguracion IN (SELECT sds_stk_entrega.organizacion_social from sds_stk_entrega)")->orderBy(['descripcion' => SORT_ASC])->all(), 
                'idconfiguracion','descripcion'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Tipo Norma Legal...'],
        ], */
    /*[
        'attribute' => 'acta_original',
    ],
    [
        'attribute' => 'generada',
    ], */

    [
        'attribute' => 'es_organizacion_social',
        'width' => $columna8,
        'label' => 'OS',
        'value' => function ($model) {
            if ($model->es_organizacion_social) {
                return "Si";
            } else {
                return "No";
            }
        },
        'filter' => ['1' => 'Si', '0' => 'No', '' => 'Todos'],
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => $columna9,
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{view} {update} {imprimir_acta_entrega} {items} {generarei} {generaref} {delete}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'ver', 'data-toggle' => 'tooltip'],
        /* 'updateOptions' => ['role' => 'modal-remote', 'title' => 'editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Esta seguro?',
            'data-confirm-message' => 'Esta seguro que desea eliminar esta entrega?'
        ], */
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_entrega/update', 'id'=>$model->identrega, 'generada' => $model->generada]);
                return  Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip']);
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_entrega/delete', 'id' => $model->identrega
            ]);
                return  $model->generada ? '' : Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Eliminar',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '¿Está seguro?',
                    'data-confirm-message' => '¿Está seguro de que quiere eliminar este item?'
                ]);
            },
            'imprimir_acta_entrega' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_entrega/imprimir_acta_entrega', 'identrega' => $model->identrega]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'title' => "Imprimir ",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'items' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_entrega/update', 'id' => $model->identrega, false, 'items' => 1]);
                return $model->generada ? '' : Html::a('<span class= "fas fa-cubes"></span>', $url, [
                    'title' => "Ver Solo Items ",
                    'role' => 'modal-remote', 'data-pjax' => 0,
                    'data-toggle' => 'tooltip',
                ]);
            },
            'generarei' => function ($url, $model) {
                $ban = 1;

                if($model->referente == 0)
                    {$ban = 0;}
                else
                {
                    $consulta = "SELECT  r.generada
                                    FROM sds_stk_entrega_item ei
                                    JOIN sds_stk_recepcion_item ri on ei.recepcion_item = ri.idrecepcionitem
                                    JOIN sds_stk_recepcion r on ri.idrecepcion = r.idrecepcion
                                    WHERE ei.identrega = $model->identrega";
                    
                    $e_items = Sds_stk_entrega::findBySql($consulta)->all();

                    if($e_items)
                    {
                        foreach ($e_items as $e_item) {
                        if($e_item->generada==0)
                            {$ban = 0;}
                        }
                    }
                else
                    {$ban = 0;}
                }

                if($model->generada == 1)
                    {$ban = 0;}

                $url =  Url::to(['/sds_stk_entrega/generar_ei', 'identrega'=>$model->identrega]);

                return $ban==1 ? Html::a('<span class= "glyphicon glyphicon-arrow-right"></span>', $url, [
                    'title' => "Generar EI",
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '¿Está seguro?',
                    'data-confirm-message' => 'Se va a generar la entrega intermedia, esta de acuerdo?'
                ]) : '';
            },
            
            'generaref' => function ($url, $model) {
                $ban = 1;

                if($model->referente == 1)
                    {$ban = 0;}

                $consulta = "SELECT  r.generada
                                FROM sds_stk_entrega_item ei
                                JOIN sds_stk_recepcion_item ri on ei.recepcion_item = ri.idrecepcionitem
                                JOIN sds_stk_recepcion r on ri.idrecepcion = r.idrecepcion
                                WHERE ei.identrega = $model->identrega";
                
                $e_items = Sds_stk_entrega::findBySql($consulta)->all();

                if($e_items)
                    {
                        foreach ($e_items as $e_item) {
                        if($e_item->generada==0)
                            {$ban = 0;}
                        }
                    }
                else
                    {$ban = 0;}
                
                if($model->generada == 1)
                    {$ban = 0;}
                $url =  Url::to(['/sds_stk_entrega/generar_ef', 'identrega'=>$model->identrega]);

                return $ban==1 ? Html::a('<span class= "glyphicon glyphicon-arrow-right"></span>', $url, [
                    'title' => "Generar EF",
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '¿Está seguro?',
                    'data-confirm-message' => 'Se va a generar la entrega final, esta de acuerdo?'
                ]) : '';
            },

        ],
    ],

];
