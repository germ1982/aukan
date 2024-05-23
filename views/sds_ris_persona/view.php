<?php

use app\models\Sds_ris_persona_enfermedad;
use app\models\Sds_ris_persona_discapacidad;
use app\models\Sds_ris_persona_sustancia;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ris_persona */
?>
<div class="sds-ris-persona-view">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model_persona, 'documento_tipo')->dropDownList($tipoDoc, ['prompt' => '', 'disabled' => true]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model_persona, 'documento')->textInput(['disabled' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model_persona, 'apellido')->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model_persona, 'nombre')->textInput(['maxlength' => true, 'disabled' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model_persona, 'genero')->dropDownList($tipoGenero, ['prompt' => '', 'disabled' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model_persona, 'nacionalidad')->dropDownList($tipoNacionalidad, ['prompt' => '', 'disabled' => true]); ?>
        </div>
        <div class="col-md-4">
            <?php
            if ($model_persona->fecha_nacimiento != null) {
                $model_persona->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model_persona->fecha_nacimiento)));
            }
            echo $form->field($model_persona, 'fecha_nacimiento')->textInput(['disabled' => true]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'parentezco')->dropDownList($tipoParentezco, ['prompt' => '', 'disabled' => true]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'situacion_conyugal')->dropDownList($tipoSitConyugal, ['prompt' => '', 'disabled' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'escolaridad')->dropDownList($tipoEscolaridad, ['prompt' => '', 'disabled' => true]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'ultimo_ano_aprobado')->dropDownList($tipoUltAnioAprobado, ['prompt' => '', 'disabled' => true]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'tipo_establecimiento_educativo')->dropDownList($tipoEstEducativo, ['prompt' => '', 'disabled' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'trabajo')->dropDownList($tipoTrabajo, ['prompt' => '', 'disabled' => true]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'trabajo_horas')->textInput(['type' => 'number', 'disabled' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'trabajo_dias')->textInput(['type' => 'number', 'disabled' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'vinculo_contractual')->dropDownList($tipoVinculoContractual, ['prompt' => '', 'disabled' => true]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'trabajo_tipo')->dropDownList($tipoTipoTrabajo, ['prompt' => '', 'disabled' => true]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'ingreso')->dropDownList(
                [0 => 'No', 1 => 'Si'],
                ['prompt' => '', 'disabled' => true]
            );
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'cud')->dropDownList(
                [0 => 'No', 1 => 'Si'],
                ['prompt' => '', 'disabled' => true]
            );
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'cobertura_salud')->dropDownList($tipoCoberturaSalud, ['prompt' => '', 'disabled' => true]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php
            if (isset($model) && $model->idpersonarisneu) {
                $discapacidades = Sds_ris_persona_discapacidad::find()->where("idpersonarisneu = $model->idpersonarisneu AND deleted_at IS NULL")->all();
                $discapacidades_id = array();
                foreach ($discapacidades as $discapacidad) {
                    $discapacidades_id[] = $discapacidad['discapacidad'];
                }
                $model->discapacidades = $discapacidades_id;
            }
            ?>
            <?=
            $form->field($model, 'discapacidades')->widget(Select2::class, [
                'data' => $tipoDiscapacidad,
                'options' => ['id' => 'discapacidades', 'placeholder' => '', 'multiple' => true],
                'size' => Select2::MEDIUM,
                'pluginOptions' => [
                    'tags' => true,
                    // 'tokenSeparators' => [',', ' '],
                    //'maximumInputLength' => 50,
                    'allowClear' => false
                ],
                'disabled' => true
            ])->label('Discapacidades');
            ?>
        </div>
        <div class="col-md-6">
            <?php
            if (isset($model) && $model->idpersonarisneu) {
                $enfermedades = Sds_ris_persona_enfermedad::find()->where("idpersonarisneu = $model->idpersonarisneu AND deleted_at IS NULL")->all();
                $enfermedades_id = array();
                foreach ($enfermedades as $enfermedad) {
                    $enfermedades_id[] = $enfermedad['enfermedad'];
                }
                $model->enfermedades = $enfermedades_id;
            }
            ?>
            <?=
            $form->field($model, 'enfermedades')->widget(Select2::class, [
                'data' => $tipoEnfermedad,
                'options' => ['id' => 'enfermedades', 'placeholder' => '', 'multiple' => true],
                'size' => Select2::MEDIUM,
                'pluginOptions' => [
                    'tags' => true,
                    // 'tokenSeparators' => [',', ' '],
                    //'maximumInputLength' => 50,
                    'allowClear' => false
                ],
                'disabled' => true
            ])->label('Enfermedades');
            ?>
        </div>
    </div>
    <?php if ($oficial == 0) : ?>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model_persona, 'genero_autopercibido')->dropDownList($tipoGeneroAutopercibido, ['prompt' => '', 'disabled' => true]); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'condicion_hacinamiento')->dropDownList($tipoCondicionHacinamiento, ['prompt' => 'Seleccione opción...', 'disabled' => true]); ?>
            </div>
        </div>
        <?php
        if (isset($model) && $model->idpersonarisneu) {
            $sustancias = Sds_ris_persona_sustancia::find()->where("idpersonarisneu = $model->idpersonarisneu AND deleted_at IS NULL")->all();
            $sustancias_id = array();
            foreach ($sustancias as $sustancia) {
                $sustancias_id[] = $sustancia['sustancia'];
            }
            $model->sustancias = $sustancias_id;
        }
        ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'consume_sustancia')->dropDownList(
                    [0 => 'No', 1 => 'Si'],
                    [
                        'prompt' => [
                            'text' => 'Seleccione opción...',
                            'options' => ['disabled' => true, 'selected' => $model->isNewRecord || !$model->consume_sustancia ? true : false]
                        ],
                        'disabled' => true
                    ]
                )->label('Consumo problemático');
                ?>
            </div>
            <div class="col-md-6">
                <?=
                $form->field($model, 'sustancias')->widget(Select2::class, [
                    'data' => $tipoSustancia,
                    'options' =>
                    [
                        'id' => 'sustancias',
                        'placeholder' => '',
                        'multiple' => true,
                        'disabled' => true
                    ],
                    'size' => Select2::MEDIUM,
                    'pluginOptions' => [
                        'tags' => true,
                        // 'tokenSeparators' => [',', ' '],
                        //'maximumInputLength' => 50,
                        'allowClear' => true
                    ],
                ])->label('Indique consumos problemáticos');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'pueblo_originario_pertenece')->dropDownList(
                    [0 => 'No', 1 => 'Si'],
                    [
                        'prompt' => [
                            'text' => 'Seleccione opción...',
                            'options' => ['disabled' => true, 'selected' => $model->isNewRecord || !$model->pueblo_originario_pertenece ? true : false]
                        ],
                        'disabled' => true
                    ]
                )->label('Pertenencia o descendencia de pueblos indígenas u originarios');
                ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'pueblo_originario_reconoce')->dropDownList(
                    [0 => 'No', 1 => 'Si'],
                    [
                        'prompt' => [
                            'text' => 'Seleccione opción...',
                            'options' => ['disabled' => true, 'selected' => $model->isNewRecord || !$model->pueblo_originario_reconoce ? true : false]
                        ],
                        'disabled' => true
                    ]
                )->label('¿Se reconoce?');
                ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'pueblo_originario')->dropDownList($tipoPueblosOriginarios, ['prompt' => 'Seleccione opción...', 'disabled' => true]); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'observaciones')->textarea(['rows' => 5, 'tabindex' => '1', 'readonly' => true]) ?>
            </div>
        </div>
    <?php endif; ?>
    <?php ActiveForm::end(); ?>
</div>