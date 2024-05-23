

<?php

use yii\helpers\Url;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_usuario;
use app\models\Sds_bdc_equipo;
use app\models\Sds_com_persona;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$columna0 = '1%';
$columna1 = '9%';
$columna2 = '8%';
$columna3 = '18%';
$columna4 = '18%';
$columna5 = '7%';
$columna6 = '7%';
$columna7 = '7%';
$columna8 = '7%';
$columna9 = '7%';
$columna10 = '11%';


return [
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => $columna0,
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return $model->observaciones != null ?
                Yii::$app->controller->renderPartial('_expand', ['model' => $model]) : "";
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'width' => $columna1,
        'attribute' => 'ip_completa',
        'format' => 'raw',
        'value'=>function($model){
            $gif = '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';
            $ip_addr = $model->ip_completa; 
            $aux = str_replace(".","_",$ip_addr);
            $boton = Html::button('<span class="fas fa-network-wired"></span>', [
                'title' => "Ping",
                'data-toggle' => 'tooltip',
                'class' => 'btn btn-link',
                'onclick' => "setear_ip($ip_addr,$aux)",

            ]);
            $aux = "<div id='div_ip_$aux'>$ip_addr</div>";
            //$aux = "<div id='div_ip_$aux'><img id='id_gif_$aux' src='data:image/gif;base64,$gif' alt=''  height='10px' />$ip_addr</div>";
            return $aux;
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'asignacion',
        'label' => 'Asignación',
        'value' => function ($model) {
            $idasignacion = $model->asignacion;

            if($model->idequipo)
            {
                $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                if ($equipo->tipo != null) {
                    $idasignacion = $equipo->tipo;
                }
            }
            if ($idasignacion != null) {
                $asignacion = Sds_com_configuracion::findOne($idasignacion);
                return $asignacion->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::findBySql(
            "select idconfiguracion, descripcion from sds_com_configuracion 
            where idconfiguracion = 1 
            or idconfiguraciontipo = " . Sds_com_configuracion_tipo::TIPO_ASIGNACION_IP . " and activo = 1 
            or idconfiguraciontipo = " . Sds_com_configuracion_tipo::BDC_TIPO_EQUIPO . " and activo = 1 
            order by descripcion"
        )->all(), 'idconfiguracion', 'descripcion'),
        //'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_ASIGNACION_IP), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
        'format' => 'raw',
        'width' => $columna2,
    ],
    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'iddispositivo',
        'label' => 'Sector',
        'value' => function ($model) {
            $idcontacto = $model->idcontacto;
            if ($idcontacto != null) {
                $contacto = Mds_org_contacto::findOne($idcontacto);
                $iddispositivo = $contacto->iddispositivo;

                if ($iddispositivo != null) {
                    $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
                    $organismo = Mds_org_organismo::findOne($dispositivo->idorganismo);
                    return $dispositivo->descripcion . " - " . $organismo->descripcion;
                }
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Mds_org_dispositivo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
            'iddispositivo',
            function ($model) {
                $organismo = Mds_org_organismo::findOne($model->idorganismo);
                return $model->descripcion . " - " . $organismo->descripcion;
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Sector...'],
        'format' => 'raw',
        'width' => $columna3,
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'organismo',
        'label' => 'Organismo',
        'value' => function ($model) {
            if ($model->idequipo != null) 
                {
                    $equipo = Sds_bdc_equipo::findOne($model->idequipo);

                    if ($equipo->idorganismo != null) {
                        $organismo = Mds_org_organismo::findOne($equipo->idorganismo);
                        return $organismo->descripcion;
                    }
                }
            else
                {
                    $idcontacto = $model->idcontacto;
                    if ($idcontacto != null) {
                        $contacto = Mds_org_contacto::findOne($idcontacto);
                        $iddispositivo = $contacto->iddispositivo;
        
                        if ($iddispositivo != null) {
                            $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
                            $organismo = Mds_org_organismo::findOne($dispositivo->idorganismo);
                            return $organismo->descripcion;
                        }
                    }
                }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
            'idorganismo','descripcion',
 
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Sector...'],
        'format' => 'raw',
        'width' => $columna3,
    ],

    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idpersona',
        'label' => 'Usuario',
        'value' => function ($model) {
            if ($model->idequipo != null) 
                {
                    $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                    if ($equipo->usuario != null) {
                        $contacto=Mds_org_contacto::findOne($equipo->usuario);
                        if($contacto!=null){
                            $usuario=Sds_com_persona::findOne($contacto->idpersona);
                            return $usuario->apellido.' '.$usuario->nombre;
                        }
                    }
                }
            else
                {
                    $idcontacto = $model->idcontacto;
                    if ($idcontacto != null) {
                        $contacto = Mds_org_contacto::findOne($idcontacto);
                        $idpersona = $contacto->idpersona;
                        if ($idpersona != null) {
                            $persona = Sds_com_persona::findOne($idpersona);
                            $aux = "$persona->apellido, $persona->nombre";
                            return $aux;
                        }
                    }
                }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_persona::findBySql(
                "Select idpersona, apellido,nombre from sds_com_persona 
                where idpersona in (select idpersona from mds_org_contacto where idcontacto in (select idcontacto from sds_reg_ip)) 
                OR
                idpersona in (select idpersona from mds_org_contacto where idcontacto in (select usuario from sds_bdc_equipo)) 
                order by apellido, nombre"
            )->all(),
            'idpersona',
            function ($model) {
                return "$model->apellido, $model->nombre";
            }
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Persona...'],
        'format' => 'raw',
        'width' => $columna4,
    ],

    /* [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'usuario',
        'label' => 'Usuario',
        'value' => function ($model) {
            if ($model->idequipo != null) {
                $equipo = Sds_bdc_equipo::findOne($model->idequipo);

                if ($equipo->usuario != null) {
                    $contacto=Mds_org_contacto::findOne($model->usuario);
                    if($contacto!=null){
                        $usuario=Sds_com_persona::findOne($contacto->idpersona);
                        return $usuario->apellido.' '.$usuario->nombre;
            }
                }
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' =>  ArrayHelper::map(
            Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
            'idorganismo','descripcion',
 
        ),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Seleccionar Sector...'],
        'format' => 'raw',
        'width' => $columna4,
    ], */
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'sistema_operativo',
        'value' => function ($model) {
            $id_sistema_operativo = $model->sistema_operativo;

            if($model->idequipo)
            {
                $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                if ($equipo->sistema_operativo != null) {
                    $id_sistema_operativo = $equipo->sistema_operativo;
                }
            }
            if ($id_sistema_operativo != null) {
                $sistema_operativo = Sds_com_configuracion::findOne($id_sistema_operativo);
                return $sistema_operativo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_SISTEMA_OPERATIVO), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'SO...'],
        'format' => 'raw',
        'width' => $columna5,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'procesador',
        'label' => 'Micro',
        'value' => function ($model) {
            $id_procesador = $model->procesador;
            if($model->idequipo)
            {
                $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                if ($equipo->procesador != null) {
                    $id_procesador = $equipo->procesador;
                }
            }
            
            if ($id_procesador != null) {
                $procesador = Sds_com_configuracion::findOne($id_procesador);
                return $procesador->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_PROCESADOR), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Micro...'],
        'format' => 'raw',
        'width' => $columna6,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'memoria',
        'label' => 'RAM',
        'value' => function ($model) {
            $id_memoria = $model->memoria;

            if($model->idequipo)
            {
                $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                if ($equipo->memoria != null) {
                    $id_memoria = $equipo->memoria;
                }
            }

            if ($id_memoria != null) {
                $memoria = Sds_com_configuracion::findOne($id_memoria);
                return $memoria->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_MEMORIA), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'RAM'],
        'format' => 'raw',
        'width' => $columna7,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'disco',
        'value' => function ($model) {
            $id_disco = $model->disco;

            if($model->idequipo)
            {
                $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                if ($equipo->disco != null) {
                    $id_disco = $equipo->disco;
                }
            }

            if ($id_disco != null) {
                $disco = Sds_com_configuracion::findOne($id_disco);
                return $disco->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_DISCO), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'disco...'],
        'format' => 'raw',
        'width' => $columna8,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'conectividad',
        'label' => 'Conect.',
        'value' => function ($model) {
            $id_conectividad = $model->conectividad;
            if($model->idequipo)
            {
                $equipo = Sds_bdc_equipo::findOne($model->idequipo);
                if ($equipo->conectividad != null) {
                    $id_conectividad = $equipo->conectividad;
                }
            }
            if ($id_conectividad != null) {
                $conectividad = Sds_com_configuracion::findOne($id_conectividad);
                return $conectividad->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::TIPO_CONECTIVIDAD), 'idconfiguracion', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'conec'],
        'format' => 'raw',
        'width' => $columna9,
    ],
    
    [
        'class' => 'kartik\grid\ActionColumn',
        'width' => $columna10,
        'dropdown' => false,
        'vAlign' => 'middle',
        /* 'template'=>function($model)
                    {
                        if( $model->idequipo)
                            { return '{view} {ping}';}
                        else
                            {return '{view} {update} {delete}{ping} {liberar}';};
                    }, */
        'template' => ' {view} {update} {delete}{ping} {liberar}',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip', 'visible' => false],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Update', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Delete',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => 'Are you sure?',
            'data-confirm-message' => 'Are you sure want to delete this item'
        ],
        'buttons' => [

            'update' => function ($url, $model) {
                $url =  Url::to(['/sds_reg_ip/update', 'id' => $model->idip]);
                return $model->idequipo!=null ? '' : Html::a('<span class= "glyphicon glyphicon-pencil fa-lg" style="padding:1%;"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 1, 'title' => 'Editar Expediente',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'delete' => function ($url, $model) {
                $url =  Url::to(['/sds_reg_ip/delete', 'id' => $model->idip]);
                return $model->idequipo!=null ? '' : Html::a('<span class= "glyphicon glyphicon-trash fa-lg" style="padding:1%;"></span>', $url, [
                    'role' => 'modal-remote', 'data-pjax' => 1, 'title' => 'Borrar Expediente',
                    'data-toggle' => 'tooltip',
                ]);
            },

            'ping' => function ($url, $model) {

                $ip_addr = $model->ip_completa; 
                $ip_div = str_replace(".","_",$ip_addr);
                $ip_div = "div_ip_$ip_div";

                return Html::button('<span class="fas fa-network-wired"></span>', [
                    'title' => "Ping",
                    'data-toggle' => 'tooltip',
                    'class' => 'btn btn-link',
                    'onclick' => "setear_ip('$ip_addr','$ip_div')",
                ]);
            },
            'liberar' => function ($url, $model) {
                $url =  Url::to(['/sds_reg_ip/liberar_ip', 'id' => $model->idip]);

                return $model->idequipo!=null ? '' : Html::a(
                    '<i class="fas fa-recycle"></i>',
                    $url,
                    ['data-pjax' => 1, 
                    'class' => 'btn btn-link', 
                    'role' => 'modal-remote',
                    'title' => 'liberar Ip']
                );

            },
            
        ],
    ],

];
?>
