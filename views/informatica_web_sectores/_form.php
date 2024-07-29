<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InformaticaWebSectores */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="informatica-web-sectores-form">

      <?php $form = ActiveForm::begin(); ?>


      <div class="row">
            <div class="col-md-7">
                  <div class="row">
                        <div class="col-md-12">
                              <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                        </div>
                  </div>
                  <div class="row">
                        <div class="col-md-4">
                              <?= $form->field($model, 'orden')->textInput() ?>
                        </div>
                        <div class="col-md-4">
                              <?= $form->field($model, 'alto_foto')->textInput() ?>
                        </div>
                        <div class="col-md-4" style="padding-top:30px;">
                              <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
                        </div>

                  </div>
            </div>
            <div class="col-md-5">
                  <?= $form->field($model, 'fotos')->textInput(['maxlength' => true]) ?>
            </div>
      </div>

      <div class="row">
            <div class="col-md-12">
                  <?= $form->field($model, 'descripcion')->textarea(['rows' => 8]) ?>
            </div>
      </div>






      <?php ActiveForm::end(); ?>

</div>