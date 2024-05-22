<?php

/* @var $this yii\web\View */
use app\models\Empleado;
use app\models\Mds_seg_usuario;

use johnitvn\ajaxcrud\CrudAsset;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Página Principal';

/* CrudAsset::register($this); */

/* $usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model,
    ]);
} */


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
        <?php /* include 'novedades.php' */ ?>
    </div>

    <!-- Accesos -->
    <div class="row" style="padding: 10px 10px 5px 10px; background: linear-gradient(to bottom, #a9a9a929, #ecedf3);">
        <div class="col-md-4 panel panel-featured-left panel-featured-primary" style="padding-left:20px; padding-top:2px; padding-bottom:2px; background: #fdfdfd;">

            <div class="row">
                <div class="text-primary col-md-3 text-center" style="padding: 10px 0 10px 5px;">
                    <h5>Internos</h5>
                </div>

            </div>
            <!-- Fin Reporte Internos -->          
        </div>

    </div>

    <br>

</div>
