<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use app\models\Sds_com_persona;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_reproam_registro */
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
                <div class="mds-acomp-asistencia-form">
                    <div class="row">
                        <div class="col-md-6">
                            <span id="txt_mensaje"></span>
                            <div class="input-group">
                                <?= $form
                                    ->field($model, 'idbeneficiario')
                                    ->hiddenInput() ?>
                                <input class="form-control" style="margin-top: -10px;" type="text" maxlength="10" id="txtDNI_search" value="<?php echo $model->beneficiario
                                                                                                                                                ? $model->beneficiario->apellido .
                                                                                                                                                ' ' .
                                                                                                                                                $model->beneficiario->nombre .
                                                                                                                                                ' (' .
                                                                                                                                                $model->beneficiario->documento .
                                                                                                                                                ')'
                                                                                                                                                : ''; ?>" name="txtDNI_search">
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
                                                'Buscar DNI'
                                            ),
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
                        <div class="col-md-6" id="divriesgo" style="<?= !($model->beneficiario && $model->beneficiario->idpersona) ? 'display:none' : '' ?>">
                            <?= $form
                                ->field($model, 'idriesgo')
                                ->widget(Select2::class, [
                                    'data' => $riesgos,
                                    'hideSearch' => true,
                                    'options' => [
                                        'placeholder' =>
                                        'Seleccione Riesgo...',
                                        'tabIndex' => '1',
                                        'id' => 'idriesgo',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]) ?>
                        </div>
                    </div>
                    <div id="campos" style="<?= !($model->beneficiario && $model->beneficiario->idpersona) ? 'display:none' : '' ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if ($model->isNewRecord) {
                                    $model->idlocalidad = $ID_LOCALIDAD_NEUQUEN_CAPITAL;
                                } ?>
                                <?= $form
                                    ->field($model, 'idlocalidad')
                                    ->widget(Select2::class, [
                                        'data' => $localidades,
                                        'options' => [
                                            'placeholder' =>
                                            'Seleccionar Localidad...',
                                            'tabIndex' => '1',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?php if ($model->isNewRecord) {
                                    $model->idlocalidad_ingreso = $ID_LOCALIDAD_NEUQUEN_CAPITAL;
                                } ?>
                                <?= $form
                                    ->field($model, 'idlocalidad_ingreso')
                                    ->widget(Select2::class, [
                                        'data' => $localidades,
                                        'options' => [
                                            'placeholder' =>
                                            'Seleccionar Localidad...',
                                            'tabIndex' => '1',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                        ],
                                    ]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6" id="divDatepickerInicio" name="divDatepickerInicio">
                                <?php
                                if ($model->periodo_desde != null) {
                                    $model->periodo_desde = date(
                                        'd/m/Y',
                                        strtotime(
                                            str_replace(
                                                '/',
                                                '-',
                                                $model->periodo_desde
                                            )
                                        )
                                    );
                                }
                                echo $form
                                    ->field($model, 'periodo_desde')
                                    ->widget(DatePicker::class, [
                                        'name' => 'check_issue_date',
                                        'language' => 'es',
                                        'readonly' => false,
                                        'layout' => '{picker}{input}{remove}',
                                        'options' => [
                                            'id' => 'fechaInicio',
                                            'class' => 'form-control input-md',
                                            'disabled' => false,
                                            'autocomplete' => 'off',
                                        ],
                                        'pluginOptions' => [
                                            'value' => null,
                                            'format' => 'dd/mm/yyyy',
                                            'todayHighlight' => true,
                                            'autoclose' => true,
                                        ],
                                    ]);
                                ?>
                            </div>
                            <div class="col-md-6" id="divDatepickerFin" name="divDatepickerFin">
                                <?php
                                if ($model->periodo_hasta != null) {
                                    $model->periodo_hasta = date(
                                        'd/m/Y',
                                        strtotime(
                                            str_replace(
                                                '/',
                                                '-',
                                                $model->periodo_hasta
                                            )
                                        )
                                    );
                                }
                                echo $form
                                    ->field($model, 'periodo_hasta')
                                    ->widget(DatePicker::class, [
                                        'name' => 'check_issue_date',
                                        'language' => 'es',
                                        'readonly' => false,
                                        'layout' => '{picker}{input}{remove}',
                                        'options' => [
                                            'id' => 'fechaFin',
                                            'class' => 'form-control input-md',
                                            'disabled' => false,
                                            'autocomplete' => 'off',
                                        ],
                                        'pluginOptions' => [
                                            'value' => null,
                                            'format' => 'dd/mm/yyyy',
                                            'todayHighlight' => true,
                                            'autoclose' => true,
                                        ],
                                    ]);
                                ?>
                                <small id="smallFin"></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form
                                    ->field($model, 'observaciones')
                                    ->textarea(['rows' => 8, 'cols' => 5]) ?>
                            </div>
                        </div>
                        <?php if ($hasRolGlobal || $hasRolAdminGeneral) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form
                                        ->field($model, 'deleted_at')
                                        ->dropdownList([
                                            1 => 'Si',
                                            0 => 'No',
                                        ]) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row"><br />
                        <div class="col-md-12">
                            <a class="btn btn-info" href="index.php?r=mds_acomp_asistencia/index">Volver </a>
                            <?= Html::submitButton('Guardar', [
                                'class' => 'btn btn-success',
                                'id' => 'btnSave',
                                'style' => !($model->beneficiario && $model->beneficiario->idpersona) ? 'display:none' : ''
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
        'nacionalidades' => $nacionalidades,
        'tiposDocumentos' => $tiposDocumentos,
        'username' => $username,
        'generos' => $generos,
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
    });
JS;

$this->registerJs($script);
?>

<?php $this->registerJs(
    "$(document).ready(function() {  
        
        // Deshabilitamos el comportamiento de la tecla enter para que no haga un submit del form
        $('#formAcompAsistencia').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });

        
        function validarFechas() {
            fechaDesde = $('#fechaInicio').val()
            fechaFin = $('#fechaFin').val()
            fechaInicioDias = fechaDesde.substr(0,2);
            fechaInicioMes = fechaDesde.substr(3,2);
            fechaInicioAño = fechaDesde.substr(6,4);
            fechaFinDias = fechaFin.substr(0,2);
            fechaFinMes = fechaFin.substr(3,2);
            fechaFinAño = fechaFin.substr(6,4);
            const fecha_desde = new Date(fechaInicioAño,fechaInicioMes,fechaInicioDias);
            const fecha_hasta = new Date(fechaFinAño,fechaFinMes,fechaFinDias);
        
            if(fechaDesde && fechaFin){
                if(fecha_desde < fecha_hasta) {
                    $('#btnSave').prop('disabled', false);
                    $('#divDatepickerFin').css('color', '#777');
                    $('#fechaFin').css('border-color', '#3c763d');
                    $('#smallFin').text('');
                } else {
                    $('#btnSave').prop('disabled', true);
                    $('#divDatepickerFin').css('color', 'red');
                    $('#fechaFin').css('border-color', 'red');
                    $('#smallFin').text('Fecha hasta debe ser mayor a Fecha desde.');
                }
            }
        }

        $('#divDatepickerFin').on('changeDate', function(e) {
            validarFechas();
        });

        $('#divDatepickerInicio').on('changeDate', function(e) {
            validarFechas();
        });
        
        $('#btnSave').click(function(e){
            const idbeneficiario = $('#mds_acomp_asistencia-idbeneficiario').val();
            if (!idbeneficiario){
                alert('Debe completar el beneficiario')
                e.preventDefault();
            }
        });
        $('input[name=\"mds_acomp_asistencia[idbeneficiario]\"]').on('input', function() {
            $('#txt_mensajeLocalidad').html('');
         });

        $('#btn_dni').click(function(){
            const numeroDNI = $('#txtDNI_search').val();
            if (numeroDNI){
                getPersonaByDNI(numeroDNI);
            } else {
                alert('Debe ingresar un DNI');
            }           
        });
      
        $('#txtDNI_search').on('input', function () { 
            this.value = this.value.replace(/[^0-9]/g,'');
            $('#txtDNI_search').css('border-color', '#ccc');
        });

    });"
); ?>
<script>
    function getPersonaByDNI(numeroDNI) {
        $("#div_duplicados").hide();
        $('#txt_mensaje').html("Buscando datos de la Persona...");
        $('#txt_mensajeLocalidad').html(".");
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
                    $("#idbeneficiario").val(record.idpersona);
                    $('input[name="Mds_acomp_asistencia[idbeneficiario]"]').val(record.idpersona)
                    $("#txt_mensaje").html("");
                    $("#txt_mensajeLocalidad").html("");
                    $('#divriesgo').show();
                    $('#campos').show();
                    $('#btnSave').show();
                } else {
                    $('#txt_mensaje').html(`No se encontró en el sistema una persona con DNI: ${numeroDNI}. Por favor, debe registrarla`);
                    $('#txt_mensaje').css('color', 'red');
                    $('#txtDNI_search').css('border-color', 'red');
                    $('input[name="Mds_acomp_asistencia[idbeneficiario]"]').val('')
                    $("#btnContacto").prop("disabled", false);
                    $('#txt_mensajeLocalidad').html(".");
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
</script>