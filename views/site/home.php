<?php

/* @var $this yii\web\View */


use johnitvn\ajaxcrud\CrudAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use app\models\Empleado;
use app\models\Persona;

$this->title = 'Página Principal';

CrudAsset::register($this);

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->id : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
}
$persona = $usuario->idpersona != null ? Persona::findOne($usuario->idpersona) : null;
$empleado = $usuario->idpersona != null ? Empleado::findOne($usuario->idpersona) : null;

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
        <?php echo "include 'novedades.php'" ?>
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

                    <div class="row">
                        <select class="form-control col-md-10" id="select-edificio" style="width:82%;">

                        </select>

                    </div>
                </div>
            </div>
            <!-- Fin Reporte Internos -->          
        </div>
        <?php include 'accesos_directos.php' ?>
    </div>

    <br>
    <!-- Informes Pendientes -->

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
                    /* echo $this->render('/empleado/_form', [
                        'model' => $empleado,
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
        var idcontacto = \"" . ($empleado != null ? $empleado->idcontacto : 0) . "\";
        $.post(\"index.php?r=empleado/get_documentos&idcontacto=\" + idcontacto + \"&idtipo=\" + idtipo, function(data) {
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