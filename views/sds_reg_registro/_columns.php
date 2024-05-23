<?php
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Mds_org_contacto;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_persona;
use app\models\Sds_reg_tipo;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\helpers\Html;
use app\models\Sds_gis_capa_item;
use app\models\Sds_reg_movimiento;
use app\models\Sds_reg_registro;
use yii\base\Model;

function crear_celda($label, $contenido, $ancho){
    echo "
    <div class='col-xs-$ancho'>  
        <h6><b>$label</b></h6>
        <p style='padding: 3px 6px; font-size: 12px; line-height: 1.42857143; color: #555555; background-color: #fff; background-image: none; border: 1px solid #ccc; border-radius: 4px;'>
                $contenido
        </p>
    </div>";
}

$layoutDate = <<< HTML
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$permiso_stock = Mds_seg_usuario::getPermiso(Mds_seg_item::MODULO_STK_RECEPCION) != null ? 1 : 0;
$permiso_logs = Mds_seg_usuario::getPermiso(Mds_seg_item::MODULO_SYS_LOG) != null ? 1 : 0;

return [
   [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            return $model->problema != null ?
                Yii::$app->controller->renderPartial('_expand', ['model' => $model]) : "";
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'detailOptions' => ['class' => ''],
        'options' => ['style' => 'color:black'],
        'expandOneOnly' => true,
    ],
    [
        'attribute' => 'fecha_hora',
        'width' => '11%',
        'label' => 'Fecha',
        'value' => function ($model) {
            $fc = date_create($model->fecha_hora);
            $fc = date_format($fc, 'd/m/Y H:i');
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
        'attribute' => 'iddispositivo',
        'label' => 'Sector',
        'value' => function ($model) {

            $iddispositivo = $model->iddispositivo;

            if ($iddispositivo != null) {
                $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
                $organismo = Mds_org_organismo::findOne($dispositivo->idorganismo);
                return $dispositivo->descripcion . " - " . $organismo->descripcion;
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
        'width' => '25%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mis_edificios',
        'value' => function ($model) {
            $dispositivo = Mds_org_dispositivo::findOne($model->iddispositivo);
            $idcapaitem = $dispositivo->idcapaitem;
            if ($idcapaitem != null) {
                $edificio = Sds_gis_capa_item::findOne($idcapaitem);
                return $edificio->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => [1=>'Mis Edificios'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Edificio...'],
        'format' => 'raw',
        'width' => '18%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idpersona',
        'label' => 'Solicitante',
        'value' => function ($model) {
            return $model->usuario_solicitante != null ? Mds_org_contacto::getAyN($model->usuario_solicitante).Mds_org_contacto::get_internos($model->usuario_solicitante):"";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(
            Sds_com_persona::findBySql(
                "SELECT idpersona, apellido, nombre FROM sds_com_persona WHERE idpersona IN
                (SELECT idpersona FROM mds_org_contacto WHERE idcontacto IN
                (SELECT usuario_solicitante FROM sds_reg_registro AS reg INNER JOIN sds_reg_tipo AS tipo ON tipo.idtipo=reg.idtipo 
                    WHERE tipo.entidad=".Sds_reg_registro::ENT_INFORMATICA.")) 
                ORDER BY apellido, nombre"
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
        'width' => '12%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idtipo',
        'label' => 'Tipo',
        'value' => function ($model) {
            $idtipo = $model->idtipo;
            if ($idtipo != null) {
                $tipo = Sds_reg_tipo::findOne($idtipo);
                return $tipo->descripcion;
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_reg_tipo::find()->where(['entidad'=> Sds_reg_registro::ENT_INFORMATICA])->orderBy(['descripcion' => SORT_ASC])->all(), 'idtipo', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Tipo...'],
        'format' => 'raw',
        'width' => '8%'
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'registro_abierto',
        'label' => 'Pendiente',
        'value' => function ($model) {
            if ($model->registro_abierto == 1)
                return "Si";
            else
                return "No";
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => array('0' => "No", '1' => "Si", ' ' => "Ambos"),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => '...'],
        'format' => 'raw',
        'width' => '6%',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'label' => 'Técnico',
        'value' => function ($model) {
            $movimiento = Sds_reg_movimiento::find()->where("idregistro=$model->idregistro")->orderBy(["idmovimiento" => SORT_DESC])->limit(1)->one();
            if($movimiento)
                {
                    $usuario = Mds_seg_usuario::findOne($movimiento->idtecnico);
                    return $usuario->user;
                }
            return '';
        },
        'format' => 'raw',
        'width' => '6%',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{asignar_tecnico} {view} {update} {imprimir}' . ($permiso_stock ? ' {entregas}' : '') . ' {delete}' .
            ($permiso_logs ? ' {logs}' : ''),
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key, 'entidad' => $model->entidad]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'Ver', 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => 'Editar', 'data-toggle' => 'tooltip'],
        'deleteOptions' => [
            'role' => 'modal-remote', 'title' => 'Eliminar',
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => '¿Está seguro que desea eliminar este item?',
            'data-confirm-message' => '<span class="text-danger" style="margin:25%"><strong>Confirme que desea eliminar este item</strong></span>'
        ],
        'buttons' => [
            'imprimir' => function ($url, $model) {
                $url =  Url::to(['/sds_reg_registro/imprimir_registro', 'idregistro' => $model->idregistro]);
                return Html::a('<span class= "fas fa-print"></span>', $url, [
                    'title' => "Imprimir",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'entregas' => function ($url, $model) {
                $url =  Url::to(['/sds_reg_entrega/index', 'idregistro' => $model->idregistro]);

                return Html::a('<span class="fas fa-hand-holding"></span>', $url, [
                    'title' => "Entregas",
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ]);
            },
            'logs' => function ($url, $model) {
                $url =  Url::to([
                    '/mds_sys_log/index', 'id' => $model->idregistro,
                    'modulos' => "sds_reg_entrega,sds_reg_ip,sds_reg_registro,sds_reg_registro_autosolicitud,sds_reg_movimiento"
                ]);
                return Html::a('<span class="fas fa-clipboard-list"></span>', $url, [
                    'title' => "Logs",
                    'role' => 'post', 'data-pjax' => 0, 
                    'data-toggle' => 'tooltip',
                ]);
            },
            'asignar_tecnico' => function ($url, $model) {
                $url =  Url::to(['/sds_reg_movimiento/asignar_tecnico', 'idregistro' => $model->idregistro]);
                return Html::a('<span class="far fa-hand-paper"></span>', $url, [
                    'title' => "Voy Yo",
                    'role' => 'modal-remote',
                    'data-toggle' => 'tooltip',
                ]);
            },
        ],
        'width' => '10%',
    ],
];
