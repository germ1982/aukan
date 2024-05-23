<?php

use kartik\form\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\mds_por_familia */
?>
<div class="mds-por-familia-view">
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
                    <div class="mds-por-sst-view">
                        <?php $form = ActiveForm::begin(); ?>

                        <div class="row">
                            <div class="col-md-3">
                                <?= $form->field($model, 'dni')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'mes')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'anio')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'importe')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'programa')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'subprograma')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'dni_responsable')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'responsable_cobro')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'responsable_certificacion')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, 'area')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'expediente')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'localidad')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                    <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                </div>
            </section>
        </div>
    </div>
</div>