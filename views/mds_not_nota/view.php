<?php

use app\models\Mds_seg_usuario;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_not_nota */
?>
<div class="mds-not-nota-view">



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
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <div class="mds-not-nota-form">

                        <?php $form = ActiveForm::begin(); ?>
                        <div class="row">
                            <div class="col-md-3">

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
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                    
                                    ]
                                ])->label('Fecha (dd-mm-yyyy)'); ?>

                            </div>
                            <div class="col-md-offset-5 col-md-4" style="padding-left:0px !important;padding-right:0px !important;">

                                <div class="col-md-4">
                                    <?= $form->field($model, 'expediente_guarismo')->textInput(['disabled'=>true])->label('Expediente'); ?>
                                </div>

                                <div class="col-md-4">

                                    <?= $form->field($model, 'expediente_numero')->textInput(['disabled'=>true])->label('&nbsp;'); ?>
                                </div>

                                <div class="col-md-4">

                                    <?= $form->field($model, 'expediente_anio')->textInput(['disabled'=>true])->label('&nbsp; '); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'destinatario_nombre')->textInput(['maxlength' => true,'disabled'=>true]) ?>
                            </div>

                            <div class="col-md-4">
                                <?= $form->field($model, 'destinatario_cargo')->textInput(['maxlength' => true,'disabled'=>true]) ?>
                            </div>

                            <div class="col-md-4">

                                <?= $form->field($model, 'destinatario_area')->textInput(['maxlength' => true,'disabled'=>true]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-offset-8 col-md-4">
                                <?= $form->field($model, 'referencia')->textInput(['maxlength' => true,'disabled'=>true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'detalle')->textarea(['rows' => 10,'disabled'=>true]) ?>
                            </div>
                        </div>


              
                        <div class="form-group">
                           <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                        </div>
                   
                        
                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </section>
        </div>
    </div>

</div>