<?php

use app\models\Mds_cap_instancia;
use app\models\Mds_cap_persona;
use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_localidad;
use app\models\Sds_com_persona;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Mds_cap_inscripcion;
use yii\helpers\Html; /*agregado por Luis Garcia*/
use app\models\Mds_seg_usuario_rol;
use kartik\date\DatePicker;

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$idcontacto  = Yii::$app->user->identity->idcontacto;
$idusuario = Yii::$app->user->identity->idusuario;
$permiso_global = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GLOBAL . ")")->one();
$permiso_global = $permiso_global != null ? 1 : 0;

$el_usuario = Yii::$app->user->identity;
// analizando el rol


$permiso_cert = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GENERAR_CERTIF . ")")->one();
//'template' =>$permiso_cert == null? '{view} {update}': '{view} {update} {certificado}', 
if ($permiso_cert == null) // no tiene permisos para  generar certificados
{
    $tiene_perm_cert = false;
} else // tiene permiso para generar certificados
{
    $tiene_perm_cert = true;
}

$permiso_solo_lectura_cert = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_DESCARGAR_CERTI . ")")->one();


if ($permiso_solo_lectura_cert == null) {
    $tiene_perm_readonly_cert = false;
} else {
    $tiene_perm_readonly_cert = true;
}


$layoutDate = <<< HTML
        
    {input1}
    {input2}
    <span class="input-group-addon kv-date-remove">
        <i class="glyphicon glyphicon-remove"></i>
    </span>
HTML;

$column1='4%';
$column2='6%';
$column3='18%';
$column4='10%';
$column5='7%';
$column6='13%';
$column7='7%';
$column8='25%';
$column9='5%';
$column10='5%';


return [
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idinscripcion',
        'width' => $column1,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dni',
        'label' => 'DNI',
        'width' => $column2,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'persona',
        'format' => 'raw',
        'width' => $column3,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'mail',
        'width' => $column4,
        /* 'value' => function ($model) {
            $personacap = $model->idpersonacap0;
            if ($personacap != null) {
                return  $personacap->mail;
            }
            return "";
        }*/
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'telefono',
        'width' => $column5,
        /* 'value' => function ($model) {
            $personacap = $model->idpersonacap0;
            if ($personacap != null) {
                return  $personacap->telefono;
            }
            return "";
        }*/
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idlocalidad',
        'label' => 'Localidad',
        'value' => function ($model) {
            $localidad = $model->idlocalidad;
            $localidad = Sds_com_localidad::findOne($localidad);
            $aux = $localidad->descripcion;
            return $aux;
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ArrayHelper::map(Sds_com_localidad::find()->orderBy(['descripcion' => SORT_ASC])->all(), 'idlocalidad', 'descripcion'),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'Localidad...'],
        'format' => 'raw',
        'width' => $column6,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'dato_adicional',
        'label' => 'Dato Adicional',
        'hidden' => true,
        /*ANOTAPRI - Oculto el dato adicional de la grilla, 
        pero al exportarlo aparece en el excel*/
    ],

    [
        'attribute' => 'fecha_inscripcion',
        'label' => 'Fecha de Inscripcion',
        'value' => function ($model) {
            $fc = date_create($model->fecha_inscripcion);
            $fc = date_format($fc, 'd/m/Y');
            return $fc;
        },
        'width' => $column7,
        'options' => ['readonly' => true],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'attribute' => 'fecha_desde',
            'attribute2' => 'fecha_hasta',
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

        // 'hidden'=> true 
        /*ANOTAPRI - Oculto el dato adicional de la grilla, 
        pero al exportarlo aparece en el excel*/
    ],


    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'idcapinstancia',
        'label' => 'Instancia',
        'value' => function ($model) {
            $idcapinstancia = $model->idcapinstancia;
            if ($idcapinstancia != null) {
                $instancia = Mds_cap_instancia::findOne($idcapinstancia);
                $model->titulo_curso = $instancia->descripcion;
                return "$instancia->idinstancia - $instancia->descripcion";
            }
            return "";
        },
        'filterType' => GridView::FILTER_SELECT2,

        // 'filter' => ArrayHelper::map(
        //     Mds_cap_instancia::find()->where("idcapacitacion in (SELECT cap.idcapacitacion FROM mds_cap_capacitacion cap
        // where idorganismo in (select disp.idorganismo
        // from mds_org_contacto contacto,
        // mds_org_dispositivo disp
        // where disp.iddispositivo=contacto.iddispositivo
        // and idcontacto=$idcontacto
        // union
        // select vinc.vinculacion
        // from mds_org_contacto contacto,
        // mds_org_dispositivo disp
        // join mds_org_organismo_vinculacion vinc on vinc.idorganismo=disp.idorganismo
        // where disp.iddispositivo=contacto.iddispositivo
        // and idcontacto=$idcontacto)) or 1=" . $permiso_global)->orderBy(['descripcion' => SORT_ASC])->all(),
        //     'idinstancia',
        //     function ($model) {
        //         return $model->idinstancia . "- " . $model->descripcion;
        //     }
        // ),

        'filter' => $filterInstancias,
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => 'instancia...'],
        'format' => 'raw',
        'width' => $column8,
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'termino',
        'label' => 'Estado',
        'value' => function ($model) {
            $termino = $model->termino;
            switch ($termino) {
                case 0:
                    return "Inscripto";
                case 1:
                    return "En Curso";
                case 2:
                    return "Aprobado";
                case 3:
                    return "Desaprobado";
                case 4:
                    return "Abandonado";
                case 5:
                    return "En Espera";
                case 6:
                    return "Participa";
                case 7:
                    return "No Corresponde";
            }
        },
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => ['0' => 'Inscripto', '1' => 'En Curso', '2' => 'Aprobado',  '3' => 'Desaprobado', '4' => 'Abandonado', '5' => 'En espera', '6' => 'Participa', '7' => 'No Corresponde'],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => '-Estado-'],
        'format' => 'raw',
        'width' => $column9,
    ],

    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template' => '{view} {update} ' . ((($tiene_perm_cert) && (!$tiene_perm_readonly_cert)) ? '{certificado} {imprimir_cert} {vista_previa}' : '') . ' ' . (($tiene_perm_readonly_cert) ? '{imprimir_cert} {vista_previa}' : ''),
        'width' => $column10,
        'vAlign' => 'middle',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => ['role' => 'modal-remote', 'title' => 'View', 'data-toggle' => 'tooltip'],
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
            'certificado' => function ($url, $model) {
                $url =  Url::to(['/mds_cap_inscripcion/certificado', 'id' => $model->idinscripcion, 'nombres' => $model->la_persona, 'el_dni' => $model->dni]);
                return ($model->estado_cert != 0 ? '' : Html::a('<span class= "fas fa-file-download"></span>', $url, [
                    'role' => 'modal-remote', 'title' => 'Generar el certificado',

                    'data-confirm' => false, 'data-method' => false,
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                ]));
            },
            'imprimir_cert' => function ($url, $model) {
                $url =  Url::to(['/mds_cap_inscripcion/desc_certificado', 'id' => $model->idinscripcion]);
                return ($model->estado_cert == 0 ? '' : ($model->estado_cert == 1 ? '' : Html::a('<span class= "far fa-file-pdf"></span>', $url, [
                    'title' => 'Descargar el certificado',
                    'data-confirm' => false, 'data-method' => false,
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                ]))
                );
            },
            'vista_previa' => function ($url, $model) {
                $url =  Url::to(['/mds_cap_inscripcion/preview_certificado', 'id' => $model->idinscripcion, 'nombres' => $model->la_persona, 'el_dni' => $model->dni]);
                return ($model->estado_cert == 0 ? '' : ($model->estado_cert == 1 ? '' : Html::a('<span class= "fas fa-binoculars"></span>', $url, [
                    'title' => 'Vista Previa del Certificado',
                    'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                    'data-toggle' => 'tooltip',
                ])));
            }

        ]
    ],

];
