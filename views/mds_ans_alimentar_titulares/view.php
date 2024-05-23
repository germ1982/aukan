<?php

//use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use yii\widgets\DetailView;

$this->title = "Consulta Tarjeta Alimentar Titulares";
/* @var $this yii\web\View */
/* @var $model app\models\mds_ans_alimentar_titulares */
date_default_timezone_set('America/Argentina/Buenos_Aires');

?>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="mds-ans-alimentar-titulares-view">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'apellido')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['disabled'=>true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'dni')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'cuil')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'estado')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'provincia')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'departamento')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'localidad')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'municipio')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'totalHijos')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'embarazo')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'estado_entrega')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'fecha_hora')->textInput(['disabled'=>true,'maxlength' => true])->label("Fecha y Hora") ?>
        </div>
         
    </div>

    <div class="col-md-6">
        <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
        <!--<a class="btn btn-info" href="mds/web/index.php?r=mds_ans_alimentar_titulares">Volver </a>-->

    </div>
    <?php ActiveForm::end(); ?>
</div>

