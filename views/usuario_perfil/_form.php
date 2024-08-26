<?php

use app\controllers\SiteController;
use app\models\ConfiguracionTipo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$array_tipos = ConfiguracionTipo::find()->where(['activo' => 1])->orderBy('descripcion')->all();
//$model->id_configuracion_tipo = ConfiguracionTipo::PERFIL_DE_USUARIO;
?>



<div class="configuracion-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-md-5">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2" style="padding-top:30px;">
            <?= $form->field($model, 'activo')->checkbox(['checked' => true]) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>