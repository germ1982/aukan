<?php

use app\models\Mds_seg_usuario;
use yii\helpers\Url;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_stk_articulo;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use kartik\helpers\Html;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$herramientas = Sds_stk_articulo::find()->where("idarticulo in (SELECT idarticulo from sds_stk_devolucion)")->orderBy(["descripcion" => SORT_ASC])->all();
$responsables_entrega = Mds_seg_usuario::findBySql("SELECT idusuario, CONCAT(apellido,' ', nombre) as apellido from mds_seg_usuario WHERE idusuario in (SELECT responsable_entrega from sds_stk_devolucion) order by apellido, nombre")->all();
//$responsables_entrega = Mds_seg_usuario::find()->where("idusuario in (SELECT responsable_entrega from sds_stk_devolucion)")->orderBy(["apellido" => SORT_ASC, "nombre" => SORT_DESC])->all();
$responsables_devolucion = Mds_seg_usuario::findBySql("SELECT idusuario, CONCAT(apellido,' ', nombre) as apellido from mds_seg_usuario WHERE idusuario in (SELECT responsable_devolucion from sds_stk_devolucion) order by apellido, nombre")->all();
$contactos = Mds_org_contacto::findBySql("SELECT c.idcontacto as idcontacto, concat(p.apellido,' ',p.nombre) as apellido
                                            FROM mds_org_contacto c
                                            JOIN sds_com_persona p on c.idpersona = p.idpersona
                                            Where idcontacto in (SELECT destinatario from sds_stk_devolucion)
                                            order by p.apellido, p.nombre")->all();
$estados = Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_DEVOLUCION);

function haPasadoMediaHora($fechaHora) {
    // Convierte la fecha y hora en un objeto DateTime
    $fechaHoraObj = new DateTime($fechaHora);
    
    // Obtiene la fecha y hora actual
    $fechaActual = new DateTime();
    
    // Calcula la diferencia en minutos entre la fecha actual y la fecha y hora dada
    $intervalo = $fechaHoraObj->diff($fechaActual);
    $minutosPasados = $intervalo->days * 24 * 60 + $intervalo->h * 60 + $intervalo->i;
    
    // Comprueba si han pasado al menos 30 minutos (1800 segundos)
    return $minutosPasados >= 30;
}

function esInformatica($idcontacto){
    $contacto = Mds_org_contacto::findOne($idcontacto);
    $dispositivo  = Mds_org_dispositivo::findOne($contacto->iddispositivo);
        //return $dispositivo->idorganismo;
    return ($dispositivo->idorganismo === 103) ? 1 : 0;

}

$columna0 = '10%';//fecha_entrega
$columna1 = '15%';//Herramienta
$columna2 = '15%';//responsable entrega
$columna3 = '15%';//destinatario
$columna4 = '10%';//fecha_devolucion
$columna5 = '15%';//responsable_devolucion
$columna6 = '15%';//estado
$columna7 = '5%';//accion


return [
    [
        'attribute' => 'fecha_hora_entrega',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora_entrega);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesdee',
            'attribute2' => 'fhastae',
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
        'width' => $columna0,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idarticulo',
        'value' => function ($model) {return Sds_stk_articulo::findOne($model->idarticulo)->descripcion;},
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($herramientas, 'idarticulo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Herramienta...'],
        'width' => $columna1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'responsable_entrega',
        'value' => function ($model) {return Mds_seg_usuario::getNameUser($model->responsable_entrega);},
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($responsables_entrega, 'idusuario', 'apellido'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Responsable Entrega...'],
        'width' => $columna2,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'destinatario',
        'value' => function ($model) {return Mds_org_contacto::getAyN($model->destinatario);},
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($contactos, 'idcontacto', 'apellido'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Herramienta...'],
        'width' => $columna3,
    ],

    [
        'attribute' => 'fecha_hora_devolucion',
        'value' => function ($model) {
            if($model->responsable_devolucion==null){return '';}
            $fc = date_create($model->fecha_hora_devolucion);
            $fc = date_format($fc, 'd/m/Y H:i');
            return $fc;
        },
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fdesded',
            'attribute2' => 'fhastad',
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
            'width' => $columna4,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'responsable_devolucion',
        'value' => function ($model) {return Mds_seg_usuario::getNameUser($model->responsable_devolucion);},
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($responsables_devolucion, 'idusuario', 'apellido'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Responsable devolucion...'],
        'width' => $columna5,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'estado',
        'value' => function ($model) {return $model->estado ? Sds_com_configuracion::findOne($model->estado)->descripcion : '';},
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map($estados, 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'width' => $columna6,
    ],


    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => ' {view} {update} {imprimir_acta_entrega} {delete}',

        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Editar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Esta seguro?',
                          'data-confirm-message'=>'Esta Seguro que va a eliminar este item?'], 
        'width' => $columna7,
        'buttons' => [
            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_devolucion/update', 'id'=>$model->iddevolucion]);
                $boton_editar = Html::a('<span class= "glyphicon glyphicon-pencil"></span>', $url, ['data-pjax' => 1, 'role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip']);
                if($model->responsable_devolucion){
                        return haPasadoMediaHora($model->fecha_hora_entrega) ?  '' : $boton_editar;
                }
                else{
                    return   $boton_editar;
                }
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_devolucion/delete', 'id' => $model->iddevolucion]);
                $boton_eliminar = Html::a('<span class= "glyphicon glyphicon-trash"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Eliminar',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => '¿Está seguro?',
                    'data-confirm-message' => '¿Está seguro de que quiere eliminar este item?'
                ]);
                return haPasadoMediaHora($model->fecha_hora_devolucion) ?  '' : $boton_eliminar;
            },

            'imprimir_acta_entrega' => function ($url, $model) {
                $url =  Url::to(['/sds_stk_devolucion/imprimir_acta_entrega', 'identrega'=>$model->iddevolucion]);
                $boton_acta =  Html::a('<span class= "fas fa-print"></span>', $url, [
                    'title' => "Imprimir ",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);


                return esInformatica($model->destinatario)==0 ? $boton_acta : '';
            },

        ],
    ],

];   