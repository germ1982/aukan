<?php

use app\models\Mds_seg_item;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_permiso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-seg-permiso-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'iditem')->dropDownList(
                ArrayHelper::map(
                    Mds_seg_item::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                    'iditem',
                    'descripcion'
                ),
                [
                    'prompt' => 'Seleccionar Item Seguridad ...',
                    'id' => 'cmb_item',
                ]
            );
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'descripcion')->textInput(['maxlength' => true, 'id' => "txt_descripcion"]) ?>
        </div>
    </div>
    <div class="row" style="padding-top:10px;">
        <div class="col-md-1 col-md-offset-8">
            <?= $form->field($model, 'alta')->checkbox(['checked' => $model->alta ? true : false]) ?>
        </div>
        <div class="col-md-1">
            <?= $form->field($model, 'baja')->checkbox(['checked' => $model->baja ? true : false]) ?>
        </div>
        <div class="col-md-1" style="padding:0px;">
            <?= $form->field($model, 'modifica')->checkbox(['checked' => $model->modifica ? true : false]) ?>
        </div>
        <div class="col-md-1">
            <?= $form->field($model, 'ver')->checkbox(['checked' => $model->ver ? true : false]) ?>
        </div>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $("#cmb_item").change(function() {
        $("#txt_descripcion").val($("#cmb_item option:selected").text());
    });
</script>