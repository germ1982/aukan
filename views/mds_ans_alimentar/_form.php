<?php

use app\models\mds_ans_alimentar as ModelsMds_ans_alimentar;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = "Modificar Tarjeta Alimentar";

/* @var $this yii\web\View */
/* @var $model app\models\mds_ans_alimentar */
/* @var $form yii\widgets\ActiveForm */
?>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="mds-ans-alimentar-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'dni')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'cuil')->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'municipio')->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'estado')->dropDownList([
                null => "",
                ModelsMds_ans_alimentar::PENDIENTE => "Pendiente",
                ModelsMds_ans_alimentar::ENTREGADA => "Entregada"
            ]) ?>
        </div>
        <div class="col-md-6">

            <?= $form->field($model, 'fecha')->widget(DatePicker::ClassName(), [
                'name' => 'check_issue_date',
                'language' => 'es',
                'readonly' => false,
                'layout' => '{picker}{input}{remove}',
                'options' => [
                    'id' => 'fecha',
                    'class' => 'form-control input-md',
                    'disabled' => false
                ],
                'pluginOptions' => [
                    'value' => null,
                    'format' => 'dd-mm-yyyy',
                    'endDate' => date('d/m/Y'),
                    'todayHighlight' => true,
                    'autoclose' => true,
                ]
            ])->label('Fecha (dd-mm-yyyy)'); ?>

        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

