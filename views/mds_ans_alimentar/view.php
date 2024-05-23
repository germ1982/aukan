<?php

use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use yii\widgets\DetailView;

$this->title = "Consulta Tarjeta Alimentar";
/* @var $this yii\web\View */
/* @var $model app\models\mds_ans_alimentar */
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


<div class="mds-ans-alimentar-view">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'nombre')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'dni')->textInput(['disabled'=>true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'cuil')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'municipio')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'estado')->textInput(['disabled'=>true,'maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
      
            <?= $form->field($model, 'fecha')->widget(DatePicker::ClassName(), [
                                    'name' => 'check_issue_date',
                                    'language' => 'es',
                                    'readonly' => true,
                                    'layout' => '{picker}{input}',
                                    'options' => [
                                        'id' => 'fecha',
                                        'class' => 'form-control input-md',
                                        'disabled' => true,
                                    ],
                                    'pluginOptions' => [
                                        'value' => null,
                                        'format' => 'dd-mm-yyyy',
                                         'endDate' => date ('d/m/y'),
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                    
                                    ]
                                ])->label('Fecha (dd-mm-yyyy)'); ?>
        </div>
    </div>

    <div class="form-group">
        <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
    </div>


    <?php ActiveForm::end(); ?>

</div>