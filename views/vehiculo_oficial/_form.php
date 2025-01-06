<?php

use app\controllers\SiteController;
use app\controllers\VehiculosController;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VehiculoOficial */
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

<div class="vehiculo-oficial-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class=" row">
        <div class=" col-md-12">
            <div class=" row">
                <div class=" col-md-3">
                    <?= $form->field($model, 'idvehiculo')->textInput(['maxlength' => true]) ?>
                </div>
                <div class=" col-md-3">
                    <?= $form->field($model, 'dominio')->textInput(['maxlength' => true]) ?>
                </div>
                <div class=" col-md-3">
                    <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>
                </div>               
                <div class=" col-md-3">
                    <?= $form->field($model, 'anio')->textInput() ?>
                </div>
                
            </div>
            <div class=" row">
                <div class=" col-md-3">
                    <?=SiteController::actionGet_input_select2($form,$model, 'idmarca','cmb_marcas',VehiculosController::actionGet_marcas_combinadas(),'id_configuracion','descripcion','Marca','Ingrese marca...' )?>
                </div>
                <div class=" col-md-3">
                    <?= $form->field($model, 'modelo')->textInput(['maxlength' => true]) ?>
                </div>
                <div class=" col-md-3">
                    <?= $form->field($model, 'poliza')->textInput(['maxlength' => true]) ?>
                </div>               
                <div class=" col-md-3">
                    <?= $form->field($model, 'VTO')->textInput() ?>
                </div>
                
            </div>
           
        </div>

    </div>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
