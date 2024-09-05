<?php

use app\controllers\SiteController;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrganismoDispositivo;
use app\models\Organismo;

$organismo = Organismo::findOne($model->idorganismo)

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoDispositivo */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .file-drop-zone {
        min-height: 100px !important;
    }

    .file-preview-image {
        min-height: 100px !important;
        max-width: 100% !important;
        /* Ajusta la imagen al 100% del contenedor */
        max-height: 100% !important;
        /* Define la altura máxima de la vista previa */
        object-fit: cover !important;
        /* Cubre el contenedor sin distorsión */

    }

    .krajee-default {
        min-height: 100px !important;
        float: none !important;
    }

    .kv-file-content {
        min-height: 100px !important;
        width: 100% !important;
    }
</style>

<div class="organismo-dispositivo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class=" col-md-12">
            <div class="row">
            <div class=" col-md-5">
                <?= $form->field($model,'descripcion')->textInput() ?>                
            </div>
                <div class=" col-md-5">
                    <?= SiteController::actionGet_input_select2($form,$model, 'idorganismo', 'cmb_organismo',Organismo::get_organismos(),'idorganismo','descripcion', 'Descripcion')?>
                </div>
                
                
            </div>
        </div>
        <div class=" col-md-12">
            <div class="row">
            <div class=" col-md-6">
                    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
                </div>
                <div class=" col-md-2">
                    <?= $form->field($model,'es_oficial')->checkbox(['checked' => true]) ?>
                </div>
                <div class=" col-md-2">
                    <?= $form->field($model, 'es_organismo')->checkbox(['checked' => true]) ?>
                </div>
                <div class=" col-md-2">
                    <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
                </div>
                
            </div>
        </div>
        <div class=" col-md-12">
            <div class="row">
                <div class=" col-md-4">
                    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
                </div>
                <div class=" col-md-4">
                    <?= $form->field($model, 'idcapaitem')->textInput() ?>
                </div>
                <div class=" col-md-4">
                    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
        </div>
        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>
            
         
    </div>                    
    
    <?php ActiveForm::end(); ?>        
    
</div>