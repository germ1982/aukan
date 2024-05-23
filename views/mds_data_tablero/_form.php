<?php

use app\models\Mds_data_categoria;
use app\models\Mds_seg_item;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_data_tablero */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mds-data-tablero-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idcategoria')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            Mds_data_categoria::find()->all(),
            'idcategoria',
            'nombre'
        ),
        'options' => [
            'placeholder' => 'Seleccione...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]);
    ?>

    <?= $form->field($model, 'iditem')->widget(Select2::class, [
        'data' => ArrayHelper::map(
            Mds_seg_item::find()->all(),
            'iditem',
            'descripcion'
        ),
        'options' => [
            'placeholder' => 'Seleccione...',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]);
    ?>

    <?= $form->field($model, 'orden')->textInput() ?>

    <?= $form->field($model, 'estado')->dropDownList(
        [0 => 'Inactivo', 1 => 'Activo']); 
    ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>