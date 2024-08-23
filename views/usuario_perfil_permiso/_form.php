<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UsuarioPerfilPermiso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usuario-perfil-permiso-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= SiteController::actionGet_input_select2($form, $model, 'idperfil', 'cmb_perfil', Configuracion::get_configuraciones(ConfiguracionTipo::PERFIL_DE_USUARIO), 'id_configuracion', 'descripcion', 'Perfil', 'Seleccione Perfil...') ?>
        </div>
        <div class="col-md-6">
            <?= SiteController::actionGet_input_select2($form, $model, 'idtipopermiso', 'cmb_tipo_perfil', Configuracion::get_configuraciones(ConfiguracionTipo::PERFIL_DE_USUARIO_TIPO_DE_PERMISO), 'id_configuracion', 'descripcion', 'Tipo', 'Seleccione Tipo...') ?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'modulo')->textInput() ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'item')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'descripcion')->textarea(['rows' => 2]) ?>
        </div>
    </div>






    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>