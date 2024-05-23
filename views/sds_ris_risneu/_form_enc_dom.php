<?php

use app\models\Sds_com_configuracion_tipo;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

function botonAltaEncuestador()
{
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['//sds_com_configuracion/create_ext', 'tipo' => Sds_com_configuracion_tipo::TIPO_ENCUESTADOR]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btnEncuestador', 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        'onclick' => '
          $("#modal_abm").modal("show")
          .find("#content_abm")
          .load($(this).attr("value"));
          $("#header_abm").html("Nuevo Encuestador");'
    ]);
}

function botonAltaBarrio()
{
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'class' => 'btn btn-success btn-flat',
        'id' => 'btnBarrio', 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        'onclick' => '
            var idlocalidad= $("#sds_ris_risneu-idlocalidad").val();
            var localidad= $("#sds_ris_risneu-idlocalidad option:selected").text();
            $("#modal_abm").modal("show")
            .find("#content_abm")
            .load("index.php?r=sds_ris_risneu/create_barrio&idlocalidad="+idlocalidad);
            $("#header_abm").html("Nuevo Barrio de "+localidad);'
    ]);
}

function botonAltaCalle($interseccion)
{
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to(['create_calle']),
        'class' => 'btn btn-success btn-flat',
        'id' => $interseccion ? 'btnCalleInt' : 'btnCalle', 'style' => 'margin-top:27px',
        'tabIndex' => '-1',
        'onclick' => '
          $("#modal_abm").modal("show")
          .find("#content_abm")
          .load($(this).attr("value"));
          $("#header_abm").html("Nueva Calle");'
    ]);
}
?>
<input type="hidden" id="EXISTE_JEFE" name="EXISTE_JEFE" value="<?= $existeJefe ?>">
<div class="row">
    <?php if ($jefeNombreCompleto) : ?>
        <div class="col-md-4">
            <label class="control-label">Apellido y Nombre Responsable</label>
            <input class="form-control" type="text" value="<?= $jefeNombreCompleto ?>" readonly>
        </div>
    <?php endif; ?>
    <div class="col-md-4">
        <?= $form->field($model, 'dni_beneficiario')->textInput(['tabIndex' => '1', 'maxlength' => 10]) ?>
    </div>
    <div class="col-md-4" style="padding-top:25px;">
        <?php if ($model->isNewRecord) : ?>
            <?php
            echo Html::a('<i class="glyphicon glyphicon-search"></i>', null, [
                'name' => 'btn_dni_benef',
                'id' => 'btn_dni_benef',
                'data-request-method' => 'post',
                'data-toggle' => 'tooltip',
                'class' => 'btn btn-primary',
                'title' => Yii::t('app', 'Consultar DNI'),
            ]) . Html::a('<i class="far fa-edit"></i>', null, [
                'name' => 'btn_dni_edit',
                'id' => 'btn_dni_edit',
                'data-request-method' => 'post',
                'data-toggle' => 'tooltip',
                'class' => 'btn btn-primary',
                'style' => 'margin-left:1%;display:none',
                'title' => Yii::t('app', 'Cambiar DNI'),
            ]);
            ?>
        <?php elseif (!$model->isNewRecord && !$existeJefe) : ?>
            <?= Html::a('<i class="glyphicon glyphicon-search"></i>', null, [
                'name' => 'btn_dni_benef_update',
                'id' => 'btn_dni_benef_update',
                'data-request-method' => 'post',
                'data-toggle' => 'tooltip',
                'class' => 'btn btn-primary',
                'title' => Yii::t('app', 'Consultar DNI'),
            ]) ?>
        <?php endif; ?>
        <label style="padding-left: 10px;" id="mensaje_risneu">
        </label>
        <label style="padding-left: 10px;">
            <?= $model->oficial == 1 ? '<b>RISNeu Oficial</b>' : '<b>RISNeu no oficial</b>' ?>
        </label>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?php
        if ($model->fecha != null) {
            $model->fecha = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha)));
        }
        echo $form->field($model, 'fecha')->widget(DatePicker::class, [
            'name' => 'check_issue_date',
            'language' => 'es',
            'readonly' => false,
            'layout' => '{picker}{input}{remove}',
            'options' => [
                'id' => 'fecha',
                'tabIndex' => '1',
                'class' => 'form-control input-md',
                'disabled' => true
            ],
            'pluginOptions' => [
                'value' => null,
                'format' => 'dd/mm/yyyy',
                'endDate' => date('d/m/Y'),
                'todayHighlight' => true,
                'autoclose' => true,
            ]
        ])->label('Fecha (dd/mm/yyyy)'); ?>
    </div>
    <?php Pjax::begin(['id' => 'pjax_config_' . Sds_com_configuracion_tipo::TIPO_ENCUESTADOR, 'timeout' => '5000']); ?>
    <div class="col-md-4">
        <div class="input-group">
            <?= $form->field($model, 'encuestador')->widget(Select2::class, [
                'data' => $encuestadores,
                'options' => [
                    'placeholder' => 'Seleccionar Encuestador ...',
                    'tabIndex' => '1',
                    'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_ENCUESTADOR,
                    'disabled' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
            <span class="input-group-btn">
                <?= $modulo_encuestador ? botonAltaEncuestador() : "" ?>
            </span>
        </div>
    </div>
    <?php Pjax::end(); ?>
    <div class="col-md-4">
        <?= $form->field($model, 'realizado_por')->widget(Select2::class, [
            'data' => $realizadoPor,
            'options' => [
                'placeholder' => 'Seleccionar Entidad ...',
                'tabIndex' => '1',
                'disabled' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>
</div>
<div class="row">
    <!-- LOCALIDAD CP BARRIO -->
    <div class="col-md-3">
        <?= $form->field($model, 'idprovincia')->widget(Select2::class, [
            'data' => $provincias,
            'options' => [
                'placeholder' => 'Seleccionar Provincia ...',
                'tabIndex' => '1',
                'onchange' =>   'cargarLocalidades();',
                'disabled' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Provincia');
        ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'idlocalidad')->widget(Select2::class, [
            'data' => $localidades,
            'options' => [
                'placeholder' => 'Seleccionar Localidad ...',
                'tabIndex' => '1',
                'onchange' =>   'cargarBarrioCodPostal();',
                'disabled' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label('Localidad');
        ?>
    </div>
    <div class="col-md-3">
        <?php Pjax::begin(['id' => 'pjax_barrio', 'timeout' => '30000']); ?>
        <div class="input-group">
            <?=
            $form->field($model, 'idbarrio')->widget(Select2::class, [
                'data' => $barrios,
                'options' => [
                    'placeholder' => 'Seleccionar Barrio ...',
                    'tabIndex' => '1',
                    'id' => 'barrio',
                    'disabled' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label("Barrio");
            ?>
            <span class="input-group-btn">
                <?= botonAltaBarrio(); ?>
            </span>
        </div>
        <?php Pjax::end(); ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($model, 'cod_postal')->textInput(['disabled' => true]) ?>
    </div>
    <!-- ///FIN LOCALIDAD CP BARRIO -->
    <div class="col-md-2">
        <?= $form->field($model, 'area')->widget(Select2::class, [
            'data' => $areas,
            'options' => [
                'placeholder' => 'Seleccionar Área ...',
                'tabIndex' => '1',
                'disabled' => true,
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="input-group">
            <?= $form->field($model, 'calle')->widget(Select2::class, [
                'data' => $calles,
                'options' => [
                    'id' => 'calle',
                    'placeholder' => "Seleccionar Calle...",
                    'tabIndex' => '1',
                    'disabled' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <span class="input-group-btn">
                <?= botonAltaCalle(false); ?>
            </span>
        </div>
    </div>
    <div class="col-md-5">
        <div class="input-group">
            <?= $form->field($model, 'calle_interseccion')->widget(Select2::class, [
                'data' => $callesInterseccion,
                'options' => [
                    'id' => 'calle_int', 'tabIndex' => '1',
                    'placeholder' => "Seleccionar Calle Intersección...",
                    'disabled' => true
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
            <span class="input-group-btn">
                <?= botonAltaCalle(true); ?>
            </span>
        </div>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'calle_numero')->textInput(['maxlength' => true, 'tabIndex' => '1', 'disabled' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'casa')->textInput(['maxlength' => true, 'tabIndex' => '1', 'disabled' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'torre')->textInput(['maxlength' => true, 'tabIndex' => '1', 'disabled' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'piso')->textInput(['maxlength' => true, 'tabIndex' => '1', 'disabled' => true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'depto')->textInput(['maxlength' => true, 'tabIndex' => '1', 'disabled' => true]) ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($model, 'manzana')->textInput(['maxlength' => true, 'tabIndex' => '1', 'disabled' => true]) ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($model, 'parcela')->textInput(['maxlength' => true, 'tabIndex' => '1', 'disabled' => true]) ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($model, 'lote')->textInput(['maxlength' => true, 'tabIndex' => '1', 'disabled' => true]) ?>
    </div>
    <div class="col-md-1">
        <?= $form->field($model, 'pilar')->textInput(['maxlength' => true, 'tabIndex' => '1', 'disabled' => true]) ?>
    </div>
</div>
<?php if ($model->oficial == 0) { ?>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'mail')->textInput(['maxlength' => 255, 'tabIndex' => '1', 'disabled' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'telefono')->textInput(['maxlength' => 50, 'tabIndex' => '1', 'disabled' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form
                ->field($model, 'en_sede')
                ->dropdownList(
                    [
                        1 => 'Si',
                        0 => 'No',
                    ],
                    [
                        'prompt' => [
                            'text' => 'Seleccionar...',
                            'options' =>
                            [
                                'disabled' => true,
                                'selected' => true
                            ]
                        ],
                        'disabled' => true,
                    ]
                ) ?>
        </div>
    </div>
<?php } ?>

<?php
if (!$model->isNewRecord) {
    $this->registerJs(
        "$(document).ready(function() {
        habilitar_controles(true);
        const existeJefe = $('#EXISTE_JEFE').val();
        if (existeJefe) {
            $('#sds_ris_risneu-dni_beneficiario').prop('readonly', true);
        } else {
            $('#sds_ris_risneu-dni_beneficiario').prop('readonly', false);
        }
    });"
    );
}
if (isset($view)) {
    $this->registerJs(
        "$(document).ready(function() {
            habilitar_controles(false);
            $('#sds_ris_risneu-dni_beneficiario').prop('readonly', true);
            $('#btn_dni_benef_update').hide();
            $('#btnBarrio').hide();
            $('#btnCalle').hide();
            $('#btnCalleInt').hide();
            $('#btnEncuestador').hide();
        });"
    );
}

$this->registerJs(
    "
    $('#btn_dni_benef, #btn_dni_benef_update').click(function(){        
        buscar_persona();
    }); 

    $('#btn_dni_edit').click(function(){        
        habilitar_controles(false);
    }); 
    "
);
?>
<script>
    function cargarLocalidades() {
        $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#sds_ris_risneu-idprovincia").val(), function(data) {
            $("select#sds_ris_risneu-idlocalidad").html(data);
            $("select#barrio").html("");
            $("#sds_ris_risneu-cod_postal").val("");
            $("#sds_ris_risneu-idlocalidad").val(null).trigger('change');
        });
    }

    function cargarBarrioCodPostal() {
        $.post("index.php?r=sds_com_barrio/cmb_barrio&id=" + $("#sds_ris_risneu-idlocalidad").val(), function(data) {
            $("select#barrio").html(data);
            $("select#barrio").val(null).trigger('change');

        });
        $.post("index.php?r=sds_com_barrio/cod_postal&id=" + $("#sds_ris_risneu-idlocalidad").val(), function(data) {
            $("#sds_ris_risneu-cod_postal").val(data);
            $("#sds_ris_risneu-cod_postal").prop("disabled", true);
        });
    }

    async function buscar_persona() {
        let enviarFormulario = true;
        const isCreate = $("#IS_CREATE").val();
        const idrisneu = <?= $model->idrisneu != null ? $model->idrisneu : 0 ?>;
        let origen = <?= $origen != null ? "'" . urlencode($origen) . "'" : "''" ?>;
        const dniRisneu = <?= $model->dni_beneficiario != null ? $model->dni_beneficiario : 0 ?>;
        if (origen == '') {
            origen = null;
        }
        const dni = $("#sds_ris_risneu-dni_beneficiario").val();
        if (dni) {
            const response = await $.post(`index.php?r=sds_ris_risneu/validar_dni&idrisneu=${idrisneu}&dni=${dni}&origen=${origen}&isCreate=${isCreate}`);
            const dataParse = $.parseJSON(response);
            if (isCreate) {
                if (dataParse.isNew) {
                    $("#mensaje_risneu").text(dataParse.message);
                    habilitar_controles(true);
                } else {
                    let url = "index.php?r=sds_ris_risneu/create";
                    if (dataParse?.method == 'create' && dataParse?.dni != null) {
                        url = `index.php?r=sds_ris_risneu/create&dni=${dataParse.dni}`;
                    } else if (dataParse?.method == 'update' && dataParse?.id != null && dataParse?.dni != null) {
                        url = `index.php?r=sds_ris_risneu/update&id=${dataParse?.id}&dni=${dataParse?.dni}`;
                        if (dataParse?.origen != null && dataParse?.origen != 'null') {
                            url += `&origen=${dataParse.origen}`;
                        }
                        if (dataParse?.finalizar != null) {
                            const finalizar = dataParse.finalizar == false ? 0 : 1;
                            url += `&finalizar=${finalizar}`;
                        }
                    }
                    const base_url = "<?= Url::base(); ?>";
                    window.location = `${base_url}/${url}`;
                }
            } else {
                if (!dataParse.isNew && dni != dniRisneu) {
                    $("#ALERTA_YA_EXISTE_RISNEU").html(dataParse.message);
                    $("#ALERTA_YA_EXISTE_RISNEU").show();
                    $("#mensaje_risneu").text("");
                    $("#BOTON_GUARDAR_RISNEU").prop("disabled", true);
                    $("#BOTON_GUARDAR_Y_SALIR_RISNEU").hide();
                    enviarFormulario = false;
                } else {
                    $("#ALERTA_YA_EXISTE_RISNEU").hide();
                    $("#mensaje_risneu").text("Nuevo Responsable");
                    $("#BOTON_GUARDAR_RISNEU").prop("disabled", false);
                    $("#BOTON_GUARDAR_Y_SALIR_RISNEU").show();
                }
            }
        }
        return enviarFormulario;
    }

    async function validar_persona() {
        const enviarFormulario = await buscar_persona();
        if (enviarFormulario) {
            $('#FORM_CREAR_RISNEU').yiiActiveForm('submitForm');
        }
    }

    function habilitar_controles(habilitar) {
        $("#fecha").prop("disabled", !habilitar);
        $("#config_21").prop("disabled", !habilitar);
        $("#sds_ris_risneu-realizado_por").prop("disabled", !habilitar);
        $("#sds_ris_risneu-idprovincia").prop("disabled", !habilitar);
        $("#sds_ris_risneu-idlocalidad").prop("disabled", !habilitar);
        $("#barrio").prop("disabled", !habilitar);
        $("#sds_ris_risneu-area").prop("disabled", !habilitar);
        $("#calle").prop("disabled", !habilitar);
        $("#calle_int").prop("disabled", !habilitar);
        $("#sds_ris_risneu-calle_numero").prop("disabled", !habilitar);
        $("#sds_ris_risneu-casa").prop("disabled", !habilitar);
        $("#sds_ris_risneu-torre").prop("disabled", !habilitar);
        $("#sds_ris_risneu-piso").prop("disabled", !habilitar);
        $("#sds_ris_risneu-casa").prop("disabled", !habilitar);
        $("#sds_ris_risneu-depto").prop("disabled", !habilitar);
        $("#sds_ris_risneu-manzana").prop("disabled", !habilitar);
        $("#sds_ris_risneu-parcela").prop("disabled", !habilitar);
        $("#sds_ris_risneu-lote").prop("disabled", !habilitar);
        $("#sds_ris_risneu-pilar").prop("disabled", !habilitar);
        $("#sds_ris_risneu-mail").prop("disabled", !habilitar);
        $("#sds_ris_risneu-telefono").prop("disabled", !habilitar);
        $("#sds_ris_risneu-en_sede").prop("disabled", !habilitar);

        if (habilitar) {
            $("#btn_dni_edit").show();
            $("#btn_dni_benef").hide();
            $("#BOTON_GUARDAR_RISNEU").prop("disabled", false);
            $("#BOTON_GUARDAR_Y_SALIR_RISNEU").show();
        } else {
            $("#btn_dni_edit").hide();
            $("#btn_dni_benef").show();
            $("#BOTON_GUARDAR_RISNEU").prop("disabled", true);
            $("#BOTON_GUARDAR_Y_SALIR_RISNEU").hide();
        }
        $("#sds_ris_risneu-dni_beneficiario").prop("readonly", habilitar);
        // $("#sds_ris_risneu-cod_postal").prop("disabled", !habilitar);
    }
</script>