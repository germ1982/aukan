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
use yii\web\JqueryAsset;

// Registro de scripts externos
$this->registerJsFile('@web/js/indicadores_menu.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('@web/js/home.js', ['depends' => [JqueryAsset::class]]);

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
$idEmpleadoVal = ($empleado != null) ? $empleado->idempleado : 0;

// display error message
if (Yii::$app->session->hasFlash('error_modulo')) : ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i> No se puede ingresar al módulo</h4>
        <?= Yii::$app->session->getFlash('error_modulo') ?>
    </div>
<?php endif; ?>

<style>
    <?php include __DIR__ . '/home.css'; ?>
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php?r=site%2Findex">
                    <i class="neon fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="site-index">

    <!-- Contenedor del video de fondo general -->
    <div class="video-background-container">
        <iframe
            src="https://www.youtube.com/embed/eWRDwD6cVe8?autoplay=1&mute=1&loop=1&playlist=eWRDwD6cVe8&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&vq=hd2160&start=8&end=1224"
            frameborder="0"
            allow="autoplay; encrypted-media"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen>
        </iframe>
    </div>

    <div class="banner">
        <div class="capa-lineas"></div>
        <div class="capa-chispas"></div>

        <div class="contenido-banner">
            <video autoplay loop muted>
                <source src="img\aukan_home_banner.mp4" type="video/mp4">
                Tu navegador no soporta la etiqueta de video.
            </video>
            <h1 class="texto-bienvenida">
                <div>BIENVENIDO A AUKAN CENTRO DE INFORMACION</div>
            </h1>
        </div>
    </div>

    <!-- Accesos distribuidos en 5 columnas -->
    <div class="row" style="padding: 5px 0px 0px 10px; background: linear-gradient(to bottom, #a9a9a929, #ecedf3);">

        <!-- Columna 1 -->


        <!-- Columna 2 -->
        <div class="col-5-tarjeta">
            <?php
            $titulo = "Cumpleaños";
            $archivo_contenido_tarjeta = "cumpleaños.php";
            include 'tarjetas/tarjeta_base.php';
            ?>
            <br>
            <?php
            $titulo = "Informacion Personal";
            $archivo_contenido_tarjeta = "sitio_en_construccion.php";
            include 'tarjetas/tarjeta_base.php';
            ?>
        </div>

        <!-- Columna 3 -->
        <div class="col-5-tarjeta">
            <?php
            $titulo = "El Clima en Neuquen";
            $archivo_contenido_tarjeta = "eventos_del_dia.php";
            include 'tarjetas/tarjeta_base.php';
            ?>
            <br>
            <?php
            $titulo = "Efemerides";
            $archivo_contenido_tarjeta = "sitio_en_construccion.php";
            include 'tarjetas/tarjeta_base.php';
            ?>
        </div>

        <!-- Columna 4 -->
        <div class="col-5-tarjeta">
            <?php
            include 'tarjetas/tarjeta_futbol.php';
            ?>
        </div>

        <!-- Columna 5 -->
        <div class="col-5-tarjeta">
            <?php
            $titulo = "Web Informatica";
            $archivo_contenido_tarjeta = "web_informatica.php";
            include 'tarjetas/tarjeta_base.php';
            ?>
            <br>
            <?php
            $titulo = "Registro Tecnico";
            $archivo_contenido_tarjeta = "indicadores_tecnicos.php";
            include 'tarjetas/tarjeta_base.php';
            ?>
        </div>

    </div>

    <br>

</div>