<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_persona;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use phpDocumentor\Reflection\Types\Boolean;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>


<div style="background-color: #f4f4f4;margin:10px 20px 0; border-radius:5px;padding:10px;border:1px solid #000;">
    <div class="row" style="margin:0 0 10px">
        <div class="col-md-4">
        </div>
        <div class="col-md-4" style="background-color:#ed9c28;border-radius:5px;color:#fff;text-align:center;margin-top:10px;">
            <b>Cargar Destinatario</b>
        </div>
        <div class="col-md-4"></div>
    </div>
    <div class="row" style="margin-bottom:20px">
        <div class="row" style="margin: 10px 30px 10px;">
            <?php
            if (Yii::$app->session->hasFlash('save_persona')) : ?>
                <div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i> ¡Excelente! Guardado Correctamente</h4>
                    <?= Yii::$app->session->getFlash('save_persona') ?>

                </div>
            <?php endif; ?>
        <?php $persona = isset($persona) ? $persona : new Sds_com_persona(); ?>
            <?php if ($persona->hasErrors()) : ?>
                <?php Html::errorSummary($persona, ['encode' => false]);?>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($persona, 'nombre')->textInput()->label('<b>Nombre</b>') ?>
            <?php
            if (Yii::$app->session->hasFlash('fail_save_form')) { ?>
                <div style="color:#a94442;margin:-10px 0 20px 0">
                    <?= Yii::$app->session->getFlash('fail_save_form') ?>

                </div>
            <?php } ?>
        </div>
        <div class="col-md-6">
            <?php // $persona->getErrors(['apellido'])
            ?>
            <?= $form->field($persona, 'apellido')->textInput([
                'options' => [
                    'required' => true,
                ]
            ])->label('<b>Apellido</b>') ?>
            <?php
            if (Yii::$app->session->hasFlash('fail_save_form')) { ?>
                <div style="color:#a94442;margin:-10px 0 20px 0">
                    <?= Yii::$app->session->getFlash('fail_save_form') ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row" style="margin-bottom:20px;">
        <div class="col-md-6">
            <?= $form->field($persona, 'genero')->textInput()->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_com_configuracion::findBySql(
                        "SELECT * FROM sds_com_configuracion WHERE idconfiguracion IN (81,82)"
                    )->all(),
                    'idconfiguracion',
                    'descripcion',
                ),
                'options' => [
                    'placeholder' => '- Género -',
                    'style' => (Yii::$app->session->hasFlash('fail_save_form') ? 'box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%), 0 0 6px #ce8483;border: 1px solid;border-color: #843534;border-radius:5px;' : ''),
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => false,
                ],
            ])->label(false);
            ?>
            <?php
            if (Yii::$app->session->hasFlash('fail_save_form')) { ?>
                <div style="color:#a94442;margin:-10px 0 20px 0">
                    <?= Yii::$app->session->getFlash('fail_save_form') ?>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($persona, 'nacionalidad')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    Sds_com_configuracion::findBySql(
                        "SELECT * FROM sds_com_configuracion WHERE idconfiguracion BETWEEN 70 AND 80"
                    )->all(),
                    'idconfiguracion',
                    'descripcion',
                ),
                'options' => [
                    'placeholder' => '- País -',
                    'style' => (Yii::$app->session->hasFlash('fail_save_form') ? 'box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%), 0 0 6px #ce8483;border: 1px solid;border-color: #843534;border-radius:5px;' : ''),
                    'class' => 'has-error select2-container--krajee select2-container--focus select2-selection',
                    'required' => true

                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'disabled' => false,
                ]
            ])->label(false);
            ?>
            <?php
            if (Yii::$app->session->hasFlash('fail_save_form')) { ?>
                <div style="color:#a94442;margin:-10px 0 20px 0">
                    <?= Yii::$app->session->getFlash('fail_save_form') ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php $f_nacimiento=($persona->fecha_nacimiento!=null?date('d/m/Y', strtotime($persona->fecha_nacimiento)):null);?>
            <?= $form->field($persona, 'fecha_nacimiento')->widget(DatePicker::class, [
                'language' => 'es',
                'layout' => '{picker}{input}',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'Fecha Nacimiento (DD/MM/YYYY)',
                    'value' => $f_nacimiento
                ],
                'pluginOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'endDate' => date('d/m/Y'),
                    'todayHighlight' => true,
                    'autoclose' => true,
                    'required' => true
                ]
            ])->label(false); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($persona, 'idlocalidad')->hiddenInput()->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <b><?= $form->field($persona, 'domicilio_calle')->textInput()->label('<b>Domicilio Calle</b>') ?></b>
        </div>
        <div class="col-md-6">
            <b><?= $form->field($persona, 'domicilio_numero')->textInput()->label('<b>Domicilio Numero</b>') ?></b>
        </div>
    </div>
</div>