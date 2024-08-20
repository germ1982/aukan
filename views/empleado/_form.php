<?php

use app\controllers\SiteController;
use app\models\Empleado;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="empleado-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="col-md-2">
            <?= SiteController::actionGet_input_select2($form, $model, 'idempleado', 'cmb_empleado', Empleado::get_empleados(), 'idempleado', 'descripcion', 'Empleado', 'seleccione empleado...') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'iddispositivo')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'email')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'foto')->fileInput() ?>
            <?php if ($model->foto): ?>
                <div class="form-group">
                    <img src="<?= Yii::$app->request->baseUrl . '/' . $model->foto ?>" alt="Foto del Empleado" class="img-thumbnail">
                </div>
            <?php endif; ?>

        </div>
        
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'legajo')->textInput() ?>
        </div>        
        <div class="col-md-2">
            <?= $form->field($model, 'telefono')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'fichado')->textInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'categoria')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'cuil')->textInput() ?>
        </div>
        <div class="col-md-2">             
            <?= $form->field($model, 'funcion')->textInput() ?>
        </div>
    </div>
    <div class="row">       
        
        <div class="col-md-2">
            <?= $form->field($model, 'antiguedad_legal')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'antiguedad_total')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'contratacion')->textInput() ?>
        </div>
       
    </div>
    
    <div class="row">    
        <div class="col-md-2">    
            <?= $form->field($model, 'ingreso_real')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'ingreso_administrativo')->textInput() ?>
        </div>       
        
    </div>
    <div class="row">        
        
        
    <div class="col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'afiliacion')->checkbox(['checked' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
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