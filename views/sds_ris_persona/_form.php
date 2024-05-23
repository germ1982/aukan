<?php

use app\models\Sds_ris_persona_enfermedad;
use app\models\Sds_ris_persona_discapacidad;
use app\models\Sds_ris_persona_sustancia;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ris_persona */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sds-ris-persona-form">
    <input type="hidden" id="ES_PRIMERA_PERSONA" name="ES_PRIMERA_PERSONA" value="<?= $esPrimeraPersona ?>">

    <?php $form = ActiveForm::begin(['action' => ['sds_ris_persona/' . ($model->isNewRecord ? 'create' . (isset($botones) ? '_ext' : '') : 'update'), 'id' => $model->idpersonarisneu, 'idrisneu' => $model->idrisneu], 'id' => $model->formName()]); ?>

    <?= $this->render('/sds_com_persona/_form_risneu', [
        'form' => $form,
        'model' => $model_persona,
        'idrisneu' => $model->idrisneu,
        'tipoDoc' => $tipoDoc,
        'tipoGenero' => $tipoGenero,
        'tipoGeneroAutopercibido' => $tipoGeneroAutopercibido,
        'tipoNacionalidad' => $tipoNacionalidad,
        'isCreate' => $isCreate,
        'oficial' => $oficial
    ]) ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'parentezco')->dropDownList(
                $tipoParentezco,
                [
                    'disabled' => true,
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'readOnly' => $configuracionParentezco == $model->parentezco,
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                ],
            );
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'situacion_conyugal')->dropDownList(
                $tipoSitConyugal,
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'disabled' => true
                ]
            );
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'escolaridad')->dropDownList(
                $tipoEscolaridad,
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'id' => 'cmb_escolaridad',
                    'disabled' => true
                ]
            );
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'ultimo_ano_aprobado')->dropDownList(
                $tipoUltAnioAprobado,
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'disabled' => true
                ]
            );
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'tipo_establecimiento_educativo')->dropDownList(
                $tipoEstEducativo,
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'disabled' => true
                ]
            );
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'trabajo')->dropDownList(
                $tipoTrabajo,
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'id' => 'cmb_trabajo',
                    'disabled' => true
                ]
            );
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'trabajo_horas')->textInput(['type' => 'number', 'disabled' => true, 'min' => 0]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'trabajo_dias')->textInput(['type' => 'number', 'disabled' => true, 'min' => 0]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'vinculo_contractual')->dropDownList(
                $tipoVinculoContractual,
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'disabled' => true
                ]
            );
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'trabajo_tipo')->dropDownList(
                $tipoTipoTrabajo,
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'disabled' => true
                ]
            );
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'ingreso')->dropDownList(
                [1 => 'Si', 0 => 'No'],
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'disabled' => true
                ]
            );
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'cud')->dropDownList(
                [1 => 'Si', 0 => 'No'],
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'disabled' => true
                ]
            );
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'cobertura_salud')->dropDownList(
                $tipoCoberturaSalud,
                [
                    'prompt' => [
                        'text' => 'Seleccione opción...',
                        'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                    ],
                    'disabled' => true
                ]
            );
            ?>
        </div>
        <?= $form->field($model, 'idpersona')->hiddenInput()->label(false) ?>

        <!-- <div class="col-md-3">
            <?php /* $form->field($model, 'trabajo_porque')->textInput()  */ ?>
        </div> -->
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
                'options' => ['id' => 'discapacidades', 'placeholder' => '', 'multiple' => true, 'disabled' => true],
                'size' => Select2::MEDIUM,
                'pluginOptions' => [
                    // 'tags' => true,
                    // 'tokenSeparators' => [',', ' '],
                    //'maximumInputLength' => 50,
                    'allowClear' => true
                ],
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
                'options' => ['id' => 'enfermedades', 'placeholder' => '', 'multiple' => true, 'disabled' => true],
                'size' => Select2::MEDIUM,
                'pluginOptions' => [
                    // 'tags' => true,
                    // 'tokenSeparators' => [',', ' '],
                    //'maximumInputLength' => 50,
                    'allowClear' => true
                ],
            ])->label('Enfermedades');
            ?>
        </div>
    </div>
    <?php if ($oficial == 0) : ?>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <?= $form
                    ->field($model_persona, 'genero_autopercibido')
                    ->dropDownList(
                        $tipoGeneroAutopercibido,
                        [
                            'prompt' => [
                                'text' => 'Seleccione opción...',
                                'options' => ['disabled' => true, 'selected' => $model->isNewRecord ? true : false]
                            ],
                            'disabled' => true
                        ]
                    ) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'condicion_hacinamiento')->dropDownList(
                    $tipoCondicionHacinamiento,
                    [
                        'prompt' => [
                            'text' => 'Seleccione opción...',
                            'options' => ['disabled' => true, 'selected' => $model->isNewRecord || !$model->condicion_hacinamiento ? true : false]
                        ],
                        'disabled' => true
                    ]
                );
                ?>
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
        <div class="row" style="margin-bottom: 1rem;">
            <div class="col-md-6">
                <?= $form->field($model, 'consume_sustancia')->dropDownList(
                    [1 => 'Si', 0 => 'No'],
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
            <div class="col-md-6" id="sustancias_container">
                <?=
                $form->field($model, 'sustancias')->widget(Select2::class, [
                    'data' => $tipoSustancia,
                    'options' =>
                    [
                        'id' => 'sustancias',
                        'placeholder' => '',
                        'multiple' => true,
                        'disabled' => $model->isNewRecord || !$model->sustancias ? true : false
                    ],
                    'size' => Select2::MEDIUM,
                    'pluginOptions' => [
                        // 'tags' => true,
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
                    [1 => 'Si', 0 => 'No'],
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
            <div class="col-md-2" id="reconoce_pueblo_originario_container">
                <?= $form->field($model, 'pueblo_originario_reconoce')->dropDownList(
                    [1 => 'Si', 0 => 'No'],
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
            <div class="col-md-4" id="pueblo_originario_container">
                <?= $form->field($model, 'pueblo_originario')->widget(Select2::class, [
                    'data' => $tipoPueblosOriginarios,
                    'options' => [
                        'placeholder' => 'Seleccione opción...',
                        'tabIndex' => '1',
                        'disabled' => true,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'observaciones')->textarea(['rows' => 5, 'tabindex' => '1', 'readonly' => true]) ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<?php

$this->registerJs(
    "
    $('#cmb_trabajo').change(function(){    
        habilitar_campos_trabajo();        
    });
    
    $('#cmb_escolaridad').change(function(){    
        habilitar_campos_escolaridad();        
    });

    if($('#sds_ris_persona-pueblo_originario_pertenece').val() == 1) {
        // $('#reconoce_pueblo_originario_container').show();        
        $('#sds_ris_persona-pueblo_originario_reconoce').prop('disabled', false);
    } else {
        // $('#reconoce_pueblo_originario_container').hide();
        $('#sds_ris_persona-pueblo_originario_reconoce').prop('disabled', true);
        $('#sds_ris_persona-pueblo_originario').prop('disabled', true);
    }   
    
    $('#sds_ris_persona-pueblo_originario_pertenece').change(function(){
        if($('#sds_ris_persona-pueblo_originario_pertenece').val() == 1) {
            // $('#reconoce_pueblo_originario_container').show();        
            $('#sds_ris_persona-pueblo_originario_reconoce').prop('disabled', false);
        } else {
            // $('#reconoce_pueblo_originario_container').hide();
            $('#sds_ris_persona-pueblo_originario_reconoce').prop('disabled', true);
            $('#sds_ris_persona-pueblo_originario_reconoce').val('');
            // $('#pueblo_originario_container').hide();
            $('#sds_ris_persona-pueblo_originario').prop('disabled', true);
            $('#sds_ris_persona-pueblo_originario').val(null).trigger('change');
        }    
    });

    if($('#sds_ris_persona-pueblo_originario_reconoce').val() == 1) {
        // $('#pueblo_originario_container').show();        
        $('#sds_ris_persona-pueblo_originario').prop('disabled', false);
    } else {
        // $('#pueblo_originario_container').hide();
        $('#sds_ris_persona-pueblo_originario').prop('disabled', true);
    }   

    $('#sds_ris_persona-pueblo_originario_reconoce').change(function(){
        if($('#sds_ris_persona-pueblo_originario_reconoce').val() == 1) {
            // $('#pueblo_originario_container').show();        
            $('#sds_ris_persona-pueblo_originario').prop('disabled', false);
        } else {
            // $('#pueblo_originario_container').hide();
            $('#sds_ris_persona-pueblo_originario').prop('disabled', true);
            $('#sds_ris_persona-pueblo_originario').val(null).trigger('change');
        }    
    });
    
    if($('#sds_ris_persona-consume_sustancia').val() == 1) {
        // $('#sustancias_container').show();      
        $('#sustancias').prop('disabled', false);
    } else {
        // $('#sustancias_container').hide();
        $('#sustancias').prop('disabled', true);
        $('#sustancias').val(null).trigger('change');
    }    

    $('#sds_ris_persona-consume_sustancia').change(function(){
        if($('#sds_ris_persona-consume_sustancia').val() == 1) {
            // $('#sustancias_container').show();      
            $('#sustancias').prop('disabled', false);
        } else {
            // $('#sustancias_container').hide();
            $('#sustancias').prop('disabled', true);
            $('#sustancias').val(null).trigger('change');
        }    
    });
    "
);
?>
<script>
    function habilitar_campos_trabajo() {
        const VINCULO_CONTRACTUAL_OTRO_ID = 27;
        const VINCULO_CONTRACTUAL_OTRO = 4988;
        var trabaja = $('#cmb_trabajo').prop('selectedIndex') == 1;
        $('#sds_ris_persona-trabajo_horas').val(trabaja ? null : 0);
        $('#sds_ris_persona-trabajo_dias').val(trabaja ? null : 0);
        if (!trabaja) {
            $('#sds_ris_persona-vinculo_contractual').val(VINCULO_CONTRACTUAL_OTRO_ID);
            $('#sds_ris_persona-trabajo_tipo').val(VINCULO_CONTRACTUAL_OTRO);
        } else {
            $('#sds_ris_persona-vinculo_contractual').val(null);
            $('#sds_ris_persona-trabajo_tipo').val(null);
        }
    }

    function habilitar_campos_escolaridad() {
        var escolaridad = $('#cmb_escolaridad').prop('selectedIndex') != 3;
        $('#sds_ris_persona-ultimo_ano_aprobado').prop('selectedIndex', !escolaridad ? 1 : 0);
        //$('#sds_ris_persona-ultimo_ano_aprobado').prop('disabled', !escolaridad);  
        $('#sds_ris_persona-tipo_establecimiento_educativo').prop('selectedIndex', !escolaridad ? 1 : 0);
        //$('#sds_ris_persona-tipo_establecimiento_educativo').prop('disabled', !escolaridad);          
    }
</script>