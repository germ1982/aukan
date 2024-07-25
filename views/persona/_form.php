<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Persona */
/* @var $form yii\widgets\ActiveForm */
$array_tipos_documentos = Configuracion::findBySql("select * from configuracion where id_configuracion_tipo = 2 order by descripcion")->all();
?>

<div class="persona-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'documento')->textInput() ?>
        </div>
        <div class="col-md-4">


            <?=SiteController::actionGet_input_select2($form,$model,'documento_tipo','cmb_documento_tipo',$array_tipos_documentos,'id_configuracion','descripcion','Tipo Documento','seleccione tipo documento...')?>
        </div>
        <div class="col-md-4">
            <?=SiteController::actionGet_input_fecha($form,$model,"fecha_nacimiento","input_fecha_nacimiento","Fecha Nacimiento")?>
        </div>
    </div>

    <div class="row">
        
    </div>
    <div class="row">
        
    </div>
    <div class="row">
        
    </div>
    <div class="row">
        
    </div>

    <div class="row">
        
    </div>

    

    

    <?= $form->field($model, 'nacionalidad')->textInput() ?>

    <?= $form->field($model, 'genero')->textInput() ?>

    

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'padre')->textInput() ?>

    <?= $form->field($model, 'conviviente')->textInput() ?>

    <?= $form->field($model, 'domicilio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domicilio_calle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domicilio_numero')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idlocalidad')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
