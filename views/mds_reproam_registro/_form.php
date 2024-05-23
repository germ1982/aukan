<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;

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
                <div class="mds-reproam-registro-form">
                    <div class="row">

                        <div class="col-md-6">

                            <?=
                            $form->field($model, 'inscripto')->dropdownList(

                                [
                                    1 => "Si",
                                    0 => "No",
                                ],

                                [
                                    'prompt' => [
                                        'text' => 'Seleccione opción...',
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                    'id' => 'inscripto',
                                    'Inscripto' => 'inscripto',
                                    'onChange' => 'changeInscripto()'
                                ],
                            );
                            ?>

                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'numero_legajo_reproam')->textInput(
                                ['id' => 'legajoIncripto']
                            ) ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'nombre')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <!-- LOCALIDAD BARRIO -->
                        <div class="col-md-6">
                            <?php
                            if ($model->isNewRecord) {
                                $model->idlocalidad = $ID_LOCALIDAD_NEUQUEN_CAPITAL;
                            }
                            ?>
                            <?= $form->field($model, 'idlocalidad')->widget(Select2::class, [
                                'data' => $localidades,
                                'options' => [
                                    'placeholder' => 'Seleccionar Localidad...',
                                    'tabIndex' => '1',
                                    'onchange' =>   'cargarLocalidad();',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                        </div>

                        <div class="col-md-6">
                            <?php Pjax::begin(['id' => 'pjax_barrio', 'timeout' => '30000']); ?>
                            <div class="input-group">
                                <?php if ($model->isNewRecord) : ?>
                                    <?=
                                    $form->field($model, 'idbarrio')->widget(Select2::class, [
                                        'data' => [],
                                        'options' => [
                                            'tabIndex' => '1',
                                            'id' => 'idbarrio',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                                    ?>
                                <?php else : ?>
                                    <input type="hidden" id="idBarrioSelected" name="idBarrioSelected" value="<?php echo $model->idbarrio ?>">
                                    <?=
                                    $form->field($model, 'idbarrio')->widget(Select2::class, [
                                        'data' => $barrios,
                                        'options' => [
                                            'tabIndex' => '1',
                                            'id' => 'idbarrio',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);
                                    ?>
                                <?php endif; ?>
                                <span class="input-group-btn">
                                    <?= botonAltaBarrio(); ?>
                                </span>
                            </div>
                            <?php Pjax::end(); ?>
                        </div>
                        <!-- ///FIN LOCALIDAD BARRIO -->

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'idzona')->widget(Select2::class, [
                                'data' => $listaZonas,
                                'options' => [
                                    'tabIndex' => '1',
                                    'id' => 'idzona',
                                    'placeholder' => 'Seleccione Zona...'
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'direccion')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'situacion_habitacional')->dropdownList(
                                $listaSituacionHabitacional,
                                [
                                    'prompt' => [
                                        'text' => 'Seleccione opción...',
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                ],
                            );
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'telefono')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'telefono_movil')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'nombre_presidente')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'nombre_vicepresidente')->textInput() ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'nombre_secretario')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php if ($model->isNewRecord) $model->personeria_juridica = 'Si'; ?>
                            <?=
                            $form->field($model, 'personeria_juridica')->dropdownList(
                                [
                                    1 => "Si",
                                    0 => "No",
                                ],
                                [
                                    'Personeria Juridica' => 'personeria_juridica',
                                    'prompt' => [
                                        'text' => 'Seleccione opción...',
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                    'id' => 'personeriaJuridica',
                                    'onChange' => 'changePersoneria()',
                                ],
                            );
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'personeria_juridica_resolucion')->textInput(
                                ['id' => 'personeriaResolucion']
                            ) ?>
                        </div>
                        <div class="col-md-6">
                            <?php
                            if ($model->personeria_juridica_fecha_vencimiento != null) {
                                $model->personeria_juridica_fecha_vencimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->personeria_juridica_fecha_vencimiento)));
                            }
                            echo $form->field($model, 'personeria_juridica_fecha_vencimiento')->widget(DatePicker::class, [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'personeriaFechaVen',
                                    'class' => 'form-control input-md',
                                    'disabled' => false,
                                    'autocomplete' => 'off'
                                ],
                                'pluginOptions' => [
                                    'value' => null,
                                    'format' => 'dd/mm/yyyy',
                                    'todayHighlight' => true,
                                    'autoclose' => true,
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'entrega_constancia_inscripcion')->dropdownList(

                                [
                                    1 => "Si",
                                    0 => "No",
                                ],

                                [
                                    'prompt' => [
                                        'text' => 'Seleccione opción...',
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                    'id' => 'constancia',
                                    'Constancia' => 'constancia',
                                    'onChange' => 'changeConstancia()'
                                ],
                            );
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'entrega_constancia_inscripcion_nombre')->textInput(
                                ['id' => 'constanciaNombre']
                            ) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'observaciones')->textarea(array('rows' => 8, 'cols' => 5)); ?>
                        </div>
                    </div>
                    <?php if ($puedeEliminar) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?=
                                $form->field($model, 'deleted_at')->dropdownList(
                                    [
                                        1 => "Si",
                                        0 => "No",
                                    ],

                                )
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label><strong>Otros documentos (adjuntar de a UN archivo a la vez)</strong></label>
                            <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                            <input type="hidden" id="adjuntos_eliminados" name="Mds_legales_oficio[adjuntos_eliminados]">
                            <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                                <div class="fallback">
                                    <input name="file" type="file" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"><br />
                        <div class="col-md-12">
                            <a class="btn btn-info" href="index.php?r=mds_reproam_registro/index">Volver </a>
                            <?= Html::submitButton("Guardar", ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>


<?php

function botonAltaBarrio()
{
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'class' => 'btn btn-success btn-flat', 'disabled' => 'disabled',
        'id' => 'btnBarrio', 'style' => 'margin-top:27px;',
        'tabIndex' => '-1',
        'onclick' => '
            const idlocalidad= $("#mds_reproam_registro-idlocalidad").val();
            const localidad= $("#mds_reproam_registro-idlocalidad option:selected").text();
            $("#modal_abm").modal("show")
            .find("#content_abm")
            .load("index.php?r=mds_reproam_registro/create_barrio&idlocalidad="+idlocalidad);
            $("#header_abm").html("Nuevo Barrio de "+localidad);'
    ]);
}

?>

<script>
    function cargarLocalidad() {
        const idBarrioSelected = $("#idBarrioSelected").val();

        $.post("index.php?r=sds_com_barrio/cmb_barrio&id=" + $("#mds_reproam_registro-idlocalidad").val(), function(data) {
            let data1 = "<option value=null selected='true' disabled='disabled'>Seleccione opción...</option>" + data;
            $("#idbarrio").html(data1);
            $("#idbarrio").prop("disabled", false);
            $('#btnBarrio').prop("disabled", false);
            if (idBarrioSelected) {
                $("#idbarrio option[value='" + idBarrioSelected + "']").attr("selected", true);
            }
        });
    }

    function changePersoneria() {
        if ($('#personeriaJuridica option:selected').val() === '1') {
            $('#personeriaResolucion').prop("disabled", false);
            $('#personeriaFechaVen').prop("disabled", false);

        } else {
            $('#personeriaResolucion').prop("disabled", true);
            $('#personeriaFechaVen').prop("disabled", true);
            $('#personeriaResolucion').val('');
            $('#personeriaFechaVen').val('');
        }
    }


    function changeInscripto() {
        if ($('#inscripto option:selected').val() === '1') {
            $('#legajoIncripto').prop("disabled", false);
        } else {
            $('#legajoIncripto').prop("disabled", true);
            $('#legajoIncripto').val('');


        }
    }

    function changeConstancia() {
        if ($('#constancia option:selected').val() === '1') {
            $('#constanciaNombre').prop("disabled", false);
        } else {
            $('#constanciaNombre').prop("disabled", true);
            $('#constanciaNombre').val('');
        }
    }
</script>

<?php
Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    'id' => 'modal_abm',
    'size' => 'modal-md',
]);

echo "<div id='content_abm'></div>";

Modal::end();
?>

<?php
$this->registerJs(
    "$(document).ready(function() {  
        cargarLocalidad();
        changePersoneria();
        changeInscripto();
        changeConstancia();
    });"
);
?>