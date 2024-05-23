<?php

/* @var $this yii\web\View */

use app\models\Mds_cap_capacitacion;
use app\models\Mds_cap_inscripcion;
use app\models\Mds_cap_instanciaSearch;
use app\models\Mds_cap_persona;
use app\models\Mds_hor_licencia;
use app\models\Mds_hor_motivo_inasistencia;
use app\models\Mds_hor_remanente;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_documento;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_persona;
use app\models\Sds_gis_capa;
use app\models\Sds_gis_capa_item;
use app\models\Sds_gis_capaSearch;
use app\models\Sds_reg_interno;
use johnitvn\ajaxcrud\CrudAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Página Principal';

CrudAsset::register($this);

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$model_contacto = $usuario->idcontacto != null ? Mds_org_contacto::findOne($usuario->idcontacto) : null;

$searchModel_cap = new Mds_cap_instanciaSearch();
$searchModel_cap->fhasta_desde = date('Y-m-d');
$searchModel_cap->modo_consulta = true;
$dataProvider_cap = $searchModel_cap->search(Yii::$app->request->queryParams);
$dataProvider_cap->pagination->pageSize = 12;

$searchModel_gis = new Sds_gis_capaSearch();
$dataProvider_gis = $searchModel_gis->search(Yii::$app->request->queryParams);
$dataProvider_gis->pagination->pageSize = 20;
// display error message
if (Yii::$app->session->hasFlash('error_modulo')) : ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> No se puede ingresar al módulo</h4>
        <?= Yii::$app->session->getFlash('error_modulo') ?>
    </div>
<?php endif; ?>
<style>
    .content-body{
        padding: 5px 10px 20px 17px;
    }
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php?r=site%2Findex">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="site-index">
    <!-- Novedades -->
    <div class="row">
        <?php include 'novedades.php' ?>
    </div>

    <!-- Accesos -->
    <div class="row" style="padding: 10px 10px 5px 10px; background: linear-gradient(to bottom, #a9a9a929, #ecedf3);">
        <div class="col-md-4 panel panel-featured-left panel-featured-primary" style="padding-left:20px; padding-top:2px; padding-bottom:2px; background: #fdfdfd;">
            <!-- <div class="row">
                <div class="col-md-12" style="padding:0;">
                    <div class="col-md-3 text-primary text-center" style="padding:0">
                        <h5>Cruce</h5>
                    </div>
                    <div class="col-md-9" style="padding:2px 5px 0 0; ">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <?php /* echo Html::a('<i class="glyphicon glyphicon-search"></i>', null, [
                                    'name' => 'btn_dni',
                                    'data-request-method' => 'post',
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-primary',
                                    'title' => Yii::t('app', 'Consultar DNI en Cruce'),
                                    'onclick' => 'location.href ="index.php?r=site/cruce&dni=' . '"+$("#txtDni").val()'
                                ]); */ ?>
                            </span>
                            <input type="text" class="form-control" id="txtDni" placeholder="DNIs..." onchange='location.href ="index.php?r=site/cruce&dni="+$("#txtDni").val()'>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">-->
                <!-- Historico -->
                <!--
                <div class="col-md-12" style="padding:0; padding-top:5px;">
                    <div class="col-md-3 text-primary text-center" style="padding:0;">
                        <h5>Histórico</h5>
                    </div>
                    <div class="col-md-9" style="padding:2px 5px 0 0; ">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <?php /* echo Html::a('<i class="glyphicon glyphicon-search"></i>', null, [
                                    'name' => 'btn_dni',
                                    'data-request-method' => 'post',
                                    'data-toggle' => 'tooltip',
                                    'class' => 'btn btn-primary',
                                    'title' => Yii::t('app', 'Consultar DNI en Cruce'),
                                    'onclick' => 'location.href ="index.php?r=site/cruce_historico&dni=' . '"+$("#txtDniHistorico").val()'
                                ]); */ ?>
                            </span>
                            <input type="text" class="form-control" id="txtDniHistorico" placeholder="DNI..." onchange='location.href ="index.php?r=site/cruce_historico&dni="+$("#txtDniHistorico").val()'>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- Fin Cruce -->
            <!-- Reporte internos -->
            <!-- <div class="row" style="border-top:1px solid #bbb; padding-top: 4px; margin-top:11px;"> -->
            <div class="row">
                <div class="text-primary col-md-3 text-center" style="padding: 10px 0 10px 5px;">
                    <h5>Internos</h5>
                </div>
                <div class="col-md-9" style="padding-left:14px; padding-top:10px;">
                    <?php
                    $edificios=Sds_gis_capa_item::findBySql(
                        "SELECT ci.* 
                        FROM sds_reg_interno i 
                        JOIN sds_gis_capa_item ci ON ci.idcapaitem=i.idcapaitem
                        GROUP BY i.idcapaitem;"
                    )->all();?>
                    <div class="row">
                        <select class="form-control col-md-10" id="select-edificio" style="width:82%;">
                            <?php
                            foreach($edificios as $edificio){
                                echo '<option value="'.$edificio->idcapaitem.'">'.$edificio->descripcion.'</option>';
                            }
                            ?>
                        </select>

                        <?=
                            Html::a('<i class="glyphicon glyphicon-print" style="font-size:20px;"></i>', ['sds_reg_interno/reporte', 'type' => 'recepcion'], 
                            ['id' => 'generate-report', 
                            'class' => 'btn btn-info col-md-2', 
                            'style'=>'padding:3px 3px; margin: 0 5px; width: 14%;', 
                            'target' => '_blank',
                            'title' => 'Imprimir Internos'])
                        ?>
                    </div>
                </div>
            </div>
            <!-- Fin Reporte Internos -->          
        </div>
        <?php include 'accesos_directos.php' ?>
    </div>
    <?php
    if ($model_contacto != null) :
    ?>
        <!-- Columna con secciones Perfil de Usuario, Descargables, Capacitaciones Vigentes y Ubicaciones -->
        <div class="col-12" id="idperfil">
            <div class="row">
                <div class="col-md-3 col-lg-3 col-xl-3">
                    <?php include 'fichadas.php' ?>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <section class="panel-featured panel-featured-primary">
                        <header class="panel-heading bg-default">
                            <div class="panel-actions">
                                <?= Html::a(
                                    '<i class="fas fa-print"></i>',
                                    ['/mds_org_contacto/reporte_credencial', 'idcontacto' => $model_contacto->idcontacto, 'organigrama' => true],
                                    ['role' => "post", 'target' => 'blank', 'class' => 'text-primary', 'title' => 'Imprimir Credencial', 'data-toggle' => 'tooltip']
                                ) /* . ' ' .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-pencil"></i>',
                                        ['/mds_org_contacto/update', 'id' => $model_contacto->idcontacto, 'organigrama' => true],
                                        ['role' => "modal-remote", 'class' => 'text-primary', 'title' => 'Modificar datos de contacto', 'data-toggle' => 'tooltip']
                                    ) */
                                ?>
                            </div>
                            <h2 class="panel-title">Perfil de Usuario</h2>
                        </header>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= DetailView::widget([
                                        'model' => $model_contacto,
                                        'attributes' => [
                                            [
                                                'attribute' => 'nombre',
                                                'label' => 'Nombre',
                                                'value' => function ($model_contacto) {
                                                    $idpersona = $model_contacto->idpersona;
                                                    if ($idpersona != null) {
                                                        $persona = Sds_com_persona::findOne($idpersona);
                                                        return $persona->nombre;
                                                    }
                                                    return "";
                                                },
                                            ],
                                            [
                                                'attribute' => 'apellido',
                                                'label' => 'Apellido',
                                                'value' => function ($model_contacto) {
                                                    $idpersona = $model_contacto->idpersona;
                                                    if ($idpersona != null) {
                                                        $persona = Sds_com_persona::findOne($idpersona);
                                                        return $persona->apellido;
                                                    }
                                                    return "";
                                                },
                                            ],
                                            [
                                                'attribute' => 'legajo',
                                            ],
                                            [
                                                'attribute' => 'documento',
                                                'label' => 'Dni',
                                                'value' => function ($model_contacto) {
                                                    $idpersona = $model_contacto->idpersona;
                                                    if ($idpersona != null) {
                                                        $persona = Sds_com_persona::findOne($idpersona);
                                                        return $persona->documento;
                                                    }
                                                    return "";
                                                },
                                            ],
                                            [
                                                'attribute' => 'idorganismo',
                                                'value' => function ($model_contacto) {
                                                    $iddispositivo = $model_contacto->iddispositivo;
                                                    if ($iddispositivo != null) {
                                                        $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
                                                        $organismo = Mds_org_organismo::findOne($dispositivo->idorganismo);
                                                        return $organismo->descripcion;
                                                    }
                                                    return "";
                                                },
                                            ],
                                            [
                                                'attribute' => 'iddispositivo',
                                                'value' => function ($model_contacto) {
                                                    $iddispositivo = $model_contacto->iddispositivo;
                                                    if ($iddispositivo != null) {
                                                        $dispositivo = Mds_org_dispositivo::findOne($iddispositivo);
                                                        return $dispositivo->descripcion;
                                                    }
                                                    return "";
                                                },
                                            ],
                                            [
                                                'attribute' => 'mail',
                                            ],
                                            [
                                                'attribute' => 'telefono',
                                            ],
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                            <?php
                            //Remanentes:
                            $remanentes = Mds_hor_remanente::find()->where('idcontacto=' . ($model_contacto->idcontacto))->all();
                            if (count($remanentes) > 0) : ?>
                                <br>
                                <div class="row" style="margin: 5px 0;">
                                    <div class="col-md-12 " class="panel panel-primary" style="border: 1px solid #ddd; border-radius:3px; padding:0;">
                                        <div class="panel-heading" style="padding: 10px 15px;">
                                            <?php
                                            $fecharemanente = Sds_com_configuracion::findOne(Mds_hor_remanente::ID_FECHA_ALTA);
                                            ?>
                                            <h3 class="panel-title">Remanente al <?= $fecharemanente->descripcion ?></h3>
                                        </div>
                                        <div class="col-md-12" class="panel-body" style="padding: 0; margin:0">
                                            <?php
                                            if (count($remanentes)) {
                                                foreach ($remanentes as $remanente) { ?>
                                                    <div class="col-md-3" style="border-bottom: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0;"><b><?= $remanente->anio ?></b></div>
                                                    <div class="col-md-9" style="border-bottom: 1px solid #c0c0c0;"><?= $remanente->dias ?> días</div>
                                            <?php }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            <?php endif; ?>
                            <?php //Licencias:
                            $licencias = Mds_hor_licencia::find()->where('idcontacto='.($model_contacto->idcontacto))->orderBy('desde DESC')->limit(5)->all();
                            if (count($licencias) > 0) : ?>
                                <br>
                                <div class="row" style="margin: 5px 0;">
                                    <div class="col-md-12 " class="panel panel-primary" style="border: 1px solid #ddd; border-radius:3px; padding:0;">
                                        <div class="panel-heading" style="padding: 10px 15px; border: 1px solid #ccc;">
                                            <h3 class="panel-title">Últimas Licencias</h3>
                                        </div>
                                        <div class="col-md-12" class="panel-body" style="padding: 0; margin:0">
                                            <?php if (count($licencias)){ ?>
                                                <table class="col-md-12" style="border: 1px solid #ccc;">
                                                    <thead style="border: 1px solid #ccc;">
                                                        <tr>
                                                            <th style="border: 1px solid #ccc; padding:10px; text-align: center;">
                                                                Desde
                                                            </th>
                                                            <th style="border: 1px solid #ccc; padding:10px; text-align: center;">
                                                                Hasta
                                                            </th>
                                                            <th style="border: 1px solid #ccc; padding:10px; text-align: center;">
                                                                Motivo
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($licencias as $licencia){
                                                            $motivo = 'S/D';
                                                            $motivo_model = Mds_hor_motivo_inasistencia::findOne($licencia->idmotivoinasistencia);
                                                            if($motivo_model != null){
                                                                if($motivo_model->activo==1){
                                                                    $motivo = $motivo_model->descripcion;
                                                                }
                                                            }
                                                            $desde = date("d/m/y", strtotime($licencia->desde));
                                                            $hasta = date("d/m/y", strtotime($licencia->hasta));
                                                            ?>
                                                            <tr>
                                                                <td style="border: 1px solid #ccc; padding:10px; text-align: center;"><?=$desde?></td>
                                                                <td style="border: 1px solid #ccc; padding:10px; text-align: center;"><?=$hasta?></td>
                                                                <td style="border: 1px solid #ccc; padding:10px; text-align: center;"><?=$motivo?></td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            <?php endif; ?>
                            <?php
                            //Capacitaciones:
                            $persona_cap=Mds_cap_persona::find()->where('idpersona='.$model_contacto->idpersona)->one();
                            if($persona_cap!=null){
                               $capacitaciones=Mds_cap_inscripcion::findBySql("SELECT
                                    ifnull(capa.nombre_corto, capa.descripcion) capacitacion,
                                    capa.idcapacitacion idcapacitacion,
                                    insta.descripcion instancia,
                                    insc.termino,
                                    insc.path_cert,
                                    insc.estado_cert
                                    FROM mdsyt.mds_cap_inscripcion insc
                                    join mds_cap_instancia insta on insc.idcapinstancia=insta.idinstancia
                                    join mds_cap_capacitacion capa on insta.idcapacitacion=capa.idcapacitacion
                                    where insc.idpersonacap=$persona_cap->idpersonacap and (insc.termino=1 or insc.termino=2) ORDER BY insc.fecha_inscripcion DESC LIMIT 5")->all();
                            }else{
                                $capacitaciones=null;
                            }
                            if($capacitaciones!=null):?>
                                <div class="row" style="margin: 5px 0;">
                                    <div class="col-md-12 " class="panel panel-primary" style="border: 1px solid #ddd; border-radius:3px; padding:0;">
                                        <div class="panel-heading" style="padding: 10px 15px; border: 1px solid #ccc;">
                                            <h3 class="panel-title">Capacitaciones</h3>
                                        </div>
                                        <div class="col-md-12" class="panel-body" style="padding: 0; margin:0">
                                            <table class="col-md-12" style="border: 1px solid #ccc;">

                                                <?php 
                                                $nombre_capacitacion='';
                                                foreach ($capacitaciones as $capacitacion):
                                                    if($nombre_capacitacion!=$capacitacion->capacitacion):?>
                                                        <thead style="border: 1px solid #ccc;">
                                                            <tr>
                                                                <th colspan="2" style="border: 1px solid #ccc; padding:2px; text-align: center; background-color:#0088cc; color:#fff">
                                                                    Capacitación: <?=$capacitacion->capacitacion?>
                                                                </th>
                                                            </tr>
                                                            <tr style="background-color: #F4FBFF;">
                                                                <th style="border: 1px solid #ccc; padding:2px; text-align: center;">Instancia</th>
                                                                <th style="border: 1px solid #ccc; padding:2px; text-align: center;">Estado</th>
                                                            </tr>
                                                        </thead>
                                                    <?php endif;
                                                    $nombre_capacitacion=$capacitacion->capacitacion;
                                                    ?>
                                                    <tbody>
                                                        <tr>
                                                            <?php switch($capacitacion->termino):
                                                                case Mds_cap_inscripcion::ESTADO_ENCURSO:?>
                                                                    <td class="alert alert-info" style="border: 1px solid #ccc; padding:2px; text-align: center;">
                                                                        <?=$capacitacion->instancia?>
                                                                    </td>
                                                                    <td class="alert alert-info" style="border: 1px solid #ccc; padding:10px; text-align: center;">
                                                                        En Curso
                                                                    </td>
                                                                <?php break;
                                                                case Mds_cap_inscripcion::ESTADO_APROBADO:?>
                                                                    <td class="alert alert-success" style="border: 1px solid #ccc; padding:2px; text-align: center;">
                                                                        <?=$capacitacion->instancia?>
                                                                    </td>
                                                                    <td class="alert alert-success" style="border: 1px solid #ccc; padding:10px; text-align: center;">
                                                                        Aprobado 
                                                                        <?php if($capacitacion->estado_cert==Mds_cap_inscripcion::ESTADO_APROBADO && $capacitacion->path_cert!=''):?>
                                                                            &nbsp;&nbsp;
                                                                            <a class="fas fa-download" href="<?=$capacitacion->path_cert?>" title="Descargar certificado" target="_blank"></a>
                                                                        <?php endif;?>
                                                                    </td>
                                                                <?php break;?>
                                                            <?php endswitch; ?>
                                                        </tr>
                                                    </tbody>
                                            <?php endforeach ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?>
                            
                            <?php
                            //Ultimo Certificado Medico Rechazado:
                            $ultimo_certificado=Mds_org_documento::findBySql("SELECT * FROM mds_org_documento WHERE 
                                idcontacto=$model_contacto->idcontacto AND tipo=".Mds_org_documento::DOC_CERTIFICADO_MEDICO.
                                " AND estado=".Mds_org_documento::DOC_RECHAZADO." AND idcontacto=".$model_contacto->idcontacto.
                                " AND datediff(curdate(), fecha)<=30 order by fecha desc")->one();
                            if($ultimo_certificado!=null):
                                $tipo_documento= Sds_com_configuracion::findOne($ultimo_certificado->tipo);?>
                                <div class="alert alert-danger" style="margin-top: 25px;">
                                    <b>
                                        Se le notifica que el siguiente <?=$tipo_documento->descripcion?> ha sido rechazado.<br>
                                        Observaciones: <?=$ultimo_certificado->detalle?>.<br>
                                        <a href="<?=$ultimo_certificado->path?>">Ver documento</a>
                                    </b>
                                </div>
                            <?php endif;?>

                            <hr>
                            <div class="row" style="padding: 0 15px;">
                                <div class="col-md-12" style="border: 1px solid #ccc; padding: 5px 8px; border-radius:3px; border-left: 5px solid #0088cc;">
                                    <?php
                                    $form = ActiveForm::begin([
                                        'action' => ['index'],
                                        'method' => 'get',
                                    ]);
                                    $model_doc_aux = new Mds_org_documento();
                                    echo $form->field($model_doc_aux, 'tipo', ['enableClientValidation' => false, 'enableAjaxValidation' => false])->dropdownList(
                                        ArrayHelper::map(
                                            Sds_com_configuracion::find()->where("idconfiguraciontipo=" .
                                                Sds_com_configuracion_tipo::TIPO_CONTACTO_DOCUMENTO_TIPO . " and idconfiguracion in (
                                            SELECT doc.tipo FROM mds_org_documento doc
                                            WHERE idcontacto = " . $model_contacto->idcontacto . ")")
                                                ->orderBy(['descripcion' => SORT_ASC])->all(),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        [
                                            'prompt' => '- Seleccionar Tipo -',
                                            'id' => 'tipo_documento'
                                        ]
                                    )->label("Legajo Digital");
                                    ActiveForm::end();
                                    ?>
                                </div>
                                <div class="col-md-12">
                                    <div id="tabla_docs" style="padding-top: 15px;overflow: auto">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <br>
                </div>
                <div class="col-md-5 col-lg-5 col-xl-5">
                    <?php include 'instructivos_usuarios.php' ?>
                    <br>
                    <?php include 'instructivos.php' ?>
                </div>
            </div>
            <div class="row">
                <br>
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <?php include 'descargables.php' ?>
                    <br>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <section class="panel-featured panel-featured-primary">
                        <header class="panel-heading bg-default">
                            <div class="panel-actions">

                            </div>
                            <h2 class="panel-title">Capacitaciones Vigentes</h2>
                        </header>
                        <div class="panel-body">
                            <div id="tblcapacitaciones">
                                <?= GridView::widget([
                                    'id' => 'crud-datatable',
                                    'dataProvider' => $dataProvider_cap,
                                    // 'filterModel' => $searchModel_cap,
                                    'pjax' => false,
                                    'columns' => [
                                        [
                                            'class' => '\kartik\grid\DataColumn',
                                            'attribute' => 'descripcion',
                                            'filter' => false,
                                        ],
                                        [
                                            'attribute' => 'desde',
                                            'value' => function ($searchModel_cap) {
                                                $fc = date_create($searchModel_cap->desde);
                                                $fc = date_format($fc, 'd/m/Y');
                                                return $fc;
                                            },
                                            'options' => ['readonly' => true],
                                            'filter' => false
                                        ],
                                        [
                                            'attribute' => 'hasta',
                                            'value' => function ($searchModel_cap) {
                                                $fc = date_create($searchModel_cap->hasta);
                                                $fc = date_format($fc, 'd/m/Y');
                                                return $fc;
                                            },

                                            'options' => ['readonly' => true],
                                            'filter' => false
                                        ],
                                        [
                                            'class' => 'kartik\grid\ActionColumn',
                                            'dropdown' => false,
                                            'header' => '',
                                            'template' => '{cumbre}',
                                            'vAlign' => 'middle',
                                            'width' => '3%',
                                            'buttons' => [
                                                'cumbre' => function ($url, $model) {
                                                    $url =  'https://cumbre.neuquen.gov.ar/curso/' . $model->alias;
                                                    return Html::a('<img src="img/cumbre_link.png"  style="cursor: pointer;width:18px;">', $url, [
                                                        'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                                        'title' => 'Inscribirse',
                                                        'data-toggle' => 'tooltip',
                                                    ]);
                                                }
                                            ],
                                        ],
                                    ],
                                    'toolbar' => ['content' => null],
                                    'striped' => true,
                                    'condensed' => true,
                                    'responsive' => true,
                                    'panel' => [
                                        'type' => 'default',
                                        'heading' => false,
                                        'after' => false,
                                        'before' => false
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                    <section class="panel-featured panel-featured-primary">
                        <header class="panel-heading bg-default">
                            <div class="panel-actions">

                            </div>
                            <h2 class="panel-title">Ubicaciones</h2>
                        </header>
                        <div class="panel-body">
                            <div id="tblcapacitaciones">
                                <?= GridView::widget([
                                    'id' => 'crud-datatable',
                                    'dataProvider' => $dataProvider_gis,
                                    // 'filterModel' => $searchModel_gis,
                                    'pjax' => false,
                                    'columns' => [
                                        [
                                            'attribute' => 'descripcion',
                                            'filter' => false
                                        ],
                                        [
                                            'label' => 'Cantidad',
                                            'value' => function ($searchModel_gis) {
                                                $amount = Sds_gis_capa_item::find()->where(['idcapa' => $searchModel_gis->idcapa, 'activo' => 1])->count();
                                                return $amount;
                                            },
                                            'filter' => false
                                        ],
                                        [
                                            'class' => 'kartik\grid\ActionColumn',
                                            'dropdown' => false,
                                            'header' => '',
                                            'template' => '{imprimir}',
                                            'vAlign' => 'middle',
                                            'width' => '3%',
                                            'buttons' => [
                                                'imprimir' => function ($url, $model) {
                                                    $url =  Url::to(['/sds_gis_capa/reporte_capa', 'idcapa' => $model->idcapa]);
                                                    return Html::a('<span class= "fas fa-print"></span>', $url, [
                                                        'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                                        'data-toggle' => 'tooltip',
                                                    ]);
                                                },
                                            ],
                                        ],
                                    ],
                                    'toolbar' => ['content' => null],
                                    'striped' => true,
                                    'condensed' => true,
                                    'responsive' => true,
                                    'panel' => [
                                        'type' => 'default',
                                        'heading' => false,
                                        'after' => false,
                                        'before' => false
                                    ]
                                ]) ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    <?php
    endif;
    ?>
    <br>
    <!-- Informes Pendientes -->
    <div class="row">
        <?php include 'informes_pendientes.php' ?>
    </div>
    <!--     <div class="row" id="abm_contacto" style="display:none;padding-top: 10px;">
        <div class="col-md-12">
            <section class="panel panel-featured panel-featured-default">
                <header class="panel-heading">
                    <h3 class="panel-title">
                        Agregar Contacto
                    </h3>
                </header>
                <div class="panel-body">
                    <?php
                    /* echo $this->render('/mds_org_contacto/_form', [
                        'model' => $model_contacto,
                        'botones' => true
                    ]); */
                    ?>
                </div>
            </section>
        </div>
    </div> -->
</div>
<?php
$this->registerJsFile('@web/js/indicadores_menu.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJs(
    "$(\"#tipo_documento\").change(function() {
        var idtipo = $(\"#tipo_documento\").val();
        var idcontacto = \"" . ($model_contacto != null ? $model_contacto->idcontacto : 0) . "\";
        $.post(\"index.php?r=mds_org_contacto/get_documentos&idcontacto=\" + idcontacto + \"&idtipo=\" + idtipo, function(data) {
            $(\"#tabla_docs\").html(data);
        });
    });
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        });
    
    var href=$('#generate-report').attr('href')+'&edificio='+$('#select-edificio').val();
    $('#generate-report').attr('href', href);
    $('#select-edificio').attr();
    $('#select-edificio').change(function(){
        var href=$('#generate-report').attr('href')+'&edificio='+$(this).val();
        $('#generate-report').attr('href', href);
    });"
);
?>