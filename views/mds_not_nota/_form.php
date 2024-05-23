<?php

use app\models\Mds_not_nota;
use app\models\Mds_org_organismo;
use app\models\Mds_seg_usuario;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_not_nota */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->isNewRecord ? 'Nueva Nota' : 'Modificar Nota';

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
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-not-nota-form">

                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            $nota_fecha = Mds_not_nota::findBySql("SELECT fecha FROM mds_not_nota WHERE idorganismo=" . ($model->idorganismo) . " ORDER BY fecha DESC LIMIT 1")->one();
                            $fecha_desde = $nota_fecha != null ? $nota_fecha->fecha : date('1985-05-22');
                            $fecha_desde = date_format(date_create($fecha_desde), 'd-m-Y');
                            ?>
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
                                    'startDate' => $fecha_desde,
                                    'endDate' => date('d-m-Y'),
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ]
                            ])->label('Fecha (dd-mm-yyyy)'); ?>

                        </div>
                        <div class="col-md-offset-5 col-md-4" style="padding-left:0px !important;padding-right:0px !important;">

                            <div class="col-md-4">
                                <?= $form->field($model, 'expediente_guarismo')->textInput()->label('Expediente'); ?>
                            </div>

                            <div class="col-md-4">

                                <?= $form->field($model, 'expediente_numero')->textInput()->label('&nbsp;'); ?>
                            </div>

                            <div class="col-md-4">

                                <?= $form->field($model, 'expediente_anio')->textInput()->label('&nbsp; '); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'destinatario_nombre')->textInput(['maxlength' => true]) ?>
                        </div>

                        <div class="col-md-4">
                            <?= $form->field($model, 'destinatario_cargo')->textInput(['maxlength' => true]) ?>
                        </div>

                        <div class="col-md-4">

                            <?= $form->field($model, 'destinatario_area')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-offset-8 col-md-4">
                            <?= $form->field($model, 'referencia')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'detalle')->textarea(['rows' => 10]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'idorganismo')
                                ->dropDownList(
                                    ArrayHelper::map(
                                        Mds_org_organismo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                        'idorganismo',
                                        'descripcion'
                                    ),
                                    ['prompt' => "", 'disabled' => true]
                                ) ?>
                        </div>
                    </div>
                
                    <?php if (!Yii::$app->request->isAjax) { ?>
                        <div class="form-group">
                            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>
                    <?php } ?>


                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </section>
    </div>
</div>