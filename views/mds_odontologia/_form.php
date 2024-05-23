<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use app\models\Sds_com_persona;
use app\models\Mds_odontologia;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_odontologia */
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
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-odontologia-form">
                    <div class="row">
                        <div class="col-md-6">
                            <span id="txt_mensaje"></span>
                            <div class="input-group">
                                <?= Html::input(
                                    'hidden',
                                    'hidden_tipo_hc',
                                    Mds_odontologia::TIPO_INTERVENCION_HC,
                                    $options = ['id' => 'hidden_tipo_hc']
                                ) ?>
                                <?= Html::input(
                                    'hidden',
                                    'hidden_tipo_visita',
                                    Mds_odontologia::TIPO_INTERVENCION_VISITA,
                                    $options = ['id' => 'hidden_tipo_visita']
                                ) ?>
                                <?= $form
                                    ->field($model, 'idpersona')
                                    ->hiddenInput()
                                    ->label('<b>Persona</b>') ?>
                                <input class="form-control" style="margin-top: -10px;" type="text" maxlength="10" id="txtDNI_search" value="<?= $model->persona
                                                                                                                                                ? "{$model->persona->apellido} {$model->persona->nombre} ({$model->persona->documento})"
                                                                                                                                                : '' ?>" name="txtDNI_search" placeholder="ingrese número de DNI" <?= $model->persona
                                                                                                                                                                                                                        ? 'disabled'
                                                                                                                                                                                                                        : '' ?>>
                                <span class="input-group-btn">
                                    <?php echo Html::button(
                                        '<i class="glyphicon glyphicon-search"></i>',
                                        [
                                            'class' =>
                                            'btn btn-primary btn-flat',
                                            'name' => 'btn_dni',
                                            'id' => 'btn_dni',
                                            'style' => 'margin-top:26px',
                                            'title' => Yii::t(
                                                'app',
                                                'Buscar persona por DNI'
                                            ),
                                            'disabled' => $model->persona
                                                ? true
                                                : false,
                                        ]
                                    ); ?>
                                    <?php echo Html::button(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        [
                                            'value' => Url::to([
                                                'mds_cap_persona/create',
                                            ]),
                                            'class' =>
                                            'btn btn-success btn-flat showModalButton',
                                            'style' => 'margin-top:26px',
                                            'id' => 'btnContacto',
                                            'disabled' => 'disabled',
                                            'onclick' =>
                                            '$("#abm_persona").show();',
                                            'title' => Yii::t(
                                                'app',
                                                'Crear Nueva Persona'
                                            ),
                                        ]
                                    ); ?>
                                </span>
                            </div>
                        </div>
                        <div id="form-tipointervencion" class="col-md-3" style="display: none;">
                            <?= $form
                                ->field($model, 'idtipointervencion')
                                ->dropdownList(
                                    $tiposIntervenciones,
                                    [
                                        'prompt' => [
                                            'text' => 'Seleccione',
                                            'options' => [
                                                'selected' => true,
                                            ],
                                        ],
                                        'disabled' => $model->idtipointervencion
                                            ? true
                                            : false,
                                        'id' => 'tipoIntervencion',
                                        'onChange' => 'changeTipoIntervencion()'
                                    ]
                                )
                                ->label('<b>Tipo de intervención</b>') ?>
                        </div>
                    </div>
                    <hr>
                    <div id="form-campos" style="<?= !($model->persona && $model->persona->idpersona) ? 'display:none' : '' ?>">
                        <div class="row">
                            <div class="col-md-4">
                                <?php
                                if ($model->fecha_atencion != null) {
                                    $model->fecha_atencion = date(
                                        'd/m/Y',
                                        strtotime(
                                            str_replace(
                                                '/',
                                                '-',
                                                $model->fecha_atencion
                                            )
                                        )
                                    );
                                }
                                echo $form
                                    ->field($model, 'fecha_atencion')
                                    ->widget(DatePicker::class, [
                                        'name' => 'check_issue_date',
                                        'language' => 'es',
                                        'readonly' => false,
                                        'layout' => '{picker}{input}{remove}',
                                        'options' => [
                                            'id' => 'fecha_atencion',
                                            'class' => 'form-control input-md',
                                            'disabled' => false,
                                        ],
                                        'pluginOptions' => [
                                            'value' => null,
                                            'format' => 'dd/mm/yyyy',
                                            'endDate' => date('d/m/Y'),
                                            'todayHighlight' => true,
                                            'autoclose' => true,
                                        ],
                                    ])
                                    ->label('<b>Fecha de atención</b>');
                                ?>

                            </div>
                            <div class="col-md-4" id="form-dispositivo">
                                <?php
                                $items = [];
                                foreach (Sds_com_configuracion::getConfiguracionesActivas(
                                    Sds_com_configuracion_tipo::TIPO_DISPOSITIVO_ODONTOLOGIA
                                )
                                    as $dispositiv) {
                                    $items[$dispositiv->idconfiguracion] = mb_strtoupper($dispositiv->descripcion);
                                }
                                echo $form
                                    ->field($model, 'iddispositivo')
                                    ->dropDownList($items, [
                                        'prompt' => [
                                            'id' => 'disp' . Sds_com_configuracion_tipo::TIPO_DISPOSITIVO_ODONTOLOGIA,
                                            'text' => 'Seleccione opción...',
                                            'options' => [
                                                'disabled' => true,
                                                'selected' => true,
                                            ],
                                        ],
                                    ])
                                    ->label('<b>Institución/Dispositivo</b>');
                                ?>
                            </div>
                            <div class="col-md-4" id="form-escolaridad">
                                <?= $form
                                    ->field($model, 'idescolaridad')
                                    ->dropDownList(
                                        $tiposEscolaridad,
                                        [
                                            'prompt' => [

                                                'id' => 'esc' . Sds_com_configuracion_tipo::TIPO_ULTIMO_ANIO_APROBADO,
                                                'text' => 'Seleccione opción...',
                                                'options' => [
                                                    'disabled' => true,
                                                    'selected' => true,
                                                ],
                                            ]
                                        ]
                                    )
                                    ->label('<b>Escolaridad</b>') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form
                                    ->field($model, 'telefono')
                                    ->textInput(
                                        ['maxlength' => true],
                                        ['type' => 'number']
                                    )
                                    ->label('<b>Teléfono</b>') ?>
                            </div>
                            <div class="col-md-4 required">
                                <?= $form
                                    ->field($model, 'vacunas_obligatorias')
                                    ->dropdownList(
                                        [
                                            1 => 'Si',
                                            0 => 'No',
                                            2 => 'Se desconoce',
                                        ],
                                        [
                                            'prompt' => [
                                                'text' => 'Seleccione opción...',
                                                'options' => [
                                                    'disabled' => true,
                                                    'selected' => true,
                                                ],
                                            ]
                                        ]
                                    )
                                    ->label('<b>Vacunas obligatorias</b>') ?>
                            </div>
                            <div class="col-md-4 required">
                                <?= $form
                                    ->field($model, 'vacuna_covid19')
                                    ->dropDownList(
                                        $tiposVacunasCovid,
                                        [
                                            'prompt' => [
                                                'text' => 'Seleccione opción...',
                                                'id' => 'vaccovid19' . Sds_com_configuracion_tipo::VACUNA_COVID19,
                                                'options' => [
                                                    'disabled' => true,
                                                    'selected' => true,
                                                ],
                                            ]
                                        ]
                                    )
                                    ->label('<b>Vacunas COVID19</b>') ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3 required" id="form-tipovisita">
                                <?= $form
                                    ->field($model, 'idtipovisita')
                                    ->dropDownList(
                                        $tiposVisitas,
                                        [
                                            'prompt' => [

                                                'text' => 'Seleccione opción...',
                                                'options' => [
                                                    'disabled' => true,
                                                    'selected' => true,
                                                ],
                                                'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_VISITA_ODONTOLOGIA,
                                            ]
                                        ]
                                    )
                                    ->label('<b>Tipo de visita</b>') ?>
                            </div>
                        </div>
                        <div class="row">

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <b>Dientes permanentes</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?= $form
                                    ->field($model, 'cant_dientes')
                                    ->textInput(
                                        ['maxlength' => true],
                                        ['type' => 'number']
                                    )
                                    ->label('Cantidad de dientes') ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form
                                    ->field($model, 'cant_caries')
                                    ->textInput(
                                        ['maxlength' => true],
                                        ['type' => 'number']
                                    )
                                    ->label('Cantidad de caries') ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form
                                    ->field($model, 'cant_obturados')
                                    ->textInput(
                                        ['maxlength' => true],
                                        ['type' => 'number']
                                    )
                                    ->label('Cantidad de obturados') ?><br>
                            </div>
                            <div class="col-md-3">
                                <?= $form
                                    ->field($model, 'cant_perdidos')
                                    ->textInput(
                                        ['maxlength' => true],
                                        ['type' => 'number']
                                    )
                                    ->label('Cantidad de perdidos') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <b>Dientes temporales</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?= $form
                                    ->field($model, 'cant_dientes_temporales')
                                    ->textInput(
                                        ['maxlength' => true],
                                        ['type' => 'number']
                                    )
                                    ->label('Cantidad de dientes') ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form
                                    ->field($model, 'cant_caries_temporales')
                                    ->textInput(
                                        ['maxlength' => true],
                                        ['type' => 'number']
                                    )
                                    ->label('Cantidad de caries') ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form
                                    ->field($model, 'cant_obturados_temporales')
                                    ->textInput(
                                        ['maxlength' => true],
                                        ['type' => 'number']
                                    )
                                    ->label('Cantidad de obturados') ?><br>
                            </div>
                            <div class="col-md-3">
                                <?= $form
                                    ->field($model, 'cant_perdidos_temporales')
                                    ->textInput(
                                        ['maxlength' => true],
                                        ['type' => 'number']
                                    )
                                    ->label('Cantidad de perdidos') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="form-enfermedad_periodontal">
                                <?= $form
                                    ->field($model, 'enfermedad_periodontal')
                                    ->widget(\bizley\quill\Quill::class, [
                                        'allowResize' => true,
                                        'options' => [
                                            'style' => 'height: 125px;',
                                            'id' => 'enfermedad_periodontal_id',
                                        ],
                                    ])
                                    ->label('<b>Enfermedad periodontal</b>') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" id="form-enfermedad_base">
                                <?= $form
                                    ->field($model, 'enfermedad_base')
                                    ->widget(\bizley\quill\Quill::class, [
                                        'allowResize' => true,
                                        'options' => [
                                            'style' => 'height: 125px;',
                                            'id' => 'enfermedad_base_id',
                                        ],
                                    ])
                                    ->label('<b>Enfermedad de base</b>') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form
                                    ->field($model, 'observaciones')
                                    ->widget(\bizley\quill\Quill::class, [
                                        'allowResize' => true,
                                        'options' => [
                                            'style' => 'height: 125px;',
                                            'id' => 'observaciones_id',
                                        ],
                                    ])
                                    ->label('<b>Observaciones</b>') ?>
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <label><strong>Documentación adjunta</strong></label>
                                <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                                <input type="hidden" id="adjuntos_eliminados" name="Mds_legales_oficio[adjuntos_eliminados]">
                                <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                                    <div class="fallback">
                                        <input name="file" type="file" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-info" href="index.php?r=mds_odontologia/index">Volver </a>
                            <?= Html::submitButton('Guardar', [
                                'class' => 'btn btn-success',
                                'id' => 'btnSave',
                                'style' => !($model->persona && $model->persona->idpersona) ? 'display:none' : ''
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php Modal::begin([
    'header' => '<h4>Agregar persona</h4>',
    'id' => 'modal',
    'size' => 'modal-lg',
]); ?>
<div class="panel-body">
    <?php
    $model_persona = new Sds_com_persona();
    echo $this->render('./_form_new_persona', [
        'model' => $model_persona,
        'botones' => true,
        'nacionalidades' => $tiposNacionalidades,
        'tiposDocumentos' => $tiposDocumentos,
        'username' => $username,
        'generos' => $tiposGeneros,
        'token' => $token
    ]);
    ?>
</div>
<?php Modal::end(); ?>

<?php
$script = <<<JS
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
        if($("#tipoIntervencion").val()){
            $("#form-tipointervencion").show();
        } else {
            $("#form-tipointervencion").hide();
        }
    });
JS;

$this->registerJs($script);
?>


<?php $this->registerJs(
    "$(document).ready(function() {  
        
        // Deshabilitamos el comportamiento de la tecla enter para que no haga un submit del form
        $('#formOdontologia').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        $('#btnSave').click(function(e){
            const idpersona = $('#mds_odontologia-idpersona').val();
            if (!idpersona){
                alert('Debe completar el dato de la persona')
                e.preventDefault();
            }
        });

        $('#btn_dni').click(function(){
            const numeroDNI = $('#txtDNI_search').val();
            if (numeroDNI){
                getPersonaByDNI(numeroDNI);
            } else {
                $('#form-tipointervencion').hide();
                alert('Debe ingresar un DNI');
            }           
        });
      
        $('#txtDNI_search').on('input', function () { 
            this.value = this.value.replace(/[^0-9]/g,'');
            $('#txtDNI_search').css('border-color', '#ccc');
        });
        changeTipoIntervencion();

    });"
); ?>
<script>
    function getRenaper(dni) {
        $.post("index.php?r=sds_com_persona/get_xroad_ren&dni=" + dni, function(data) {
            if (data.status == "error") {
                $("#txt_mensaje").html("<b>Error!</b><i> " + (data.message != null ? data.message : "No se pudo conectar con el servicio.") + "</i>");
                limpiarDatos();
            } else {
                var nombre = "";
                var apellido = "";
                var domicilio = "";
                var localidad = "";
                var fecha_nacimiento = "";
                //var sexo = "";
                var nacionalidad = "";
                $.each(data, function(ind, elem) {
                    //nacionalidad = JSON.stringify(data);//esto lo use para ver todo lo que traia de renaper
                    //$("#detalle").val(nacionalidad);//lo plante aca porque era un texto largo
                    if (ind == 'records') {
                        nombre = elem[0].nombres;
                        apellido = elem[0].apellido;
                        fecha_nacimiento = elem[0].fecha_nacimiento;
                        //foto = elem[0].foto;
                    }
                });
                $("#hidden_nueva_persona").val('0');
                nombre = corregir_palabra(nombre);
                $("#nombre_persona").val(nombre);
                apellido = corregir_palabra(apellido);
                $("#apellido_persona").val(apellido);
                $("#fecha_nacimiento").val(fecha_nacimiento);
                $("#nacionalidad_persona").val('');
                $("#sexo_persona").val('');
                /* $("#renaper_foto").attr("src", foto); */
                habilitar_controles();
                $('#txt_mensaje').html("");
                $("#btnContacto").prop("disabled", true);
            }
        });
    }

    function getPersonaByDNI(numeroDNI) {
        $("#div_duplicados").hide();
        $('#txt_mensaje').html("Buscando datos de la Persona...");
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
                    $("#txtDNI_search").val(`${record.apellido} ${record.nombre} (${record.documento})`)
                    $("#idpersona").val(record.idpersona);
                    $('input[name="Mds_odontologia[idpersona]"]').val(record.idpersona)
                    $("#txt_mensaje").html("");
                    $("#form-tipointervencion").show();
                } else {
                    $('#txt_mensaje').html(`No se encontró en el sistema una persona con DNI: ${numeroDNI}. Por favor, debe registrarla`);
                    $("#idpersona").val(null);
                    $('input[name="Mds_odontologia[idpersona]"]').val(null)
                    $("#form-tipointervencion").hide();
                    $('#txt_mensaje').css('color', 'red');
                    $('#txtDNI_search').css('border-color', 'red');
                    $('input[name="Mds_odontologia[idpersona]"]').val('')
                    $("#btnContacto").prop("disabled", false);
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
            },
        });
    }

    function changeTipoIntervencion() {

        if ($('#tipoIntervencion option:selected').val() == $('#hidden_tipo_hc').val()) {
            $('#form-campos').show();
            $('#form-tipovisita').hide();
            $('#form-dispositivo').show();
            $('#form-escolaridad').show();
            $('#form-enfermedad_periodontal').show();
            $('#form-enfermedad_base').show();
            $('#btnSave').show();
        } else if ($('#tipoIntervencion option:selected').val() == $('#hidden_tipo_visita').val()) {
            $('#form-campos').show();
            $('#form-tipovisita').show();
            //$('#form-dispositivo').hide();
            //$('#form-escolaridad').hide();
            $('#form-enfermedad_periodontal').hide();
            $('#form-enfermedad_base').hide();
            $('#btnSave').show();
        } else {
            $('#form-campos').hide();
            $('#btnSave').hide();
        }
    }
</script>