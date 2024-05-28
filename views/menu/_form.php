<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\SiteController;
use app\models\Menu;
$array_padres = Menu::find()->where(['activo'=>1])->orderBy(['title' => SORT_ASC])->all();
?>

<div id="form_principal" class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
        <?=SiteController::actionGet_input_select2($form,$model,'padre','cmb_padre',$array_padres,'id','title','Padre','seleccione padre...')?>
            <?= $form->field($model, 'padre')->textInput() ?>

        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'icon_yii')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'link_yii')->textInput(['maxlength' => true]) ?>

        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'orden')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'activo')->textInput() ?>
        </div>
    </div>











    <?php ActiveForm::end(); ?>

</div>