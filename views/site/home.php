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
    .content-body {
        padding: 5px 10px 20px 17px;
    }

    .banner {
        position: relative;
        height: 150px;
        background-color: black;
        text-align: center;

        justify-content: center;
        align-items: center;
        overflow: hidden;
        border: 2px solid #87b867;
        border-radius: 15px;
        margin: 5px;
        margin-top: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .banner video {
        position: absolute;
        top: 50%;
        left: 50%;
        width: auto;
        /* Ajusta el ancho del video */
        height: 100%;
        /* Ajusta la altura del video */
        transform: translate(-50%, -50%);
        object-fit: cover;
        /* Ajusta el video para cubrir todo el área del contenedor */
    }
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class=" breadcrumbs">
            <li>
                <a href="index.php?r=site%2Findex">
                    <i class=" neon fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="site-index">
    <div class="banner">
        <video autoplay loop muted>
            <source src="img\datafam_home_banner.mp4" type="video/mp4">
            Tu navegador no soporta la etiqueta de video.
        </video>
    </div>

    <!-- Accesos -->
    <div class="row" style="padding: 5px 0px 0px 10px; background: linear-gradient(to bottom, #a9a9a929, #ecedf3);">
        <div class="col-md-4">
            <?php
                $titulo = "Registro Tecnico";
                $archivo_contenido_tarjeta = "indicadores_tecnicos.php";
                include 'tarjetas/tarjeta_base.php'
            ?>
            <?php
                $titulo = "Web Informatica";
                $archivo_contenido_tarjeta = "web_informatica.php";
                include 'tarjetas/tarjeta_base.php'
            ?>
        </div>
        <div class="col-md-4">
            <?php
                $titulo = "Web Informatica";
                $archivo_contenido_tarjeta = "web_informatica.php";
                include 'tarjetas/tarjeta_base.php'
            ?>
        </div>
        <div class="col-md-4">

        </div>

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