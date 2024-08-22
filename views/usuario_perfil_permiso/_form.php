<?php

use app\controllers\SiteController;
use app\models\Configuracion;
use app\models\ConfiguracionTipo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="usuario-perfil-permiso-form">

      <?php $form = ActiveForm::begin(); ?>

      <div class="row">
            <div class="col-md-4">
                  <?= SiteController::actionGet_input_select2($form, $model, 'idperfil', 'cmb_perfil', Configuracion::get_configuraciones(ConfiguracionTipo::PERFIL_DE_USUARIO), 'id_configuracion', 'descripcion', 'Perfil', 'Seleccione Perfil...') ?>
            </div>
            <div class="col-md-4">
                  <?= SiteController::actionGet_input_select2($form, $model, 'idtipopermiso', 'cmb_tipo_perfil', Configuracion::get_configuraciones(ConfiguracionTipo::PERFIL_DE_USUARIO_TIPO_DE_PERMISO), 'id_configuracion', 'descripcion', 'Tipo', 'Seleccione Tipo...') ?>
            </div>
            <div class="col-md-4">
            <?= $form->field($model, 'idacceso')->textInput() ?>
            </div>
      </div>

      <div class="row">
            <div class="col-md-12">
            <?= $form->field($model, 'descripcion')->textarea(['rows' => 2]) ?>
            </div>
      </div>

      <?php ActiveForm::end(); ?>

</div>