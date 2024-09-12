<?php

use app\controllers\SiteController;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Organismo;

$organismo = Organismo::findOne($model->abreviatura) 

/* @var $this yii\web\View */
/* @var $model app\models\Organismo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="organismo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class=" col-md-12">
            <div class="row">
                <div class=" col-md-6">
                    <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
                </div>
                <div class=" col-md-6">
                <?= SiteController::actionGet_input_select2($form,$model, 'idorganismo', 'cmb_organismo',Organismo::get_organismos(),'idorganismo','descripcion', 'Padre')?>
                </div>
                
            </div>
        </div>

        <div class=" col-md-12">
            <div class="row">               
                
                <div class=" col-md-4" >
                    <?= $form->field($model, 'abreviatura')->textInput(['maxlength' => true]) ?>
                </div>
                <div class=" col-md-2"style="padding-top:30px;">
                    <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
                </div>
                <div class=" col-md-2">
                    <?= $form->field($model, 'nivel')->textInput() ?>
                </div>
                <div class= "col-md-4">
                <div class="reminder-card">
                                    <div class="reminder-header">
                                      <h4>Recordatorio de Nivel</h4>
                                    </div>
                                    <div class="reminder-content">
                                      <ol>
                                        <li> Ministerios</li>
                                        <li> Subsecretarias</li>
                                        <li> Coordinaciones</li>
                                        <li> Direcciones Provinciales</li>
                                        <li> Direcciones Generales</li>
                                        <li> Direcciones</li>
                                        <li> Departamentos / Otros</li>
                                        <!-- Agrega más elementos aquí -->
                                      </ol>
                                    </div>
                              </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>