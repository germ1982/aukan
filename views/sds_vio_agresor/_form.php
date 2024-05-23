<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_vio_agresor;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_vio_agresor */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->isNewRecord ? "Crear Agresor" : "Actualizar Agresor DNI: {$model->dni}";

?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }

    .boton-nuevo-agresor {
        margin-top: 2.7rem;
    }

    .pui-logo {
        padding-top: 25px;
    }

    @media only screen and (max-width: 991px) {
        .boton-nuevo-agresor {
            margin: 1rem 0 1rem 0;
        }

        .pui-logo {
            padding-top: 0;
            margin: 1rem 0 1rem 0;
        }
    }
</style>

<div class="sds-vio-agresor-form">
    <?php $form = ActiveForm::begin(['id' => 'form-agresor']); ?>

    <?php if (!Yii::$app->request->isAjax) : ?>

        <header class="page-header">
            <h2><?= $this->title ?></h2>

            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="index.php">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span><?= $this->title ?></span></li>
                </ol>
                <div class="sidebar-right-toggle"></div>
            </div>
        </header>
    <?php endif ?>

    <div class="sds-vio-agresor-form">

        <div class="sds-vio-agresor-form">
            <div class="col-md-12 col-lg-12 col-xl-12">
                <section class="panel">
                    <div class="panel-body">

                        <!-- <div class="row">
                        <div class="col-md-12 ">
                            <div class='alert alert-warning' role='alert'>
                                Recuerde que debe completar los campos obligatorios (Nombre, Apellido y Parentesco)
                            </div>
                        </div>
                    </div> -->

                        <div class="row d-flex">
                            <div class="col-md-8">
                                <?= $form->field($model, 'idagresor')->widget(
                                    Select2::class,
                                    [
                                        'data' => ArrayHelper::map(
                                            Sds_vio_agresor::find()->where(['activo' => 1])->orderBy(['nombre' => SORT_ASC, 'apellido' => SORT_ASC])->all(),
                                            'idagresor',
                                            function ($model) {
                                                $apellido = $model->apellido;
                                                $nombre = $model->nombre;
                                                $dni = ($model->dni) ? "($model->dni)" : '';

                                                return  "$apellido $nombre $dni";
                                            }
                                        ),
                                        "disabled" => !$model->isNewRecord,
                                        'options' => [
                                            'id' => 'idagresor',
                                            'placeholder' => 'Seleccionar Agresor',
                                            'onchange' => 'changeAgresor()',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]
                                )->label('Agresor'); ?>
                            </div>
                            <div class="col-md-4 boton-nuevo-agresor">
                                <?php
                                echo Html::button('<i class="glyphicon glyphicon-plus"></i> Nuevo Agresor', [
                                    'class' => 'btn btn-success btn-flat',
                                    'id' => 'id-boton',
                                    'onclick' => 'habilitarCampos("nuevo")',
                                    "disabled" => !$model->isNewRecord,
                                ]);
                                ?>
                            </div>

                            <div class="col-md-12">
                                <div class="alert alert-warning" role="alert" id="divWarningAgresor" style="display: none; margin-bottom: 10px">
                                    El agresor que ingresó ya se encuentra cargado en el sistema, al guardar se actualizarán los datos del agresor.
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-11">
                                <?= $form->field($model, 'dni')->textInput(['id' => "dni", 'maxlength' => true, "pattern" => "[a-zA-Z0-9]*", "disabled" => $model->isNewRecord])->label('DNI (sin comas, puntos y/o espacios)') ?>
                            </div>
                            <div class="col-md-1 pui-logo">
                                <?php
                                echo  Html::a('<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">', null, [
                                    'name' => 'btn_pui_agresor',
                                    'id' => 'btn_pui_agresor',
                                    'data-request-method' => 'post',
                                    'data-toggle' => 'tooltip',
                                    'style' => 'padding:0px;padding-left:2px;',
                                    'class' => 'btn',
                                    'title' => Yii::t('app', 'Consulta a Portal Unificado'),
                                ]);
                                ?>
                            </div>

                            <div class="col-md-12">
                                <div class="alert alert-warning" role="alert" id="divWarningDNI" style="display: none; margin-bottom: 10px">
                                    El DNI que ingresó ya se encuentra cargado en el sistema, al guardar se actualizarán los datos del agresor.
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'nombre')->textInput(['id' => 'nombre', 'maxlength' => true, "disabled" => $model->isNewRecord]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'apellido')->textInput(['id' => 'apellido', 'maxlength' => true, "disabled" => $model->isNewRecord]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'genero')->dropdownList(
                                    ArrayHelper::map(
                                        Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO_AUTOPERCIBIDO, false),
                                        'idconfiguracion',
                                        'descripcion'
                                    ),
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'genero',
                                        'tabindex' => '1',
                                        'disabled' => $model->isNewRecord
                                    ]
                                )
                                ?>
                            </div>
                            <?php if (Yii::$app->request->isAjax && ($parentezco || $model->isNewRecord)) : ?>
                                <div class="col-md-6 required">
                                    <?= $form->field($model, 'parentezco')->dropdownList(
                                        ArrayHelper::map(
                                            Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_PARENTEZCO, false),
                                            'idconfiguracion',
                                            'descripcion'
                                        ),
                                        [
                                            'id' => 'parentezco',
                                            'value' => $parentezco,
                                            "disabled" => $model->isNewRecord,
                                            'prompt' => [
                                                'text' => 'Seleccione',
                                                'options' => ['disabled' => true, 'selected' => true]
                                            ],
                                        ]
                                    )
                                    ?>
                                </div>
                            <?php endif ?>
                        </div>

                        <div class="row">
                            <div class="col-md-12" id="agresor_dato_denuncia_container">
                                <?= $form->field($model, 'agresor_dato_denuncia')->widget(\bizley\quill\Quill::class, [
                                    // 'allowResize' => true,
                                    'options' => [
                                        'style' => 'height: 125px;',
                                        'id' => 'agresor_dato_denuncia',
                                    ],
                                ])->label("Agresor Dato Denuncia") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <?=
                                $form->field($model, 'agresor_dav')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'onchange' => 'ocultar_agresor_dav()',
                                        'id' => 'check_agresor_dav',
                                        "disabled" => $model->isNewRecord
                                    ]
                                )
                                ?>
                            </div>
                            <div class="col-md-9" id="agresor_dav">
                                <?= $form->field($model, 'agresor_dav_datos')->textInput(['id' => 'agresor_dav_datos', 'maxlength' => true, "disabled" => $model->isNewRecord]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?=
                                $form->field($model, 'escolaridad')->dropdownList(
                                    $escolaridad,
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_estudios',
                                        "disabled" => $model->isNewRecord
                                    ],
                                )
                                ?>
                            </div>

                            <div class="col-md-6">
                                <?= $form->field($model, 'funcionario')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_funcionario',
                                        "disabled" => $model->isNewRecord
                                    ]
                                ) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?=
                                $form->field($model, 'desc_actividad')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_desc_actividad',
                                        'onchange' => 'ocultar_actividad()',
                                        "disabled" => $model->isNewRecord
                                    ],
                                )
                                ?>
                            </div>
                            <div class="col-md-6" id="actividad">
                                <?= $form->field($model, 'desc_jubilacion')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_desc_jubilacion',
                                        "disabled" => $model->isNewRecord
                                    ]
                                ) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'acceso_armas')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_acceso_armas',
                                        "disabled" => $model->isNewRecord
                                    ]
                                ) ?>
                            </div>
                            <div class="col-md-6">
                                <?=
                                $form->field($model, 'antecedente_penales')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_antecedente_penales',
                                        "disabled" => $model->isNewRecord
                                    ],
                                )
                                ?>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <?= $form->field($model, 'antecedente_violencia')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_antecedente_violencia',
                                        "disabled" => $model->isNewRecord
                                    ]
                                ) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'antecedente_restricciones')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_antecedente_restricciones',
                                        "disabled" => $model->isNewRecord
                                    ]
                                ) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?=
                                $form->field($model, 'vinculo_ilicito')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_vinculo_ilicito',
                                        "disabled" => $model->isNewRecord
                                    ],
                                )
                                ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'vinculo_personal_seguridad')->dropdownList(
                                    $vioVinculoSelectOptions,
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_vinculo_personal_seguridad',
                                        "disabled" => $model->isNewRecord
                                    ],
                                );
                                ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'consumo_problematico')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => ['disabled' => true, 'selected' => true]
                                        ],
                                        'id' => 'check_consumo_problematico',
                                        'onchange' => 'ocultar_consumos()',
                                        "disabled" => $model->isNewRecord
                                    ]
                                ) ?>
                            </div>
                            <div class="col-md-6 required" id="consumo">
                                <label class="form-label">Tipos de consumo problemático</label>
                                <?=
                                Select2::widget([
                                    'name' => 'consumos',
                                    'id' => 'consumos',
                                    'value' => $model->isNewRecord ? "" : $vioConsumosPreCargados,
                                    'data' => $vioConsumoSelectOptions,
                                    'options' => [
                                        'id' => 'check_consumos',
                                        'placeholder' => 'Seleccione ploblematicas',
                                        'multiple' => true,
                                        // 'required' => false,
                                    ],
                                    'pluginOptions' => [
                                        // 'tags' => true,
                                        'tokenSeparators' => [','],
                                        'allowClear' => true,
                                    ],
                                    "disabled" => $model->isNewRecord,
                                    'showToggleAll' => false,
                                ]);
                                ?>
                            </div>
                        </div>

                        <?php if (!Yii::$app->request->isAjax && !$model->isNewRecord) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <?=
                                    $form->field($model, 'activo')->dropdownList(
                                        [
                                            1 => "Si",
                                            0 => "No",
                                        ],

                                    )
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!Yii::$app->request->isAjax) { ?>
                            <div class="row"><br />
                                <div class="col-md-12">
                                    <a class="btn btn-info" href="index.php?r=sds_vio_agresor/index">Volver </a>
                                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </section>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <?php
    $newRecord = $model->isNewRecord ? 1 : 0;
    $this->registerJs(
        "$(document).ready(function() {

        // if ( !$('#inicializarSelect').val() )  {
        //     habilitarCampos('nuevo');
        // }

        let nombre = $('#nombre').val();
        let apellido = $('#apellido').val();
        let parentezco = $('#parentezco').val();

        if (!nombre || !apellido || !parentezco) {
            $('#boton-guardar-agresor').prop('disabled', true);
        }

        $( '#nombre').change(function() {
            validarCamposObligatorios();
        });
        $( '#apellido').change(function() {
            validarCamposObligatorios();
        });
        $( '#parentezco').change(function() {
            validarCamposObligatorios();
        });

        $( '#check_consumo_problematico').change(function() {
            validarCamposObligatorios();
        });

        $( '#check_consumos').change(function() {
            validarCamposObligatorios();
        });

        if ($('#check_agresor_consumo').val() == '1') {
            $('#agresor_con').show();
        } else {
            $('#agresor_con').hide();
        }

        if ($('#check_agresor_dav').val() == '1') {
            $('#agresor_dav').show();
        } else {
            $('#agresor_dav').hide();
        }

        if($('#dni').val()) {
            $('#dni').prop('disabled', false);
        }

        $('#dni').blur(function() {
            let dni = $('#dni').val();
            
            if(dni) {
                getAgresorAPI('', dni);
            }
        });

        $(document).on('keypress', '#dni', function () {
            if (event.which == 32 || event.which == 44 || event.which == 46) {
                event.preventDefault();
            }
        });

        // $('#boton-guardar-agresor').click(function(e){    
        //     e.preventDefault();
        //     return false;
        // })

        ocultar_actividad();
        ocultar_consumos();
  
        if ($newRecord) {
            $('#agresor_dato_denuncia_container .ql-editor').attr('contenteditable', false);
        }

    });

    $('#btn_pui_agresor').click(function(){        
        var dni_campo_agresor = $('#dni').val();        
        window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo_agresor, '_blank');
    });"
    );
    ?>

    <script>
        function changeAgresor() {

            const idagresor = $('#idagresor').val();

            if (!idagresor) {
                limpiarDatos('unSelect');
                deshabilitarCampos();
                $("#divWarningAgresor").hide();
            } else {
                //carga los datos que hay en BD del agresor y habilita todos los campos para poder modificarlo, menos el DNI.
                habilitarCampos('onChange');
                // $('#id-boton').prop("disabled", true);

                getAgresorAPI(idagresor, '');
            }
        };

        function habilitarCampos(llamadoDesde) {
            limpiarDatos(llamadoDesde);
            if (llamadoDesde === 'nuevo') {
                $("#divWarningAgresor").hide();
                $('#idagresor').prop("disabled", true);
            }
            //habilita todos los campos para cargar un agresor desde cero 
            if (llamadoDesde !== 'onChange') {
                $('#dni').prop("disabled", false);
            }
            $('#nombre').prop("disabled", false);
            $('#apellido').prop("disabled", false);
            $('#genero').prop("disabled", false);
            $('#parentezco').prop("disabled", false);
            $("#agresor_dato_denuncia_container .ql-editor").attr('contenteditable', true);
            $('#check_agresor_dav').prop("disabled", false);
            $('#agresor_dav_datos').prop("disabled", false);
            $('#check_agresor_consumo').prop("disabled", false);
            $('#agresor_consumo').prop("disabled", false);

            $('#check_estudios').prop("disabled", false);
            $('#check_funcionario').prop("disabled", false);
            $('#check_desc_actividad').prop("disabled", false);
            $('#check_desc_jubilacion').prop("disabled", false);
            $('#check_acceso_armas').prop("disabled", false);
            $('#check_antecedente_penales').prop("disabled", false);
            $('#check_antecedente_violencia').prop("disabled", false);
            $('#check_antecedente_restricciones').prop("disabled", false);
            $('#check_vinculo_ilicito').prop("disabled", false);
            $('#check_vinculo_personal_seguridad').prop("disabled", false);
            $('#check_consumo_problematico').prop("disabled", false);
            $('#check_consumos').prop("disabled", false);
        }

        function deshabilitarCampos() {
            $('#dni').prop("disabled", true);
            $('#nombre').prop("disabled", true);
            $('#apellido').prop("disabled", true);
            $('#genero').prop("disabled", true);
            $('#parentezco').prop("disabled", true);
            $("#agresor_dato_denuncia_container .ql-editor").attr('contenteditable', false);
            $('#check_agresor_dav').prop("disabled", true);
            $('#agresor_dav_datos').prop("disabled", true);
            $('#check_agresor_consumo').prop("disabled", true);
            $('#agresor_consumo').prop("disabled", true);

            $('#check_estudios').prop("disabled", true);
            $('#check_funcionario').prop("disabled", true);
            $('#check_desc_actividad').prop("disabled", true);
            $('#check_desc_jubilacion').prop("disabled", true);
            $('#check_acceso_armas').prop("disabled", true);
            $('#check_antecedente_penales').prop("disabled", true);
            $('#check_antecedente_violencia').prop("disabled", true);
            $('#check_antecedente_restricciones').prop("disabled", true);
            $('#check_vinculo_ilicito').prop("disabled", true);
            $('#check_vinculo_personal_seguridad').prop("disabled", true);
            $('#check_consumo_problematico').prop("disabled", true);
            $('#check_consumos').prop("disabled", true);
        }

        function limpiarDatos(llamadoDesde) {
            $('#dni').val('');
            $('#nombre').val('');
            $('#apellido').val('');
            $('#genero').val('');
            $('#parentezco').val('');
            $('#agresor_dato_denuncia').val('');
            $("#agresor_dato_denuncia_container .ql-editor").html('');
            $('#check_agresor_dav').val('');
            $('#agresor_dav_datos').val('');
            $('#check_agresor_consumo').val('');
            $('#agresor_consumo').val('');
            if (llamadoDesde !== 'onChange') {
                $('#idagresor').val('');
            }
            if (llamadoDesde === 'nuevo') {
                $('#idagresor').empty();
            }

            $('#check_estudios').val('');
            $('#check_funcionario').val('');
            $('#check_desc_actividad').val('');
            $('#check_desc_jubilacion').val('');
            $('#check_acceso_armas').val('');
            $('#check_antecedente_penales').val('');
            $('#check_antecedente_violencia').val('');
            $('#check_antecedente_restricciones').val('');
            $('#check_vinculo_ilicito').val('');
            $('#check_vinculo_personal_seguridad').val('');
            $('#check_consumo_problematico').val('');
            ocultar_consumos();
        }


        function ocultar_agresor_dav() {
            if ($('#check_agresor_dav').val() == '1') {
                $('#agresor_dav').show();
            } else {
                $('#agresor_dav').hide();
                $('#agresor_dav_datos').val('');
            }
        }

        function ocultar_agresor_problematico() {
            if ($('#check_agresor_consumo').val() == '1') {
                $('#agresor_con').show();
            } else {
                $('#agresor_con').hide();
                $('#agresor_consumo').val('');
            }
        }

        function ocultar_actividad() {

            if ($('#check_desc_actividad').val() == '1') {
                $('#actividad').show();
            } else {
                $('#check_desc_jubilacion').val('');
                $('#actividad').hide();
            }
        }

        function ocultar_consumos() {
            if ($('#check_consumo_problematico').val() == '1') {
                $('#consumo').show();
            } else {
                $('#check_consumos').val('').trigger("change");
                $('#consumo').hide();
            }
        }

        function validarCamposObligatorios() {
            // let datosValidos = false;
            let nombre = $('#nombre').val();
            let apellido = $('#apellido').val();
            let parentezco = $('#parentezco').val();
            let consumos = true;

            if ($('#check_consumo_problematico').val() == '1') {
                consumos = ($('#check_consumos').val()).length == 0 ? false : true;
            }

            if (nombre && apellido && parentezco && consumos) {
                $('#boton-guardar-agresor').prop('disabled', false);
                // datosValidos = true;
                // $("#form-agresor").submit();
            } else {
                $('#boton-guardar-agresor').prop('disabled', true);
                // $('#alerta-campos-obligatorios').html(`
                //         <div class='alert alert-danger' role='alert'>
                //             Debe completar los campos obligatorios (Nombre, Apellido y Parentesco)
                //         </div>
                //     `);
            }

            // return datosValidos;
        }

        function getAgresorAPI(idagresor, dni) {
            $.post("consultas/sds_vio_get_agresor.php", {
                    'idagresor': idagresor,
                    'dni': dni,
                },
                function(data) {
                    if (data['dni'] || idagresor) {
                        if (idagresor) {
                            $("#divWarningAgresor").show();
                        } else {
                            $("#divWarningDNI").show();
                        }

                        $("#dni").val(data['dni']);
                        $("#nombre").val(data['nombre']);
                        $("#apellido").val(data['apellido']);
                        $("#genero").val(data['genero']);
                        $("#agresor_dato_denuncia").val(data['agresor_dato_denuncia']);
                        $("#agresor_dato_denuncia_container .ql-editor").html(data['agresor_dato_denuncia']);
                        $("#check_agresor_dav").val(data['agresor_dav']);

                        if ($("#check_agresor_dav").val() == '1') {
                            $("#agresor_dav_datos").val(data['agresor_dav_datos']);
                            $('#agresor_dav').show();
                        } else {
                            $('#agresor_dav').hide();
                        }

                        $("#check_agresor_consumo").val(data['agresor_problematico']);

                        if ($("#check_agresor_consumo").val() == '0') {
                            $('#agresor_con').hide();
                        } else {
                            $('#agresor_con').show();
                        }

                        $("#agresor_consumo").val(data['agresor_consumo']);

                        $("#check_estudios").val(data['escolaridad']);
                        $("#check_funcionario").val(data['funcionario']);
                        $("#check_desc_actividad").val(data['desc_actividad']);

                        if ($("#check_desc_actividad").val() == '1') {
                            $("#check_desc_jubilacion").val(data['desc_jubilacion']);
                            $('#actividad').show();
                        } else {
                            $('#actividad').hide();
                        }

                        $("#check_acceso_armas").val(data['acceso_armas']);
                        $("#check_antecedente_penales").val(data['antecedente_penales']);
                        $("#check_antecedente_violencia").val(data['antecedente_violencia']);
                        $("#check_antecedente_restricciones").val(data['antecedente_restricciones']);
                        $("#check_vinculo_ilicito").val(data['vinculo_ilicito']);
                        $("#check_vinculo_personal_seguridad").val(data['vinculo_personal_seguridad']);
                        $("#check_consumo_problematico").val(data['consumo_problematico']);

                        if ($("#check_consumo_problematico").val() == '1') {
                            var arrayConsumos = data['arrayConsumos'];
                            arrayConsumos = Object.keys(arrayConsumos).map(key => arrayConsumos[key]);
                            $("#check_consumos").val(arrayConsumos).trigger('change');
                            $('#consumo').show();
                        } else {
                            $('#consumo').hide();
                        }
                    } else {
                        $("#divWarningAgresor").hide();
                    }
                }, "json"
            );
        }
    </script>