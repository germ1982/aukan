<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use app\models\Mds_certificacion;
use app\models\Mds_seg_usuario_rol;
use app\models\Sds_com_persona;
use yii\bootstrap\Modal;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $model->isNewRecord ? $this->title : $titleright ?></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-certificacion-form">
                    <div class="panel-group" id="accordion_beneficiario">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_beneficiario" href="">
                                        Beneficiario
                                    </a>
                                </h4>
                            </div>
                            <div class="accordion-body collapse in">
                                <div class="panel-body" id="beneficiario_content">
                                    <span id="txt_mensaje" style="margin: 10px"></span>
                                    <div class="row">
                                        <div class=" col-md-2 form-group required">
                                            Tipo de documento
                                            <div style="padding-top:6px;">
                                                <?=
                                                Html::dropdownList(
                                                    'ListaTiposDocumento',
                                                    '',
                                                    $tiposDocumentos,
                                                    [
                                                        'prompt' => [
                                                            'text' => 'Seleccione',
                                                            'options' => [
                                                                'disabled' => true,
                                                                'selected' => $model->isNewRecord ? true : false
                                                            ]
                                                        ],
                                                        'id' => 'TIPO_DOCUMENTO',
                                                        'class' => 'form-control input-md padding-top:6px;',
                                                        'disabled' => $model->isNewRecord ? false : true,
                                                        'onChange' => 'tipoDocumento()'
                                                    ]
                                                );
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group" id="div-dni">
                                                <?= $form->field($model, 'idbeneficiario')->hiddenInput()->label('Número') ?>
                                                <input class="form-control" style="margin-top: -10px;" type="text" maxlength="10" id="txtDNI_search" value="<?php echo $model->beneficiario ? $model->beneficiario->apellido . " " . $model->beneficiario->nombre . " (" . $model->beneficiario->documento . ")" : "" ?>" name="txtDNI_search" placeholder="Ingrese N° Documento" disabled>
                                                <input type="hidden" id="idDNI" name="idDNI">
                                                <span class="input-group-btn">
                                                    <?= Html::button('<i class="glyphicon glyphicon-search"></i>', [
                                                        'class' => 'btn btn-primary btn-flat',
                                                        'name' => 'btn_dni',
                                                        'id' => 'btn_dni',
                                                        'style' => 'margin-top:26px',
                                                        'title' => Yii::t('app', 'Buscar DNI'),
                                                        'readonly' => true
                                                    ]);
                                                    ?>
                                                    <?= Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                                                        'value' => Url::to(['mds_cap_persona/create']),
                                                        'class' => 'btn btn-success btn-flat showModalButton',
                                                        'style' => 'margin-top:26px',
                                                        'id' => 'btnContacto',
                                                        'disabled' => 'disabled',
                                                        'onclick' => 'abrirModalPersona()',
                                                        'title' => Yii::t('app', 'Crear Nueva Persona')
                                                    ]);
                                                    ?>
                                                    <?= Html::a(
                                                        '<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">',
                                                        null,
                                                        [
                                                            'name' => 'btn_pui_beneficiario',
                                                            'id' => 'btn_pui_beneficiario',
                                                            'data-request-method' => 'post',
                                                            'data-toggle' => 'tooltip',
                                                            'style' => 'margin-top:26px; padding:0px;padding-left:6px;',
                                                            'class' => 'btn',
                                                            'title' => Yii::t('app', 'Consulta a Portal Unificado')
                                                        ]
                                                    );
                                                    ?>
                                                </span>
                                            </div>
                                            <small id='edad'></small>
                                        </div>
                                        <div class="col-md-6" id="campos_localidad" style="display: none">
                                            <span id="txt_mensajeLocalidad" <?= $model->isNewRecord ? 'style="visibility: hidden;"' : ''  ?>></span>
                                            <?= $form->field($model, 'idlocalidad')->widget(Select2::class, [
                                                'data' => $localidades,
                                                'options' => [
                                                    'placeholder' => 'Seleccione',
                                                    'options' => [
                                                        'disabled' => true,
                                                        'selected' => true,
                                                        'id' => 'idlocalidad',
                                                    ]
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="campos" <?= $model->isNewRecord ? 'style="display: none"' : ''  ?>>
                        <div class="panel-group" id="accordion_asistencia">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_asistencia" href="#asistencia">
                                            Asistencia
                                            <i class="glyphicon glyphicon-menu-down"></i>
                                        </a>
                                    </h4>
                                </div>
                                <div id="asistencia" class="accordion-body collapse in">
                                    <div class="panel-body" id="asistencia_content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?= $form->field($model, 'equipo_tecnico')->textarea(['rows' => 3]) ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" id="divDatepickerInicio" name="divDatepickerInicio">
                                                <?php
                                                if ($model->periodo_desde != null) {
                                                    $model->periodo_desde = date('d/m/Y', strtotime(str_replace('/', '-', $model->periodo_desde)));
                                                }
                                                echo $form->field($model, 'periodo_desde')->widget(DatePicker::class, [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    'layout' => $model->isNewRecord ? '{picker}{input}{remove}' : '{picker}{input}',
                                                    'options' => [
                                                        'id' => 'fechaInicio',
                                                        'class' => 'form-control input-md',
                                                        'disabled' => $model->isNewRecord ? false : true,
                                                        'autocomplete' => 'off',
                                                        'placeholder' => '--/--/----'
                                                    ],
                                                    'pluginOptions' => [
                                                        'value' => null,
                                                        'format' => 'dd/mm/yyyy',
                                                        'todayHighlight' => true,
                                                        'autoclose' => true
                                                    ],
                                                    'pluginEvents' => [
                                                        'changeDate' => 'function(e) {
                                                        setFechaFin(e.date);
                                                    }'
                                                    ]
                                                ]);
                                                ?>
                                            </div>
                                            <div class="col-md-6" id="divDatepickerFin" name="divDatepickerFin">
                                                <?php
                                                if ($model->periodo_hasta != null) {
                                                    $model->periodo_hasta = date('d/m/Y', strtotime(str_replace('/', '-', $model->periodo_hasta)));
                                                }
                                                echo $form->field($model, 'periodo_hasta')->widget(DatePicker::class, [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    'layout' => $model->isNewRecord ? '{picker}{input}{remove}' : '{picker}{input}',
                                                    'options' => [
                                                        'id' => 'fechaFin',
                                                        'class' => 'form-control input-md',
                                                        //'disabled' => $permissionUpdate ? false : true,
                                                        'autocomplete' => 'off',
                                                        'placeholder' => '--/--/----'
                                                    ],
                                                    'pluginOptions' => [
                                                        'value' => null,
                                                        'format' => 'dd/mm/yyyy',
                                                        'todayHighlight' => true,
                                                        'autoclose' => true
                                                    ]
                                                ]);
                                                ?>
                                                <small id="smallFin"></small>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?=
                                                $form->field($model, 'idarea')->dropdownList(
                                                    $direcciones,
                                                    [
                                                        'prompt' => [
                                                            'text' => 'Seleccione',
                                                            'options' => [
                                                                'disabled' => true,
                                                                'selected' => true
                                                            ]
                                                        ],
                                                        'id' => 'cmb_idarea',
                                                        'onChange' => 'cargarProgramas()',
                                                        'disabled' => $model->isNewRecord ? false : true
                                                    ]
                                                )->label('Área');
                                                ?>
                                                <small id='director_cargo'></small>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="hidden" id="idProgramaSelected" name="idProgramaSelected" value="<?= $model->idprograma ?>">
                                                <?= $form->field($model, 'idprograma')->widget(Select2::class, [
                                                    'data' => $model->isNewRecord ? '' : $programas,
                                                    'options' => [
                                                        'placeholder' => 'Seleccione',
                                                        'id' => 'cmb_programa',
                                                        'disabled' => true,
                                                        'onChange' => 'precargarDependientesPrograma()'
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ]
                                                ]);
                                                ?>
                                                <span id="txt_mensaje_programa"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="hidden" id="idNivelAutorizacionSelected" name="idNivelAutorizacionSelected" value="<?= $model->idnivel_autorizacion ?>">
                                                <?=
                                                $form->field($model, 'idnivel_autorizacion')->dropdownList(
                                                    $niveles_autorizacion,
                                                    [
                                                        'prompt' => [
                                                            'text' => 'Seleccione',
                                                            'options' => ['disabled' => true, 'selected' => true]
                                                        ],
                                                        'id' => 'idnivel_autorizacion',
                                                        'onchange' => 'cargarDirecciones()',
                                                        'disabled' => true
                                                    ]
                                                );
                                                ?>
                                            </div>
                                            <div class="col-md-6 required" id="direccion-enviar">
                                                <input type="hidden" id="idDireccionSelected" name="idDireccionSelected" value="<?= $model->iddireccion ?>">
                                                <?= $form->field($model, 'iddireccion')->widget(Select2::class, [
                                                    'options' => [
                                                        'placeholder' => 'Seleccione',
                                                        'id' => 'cmb_iddireccion',
                                                        'disabled' => true
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ]
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6" id="monto_solicitado_div">
                                                <?= $form->field($model_certificacion_monto, 'monto')->textInput(['min' => 0, 'max' => 70000, 'type' => 'number', 'step' => 'any', 'placeholder' => '$', 'readonly' =>  true]); ?>
                                                <p id='monto_solicitado-msg'></p>
                                            </div>
                                            <div class="col-md-3">
                                                <?=
                                                $form->field($model, 'idcaracter')->dropdownList(
                                                    $caracteres,
                                                    [
                                                        'prompt' => [
                                                            'text' => 'Seleccione',
                                                            'options' => ['disabled' => true, 'selected' => true]
                                                        ],
                                                        'id' => 'idcaracter',
                                                        'onChange' => 'verificarCaracter()',
                                                        'disabled' => $model->isNewRecord ? false : true
                                                    ]
                                                );
                                                ?>
                                                <span id="txt_mensaje_caracter"></span>
                                            </div>
                                            <div class="col-md-3" id="incremento_div">
                                                <?= $form->field($model, 'id_certificacion_incremento')->widget(Select2::class, [
                                                    'data' => '',
                                                    'options' => [
                                                        'placeholder' => 'Seleccione',
                                                        'id' => 'id_certificacion_incremento',
                                                        'disabled' => true
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ]
                                                ]);
                                                ?>
                                            </div>

                                            <?php
                                            //if (!$model->isNewRecord) { 
                                            ?>
                                            <!-- <div class="col-md-3">
                                                    <?php // $form->field($model, 'codigo')->textInput(['readonly' => true]); 
                                                    ?>
                                                </div> -->
                                            <?php // } 
                                            ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <?= $form->field($model, 'nro_expediente')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => 'Ingrese número de expediente']) ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?= $form->field($model, 'nro_nota')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'placeholder' => 'Ingrese número de nota']) ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?=
                                                $form->field($model, 'tipo_certificacion')->dropdownList(
                                                    [
                                                        0 => "INTERNA",
                                                        1 => "EXTERNA"
                                                    ],
                                                    [
                                                        'prompt' => [
                                                            'text' => 'Seleccione',
                                                            'options' => ['disabled' => true, 'selected' => true]
                                                        ],
                                                        'id' => 'tipo_certificacion',
                                                        'onChange' => 'changeTipoCertificacion()'
                                                    ]
                                                );
                                                ?>
                                            </div>
                                            <div class="col-md-3 required" id="externa-tipo">
                                                <?= $form->field($model, 'idorganismo_solicitante')->widget(Select2::class, [
                                                    'data' => $organismo_solicitante,
                                                    'options' => [
                                                        'placeholder' => 'Seleccione',
                                                        'id' => 'idorganismo_solicitante',
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ]
                                                ]);
                                                ?>
                                                <p id='organismo_solicitante-msg'></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?=
                                                $form->field($model, 'jubilacion')->dropdownList(
                                                    [
                                                        1 => "Si",
                                                        0 => "No"
                                                    ],
                                                    [
                                                        'prompt' => [
                                                            'text' => 'Seleccione',
                                                            'options' => ['disabled' => true, 'selected' => true]
                                                        ],
                                                        'id' => 'jubilacion',
                                                        'onchange' => 'changeJubilacion()'
                                                    ]
                                                )
                                                ?>
                                            </div>
                                            <div class="col-md-3 required" id="tipojubilacion">
                                                <?=
                                                $form->field($model, 'tipo_jubilacion')->dropdownList(
                                                    $tipos_jubilacion,
                                                    [
                                                        'prompt' => [
                                                            'text' => 'Seleccione',
                                                            'options' => ['disabled' => true, 'selected' => true]
                                                        ],
                                                        'id' => 'tipo_jubilacion',
                                                        'onchange' => 'changeTipoJubilacion()',
                                                        'disabled' => $model->isNewRecord ? false : true
                                                    ]
                                                )
                                                ?>
                                                <p id='tipojubilacion-msg'></p>
                                            </div>
                                            <div class="col-md-3" id="monto_div">
                                                <?= $form->field($model, 'monto_jubilacion')->textInput(['id' => 'monto_jubilacion', 'min' => 0, 'max' => 100000000000, 'type' => 'number', 'step' => 'any', 'placeholder' => '$', 'readonly' => $model->isNewRecord ? false : true]); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?=
                                                $form->field($model, 'sueldo')->dropdownList(
                                                    [
                                                        1 => "Si",
                                                        0 => "No"
                                                    ],
                                                    [
                                                        'prompt' => [
                                                            'text' => 'Seleccione',
                                                            'options' => ['disabled' => true, 'selected' => true]
                                                        ],
                                                        'id' => 'sueldo',
                                                        'onchange' => 'changeSueldo()'
                                                    ]
                                                )
                                                ?>
                                            </div>
                                            <div class="col-md-3" id="sueldo_monto_div">
                                                <?= $form->field($model, 'sueldo_monto')->textInput(['id' => 'sueldo_monto', 'min' => 0, 'max' => 100000000000, 'type' => 'number', 'step' => 'any', 'placeholder' => '$', 'readonly' => true]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="responsable_campos" style="display: none">
                            <div class="panel-group" id="accordion_responsable">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_responsable" href="#responsable">
                                                Responsable de cobro/Tutor especial
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="responsable" class="accordion-body collapse in">
                                        <div class="panel-body" id="responsable_content">
                                            <div class="row">
                                                <div class="col-md-6" id="cambiar_responsable_check">
                                                    <label><input type="checkbox" id="cambiar_responsable" name="cambiar_responsable" /> Cambiar </label>
                                                </div>
                                                <div class="col-md-6" id="ver_responsable_btn">
                                                    <input type="hidden" id="idcertificacion" name="idcertificacion" value="<?= $model->idcertificacion ?>">
                                                    <?= Html::button('<i class="fas fa-eye"></i>', [
                                                        'id' => 'btnResponsables',
                                                        'tabIndex' => '-1',
                                                        'onclick' => '
                                                                    const idcertificacion= $("#idcertificacion").val();
                                                                    $("#modal_responsables").modal("show")
                                                                    .find("#content_modal")
                                                                    .load("index.php?r=mds_certificacion/ver_responsables&idcertificacion="+idcertificacion);
                                                                   ',
                                                        'title' => Yii::t('app', 'Ver historial de responsables')
                                                    ]);
                                                    ?>
                                                    <span style="margin-left: 10px">
                                                        Ver historial de responsables
                                                    </span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row" id="cambio_responsable_div">
                                                <div class="col-md-4 required" id="curador_legal_div">
                                                    <?=
                                                    $form->field($model_responsable, 'curador_legal')->dropdownList(
                                                        [
                                                            1 => "Si",
                                                            0 => "No"
                                                        ],
                                                        [
                                                            'prompt' => [
                                                                'text' => 'Seleccione',
                                                                'options' => ['disabled' => true, 'selected' => true]
                                                            ],
                                                            'id' => 'cmb_curador_legal',
                                                            'disabled' => true
                                                        ]
                                                    )
                                                    ?>
                                                </div>
                                                <div class="col-md-4 required" id="tipo_responsable_div">
                                                    <?=
                                                    $form->field($model_responsable, 'tipo_responsable')->dropdownList(
                                                        $tipo_responsable,
                                                        [
                                                            'prompt' => [
                                                                'text' => 'Seleccione',
                                                                'options' => ['disabled' => true, 'selected' => true]
                                                            ],
                                                            'disabled' => true
                                                        ]
                                                    );
                                                    ?>
                                                </div>
                                                <div class="col-md-4 required" id="rendicion_div">
                                                    <?=
                                                    $form->field($model_responsable, 'rendicion')->dropdownList(
                                                        [
                                                            1 => "Si",
                                                            0 => "No"
                                                        ],
                                                        [
                                                            'prompt' => [
                                                                'text' => 'Seleccione',
                                                                'options' => ['disabled' => true, 'selected' => true]
                                                            ],
                                                            'disabled' => true
                                                        ]
                                                    )
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <?= $form->field($model_responsable, 'nombre_apellido')->textinput(['readonly' => true, 'onkeyup' => 'this.value = this.value.toUpperCase();',])->label('Nombre y apellido'); ?>
                                                </div>
                                                <div class="required col-md-3">
                                                    <div class="input-group" id="responsable_div_dni">
                                                        <?= $form->field($model_responsable, 'dni')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'readonly' => true])->label("DNI") ?>
                                                        <span class="input-group-btn">
                                                            <?= Html::a(
                                                                '<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">',
                                                                null,
                                                                [
                                                                    'name' => 'btn_pui_responsable',
                                                                    'id' => 'btn_pui_responsable',
                                                                    'data-request-method' => 'post',
                                                                    'data-toggle' => 'tooltip',
                                                                    // 'style' =>'padding:0px;padding-left:2px;',
                                                                    'style' => 'margin-top:26px; padding:0px;padding-left:6px;',
                                                                    'class' => 'btn',
                                                                    'title' => Yii::t(
                                                                        'app',
                                                                        'Consulta a Portal Unificado'
                                                                    )
                                                                ]
                                                            );
                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <?= $form->field($model_responsable, 'cbu_alias')->textinput(['readonly' => $model->isNewRecord ? false : true, 'placeholder' => 'Ingrese CBU/alias']); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 required" id="motivo_responsable_div" <?= $model_responsable->motivo_cambio ? '' : 'style="display: none"' ?>>
                                                    <?= $form->field($model_responsable, 'motivo_cambio')->textarea(['rows' => 1,  'readonly' => true])->label('<b>Motivo del cambio</b>'); ?>
                                                </div>
                                                <div class="col-md-3" id="parentesco_div">
                                                    <?=
                                                    $form->field($model_responsable, 'idparentesco')->dropdownList(
                                                        $parentesco,
                                                        [
                                                            'prompt' => [
                                                                'text' => 'Seleccione',
                                                                'options' => ['disabled' => true, 'selected' => true]
                                                            ],
                                                            'id' => 'cmb_idparentesco',
                                                            'onChange' => 'changeParentesco()',
                                                            'disabled' => true,
                                                        ]
                                                    );
                                                    ?>
                                                </div>
                                                <div class="col-md-3 required" id="parentesco_otro_div">
                                                    <?= $form->field($model_responsable, 'parentesco_otro')->textinput(['readonly' => true]); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div id="txt_info_responsable">
                                                        <span id="txt_mensaje_responsable"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion_obs">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_obs" href="#obs">
                                                Observaciones
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="obs" class="accordion-body collapse in">
                                        <div class="panel-body" id="adjuntos_content">
                                            <div class="row">
                                                <div class="col-md-12" id="observaciones_texto_container">
                                                    <div>
                                                        <?= $form->field($model, 'observaciones')->widget(\bizley\quill\Quill::class, [
                                                            'options' => [
                                                                'style' => 'height: 125px',
                                                                'id' => 'observaciones_texto',
                                                                'readonly' => $model->isNewRecord ? false : true
                                                            ],
                                                        ])->label("") ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion_adjuntos">
                                <div class="panel panel-accordion">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_adjuntos" href="#adjuntos">
                                                Documentación Adjunta
                                                <i class="glyphicon glyphicon-menu-down"></i>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="adjuntos" class="accordion-body collapse in">
                                        <div class="panel-body" id="adjuntos_content">
                                            <div class="row" id="adjunto_container">
                                                <div class="col-md-6 required" id="adjunto_select">
                                                    <label id="tipo_adjunto_label">Tipo de documentación</label>
                                                    <?=
                                                    Html::dropdownList(
                                                        'ListaTiposAdjuntos',
                                                        '',
                                                        [],
                                                        [
                                                            'prompt' => [
                                                                'text' => 'Seleccione',
                                                                'options' => ['disabled' => true, 'selected' => true]
                                                            ],
                                                            'id' => 'TIPO_ADJUNTO',
                                                            'class' => 'form-control input-md',
                                                            'disabled' => true
                                                        ]
                                                    );
                                                    ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <span id="adjunto_txt_mensaje"></span>
                                                </div>
                                                <div class="col-md-12" id="otro_adjunto_container">
                                                    <div class="adjuntar-text" style="display: flex; justify-content: flex-end"><i class="fa fa-upload"></i> Adjuntar archivos
                                                    </div>

                                                    <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                                                    <input type="hidden" id="adjuntos_eliminados" name="Mds_legales_oficio[adjuntos_eliminados]">

                                                    <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                                                        <div class="fallback">
                                                            <input name="file" type="file" />
                                                        </div>
                                                    </div>
                                                    <small class="text-muted" style="display: flex; justify-content: flex-end">La extension debe ser del tipo
                                                        ["pdf,jpeg,jpg,png,xls,xlsx"]. Tamaño máximo 50MB.</small>
                                                </div>
                                            </div>
                                            <div class="row" id="adjunto_listado">
                                                <br />
                                                <div class="col-md-12">
                                                    <ul style="list-style: none">
                                                        <?php
                                                        foreach ($adjuntos as $adjunto) : ?>
                                                            <li><a><i class="fas fa-paperclip"></i> <?= Html::a($adjunto['nombre'], Url::base() . '/' . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                <br />
                                            </div>
                                            <?php $rolSuperAdmin = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL) ?>
                                            <div class="row" <?= !$rolSuperAdmin ? "style='display:none'" : '' ?>>
                                                <div class="col-md-6">
                                                    <h5 style="margin: 0"><b>Activo:</b></h5>
                                                </div>
                                            </div>
                                            <div class="row" style="margin: 10px" <?= !$rolSuperAdmin ? "style='display:none'" : '' ?>>
                                                <div class="col-md-6">
                                                    <?=
                                                    $form->field($model, 'deleted_at')->dropdownList(
                                                        [
                                                            1 => "Si",
                                                            0 => "No"
                                                        ],
                                                        [
                                                            'style' => !$rolSuperAdmin ? 'display:none' : ''
                                                        ]
                                                    )->label('');
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"><br />
                        <div class="col-md-12">
                            <a class="btn btn-info" href="javascript:history.back(1)" title="Volver">Volver</a> |
                            <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => 'btn btn-success', 'style' => 'display: none;', 'id' => 'btnSave']) ?>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
</div>
</div>
<?php
Modal::begin([
    'header' => '<h4>Agregar persona</h4>',
    'id' => 'modal',
    'size' => 'modal-lg'
]);
?>
<div class="panel-body">
    <?php
    $model_persona = new Sds_com_persona();
    echo $this->render('./_form_new_persona', [
        'botones' => true,
        'generos' => $generos,
        'model' => $model_persona,
        'nacionalidades' => $nacionalidades,
        'tiposDocumentos' => $tiposDocumentos,
        'username' => $username,
        'token' => $token
    ]);
    ?>
</div>
<?php
Modal::end();
?>

<?php
$script = <<< JS
    $(function() {
        $(document).on('click', '.showModalButton', function(){
            $("#txtDNI").val($("#txtDNI_search").val());
            if ($('#modal').hasClass('in')) {
                $('#modal').find('#modalContent')
                        .load($(this).attr('value'));
            } else {
                $('#modal').modal('show')
                        .find('#modalContent')
                        .load($(this).attr('value'));
            }
        });
    });
JS;

$this->registerJs($script);
?>

<?php
$this->registerJs(
    "$(document).ready(function() {
        $('#btnSave').hide();

        // Deshabilitamos el comportamiento de la tecla enter para que no haga un submit del form
        $('#formCertificacion').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });

        $('#responsable_campos').show();
        if($('#mds_certificacion-idbeneficiario').val()!=''){
            $('#campos').show();
            $('#campos_localidad').show();
            $('#btnSave').show();

            $('#cambio_responsable_div').hide();
            if($('#cmb_curador_legal').val()){
                $('#cambio_responsable_div').show();
            }

            cargarDirecciones();
            precargarSelectAdjuntos();
            deshabilitarDropzone();
            permiteCambioResponsable();
            getCertificacionesResponsable();
            getCertificacionIncremento();
            $('#btn_dni').prop('disabled', true);
        }else{
            $('#idnivel_autorizacion').val('');
            $('#ver_responsable_btn').hide();
            $('#cambio_responsable_div').hide();
            limpiarDropzone();
        }

        $('#btnSave').click(function(e){
            const idbeneficiario = $('#mds_certificacion-idbeneficiario').val();

            if(!verificarMonto()){
                alert('Por favor revise el monto solicitado');
                e.preventDefault();
            }
            if (!idbeneficiario){
                alert('Debe completar el beneficiario')
                e.preventDefault();
            }
            if(! verificarTipoCertificacion()){
                e.preventDefault();
            }
            if(! verificarTipoJubilacion()){
                e.preventDefault();
            }
            if(!verificarMotivoCambio()){
                e.preventDefault();
            }
            if(!verificarCuradorLegal()){
                e.preventDefault();
            }
            if(!verificarTipoResponsable()){
                e.preventDefault();
            }
            if(!verificarRendicion()){
                e.preventDefault();
            }
            if(!verificarParentesco()){
                e.preventDefault();
            }
            if(! verificarAdjuntos()){
                e.preventDefault();
            }
        });

        $('input[name=\"Mds_certificacion[idbeneficiario]\"]').on('input', function() {
            $('#txt_mensajeLocalidad').html('');
        });

        $('#divDatepickerFin').on('changeDate', function(e) {
            validarFechas();
            verificarCaracter();
        });

        $('#divDatepickerInicio').on('changeDate', function(e) {
            validarFechas();
            verificarCaracter();
            verificarFecha();
        });

        $('#parentesco_div').show();

        if($('#cmb_idparentesco').val()==''){
            if($('#cmb_idparentesco').val()== '<?= $PARENTESCO_OTRO_OPTION ?>'){
                $('#parentesco_otro_div').show();
            }else{
                $('#parentesco_otro_div').hide();
            }
        }

        $('#btn_dni').click(function(){
            const numeroDNI = $('#txtDNI_search').val();
            $('#idDNI').val($('#txtDNI_search').val());
            if (numeroDNI){
                getPersonaByDNI(numeroDNI);
            } else {
                $('#campos').hide();
                $('#campos_localidad').hide();
                alert('Debe ingresar un número de DNI');
            }
        });

        $('#txtDNI_search').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g,'');
            $('#btnContacto').prop('disabled', true);
            $('#txt_mensaje').html('');
        });

        changeAdjuntos();
        changeParentesco();
        changeTipoCertificacion();
        changeJubilacion();
        changeTipoJubilacion();
        changeSueldo();
        changeDniResponsable();
        $('#adjunto_container').show();
        $('#adjunto_listado').hide();

    });
    cambiarResponsable();

    $('#btn_pui_beneficiario').click(function(){
        let dni_campo = $('#txtDNI_search').val();
        if(dni_campo.includes('(')){
            const tx = dni_campo.split('(');
            const longitud = tx[1].length;
            dni_campo = tx[1].substring(0, longitud - 1);
        }
        window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
    });

    $('#btn_pui_responsable').click(function(){
        let dni_campo = $('#mds_certificacion_responsable-dni').val();
        window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
    });

    $('#formCertificacion').keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });

    "
);
?>

<script>
    function tipoDocumento() {
        $("#txtDNI_search").prop("disabled", false);
        $("#btn_dni").prop("readonly", false);
    }

    function getPersonaByDNI(numeroDNI) {
        $("#div_duplicados").hide();
        $("#txt_mensaje").html(`Buscando datos de la Persona...`);
        $("#txt_mensajeLocalidad").html(".");
        $('#edad').text('');
        let txtDNI_search = $("#txtDNI_search").val();
        const token = "<?= $token ?>";
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        const url = "<?= env('ENDPOINT_API_SUR_NEST') ?>/<?= env('ENDPOINT_API_SUR_NEST_PERSONA_GET') ?>";
        const params = `/${numeroDNI}`;
        $.ajax({
            url: url + params,
            type: "GET",
            dataType: "json",
            async: true,
            headers,
            success: function(data) {
                if (data?.status === "success" && data?.records) {
                    const record = data.records;
                    $("#txtDNI_search").val(`${record.apellido} ${record.nombre} (${record.documento})`);
                    $("#idbeneficiario").val(record.idpersona);
                    $('input[name="Mds_certificacion[idbeneficiario]"]').val(record.idpersona);
                    $("#mds_certificacion_responsable-dni").val(`${record.documento}`);
                    getCertificacionesResponsable();
                    $('#cambiar_responsable_check').hide();
                    $("#mds_certificacion_responsable-nombre_apellido").val(`${record.apellido} ${record.nombre}`);
                    $("#cmb_idparentesco").val(<?= $PARENTESCO_TITULAR ?>);
                    $("#txt_mensaje").html(``);
                    $("#txt_mensajeLocalidad").html(``);
                    verificarCaracter();
                    $("#campos").show();
                    $("#campos_localidad").show();
                    $("#btnSave").show();
                    $("#div-dni").removeClass('has-success has-error').addClass('has-success');
                    calcular_edad(record.fecha_nacimiento);
                    cargarArea();
                    getNegativaByDNI(record.documento);
                } else {
                    $("#txt_mensaje").html(`<span style="color: red;">No se encontró en el sistema una persona con DNI: ${numeroDNI}. Por favor, debe registrarla</span>`);
                    $("#div-dni").removeClass('has-success has-error').addClass('has-error');
                    $('input[name="Mds_certificacion[idbeneficiario]"]').val('')
                    $("#btnContacto").prop("disabled", false);
                    $("#txt_mensajeLocalidad").html(`.`);
                    $("#campos").hide();
                    $("#campos_localidad").hide();
                    $('#btnSave').hide();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                let errorMessage = "<span style='color: red'><b style='color: red'>Error!</b> <i>";
                const responseJSON = xhr.responseJSON;

                if (responseJSON && responseJSON.message) {
                    errorMessage += `${responseJSON.message}`;
                } else {
                    errorMessage += `Ocurrió un error al buscar a la persona`;
                }

                errorMessage += "</i></span>"
                $("#txt_mensaje").html(errorMessage);
                $("#txt_mensajeLocalidad").html(".");
            },
        });
    }

    function getNegativaByDNI(numeroDNI) {
        let txtDNI_search = $("#txtDNI_search").val();
        const token = "<?= $token ?>";
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        const url = "<?= env('ENDPOINT_API_SUR_NEST') ?>/<?= env('ENDPOINT_API_SUR_NEST_NEGATIVA_GET') ?>";
        const params = `/?dni=${numeroDNI}`;
        $.ajax({
            url: url + params,
            type: "GET",
            dataType: "json",
            async: true,
            headers,
            success: function(data) {
                if (data.status === "success") {
                    if (data?.records?.length) {
                        if (data.records[0].jubilado_pensionado == 'S') {
                            $("#jubilacion option[value='1']").attr("selected", true);
                            $("#tipojubilacion").show();
                        }
                        if (data.records[0].jubilado_pensionado == 'N') {
                            $("#jubilacion option[value='0']").attr("selected", true);
                            $("#tipojubilacion").hide();
                        }
                    }
                } else {
                    $("#txt_mensaje").html(`<b>Error!</b><i>${data.message}</i>`);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                let errorMessage = "<span style='color: red'><b style='color: red'>Error!</b> <i>";
                const responseJSON = xhr.responseJSON;

                if (responseJSON && responseJSON.message) {
                    errorMessage += `${responseJSON.message}`;
                } else {
                    errorMessage += `Ocurrió un error al buscar a la persona`;
                }

                errorMessage += "</i></span>"
                $("#txt_mensaje").html(errorMessage);
            }
        });
    }

    function getCertificacion(idcertificacion, numeroDNI, idbeneficiario, fechaInicio, fechaFin) {
        const token = "<?= $token ?>";
        const headers = {
            Authorization: `Bearer ${token}`,
        };
        const url = "<?= env('ENDPOINT_API_SUR_NEST') ?>/<?= env('ENDPOINT_API_SUR_NEST_CERTIFICACION_VERIFICAR_CARACTER') ?>";
        const caracter = "<?= $CARACTER_VIA_RAPIDA ?>"
        const params = `/${idcertificacion}/${caracter}/${numeroDNI}/${idbeneficiario}/${fechaInicio}/${fechaFin}`;
        $.ajax({
            url: url + params,
            type: "GET",
            dataType: "json",
            async: true,
            headers,
            success: function(data) {
                if (data.status === "success") {
                    if (data?.records == true) {
                        $("#txt_mensaje_caracter").html(data.message);
                        $("#txt_mensaje_caracter").css('color', 'red');
                        $("#idcaracter").val(``);
                        $("#idcaracter").removeClass('has-success has-error').addClass('has-error');
                    } else {
                        // $("#txt_mensaje_caracter").css('color', 'green');
                        // $("#txt_mensaje_caracter").html(`La última certificación otorgada NO fue de cáracter VÍA RÁPIDA.`);
                    }
                } else {
                    $("#txt_mensaje_caracter").html(`<b>Error!</b><i> ${data.message}</i>`);
                    $("#txt_mensaje_caracter").css('color', 'red');
                    $("#idcaracter").removeClass('has-success has-error').addClass('has-error');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                let errorMessage = "<span style='color: red'><b style='color: red'>Error!</b> <i>";
                const responseJSON = xhr.responseJSON;

                if (responseJSON && responseJSON.message) {
                    errorMessage += `${responseJSON.message}`;
                } else {
                    errorMessage += `Ocurrió un error al verificar el caracter de la última certificación`;
                }

                errorMessage += "</i></span>"
                $("#txt_mensaje_caracter").html(errorMessage);
            },
        });
    }

    function cargarArea() {
        let opciones = $('#cmb_idarea').children();
        if (opciones.length == 2) {
            $("#cmb_idarea option").each(function() {
                if ($(this).val()) {
                    $("#cmb_idarea option[value='" + $(this).val() + "']").attr("selected", true);
                    cargarProgramas();
                }
            });
        }
    }

    function cargarProgramas() {
        limpiarDependientesPrograma();
        $("#cmb_iddireccion").prop("disabled", true);

        let idProgramaSelected = $("#idProgramaSelected").val();
        let cmb_idarea = $("#cmb_idarea").val();
        let dato = '';
        $.post(`index.php?r=mds_certificacion_programa/listado_programas&id=${cmb_idarea}`, function(data) {
            if (data.length === 0) {
                $("#cmb_programa").html(`<option value=null selected='true' disabled='disabled'>La dirección no cuenta con programas asignados</option>`);
            } else {
                data = $.parseJSON(data);
                $.each(data, function(ind, elem) {
                    dato += `<option value='${elem.idprograma}'>${elem.descripcion}</option>`;
                });
                $("#cmb_programa").html(`<option value=null selected='true' disabled='disabled'>Seleccione</option>${dato}`);
            }

            if (idProgramaSelected) {
                $("#cmb_programa option[value='" + idProgramaSelected + "']").attr("selected", true);
                $("#cmb_iddireccion").prop("disabled", true);
                $("#cmb_programa").prop("disabled", true);
            } else {
                $("#cmb_programa").prop("disabled", false);
            }
        });
        $("#cmb_iddireccion").html(`<option value=null selected='true' disabled='disabled'>Seleccione</option>`);
    }

    function cargarDirecciones() {

        let idAreaSelected = $("#cmb_idarea").val();
        let idDireccionSelected = $("#idDireccionSelected").val();
        let idNivelAutorizacion = $("#idnivel_autorizacion").val();
        let idNivelAutorizacionSelected = $("#idNivelAutorizacionSelected").val();
        let idNivel = idNivelAutorizacion ? idNivelAutorizacion : idNivelAutorizacionSelected;
        let fechaDesde = $("#fechaInicio").val();
        let fechaHasta = $("#fechaFin").val();
        let dato = '';

        if (idNivel) {
            $.post(`index.php?r=mds_certificacion_direccion/lista_direcciones&idAreaSelected=${idAreaSelected}&nivel=${idNivel}&fechaDesde=${fechaDesde}&fechaHasta=${fechaHasta}`, function(data) {
                data = $.parseJSON(data);
                if (data.length === 0) {
                    $("#cmb_iddireccion").html(`<option value=null selected='true' disabled='disabled'>El nivel no cuenta con Direcciones asignadas</option>`);
                } else {
                    $.each(data, function(ind, elem) {
                        if (data.length === 1) {
                            dato += `<option selected='true' value="${elem.idcertificaciondireccion}"> ${elem.descripcion} (${elem.apellido} ${elem.nombre})</option>`;
                        } else {
                            dato += `<option value="${elem.idcertificaciondireccion}">${elem.descripcion} (${elem.apellido} ${elem.nombre})</option>`;
                        }
                    });
                    $("#cmb_iddireccion").html(`<option value=null selected='true' disabled='disabled'>Seleccione</option>` + dato);
                    $("#cmb_iddireccion").prop("disabled", false);
                }
                if (idDireccionSelected) {
                    $("#cmb_iddireccion option[value='" + idDireccionSelected + "']").attr("selected", true);
                    $("#cmb_iddireccion").prop("disabled", true);
                }
            });
        }
    }

    function verificarCaracter() {
        $("#txt_mensaje_caracter").html("");
        $("#idcaracter").removeClass('has-success has-error').addClass('has-success');

        if ($("#mds_certificacion-idbeneficiario").val() && $("#idcaracter").val() == <?= $CARACTER_VIA_RAPIDA ?> && $("#fechaInicio").val() && $("#fechaFin").val()) {
            const dni = $("#idDNI").val();
            const idbeneficiario = $("#mds_certificacion-idbeneficiario").val();
            const fechaInicio = formatDate($("#fechaInicio").val());
            const fechaFin = formatDate($("#fechaFin").val());
            let idcertificacion = null;

            <?php if ($model->idcertificacion) { ?>
                idcertificacion = <?= $model->idcertificacion ?>;
            <?php } ?>

            getCertificacion(idcertificacion, dni, idbeneficiario, fechaInicio, fechaFin);
        }
        getCertificacionIncremento();
    }

    function getCertificacionIncremento() {
        $("#incremento_div").hide();
        if ($("#idcaracter").val() == <?= $ID_INCREMENTO ?>) {
            const dni = $("#idDNI").val();
            let dato = '';
            $.post(`index.php?r=mds_certificacion/certificacion_incremento&dni=${dni}`, function(data) {
                data = $.parseJSON(data);
                if (data.length === 0) {
                    $("#incremento_div").hide();
                    $("#id_certificacion_incremento").html(`<option value=null selected='true' disabled='disabled'>El beneficiario no cuenta con certificaciones</option>`);
                } else {
                    $("#incremento_div").show();
                    $.each(data, function(ind, elem) {
                        if (data.length === 1) {
                            dato += `<option selected='true' value="${elem.idcertificacion}">#${elem.idcertificacion}-Desde ${elem.fecha_desde} al ${elem.fecha_hasta}</option>`;
                        } else {
                            dato += `<option value="${elem.idcertificacion}">#${elem.idcertificacion}-Desde ${elem.fecha_desde} al ${elem.fecha_hasta}</option>`;
                        }
                    });
                    $("#id_certificacion_incremento").html(`<option value=null selected='true' disabled='disabled'>Seleccione</option>` + dato);
                    $("#id_certificacion_incremento").prop("disabled", false);
                }
            });
        } else {
            $("#incremento_div").hide();
            $("#id_certificacion_incremento").html(`<option value=null selected='true' disabled='disabled'>El beneficiario no cuenta con certificaciones</option>`);
            $("#id_certificacion_incremento").prop("disabled", true);
        }
    }

    function setFechaFin(fecha) {
        limpiarDependientesPrograma();
        $("#cmb_idarea").trigger('change');
        $("#cmb_programa").trigger('change');
        $("#cmb_iddireccion").trigger('change');
        if (!$("#fechaFin").val() && fecha) {
            if (fecha.getMonth() > 5) { // Es posterior a mayo. Se debe precargar Diciembre
                fecha.setMonth(11);
                fecha.setDate(31);
            } else { // Es anterior a junio. Se debe precargar junio
                fecha.setDate(1); //Esto es porque hay conflicto cuando se selecciona el dia 31
                fecha.setMonth(5);
                fecha.setDate(30);
            }
            $("#fechaFin").parent().kvDatepicker('setDate', fecha);
        }
    }

    function changeTipoCertificacion() {
        if ($("#tipo_certificacion option:selected").val() == "1") {
            $("#externa-tipo").show();
        } else {
            $("#idorganismo_solicitante").val(``);
            $("#externa-tipo").hide();
        }
    }

    function verificarTipoCertificacion() {
        $("#externa-tipo").removeClass('has-success has-error').addClass('has-success');
        $("#organismo_solicitante-msg").text(``);
        if ($("#tipo_certificacion option:selected").val() == "1" && !$("#idorganismo_solicitante").val()) {
            $("#externa-tipo").removeClass('has-success has-error').addClass('has-error');
            $("#organismo_solicitante-msg").text(`Organismo solicitante no puede estar vacío.`).css('color', '#a94442');
            return false;
        }
        return true;
    }

    function changeJubilacion() {
        if ($("#jubilacion option:selected").val() == '1') {
            $("#tipojubilacion").show();
        } else {
            $("#tipo_jubilacion").val(``);
            $("#monto_jubilacion").val(``);
            $("#tipojubilacion").hide();
            $("#monto_div").hide();
        }
    }

    function changeTipoJubilacion() {
        if ($("#tipo_jubilacion option:selected").val() == <?= $TIPO_JUBILACION_OTRO ?>) {
            $("#monto").show();
            $("#monto_div").show();
        } else {
            $("#monto_jubilacion").val(``);
            $("#monto_div").hide();
        }
    }

    function verificarTipoJubilacion() {
        $("#tipojubilacion").removeClass('has-success has-error').addClass('has-success');
        $("#tipojubilacion-msg").text(``);
        if ($("#jubilacion option:selected").val() == '1' && $("#tipo_jubilacion option:selected").val() == "") {
            $("#tipojubilacion").removeClass('has-success has-error').addClass('has-error');
            $("#tipojubilacion-msg").text(`Tipo de jubilación/pensión no puede estar vacío.`).css('color', '#a94442');
            return false;
        }
        return true;
    }

    function verificarMonto() {
        $("#monto_solicitado_div").removeClass('has-success has-error').addClass('has-success');
        $("#monto_solicitado-msg").text(``);
        if ($('#mds_certificacion_monto-monto').val() > 70000) {
            $("#monto_solicitado_div").removeClass('has-success has-error').addClass('has-error');
            $("#monto_solicitado-msg").text(`Monto ingresado es superior al permitido.`).css('color', '#a94442');
            return false;
        }
        return true;
    }

    function changeSueldo() {
        if ($("#sueldo option:selected").val() == 1) {
            $("#sueldo_monto").prop("readonly", false);
            $("#sueldo_monto_div").show();
        } else {
            $("#sueldo_monto").prop("readonly", true);
            $("#sueldo_monto_div").hide();
        }
    }

    function verificarMotivoCambio() {
        $("#motivo_responsable_div").removeClass('has-success has-error').addClass('has-success');
        if ($("#cambiar_responsable").is(':checked') && $("#mds_certificacion_responsable-motivo_cambio").val() == '') {
            $("#motivo_responsable_div").removeClass('has-success has-error').addClass('has-error');
            return false;
        }
        return true;
    }

    function verificarParentesco() {
        $("#parentesco_otro_div").removeClass('has-success has-error').addClass('has-success');
        if ($("#cmb_idparentesco option:selected").val() == '<?= $PARENTESCO_OTRO_OPTION ?>' && $("#mds_certificacion_responsable-parentesco_otro").val() == '') {
            $("#parentesco_otro_div").removeClass('has-success has-error').addClass('has-error');
            return false;
        }
        return true;
    }

    function verificarCuradorLegal() {
        $("#curador_legal_div").removeClass('has-success has-error').addClass('has-success');
        if ($("#cambiar_responsable").is(':checked') && !$("#cmb_curador_legal").val()) {
            $("#curador_legal_div").removeClass('has-success has-error').addClass('has-error');
            return false;
        }
        return true;
    }

    function verificarTipoResponsable() {
        $("#tipo_responsable_div").removeClass('has-success has-error').addClass('has-success');
        if ($("#cambiar_responsable").is(':checked') && !$("#mds_certificacion_responsable-tipo_responsable").val()) {
            $("#tipo_responsable_div").removeClass('has-success has-error').addClass('has-error');
            return false;
        }
        return true;
    }

    function verificarRendicion() {
        $("#rendicion_div").removeClass('has-success has-error').addClass('has-success');
        if ($("#cambiar_responsable").is(':checked') && !$("#mds_certificacion_responsable-rendicion").val()) {
            $("#rendicion_div").removeClass('has-success has-error').addClass('has-error');
            return false;
        }
        return true;
    }

    function changeParentesco() {
        $("#parentesco_otro_div").removeClass('has-success has-error').addClass('has-success');
        if ($("#cmb_idparentesco option:selected").val() == '<?= $PARENTESCO_OTRO_OPTION ?>') { //opcion no es familiar
            $("#parentesco_otro_div").show();
        } else {
            $("#parentesco_otro_div").hide();
            $("#mds_certificacion_responsable-parentesco_otro").val(``);
        }
    }

    function cambiarResponsable() {
        $('#cambiar_responsable').on('change', function() {
            if ($('#cambiar_responsable').is(':checked')) {
                $('#mds_certificacion_responsable-nombre_apellido').prop("readonly", false);
                $('#mds_certificacion_responsable-dni').prop("readonly", false);
                $('#mds_certificacion_responsable-cbu_alias').prop("readonly", false);
                $('#mds_certificacion_responsable-motivo_cambio').val('').prop("readonly", false);

                $('#mds_certificacion_responsable-parentesco_otro').prop("readonly", false);
                $('#cmb_idparentesco').prop("disabled", false);
                $('#mds_certificacion_responsable-tipo_responsable').prop("disabled", false);
                $('#cmb_curador_legal').prop("disabled", false);
                $('#mds_certificacion_responsable-rendicion').prop("disabled", false);

                // $('#mds_certificacion_responsable-parentesco_otro').val(``);
                // $('#cmb_idparentesco').val(``);
                // $('#mds_certificacion_responsable-tipo_responsable').val(``);
                // $('#cmb_curador_legal').val(``);
                // $('#mds_certificacion_responsable-rendicion').val(``);

                $('#cambio_responsable_div').show();
                $('#motivo_responsable_div').show();
                $('#parentesco_div').show();
                if ($("#cmb_idparentesco option:selected").val() == '<?= $PARENTESCO_OTRO_OPTION ?>') { //opcion no es familiar
                    $("#parentesco_otro_div").show();
                }
            } else {
                $('#mds_certificacion_responsable-nombre_apellido').prop("readonly", true);
                $('#mds_certificacion_responsable-dni').prop("readonly", true);
                $('#mds_certificacion_responsable-cbu_alias').prop("readonly", true);
                $('#mds_certificacion_responsable-motivo_cambio').val('').prop("readonly", true);

                $('#mds_certificacion_responsable-parentesco_otro').prop("readonly", true);
                $('#cmb_idparentesco').prop("disabled", true);
                $('#mds_certificacion_responsable-tipo_responsable').val(``).prop("disabled", true);
                $('#cmb_curador_legal').val(``).prop("disabled", true);
                $('#mds_certificacion_responsable-rendicion').val(``).prop("disabled", true);
                $('#cambio_responsable_div').hide();

                $('#motivo_responsable_div').hide();
                $('#parentesco_otro_div').hide();
            }
        })
    }

    function changeAdjuntos() {

        $("#TIPO_ADJUNTO").on("change", function() {
            let adjuntos = obtenerAdjuntosCertificaciones();
            let dropzone_documentos = document.querySelector("#adjunto-otrosdocumentos").dropzone;
            let valor = $("#TIPO_ADJUNTO").val();

            this.adjuntosCertificaciones = [];

            let arrayDelete = $("#adjuntos_eliminados").val();
            let eliminados = arrayDelete.replace(/\[/g, '').replace(/\]/g, '').replace(/\"/g, '');
            let arrayEliminados = eliminados.split(',');

            adjuntos = adjuntos.filter(item => item.id_objeto);
            let adjNuevo = $("#otros_adjuntos").val();
            if (adjNuevo !== "") {
                let arrayNuevos = JSON.parse(adjNuevo);
                for (let i = 0; i < arrayNuevos.length; i++) {

                    adjuntos.push({
                        "nombre": arrayNuevos[i].nombre_original,
                        "tipo": arrayNuevos[i].tipo,
                    });
                }
            }

            // Filtra segun el tipo adjunto y los adjuntos que se eliminaron
            this.adjuntosCertificaciones = adjuntos.filter(
                element => {
                    return element.tipo === valor &&
                        !arrayEliminados.includes(element.idlegalesarchivo);

                }
            );

            $("#otro_adjunto_container").show();
            dropzone_documentos.hiddenFileInput.disabled = false;

            $("#adjunto-otrosdocumentos").empty();

            $("#adjunto-otrosdocumentos").html(
                '<div class="dz-default dz-message"><button class="dz-button" type="button">Adjunte documentos complementarios aquí</button></div>'
            );
            $(".dropzone.dz-started .dz-message").show();

            if (this.adjuntosCertificaciones.length > 0) {
                $.each(this.adjuntosCertificaciones, function(key, adjunto) {
                    let mockFileCertificacion = {
                        id: adjunto.idlegalesarchivo,
                        name: adjunto.nombre,
                        path: adjunto.path,
                        objeto: adjunto.objeto,
                        tipo: adjunto.tipo,
                        tipoAdjunto: adjunto.tipoAdjunto
                    };
                    //Se cargan los archivos que ya tiene registrado
                    dropzone_documentos.files.push(mockFileCertificacion); // add to files array
                    dropzone_documentos.emit("addedfile", mockFileCertificacion);

                    if (esImagen(mockFileCertificacion.name)) {
                        dropzone_documentos.emit(
                            "thumbnail",
                            mockFileCertificacion,
                            `/mds/web/uploads/certificaciones/${mockFileCertificacion.path}`
                        );
                    }

                    dropzone_documentos.emit("complete", mockFileCertificacion);
                    dropzone_documentos.emit(
                        "complete",
                        $("#adjunto-otrosdocumentos .dz-remove").html(
                            "<div><button class='btn btn-danger btn-sm mt-1'>Eliminar</button>"
                        )
                    );

                    // $("#adjunto-otrosdocumentos .dz-remove")
                    //     .last()
                    //     .after(
                    //         `<span class='badge badge-secondary'>${mockFileCertificacion.tipo}</span>`
                    //     );


                    dropzone_documentos.emit(
                        "complete",
                        $(".dz-remove").attr("id", mockFileCertificacion.id)
                    );

                    if (mockFileCertificacion.id) {
                        dropzone_documentos.emit(
                            "complete",
                            $("#adjunto-otrosdocumentos .dz-remove")
                            .last()
                            .after(`<a style="width: 100%" class="btn btn-sm btn-info mt-1 d-block" href="/mds/web/uploads/certificaciones/${mockFileCertificacion.path}" target="_blank">Ver</a>`)
                            .after(`<span style="width: 100%;text-align: center">${mockFileCertificacion.tipoAdjunto}</span>`)
                        );
                    }
                });
                $(".dropzone.dz-started .dz-message").hide();
            }
        });
    }

    function verificarAdjuntos() {
        $("#adjunto_txt_mensaje").html(``);
        $("#tipo_adjunto_label").css('color', '#3c763d');
        $("#adjunto_select").removeClass('has-success has-error').addClass('has-success');

        let adjNuevo = $("#otros_adjuntos").val();
        let adjEliminados = $("#adjuntos_eliminados").val();
        let adjGuardados = obtenerAdjuntosCertificaciones();

        let indexselect = 0;
        let adjfaltante = false;
        let eliminados = true;
        let optionsSelect = [];
        let arrayNuevos = [];

        $("#TIPO_ADJUNTO option").each(function(index, element) {
            if (index != 0) {
                optionsSelect.push($(this).val());
            }
        });

        if (adjNuevo !== "") {
            arrayNuevos = JSON.parse(adjNuevo);
        }

        if (adjGuardados.length > 0) {
            let eliminados = adjEliminados.replace(/\[/g, '').replace(/\]/g, '').replace(/\"/g, '');
            let arrayEliminados = eliminados.split(',');

            // Quitamos los adjuntos que se eliminaron
            let adjActualizados = adjGuardados.filter(
                element => {
                    return !arrayEliminados.includes(element.idlegalesarchivo)
                }
            );

            if (adjNuevo !== "") { //Agregamos los nuevos adjuntos
                for (let i = 0; i < adjActualizados.length; i++) {
                    arrayNuevos.push(adjActualizados[i]);
                }
            } else {
                arrayNuevos = adjActualizados;
            }

            eliminados = !optionsSelect.every(
                element =>
                arrayNuevos.some(function(el) {
                    return (el.tipo === element);
                })
            );
        }

        if (eliminados) {
            $("#TIPO_ADJUNTO option").each(function(index, element) {
                if (index != 0) {
                    if (selectAdjuntosObligatorios.includes($(this).val())) {
                        let adjunto = arrayNuevos.find(element => element['tipo'] === $(this).val());
                        if (!adjunto) {
                            indexselect = index;
                            adjfaltante = true;
                            return false;
                        }
                    }
                }
            });

            if (adjfaltante) {
                $("#adjunto_txt_mensaje").html(`Por favor, ingrese la documentación`).css('color', 'red');
                $("#tipo_adjunto_label").css('color', '#a94442');
                $("#adjunto_select").removeClass('has-success has-error').addClass('has-error');

                $('#TIPO_ADJUNTO>option:eq(' + indexselect + ')').prop('selected', true);
                $("#TIPO_ADJUNTO").trigger('change');
                return false;
            }
        }
        return true;
    }

    function obtenerAdjuntosCertificaciones() {
        let idcertificacion = null;
        let adjuntos = [];

        <?php if ($model->idcertificacion) { ?>
            idcertificacion = <?= $model->idcertificacion ?>;
            $.ajaxSetup({
                async: false,
            });
            $.post("index.php?r=mds_certificacion/get_adjuntos&id=" + idcertificacion,
                function(data) {
                    adjuntos = $.parseJSON(data);
                }
            );
        <?php } ?>
        return adjuntos;
    }

    function limpiarDependientesPrograma() {
        $("#idnivel_autorizacion").val('').prop("disabled", true);
        $("#cmb_iddireccion").val('').prop("disabled", true);
        $("#mds_certificacion_monto-monto").val('').attr("readonly", true);
        $("#TIPO_ADJUNTO").val('').prop("disabled", true);
        limpiarDropzone();
        limpiarResponsable();
    }

    function deshabilitarDropzone() {
        let dropzone_documentos = document.querySelector("#adjunto-otrosdocumentos").dropzone;
        dropzone_documentos.hiddenFileInput.disabled = true;
        $(".btn-danger").hide();
    }

    function limpiarDropzone() {
        // Esto limpia el dropzone
        let dropzone_documentos = document.querySelector("#adjunto-otrosdocumentos").dropzone;
        dropzone_documentos.hiddenFileInput.disabled = true;
        Dropzone.forElement("#adjunto-otrosdocumentos").removeAllFiles(true);
        $("#otros_adjuntos").val("");
    }


    function limpiarResponsable() {
        let textoNombreDni = $("#txtDNI_search").val();
        const regex = /\((.*?)\)/;
        let subcadena = textoNombreDni.match(regex);

        if (subcadena && subcadena.length > 1) {
            let dniBeneficiario = subcadena[1];
            let nombreBeneficiario = textoNombreDni.substring(0, subcadena.index - 1);
            $("#mds_certificacion_responsable-dni").val(dniBeneficiario);
            $("#mds_certificacion_responsable-nombre_apellido").val(nombreBeneficiario);
            $("#cmb_idparentesco").val(<?= $PARENTESCO_TITULAR ?>);
        }

        getCertificacionesResponsable();

        $('#cambiar_responsable').prop('checked', false)
        $('#mds_certificacion_responsable-nombre_apellido').prop("readonly", true);
        $('#mds_certificacion_responsable-dni').prop("readonly", true);
        $('#mds_certificacion_responsable-motivo_cambio').val('').prop("readonly", true);

        $('#mds_certificacion_responsable-parentesco_otro').prop("readonly", true);
        $('#cmb_idparentesco').prop("disabled", true);
        $('#mds_certificacion_responsable-tipo_responsable').val(``).prop("disabled", true);
        $('#cmb_curador_legal').val(``).prop("disabled", true);
        $('#mds_certificacion_responsable-rendicion').val(``).prop("disabled", true);
        $('#cambio_responsable_div').hide();

        $('#motivo_responsable_div').hide();
        $('#parentesco_otro_div').hide();
    }

    function precargarDependientesPrograma() {
        limpiarDependientesPrograma();
        if (verificarFecha()) {
            precargarMonto();
            precargarSelectAdjuntos();
            permiteCambioResponsable();
        }
    }

    function precargarMonto() {
        $.ajaxSetup({
            async: false,
        });
        let cmb_idarea = $("#cmb_idarea").val();
        let cmb_programa = $("#cmb_programa").val();

        if ($("#cmb_programa").val()) {
            $.post(`index.php?r=mds_certificacion_programa/precargarmonto&iddireccion=${cmb_idarea}&idprograma=${cmb_programa}`,
                function(data) {
                    if (data) {
                        data = $.parseJSON(data);
                        requiereautorizacion(data);
                        <?php if ($model_certificacion_monto->monto != NULL) { ?>
                            data = <?= $model_certificacion_monto->monto ?>;
                        <?php } ?>
                        if (data.monto) {
                            $("#mds_certificacion_monto-monto").val(data.monto);
                            $("#mds_certificacion_monto-monto").attr("readonly", true);
                        } else {
                            $("#mds_certificacion_monto-monto").attr("readonly", false);
                        }
                    }
                }
            );
        }
    }

    function permiteCambioResponsable() {
        $("#cambiar_responsable_check").show();

        let cmb_idarea = $("#cmb_idarea").val();
        let cmb_programa = $("#cmb_programa").val();

        if (cmb_idarea && cmb_programa) {
            $.post(`index.php?r=mds_certificacion_programa/permite_cambioresponsable&iddireccion=${cmb_idarea}&idprograma=${cmb_programa}`,
                function(data) {
                    if (data) {
                        data = $.parseJSON(data);
                        if (data.cambio_responsable != 1) {
                            $("#cambiar_responsable_check").hide();
                        }
                    }
                }
            );
        }
    }

    function requiereautorizacion(data) {
        let nivel = <?= $ID_NIVEL4 ?>;
        let idNivelAutorizacionSelected = $("#idNivelAutorizacionSelected").val();
        let idDireccionSelected = $("#idDireccionSelected").val();
        let fechaDesde = $('#fechaInicio').val();
        let fechaHasta = $('#fechaFin').val();
        let cmb_area = $("#cmb_idarea").val();

        let dato = '';

        if (idDireccionSelected && idNivelAutorizacionSelected) {
            cargarDirecciones();
        }
        if (data.requiere_autorizacion != 1) {
            $.post(`index.php?r=mds_certificacion_direccion/lista_direcciones&idAreaSelected=${cmb_area}&fechaDesde=${fechaDesde}&fechaHasta=${fechaHasta}`, function(data) {
                data = $.parseJSON(data);
                if (data.length === 0) {
                    $("#cmb_iddireccion").html(`<option value=null selected='true' disabled='disabled'>El nivel no cuenta con Direcciones asignadas</option>`);
                } else {
                    $.each(data, function(ind, elem) {
                        if (data.length === 1) {
                            dato += `<option selected='true' value="${elem.idcertificaciondireccion}">${elem.descripcion} (${elem.apellido} ${elem.nombre})</option>`;
                        } else {
                            dato += `<option value="${elem.idcertificaciondireccion}">${elem.descripcion} (${elem.apellido} ${elem.nombre})</option>`;
                        }
                    });

                    $("#cmb_iddireccion").html(`<option value=null selected='true' disabled='disabled'>Seleccione</option>` + dato);
                    $("#cmb_iddireccion").prop("disabled", false);
                }
                if (idDireccionSelected) {
                    $("#cmb_iddireccion option[value='" + idDireccionSelected + "']").attr("selected", true);
                }
            });
            precargarSelectNiveles(data);
        } else {
            if (idNivelAutorizacionSelected) {
                $("#idnivel_autorizacion").val(idNivelAutorizacionSelected)
            } else {
                $("#cmb_iddireccion").val('');
                $("#cmb_iddireccion").html(`<option value=null selected='true' disabled='disabled'>Seleccione</option>`);
                precargarSelectNiveles(data);
            }
        }
    }

    function precargarSelectNiveles(data) {
        $("#idnivel_autorizacion").val(``).prop("disabled", false);
        let select_item = document.getElementById('idnivel_autorizacion');
        let options = select_item.getElementsByTagName('option');
        let lastChild = options[options.length - 1];

        options.forEach(function(option) {
            option.style.display = '';
        });

        if (data.requiere_autorizacion == 0) { //Seleccionar nivel 4
            lastChild.selected = true;
            $("#idnivel_autorizacion").prop("disabled", true);
        } else { //select con los niveles que tiene el programa
            let vueltas = <?= $cantNiveles ?> - data.cant_niveles_autorizacion;
            if (vueltas > 0) {
                for (var i = 1; i <= vueltas; i++) {
                    let child = options[i];
                    child.style.display = 'none';
                }
            }
            if (data.cant_niveles_autorizacion == 1) {
                child = options[vueltas + 1];
                child.selected = true;
                cargarDirecciones();
            }
            lastChild.style.display = 'none';
        }
    }

    let selectAdjuntosObligatorios = []; //Se usa en verificarAdjuntos()
    function precargarSelectAdjuntos() {
        let dato = '';
        let cmb_area = $("#cmb_idarea").val();
        let cmb_programa = $("#cmb_programa").val();
        $.post(`index.php?r=mds_certificacion_programa/listado_adjuntos&iddireccion=${cmb_area}&idprograma=${cmb_programa}`, function(data) {
            if (data.length === 0) {
                $("#TIPO_ADJUNTO").html(`<option value=null selected='true' disabled='disabled'>El programa no cuenta con adjuntos asignados</option>`);
                $("#TIPO_ADJUNTO").prop("disabled", true);
            } else {
                data = $.parseJSON(data);
                $.each(data, function(ind, elem) {
                    let obligatorio = '';
                    if (elem.obligatorio == 1) {
                        obligatorio = ' (*)';
                        selectAdjuntosObligatorios.push(elem.idadjunto);
                    }
                    dato += `<option value='${elem.idadjunto}'>${elem.descripcion}${obligatorio}</option>`;
                });
                $("#TIPO_ADJUNTO").html(`<option value=null selected='true' disabled='disabled'>Seleccione</option>${dato}`);
                $("#TIPO_ADJUNTO option[value='" + idProgramaSelected + "']").attr("selected", true);
                $("#TIPO_ADJUNTO").prop("disabled", false);
            }
        });
    }

    function validarFechas() {
        fechaDesde = $("#fechaInicio").val()
        fechaFin = $("#fechaFin").val()
        fechaInicioDias = fechaDesde.substr(0, 2);
        fechaInicioMes = fechaDesde.substr(3, 2);
        fechaInicioAño = fechaDesde.substr(6, 4);
        fechaFinDias = fechaFin.substr(0, 2);
        fechaFinMes = fechaFin.substr(3, 2);
        fechaFinAño = fechaFin.substr(6, 4);
        let fecha_desde = new Date(fechaInicioAño, fechaInicioMes, fechaInicioDias);
        let fecha_hasta = new Date(fechaFinAño, fechaFinMes, fechaFinDias);

        if (fechaDesde && fechaFin) {
            if (fecha_desde < fecha_hasta) {
                $('#btnSave').prop('disabled', false);
                $('#divDatepickerFin').removeClass('has-success has-error').addClass('has-success');
                $('#smallFin').text('');
            } else {
                $('#btnSave').prop('disabled', true);
                $('#divDatepickerFin').removeClass('has-success has-error').addClass('has-error');
                $('#divDatepickerFin').css('color', '#a94442');
                $('#smallFin').text('Periodo Hasta debe ser mayor a Periodo Desde.');
            }
        }
    }

    function verificarFecha() {
        $("#txt_mensaje_programa").html(``);
        fechaDesde = $("#fechaInicio").val();
        fechaHasta = $("#fechaFin").val();

        if (!(fechaDesde && fechaHasta)) {
            $("#txt_mensaje_programa").html(`<i>Por favor, ingrese el Periodo Desde y el Periodo Hasta para continuar.</i>`);
            $("#txt_mensaje_programa").css('color', 'red');
            cargarProgramas();
            return false;
        }
        return true;
    }

    function getCertificacionesResponsable() {
        let dato = ``;
        let dniResponsable = $("#mds_certificacion_responsable-dni").val();
        let id = $("#idcertificacion").val();

        if (dniResponsable && id) {
            $.post(`index.php?r=mds_certificacion/responsable_asignado&dniResponsable=${dniResponsable}&idcertificacion=${id}`, function(data) {
                data = $.parseJSON(data);
                $("#txt_mensaje_responsable").html(dato);
                $("#txt_info_responsable").removeClass("alert alert-info");
                if (data.length !== 0) {
                    dato = `<p><i class="fa-solid fa-circle-info"></i>El DNI ingresado se encuentra como Responsable de cobro/Tutor especial en `;
                    dato += data.length > 2 ? `las certificaciones: ` : `la certificación: `;
                    $.each(data, function(index, elem) {
                        dato += `#<b>${elem.idcertificacion}</b>`;
                        dato += data.length - 2 > index ? `, ` : data.length - 1 > index ? ` y ` : ``;
                    });
                    dato += `</p>`;
                    $("#txt_info_responsable").addClass("alert alert-info");
                    $("#txt_mensaje_responsable").html(dato);
                }
            });
        }
    }

    function changeDniResponsable() {
        $('#mds_certificacion_responsable-dni').on('input', function() {
            let inputValue = $(this).val();
            let desiredLength = 6; // Cantidad de números deseada
            if (inputValue.length > desiredLength && !isNaN(inputValue)) {
                getCertificacionesResponsable();
            }
        });
    }

    function calcular_edad(fecha_nacimiento) {
        var hoy = new Date();
        var cumpleanos = new Date(fecha_nacimiento);
        var edad = hoy.getFullYear() - cumpleanos.getFullYear();
        var m = hoy.getMonth() - cumpleanos.getMonth();

        if (m < 0 || (m === 0 && hoy.getDate() < cumpleanos.getDate())) {
            edad--;
        }
        $("#edad").val(edad);
        if (edad != 1) {
            $('#edad').text('Edad actual ' + edad + ' años.');
        } else {
            $('#edad').text('Edad actual ' + edad + ' año.');
        }
    }

    function abrirModalPersona() {
        limpiarDatos();
        $('#DATOS_PERSONA_CONTAINER, #btn_dni_benef').hide();
        $("#abm_persona").show();
        $('#btnNewPersona').prop('disabled', true);
        $("#txtDNI").val($("#txtDNI_search").val());
        iniciarBusqueda();
    }

    function formatDate(fecha) {
        // Divide la fecha en día, mes y año
        const partes = fecha.split('/');

        // Obtiene el día, mes y año en el nuevo formato
        const nuevoFormato = partes[1] + '-' + partes[0] + '-' + partes[2];

        return nuevoFormato;
    }
</script>

<?php
Modal::begin([
    'id' => 'modal_responsables',
    'size' => 'modal-lg'
]);
echo "<div id='content_modal'></div>";
Modal::end();
?>