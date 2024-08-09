<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Articulo */
/* @var $form yii\widgets\ActiveForm */

$array_tipos = Configuracion::find()->where(['activo' => 1, 'id_configuracion_tipo' => 12])->orderBy('descripcion')->all();
$array_marca = Configuracion::find()->where(['activo' => 1, 'id_configuracion_tipo' => 14])->orderBy('descripcion')->all();
$array_rubro = Configuracion::find()->where(['activo' => 1, 'id_configuracion_tipo' => 15])->orderBy('descripcion')->all();
$array_unidad_de_medida = Configuracion::find()->where(['activo' => 1, 'id_configuracion_tipo' => 13])->orderBy('descripcion')->all();
?>

<div class="articulo-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        
        <div class=" col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'idtipo', 'cmb_tipo_articulo', $array_tipos, 'id_configuracion', 'descripcion', 'Tipo', 'seleccione articulo...') ?>
        </div>
        <div class=" col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'idmarca', 'cmb_tipo_marca', $array_marca, 'id_configuracion', 'descripcion', 'Marca', 'seleccione marca...') ?>
        </div>
        <div class=" col-md-4">
            <?= $form->field($model, 'modelo')->textInput() ?>
        </div>
    </div>


    <div class="row">
        
        <div class=" col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'idrubro', 'cmb_tipo_rubro', $array_rubro, 'id_configuracion', 'descripcion', 'Rubro', 'seleccione rubro...') ?>
        </div>
        <div class=" col-md-4">
            <?= SiteController::actionGet_input_select2($form, $model, 'id_unidad_medida', 'cmb_tipo_unidad_de_medida', $array_unidad_de_medida, 'id_configuracion', 'descripcion', 'Unidad_de_Medida', 'seleccione unidad...') ?>
        </div>
        <div class=" col-md-4" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
        </div>
    </div>



    <div class="row">

       
        <div class=" col-md-12">
            <?= $form->field($model, 'descripcion')->textInput() ?>
        </div>
       

    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>