<?php

use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Mds_legales_oficio;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\Url;

$idRolSupervisor = Mds_legales_oficio::ID_ROL_SUPERVISOR;
?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }

    .primer-oficio {
        margin-top: 34px;
    }

    .col-personas-vinculadas {
        display: flex;
        justify-content: center;
        flex-direction: column;
    }

    .boton-personas-vinculadas {
        width: 40px;
    }

    @media screen and (max-width: 991px) {
        .primer-oficio {
            margin: 10px 0;
        }
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
                <div class="sds-ris-risneu-form">
                    <?php if ($model->idlegalescaratula && $model->isNewRecord) : ?>
                        <div class="alert alert-info" role="alert">
                            <p><b>Carátula seleccionada:</b> <?= $model->caratula ?></p>
                            <?php
                            $numeroExpediente = $model->numero_expediente ? "<b>Número expediente:</b> $model->numero_expediente" : '';
                            $guionCaso = $numeroExpediente ? ' - ' : '';
                            $caso = $model->caso ? "$guionCaso<b>Caso:</b> $model->caso" : '';
                            $guionAnioExpediente = $numeroExpediente || $caso ? ' - ' : '';
                            $anioExpediente = $model->anio_expediente ? "$guionAnioExpediente<b>Año:</b> $model->anio_expediente" : '';
                            if ($numeroExpediente || $anioExpediente || $caso) : ?>
                                <p><?= "$numeroExpediente $caso $anioExpediente" ?></p>
                            <?php endif; ?>
                            <?php if ($ultimoRequerimientoByCaratula) : ?>
                                <p>Se precargaron los datos del <b>requerimiento #<?= $ultimoRequerimientoByCaratula['idlegalesoficio'] ?></b> <a href="<?= Url::base() ?>/index.php?r=mds_legales_oficio%2Fview&idlegalesoficio=<?= $ultimoRequerimientoByCaratula['idlegalesoficio'] ?>" target="_blank" title="Ver" class="btn btn-link" style="padding: 0 5px 0 0;"><i class="fas fa-eye"></i></a></p>
                            <?php endif; ?>
                            <?php if (!empty($requerimientosCaratulaSeleccionada)) : ?>
                                <p><u>Otros requerimientos asociados a esta carátula:</u></p>
                                <ul>
                                    <?php foreach ($requerimientosCaratulaSeleccionada as $requerimiento) : ?>
                                        <li><a href="<?= Url::base() ?>/index.php?r=mds_legales_oficio%2Fview&idlegalesoficio=<?= $requerimiento['idlegalesoficio'] ?>" target="_blank" title="Ver" class="btn btn-link" style="padding: 0 5px 0 0;"><i class="fas fa-eye"></i></a><b>Requerimiento #<?= $requerimiento['idlegalesoficio'] ?></b></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <input type="hidden" id="archivo_oficio" name="Mds_legales_oficio[archivo_oficio]">
                        <input type="hidden" id="otros_adjuntos" name="Mds_legales_oficio[otros_adjuntos]">
                        <input type="hidden" id="adjuntos_eliminados" name="Mds_legales_oficio[adjuntos_eliminados]">
                        <input type="hidden" id="array_personas_vinculadas" name="Mds_legales_oficio[personas_vinculadas]">
                        <input type="hidden" id="idlegalescaratula" name="Mds_legales_oficio[idlegalescaratula]" value="<?= $model->idlegalescaratula ?>">
                        <div class="col-md-4">
                            <?= $form->field($model, 'idemisor')->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::getConfiguracionesSinOrden(Sds_com_configuracion_tipo::LEGALES_EMISOR_TIPO, true),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    'id' => 'emisor',
                                    'prompt' => [
                                        'text' => 'Seleccione opción...',
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                ]
                            )->label('Emisor órgano superior')
                            ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'lugar_libramiento')->textInput(['maxlength' => true])->label("Entidad requirente") ?>

                        </div>
                        <!-- <div class="col-md-4">
                             $form->field($model, 'fecha_libramiento')->textInput(['type' => 'date'])->label('Fecha libramiento') 
                        </div> -->

                        <div class="col-md-4">
                            <?= $form->field($model, 'donde_tramita')->textInput()->label("Localidad") ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, 'doctor_a_cargo')->textInput()->label("Responsable de la entidad requirente") ?>
                        </div>
                        <div class="col-md-4">
                            <?php
                            if ($model->fecha_recepcion != null) {
                                $model->fecha_recepcion = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_recepcion)));
                            }
                            echo $form->field($model, 'fecha_recepcion')->label("Fecha recepción")->widget(DatePicker::class, [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'fecha_recepcion',
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
                        <div class="col-md-4">
                            <?php
                            if ($model->fecha_oficio != null) {
                                $model->fecha_oficio = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_oficio)));
                            }
                            echo $form->field($model, 'fecha_oficio')->label("Fecha requerimiento")->widget(DatePicker::class, [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'fecha_oficio',
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
                        <!-- <div class="col-md-6">
                             // $form->field($model, 'remitente')->textInput(['maxlength' => true])->label("Remitente") 
                        </div> -->
                        <div class="col-md-6">
                            <?= $form->field($model, 'caratula')->textInput(['readonly' => $model->idlegalescaratula ? true : false])->label("Carátula") ?>
                        </div>

                        <div class="col-md-6 col-personas-vinculadas">
                            <label>Personas vinculadas</label>
                            <?php if ($model->isNewRecord) : ?>
                                <button id="botonPersonasVinculadas" type="button" class="btn btn-success boton-personas-vinculadas" data-toggle="modal" data-target="#modalPersonasVinculadas"><i class="glyphicon glyphicon-plus"></i></button>
                            <?php else :
                                if ($model->dni_legajo_vinculado) {
                                    echo $form->field($model, 'dni_legajo_vinculado')->textarea(['maxlength' => true, 'rows' => 3])->label(false);
                                }
                                echo $model->listapersonasvinculadas;
                            endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'tiempo_respuesta')->textInput(['type' => 'text'])->label("Plazo (días)") ?>
                        </div>

                        <div class="col-md-6">
                            <?php
                            if ($model->fecha_plazo != null) {
                                $model->fecha_plazo = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_plazo)));
                            }
                            echo $form->field($model, 'fecha_plazo')->label("Fecha vencimiento")->widget(DatePicker::class, [
                                'name' => 'check_issue_date',
                                'language' => 'es',
                                'readonly' => false,
                                'layout' => '{picker}{input}{remove}',
                                'options' => [
                                    'id' => 'fecha_plazo',
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
                        <div class="col-md-3 form-group field-mds_legales_oficio-numero_expediente">
                            <label class="control-label" for="mds_legales_oficio-numero_expediente">Número expediente</label>
                            <input type="number" id="mds_legales_oficio-numero_expediente" class="form-control" name="Mds_legales_oficio[numero_expediente]" value="<?= $model->caratulaModel ? $model->caratulaModel->numero_expediente : '' ?>">
                        </div>
                        <div class="col-md-3 form-group field-mds_legales_oficio-caso">
                            <label class="control-label" for="mds_legales_oficio-caso">Caso</label>
                            <input type="text" id="mds_legales_oficio-caso" class="form-control" name="Mds_legales_oficio[caso]" value="<?= $model->caratulaModel ? $model->caratulaModel->caso : '' ?>">
                        </div>
                        <div class="col-md-3 form-group field-mds_legales_oficio-anio_expediente">
                            <label class="control-label" for="mds_legales_oficio-anio_expediente">Año</label>
                            <input type="number" id="mds_legales_oficio-anio_expediente" class="form-control" name="Mds_legales_oficio[anio_expediente]" value="<?= $model->caratulaModel ? $model->caratulaModel->anio_expediente : '' ?>">
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'tramite_simple')->textInput()->label("Número trámite / cédula / oficio") ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <?= $form->field($model, 'motivo_solicitud')->textInput()->label("Motivo de solicitud") ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'providencia')->textInput()->label("Providencia") ?>
                        </div>
                        <div class="col-md-3 primer-oficio">
                            <?= $form->field($model, 'primer_oficio')->checkBox(['label' => 'Es primer requerimiento', 'data-size' => 'small', 'class' => 'bs_switch', 'id' => 'primer_oficio']) ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($model, 'tipo_oficio')->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::find('idconfiguracion', 'descripcion')->where(['=', 'idconfiguraciontipo', Sds_com_configuracion_tipo::LEGALES_OFICIO_TIPO])->andWhere(['=', 'activo', 1])->orderBy(['descripcion' => SORT_ASC])->all(),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    'id' => 'tipooficio', 'placeholder' => 'Seleccionar Tipo de requerimiento...',
                                    'prompt' => [
                                        'text' => 'Seleccione opción...',
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                ]
                            )->label('Tipo de requerimiento')
                            ?>
                        </div>
                        <div class="col-md-12">
                            <?= $form->field($model, 'idarea')->dropdownList(
                                ArrayHelper::map(
                                    Sds_com_configuracion::find('idconfiguracion', 'descripcion')->where(['=', 'idconfiguraciontipo', Sds_com_configuracion_tipo::LEGALES_AREA_TIPO])->andWhere(['=', 'activo', 1])->orderBy(['descripcion' => SORT_ASC])->all(),
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                [
                                    'id' => 'idarea',
                                    'placeholder' => 'Seleccionar el área a derivar...',
                                    'prompt' => [
                                        'text' => 'Seleccione opción...',
                                        'options' => ['disabled' => true, 'selected' => true]
                                    ],
                                    'onchange' => 'precargarSupervisores()',
                                ]
                            )->label('Derivación a:')
                            ?>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12 required">
                            <label>Supervisores/as (responsable de supervisar respuestas)</label>
                            <?php $usuariosSupervisores = ArrayHelper::map(Mds_legales_oficio::getUsuariosSegunRol($idRolSupervisor), 'idusuario', 'nombre_apellido'); ?>
                            <?=
                            Select2::widget([
                                'name' => 'supervisores',
                                'id' => 'supervisores',
                                'value' => ($action == 'update') ? ArrayHelper::map($model->getSupervisores(), 'idusuario', function ($model) {
                                    return $model->idusuario;
                                }) : '',
                                'data' => $usuariosSupervisores,
                                'options' => ['multiple' => true, 'required' => false],
                                'showToggleAll' => false,
                            ]);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" id="observaciones_container">
                            <?= $form->field($model, 'observaciones')->widget(\bizley\quill\Quill::class, [
                                // 'allowResize' => true,
                                'options' => [
                                    'style' => 'height: 125px;',
                                    'id' => 'observaciones_texto',
                                ],
                            ])->label("Observaciones") ?>
                        </div>
                    </div>


                    <!-- <div class="row">
                        <div class="col-md-6">
                            <?=
                            // Usage without a model
                            /*echo FileInput::widget([
                                'name' => 'adjunto',
                                'options' => ['accept' => 'image/*,.pdf, .odt, .ods, .doc, .docx, .xls, .xlsx'],
                                'language' => 'es',
                            ]);*/
                            $form->field($model, 'archivo_oficio')->fileInput()
                            ?>

                        </div>


                    </div>-->

                    <br>
                    <label><strong>Adjunto requerimiento (se permite adjuntar UN solo archivo)</strong></label>
                    <div>
                        <div class="dropzone needsclick dz-clickable" id="adjunto-documentacion" name="mainFileUploader">
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>
                        </div>
                    </div>
                    <label><strong>Otros documentos (adjuntar de a UN archivo a la vez)</strong></label>
                    <div>
                        <div class="dropzone needsclick dz-clickable" id="adjunto-otrosdocumentos" name="mainFileUploader">
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>
                        </div>
                    </div>
                    <div class="row"><br />
                        <div class="col-md-12">
                            <a class="btn btn-info" href="index.php?r=mds_legales_oficio/index">Volver </a>
                            <?= Html::submitButton("Guardar", ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    function precargarSupervisores() {
        const supervisoresByArea = JSON.parse('<?= $supervisoresByArea ?>');
        const idArea = $("#idarea").val();
        const supervisoresByAreaFiltrado = supervisoresByArea.filter(supervisor => supervisor.idarea == idArea);
        const arrayIdSupervisoresASeleccionar = supervisoresByAreaFiltrado.map(supervisor => supervisor.idusuario);
        $("#supervisores").val(arrayIdSupervisoresASeleccionar).trigger("change");
    }

    function precargarDatosUltimoRequerimiento() {
        const supervisoresUltimoRequerimiento = JSON.parse('<?= isset($ultimoRequerimientoByCaratula['supervisores']) ? $ultimoRequerimientoByCaratula['supervisores'] : json_encode(array()) ?>');
        const personasVinculadasUltimoRequerimiento = JSON.parse('<?= isset($ultimoRequerimientoByCaratula['personasVinculadas']) ? $ultimoRequerimientoByCaratula['personasVinculadas'] : json_encode(array()) ?>');
        if (supervisoresUltimoRequerimiento.length) {
            precargarSupervisoresByUltimoRequerimiento(supervisoresUltimoRequerimiento);
        }
        if (personasVinculadasUltimoRequerimiento.length) {
            personasVinculadasUltimoRequerimiento.forEach(personaVinculada => {
                agregarPersonaVinculada(personaVinculada);
            });
        }
    }

    function precargarSupervisoresByUltimoRequerimiento(supervisoresUltimoRequerimiento) {
        const arrayIdSupervisoresASeleccionar = supervisoresUltimoRequerimiento.map(supervisor => supervisor.idusuario);
        $("#supervisores").val(arrayIdSupervisoresASeleccionar).trigger("change");
    }
</script>

<?php
require 'modal_personas_vinculadas.php';

$this->registerJs(
    "
    $('.select2-search').css('z-index', '1')
    $(document).ready(function() {  
        // Deshabilitamos el comportamiento de la tecla enter para que no haga un submit del oficio
        $('#formOficio').keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        $('#btnSave').click(function(e){
            const supervisores =  $('#supervisores').val()
            if (!supervisores || !supervisores.length){
                alert('Debe seleccionar a los supervisores');
                e.preventDefault();
            }

        })

        $('.js-example-basic-multiple').select2();
        precargarDatosUltimoRequerimiento();
    });"
);
?>