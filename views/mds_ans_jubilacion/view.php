<?php

use kartik\form\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_ans_jubilacion */
?>
<div class="mds-ans-jubilacion-view">
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
                    <div class="mds-ans-jubilacion-view">
                        <?php $form = ActiveForm::begin(); ?>

                        <div class="row">
                            <div class="col-md-3">
                                <?= $form->field($model, 'tipo_dni')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'dni')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'cuil')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'nombre_apellido')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'beneficio')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, 'periodo')->textInput(['maxlength' => true, 'disabled' => true]) ?>
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
