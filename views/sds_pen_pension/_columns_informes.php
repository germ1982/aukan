<?php

use app\models\Sds_com_barrio;
use app\models\Sds_com_configuracion;
use yii\helpers\Url;
use app\models\Sds_com_persona;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use kartik\helpers\Html;


$columna1 = '7%';
$columna2 = '23%';
$columna3 = '25%';
$columna4 = '20%';
$columna5 = '12%';
$columna6 = '12%';



return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'documento',
        'width' => $columna1,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idpersona',
        'value' => function($model)
            {
                $persona = Sds_com_persona::findOne($model->idpersona);
                if(!($persona==null))
                    {$aux = "$persona->apellido, $persona->nombre";}
                else    
                    {$aux = "No encontrado";}

                return $aux;
            },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_persona::find()->where("idpersona in (select idpersona from sds_pen_pension)")->orderBy(['apellido' => SORT_ASC, 'nombre' => SORT_ASC])->all(), 
            'idpersona', 
            function ($model) {
                return "$model->apellido, $model->nombre";
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Persona...'],
        'format' => 'raw',
        'width' => $columna2,
        'label' => 'Pensionado',
    ],

    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idlocalidad',
        'value' => function($model)
            {
                $localidad = Sds_com_localidad::findOne($model->idlocalidad);
                if(!($localidad==null))
                    {$aux = "$localidad->descripcion";}
                else    
                    {$aux = "(no definido)";}

                return $aux;
            },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_localidad::find()->where("idlocalidad in (select idlocalidad from sds_pen_pension)")->orderBy(['descripcion' => SORT_ASC])->all(), 
            'idlocalidad','descripcion' 
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'localidad...',],
        'format' => 'raw',
        'width' => $columna3,
        'label' => 'Localidad',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'idbarrio',
        'value' => function($model)
            {
                $barrio = Sds_com_barrio::findOne($model->idbarrio);
                if(!($barrio==null))
                    {$aux = "$barrio->nombre";}
                else    
                    {$aux = "(no definido)";}

                return $aux;
            },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_barrio::find()->
            where($searchModel['idlocalidad'] ? 
                    "idbarrio in (select idbarrio from sds_pen_pension) and idlocalidad = ".$searchModel['idlocalidad'] : 
                    "idbarrio in (select idbarrio from sds_pen_pension)")
            ->orderBy(['nombre' => SORT_ASC])->all(), 
            'idbarrio','nombre' 
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Barrio...'],
        'format' => 'raw',
        'width' => $columna4,
        'label' => 'Barrio',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'programa',
        'value' => function ($model) {

            if(!($model->programa==null))
                {
                    $config = Sds_com_configuracion::findOne($model->programa);
                    $aux = $config->descripcion;
                }
            else    
                {$aux = "No definido";}

            return $aux;
        },
        'width' => $columna5,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_PENSION_PROGRAMA), 'idconfiguracion', 'descripcion'),
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado',
        'value' => function ($model) {

            if(!($model->estado==null))
                {
                    $config = Sds_com_configuracion::findOne($model->estado);
                    $aux = $config->descripcion;
                }
            else    
                {$aux = "No definido";}

            return $aux;
        },
        'width' => $columna6,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_PENSION_ESTADO), 'idconfiguracion', 'descripcion'),
    ],
]; 
?>
