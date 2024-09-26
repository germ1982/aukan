<?php


use app\models\OrganismoDispositivo;
use app\models\Articulo;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use app\models\Empleado;
use app\models\Persona;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$mysql_persona = "SELECT e.idempleado, concat(p.apellido,' ',p.nombre) as persona 
                        FROM empleado e join personas p on e.idpersona = p.idpersona
                        where e.activo = 1
                        order by p.apellido, p.nombre";

return [

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idInventario',
        'width' => '5%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idarticulo',
        'value' => function ($model) {
            $articulo = Articulo::get_articulo($model->idarticulo);
            return   "$articulo->descripcion";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Articulo::get_articulos("inventario"), 'idarticulo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Articulo...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'value' => function ($model) {
            $iddispositivo = $model->iddispositivo;
            $dispositivo = OrganismoDispositivo::get_dispositivo($iddispositivo);
            return   "$dispositivo->descripcion";
        },
        'filterType' => GridView::FILTER_SELECT2,
        //'filter' => ArrayHelper::map(OrganismoDispositivo::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'iddispositivo', 'descripcion'),
        'filter' => ArrayHelper::map(OrganismoDispositivo::get_dispositivos('inventario'), 'iddispositivo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'dispositivo...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idempleado',
        'value' => function ($model) {
            $id = $model->idempleado;
            $empleado=Empleado::findOne($id);
            if ($id != null) {
                $persona = Persona::findOne($empleado->idpersona);               
               
                return "$persona->apellido $persona->nombre";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Persona::findBySql($mysql_persona)->all(), 'idpersona', 'nombre', 'apellido'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Empleado...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idestado',
        'value' => function ($model) {
            if($model->idestado){
            $estado = Configuracion::findOne($model->idestado);
            return   "$estado->descripcion";}
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Configuracion::get_configuraciones(ConfiguracionTipo::TIPO_ESTADO_ARTICULO), 'id_configuracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'estado...'],
        'format' => 'raw',
        'width' => '30%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} ',
        'width' => '10%',
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Delete',
            'data-confirm' => false,
            'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Esta Seguro?',
            'data-confirm-message' => 'Esta seguro que quiere eliminar este item?'
        ],
    ],

];
