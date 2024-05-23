<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Ver registro {$model->persona->apellido} {$model->persona->nombre} ({$model->persona->documento})";
?>

<style>
    .table>thead>tr>td.info,
    .table>tbody>tr>td.info,
    .table>tfoot>tr>td.info,
    .table>thead>tr>th.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>tbody>tr.info>td,
    .table>tfoot>tr.info>td,
    .table>thead>tr.info>th,
    .table>tbody>tr.info>th,
    .table>tfoot>tr.info>th {
        color: #777;
        background-color: #fafafa !important;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
    }

    .btnPrint {
        background: grey !important;
    }

    .btnPrint a {
        color: white !important;
        text-decoration: none !important;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 5px;
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
            <li><span>Ver registro</span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="panel-group" id="accordion_beneficiario">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_beneficiario" href="#beneficiario">
                                    <b>Persona</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="beneficiario" class="accordion-body collapse in">
                            <div class="panel-body" id="beneficiario_content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre y DNI</label>
                                        <?php $persona = "{$model->persona->apellido} {$model->persona->nombre} ({$model->persona->documento})" ?>
                                        <input type="text" class="form-control" value="<?= $persona ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha de atención</label>
                                        <input type="text" class="form-control" <?php if (($model->fecha_atencion) != null) { ?> value="<?php
                                                                                                                                        $fr = date_create($model->fecha_atencion);
                                                                                                                                        $fr = date_format($fr, 'd-m-Y');
                                                                                                                                        echo $fr ?>" <?php } ?> readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Obra social</label>
                                        <input type="text" class="form-control" value="<?= $model->obrasocial->descripcion ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Domicilio</label>
                                        <input type="text" class="form-control" value="<?= $model->domicilio ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Estado civil</label>
                                        <input type="text" class="form-control" value="<?= $model->estadocivil ? $model->estadocivil->descripcion : '' ?>" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" value="<?= $model->telefono ?>" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Vivienda</label>
                                        <input type="text" class="form-control" value="<?= $model->vivienda->descripcion ?>" readonly>
                                    </div>
                                    <?php if ($model->residencia) { ?>
                                        <div class="col-md-3">
                                            <label class="form-label">Nombre residencia</label>
                                            <input type="text" class="form-control" value="<?= $model->residencia ?>" readonly>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_biografia">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_biografia" href="#biografia">
                                    <b>Biografía</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="biografia" class="accordion-body collapse in">
                            <div class="panel-body" id="biografia_content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Lugar de nacimiento</label>
                                        <input type="text" class="form-control" value="<?= $model->lugar_nacimiento ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Escolaridad</label>
                                        <input type="text" class="form-control" value="<?= $model->escolaridad->descripcion ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Algunas vivencias que marcaron su vida</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->vivencias ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Tiempo libre</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->tiempo_libre ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_habitos">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_habitos" href="#habitos">
                                    <b>Hábitos</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="habitos" class="accordion-body collapse in">
                            <div class="panel-body" id="habitos_content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Fuma</label>
                                        <input type="text" class="form-control" value="<?= ($model->fuma == '') ? '' : ($model->fuma == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Sueño adecuado</label>
                                        <input type="text" class="form-control" value="<?= ($model->suenio_adecuado == '') ? '' : ($model->suenio_adecuado == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Ejercicio físico cotidiano</label>
                                        <input type="text" class="form-control" value="<?= ($model->ejercicio_fisico == '') ? '' : ($model->ejercicio_fisico == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_vacunas">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vacunas" href="#vacunas">
                                    <b>Vacunas</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="vacunas" class="accordion-body collapse in">
                            <div class="panel-body" id="vacunas_content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Obligatorias</label>
                                        <input type="text" class="form-control" value="<?= $model->vacunas_obligatorias == 1 ? 'Si' : 'No' ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Vacunas COVID-19</label>
                                        <input type="text" class="form-control" value="<?= $model->vacunascovid19->descripcion ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_continencia">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_continencia" href="#continencia">
                                    <b>Continencia esfínteres</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="continencia" class="accordion-body collapse in">
                            <div class="panel-body" id="continencia_content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Diuresis</label>
                                        <input type="text" class="form-control" value="<?= ($model->diuresis == '') ? '' : ($model->diuresis == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Catarsis</label>
                                        <input type="text" class="form-control" value="<?= ($model->catarsis == '') ? '' : ($model->catarsis == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_patologicos">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_patologicos" href="#patologicos">
                                    <b>Antecedentes personales patológicos relevantes</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="patologicos" class="accordion-body collapse in">
                            <div class="panel-body" id="patologicos_content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">HTA</label>
                                        <input type="text" class="form-control" value="<?= ($model->antecedentes_hta == '') ? '' : ($model->antecedentes_hta == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">ACV</label>
                                        <input type="text" class="form-control" value="<?= ($model->antecedentes_acv == '') ? '' : ($model->antecedentes_acv == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Enfermedades cardiovasculares (IAM, Trombosis, etc)</label>
                                        <input type="text" class="form-control" value="<?= ($model->antecedentes_cardiaca == '') ? '' : ($model->antecedentes_cardiaca == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Diabetes</label>
                                        <input type="text" class="form-control" value="<?= ($model->antecedentes_diabetes == '') ? '' : ($model->antecedentes_diabetes == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Cáncer</label>
                                        <input type="text" class="form-control" value="<?= ($model->antecedentes_cancer == '') ? '' : ($model->antecedentes_cancer == 1 ? 'Si' : 'No') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Otras</label>
                                        <input type="text" class="form-control" value="<?= $model->antecedentes_otras ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Caídas en los últimos 6 meses</label>
                                        <input type="text" class="form-control" value="<?= $model->caidas == 1 ? 'Si' : 'No' ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Medicación actual</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->medicacion_actual ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Laboratorios y estudios complementarios realizados el último año</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->estudios_complementarios ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_examenfisico">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_examenfisico" href="#examenfisico">
                                    <b>Examen físico</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="examenfisico" class="accordion-body collapse in">
                            <div class="panel-body" id="examenfisico_content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">TA</label>
                                        <input type="text" class="form-control" value="<?= $model->examen_fis_ta ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Sat O2</label>
                                        <input type="text" class="form-control" value="<?= $model->examen_fis_sato2 ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">FC lat/minuto</label>
                                        <input type="text" class="form-control" value="<?= $model->examen_fis_fc ?>" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Abdomen</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->examen_fis_abdomen ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Aparato respiratorio</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->examen_fis_aparato_respiratorio ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Miembros inferiores</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->examen_fis_miembros_inferiores ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Observaciones</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->examen_fis_observaciones ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_abvd">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_abvd" href="#abvd">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <b>Evaluación funcional ABVD : </b>Actividades Básicas de la Vida Diaria
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?= $model_evaluacion->abvd ?>" readonly>
                                        </div>
                                    </div>
                                </a>
                            </h4>
                        </div>
                        <div id="abvd" class="accordion-body collapse in">
                            <div class="panel-body" id="abvd_content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Lavado</label>
                                        <textarea class="form-control" rows="3" readonly><?= $model_evaluacion->abvdlavado->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Vestido</label>
                                        <textarea class="form-control" rows="3" readonly><?= $model_evaluacion->abvdvestido->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Uso del baño</label>
                                        <textarea class="form-control" rows="3" readonly><?= $model_evaluacion->abvdbanio->descripcion ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Movilización</label>
                                        <textarea class="form-control" rows="3" readonly><?= $model_evaluacion->abvdmovilizacion->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Continencia</label>
                                        <textarea class="form-control" rows="3" readonly><?= $model_evaluacion->abvdcontinencia->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Alimentación</label>
                                        <textarea class="form-control" rows="3" readonly><?= $model_evaluacion->abvdalimentacion->descripcion ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_aivd">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_aivd" href="#aivd">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <b>Evaluación funcional AIVD : </b>Actividades Instrumentales de la Vida Diaria
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?= $model_evaluacion->aivd ?>" readonly>
                                        </div>
                                    </div>
                                </a>
                            </h4>
                        </div>
                        <div id="aivd" class="accordion-body collapse in">
                            <div class="panel-body" id="aivd_content">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Capacidad para usar el teléfono</label>
                                        <textarea class="form-control" rows="4" readonly><?= $model_evaluacion->aivdcapacidadtelefono->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Compras</label>
                                        <textarea class="form-control" rows="4" readonly><?= $model_evaluacion->aivdcompras->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Preparación de la comida</label>
                                        <textarea class="form-control" rows="4" readonly><?= $model_evaluacion->aivdpreparacioncomida->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Cuidado de la casa</label>
                                        <textarea class="form-control" rows="4" readonly><?= $model_evaluacion->aivdcuidadocasa->descripcion ?></textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Lavado de ropa</label>
                                        <textarea class="form-control" rows="4" readonly><?= $model_evaluacion->aivdlavadoropa->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Uso de medios de transporte</label>
                                        <textarea class="form-control" rows="4" readonly><?= $model_evaluacion->aivdusotransporte->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Responsabilidad respecto a su medicación</label>
                                        <textarea class="form-control" rows="4" readonly><?= $model_evaluacion->aivdresponsabilidadmedicacion->descripcion ?></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Manejo de asuntos económicos</label>
                                        <textarea class="form-control" rows="4" readonly><?= $model_evaluacion->aivdasuntoseconomicos->descripcion ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_evaluacionsocial">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_evaluacionsocial" href="#evaluacionsocial">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <b>Evaluación social: </b>Escala de valoración socio familiar de Gijón
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" value="<?= $model_evaluacion->ev_social_total ?>" readonly>
                                        </div>
                                    </div>
                                </a>
                            </h4>
                        </div>
                        <div id="evaluacionsocial" class="accordion-body collapse in">
                            <div class="panel-body" id="evaluacionsocial_content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">a) Situación familiar</label>
                                        <input type="text" class="form-control" value="<?= $model_evaluacion->aivdsituacionfamiliar->descripcion ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">b) Relaciones sociales</label>
                                        <input type="text" class="form-control" value="<?= $model_evaluacion->aivdrelacionsocial->descripcion ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">c) Apoyos de la red social</label>
                                        <input type="text" class="form-control" value="<?= $model_evaluacion->aivdredsocial->descripcion ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_icope">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_icope" href="#icope">
                                    <b>INSTRUMENTO ICOPE DE DETECCIÓN DE LA OMS</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="icope" class="accordion-body collapse in">
                            <div class="panel-body" id="icope_content">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <table border="1px">
                                            <thead>
                                                <tr>
                                                    <th>Condiciones prioritarias asociadas con la disminución de la capacidad cognitiva</th>
                                                    <th>Pruebas</th>
                                                    <th>Evaluar a fondo todos los dominios que se seleccionen</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>DETERIORO COGNITIVO</td>
                                                    <td>1. Recordar tres palabras: flor, puerta, arroz (por ejemplo).
                                                        <br>2. Orientación en tiempo y espacio: ¿Cuál es la fecha completa de hoy?
                                                        ¿Dónde está usted ahora mismo (casa, consulta, etc.)?
                                                        <br>3. ¿Recuerda las tres palabras?
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <input type="checkbox" tabindex="1" name="icope_detcog_responde_incorrectamente" id="icope_detcog_responde_incorrectamente " <?= $model_evaluacion->icope_detcog_responde_incorrectamente == 1 ? 'checked' : '' ?> readonly="readonly" onclick="return false;" /> 2) Responde incorrectamente a las dos preguntas o no sabe.<br>
                                                            <input type="checkbox" tabindex="1" name="icope_detcog_no_responde" id="icope_detcog_no_responde " <?= $model_evaluacion->icope_detcog_no_responde == 1 ? 'checked' : '' ?> readonly="readonly" onclick="return false;" /> 3) No recuerda las tres palabras.
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>PÉRDIDA DE MOVILIDAD</td>
                                                    <td>Prueba de la silla: Debe levantarse de la silla cinco veces sin ayudarse con los brazos.
                                                        <br>¿Se levantó cinco veces de la silla en 14 segundos?
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <input type="checkbox" tabindex="1" name="icope_perdida_movilidad" id="icope_perdida_movilidad " <?= $model_evaluacion->icope_perdida_movilidad == 1 ? 'checked' : '' ?> readonly="readonly" onclick="return false;" />No
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>NUTRICIÓN DEFICIENTE</td>
                                                    <td>1. Pérdida de peso: ¿Ha perdido más de 3 kg involuntariamente en los últimos tres meses?
                                                        <br>2. Pérdida del apetito: ¿Ha perdido el apetito?
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <input type="checkbox" tabindex="1" name="icope_nut_def_perdida_peso" id="icope_nut_def_perdida_peso " <?= $model_evaluacion->icope_nut_def_perdida_peso == 1 ? 'checked' : '' ?> readonly="readonly" onclick="return false;" /> 1) Si <br>
                                                            <input type="checkbox" tabindex="1" name="icope_nut_def_perdida_peso" id="icope_nut_def_perdida_apetito " <?= $model_evaluacion->icope_nut_def_perdida_apetito == 1 ? 'checked' : '' ?> readonly="readonly" onclick="return false;" /> 2) Si
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>DISCAPACIDAD VISUAL</td>
                                                    <td>¿Tiene algún problema de la vista?<br>
                                                        ¿Le cuesta ver de lejos o leer? ¿Tiene alguna enfermedad ocular o toma
                                                        medicación (p. ej., diabetes, hipertensión)?
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <input type="checkbox" tabindex="1" name="icope_discapacidad_visual" id="icope_discapacidad_visual " <?= $model_evaluacion->icope_discapacidad_visual == 1 ? 'checked' : '' ?> readonly="readonly" onclick="return false;" />Si
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>PÉRDIDA AUDITIVA</td>
                                                    <td>Oye los susurros (prueba de susurros) <b>o bien</b>
                                                        <br>Audiometría ≤ 35 dB <b>o bien</b>
                                                        <br>Supera la prueba electrónica de dígitos sobre fondo de ruido.
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <input type="checkbox" tabindex="1" name="icope_perdida_auditiva" id="icope_perdida_auditiva " <?= $model_evaluacion->icope_perdida_auditiva == 1 ? 'checked' : '' ?> readonly="readonly" onclick="return false;" />Si
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>SÍNTOMAS DEPRESIVOS</td>
                                                    <td>En las últimas dos semanas, ¿ha tenido alguno de los siguientes problemas?<br>
                                                        1. ¿Sentimientos de tristeza, melancolía o desesperanza?
                                                        2. ¿Falta de interés o de placer al hacer las cosas?
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <input type="checkbox" tabindex="1" name="icope_sin_dep_sentimientos" id="icope_sin_dep_sentimientos " <?= $model_evaluacion->icope_sin_dep_sentimientos == 1 ? 'checked' : '' ?> readonly="readonly" onclick="return false;" /> 1) Si<br>
                                                            <input type="checkbox" tabindex="1" name="icope_sin_dep_interes" id="icope_sin_dep_interes " <?= $model_evaluacion->icope_sin_dep_interes == 1 ? 'checked' : '' ?> readonly="readonly" onclick="return false;" /> 2) Si
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_recomendaciones">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_recomendaciones" href="#recomendaciones">
                                    <b>Observaciones</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="recomendaciones" class="accordion-body collapse in">
                            <div class="panel-body" id="recomendaciones_content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Problemas actuales que impactan en su calidad de vida</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->problemas_actuales ?></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Recomendaciones</label>
                                        <textarea class="form-control" style="min-height: 30vh" readonly><?= $model->recomendaciones ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion_adjuntos">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_adjuntos" href="#adjuntos">
                                    <b>Documentación Adjunta</b>
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="adjuntos" class="accordion-body collapse in">
                            <div class="panel-body" id="adjuntos_content">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?php $adjuntos = $model->getAdjuntos(); ?>
                                        <?php if (count($adjuntos) > 0) { ?>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <ul style="list-style: none">
                                                        <?php foreach ($adjuntos as $adjunto) : ?>
                                                            <li><a><i class="fas fa-paperclip"></i> &nbsp<?= Html::a($adjunto->nombre, Url::base() . '/' . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    <br>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            Sin archivos cargados
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card-footer" id="botones">
                    <a class="btn btn-info" href="index.php?r=mds_gerontologia/index">Volver </a>
                    <button type="button" class="btn btnPrint">
                        <a href="<?= Url::to(['/mds_gerontologia/detalle_gerontologia', 'id' => $model->idgerontologia]) ?>" target="_blank">Exportar PDF</a>
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>