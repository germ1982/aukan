<?php
use yii\helpers\Url;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;
use app\models\Mds_org_contacto;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$filterTipo = ArrayHelper::map(
    Sds_com_configuracion::getConfiguraciones(
        ($searchModel->medicina==0 ? Sds_com_configuracion_tipo::TIPO_CONTACTO_DOCUMENTO_TIPO : Sds_com_configuracion_tipo::DOC_MEDICINA_LABORAL)
    ), 'idconfiguracion', 'descripcion');

function getMedicina(){
    if(isset($_GET['medicina'])){
        return $_GET['medicina'];
    }else{
        return 0;
    }
}

return [
        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'iddocumento',
        'width' => '3%',
        'label' =>'Id',
    ],
    [
        'attribute' => 'fecha',
        'width' => '10%',
        'value' => function ($model) {
            $fc = date_create($model->fecha);
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
        'attribute'=>'idusuario',
        'label' => 'Persona',
        'value' => function ($model) {
            $idusuario = $model->idusuario;
            if($idusuario!=null)
                {
                    $usuario = Mds_seg_usuario::findOne($idusuario);
                    return $usuario->user;
                }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Mds_seg_usuario::find()->orderBy(['user' => SORT_ASC])->all(), //el order debe ser asi'apellido' => SORT_ASC, 'nombre' => SORT_ASC etc
            'idusuario', //ca siempre tiene que ir el id a buscar en el search
             'user',   ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Persona...'],
        'format' => 'raw',
        'label'=>'Usuario',
        'width' => '10%',
    ], 
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'nomAp',
        'label' => 'Persona',       
        'filter' => true,
        'format' => 'raw',
        'width' => '20%',
    ],  
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'tipo',
        'value' => function ($model){
            if ($model->tipo != null) {
                $tipo = Sds_com_configuracion::findOne($model->tipo);
                if($tipo!=null){
                    return $tipo->descripcion;
                }
            }
            return "-";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => $filterTipo,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
        'format' => 'raw',
        'width' => '10%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
        'width' => '10%',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado',
        'value' => function ($model){
            $estado = Sds_com_configuracion::findOne($model->estado);
            if($estado!=null){
                return $estado->descripcion;
            } 
            return "-";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ESTADO_DOCUMENTO), 
            'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Estado...'],
        'format' => 'raw',
        'width' => '10%'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'detalle',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view}{update}{delete}',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
                return Url::to([$action, 'id'=>$key, 'medicina' => getMedicina()]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'¡Está a punto de eliminar el item!',
                          'data-confirm-message'=>'<div class="text-center">
                            <b>¿Está seguro de esto?</b><br><span class="text-danger"><b>Haga clik en OK para proceder</b></span></div>'], 
    ],

];   