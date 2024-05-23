<?php

use app\models\Sds_800_atencion;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion */
?>
<div class="sds-800-atencion-view">
    <header class="page-header">
        <h2>Llamada 0800 Atención</h2>

        <div class="right-wrapper pull-right">
            <ol class="breadcrumbs">
                <li>
                    <a href="/">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><span>Llamada 0800 Atención</span></li>
            </ol>

            <div class="sidebar-right-toggle"></div>
        </div>
    </header>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['disabled' => true]); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <h5><b>Fecha de Atención: </b>
                                <?php echo date_format(date_create($model->fecha_hora), 'd/m/Y H:i') ?></h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <h5><b>Atendió: </b>
                                <?php echo $model->idusuario0->nombre . ' ' . $model->idusuario0->apellido ?></h5>
                        </div>
                    </div>
                    <br>
                    <div class="panel-group" id="accordion_atencion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_atencion" href="#atencion">
                                        Persona en Situación de Calle
                                    </a>
                                </h4>
                            </div>
                            <div id="atencion" class="accordion-body collapse in">
                                <div class="panel-body" id="atencion_content">
                                    <?php if ($model->dni !== null) : ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <?= $form->field($model, 'dni')->textInput() ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= $form->field($model, 'nombre')->textInput() ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form->field($model, 'apellido')->textInput() ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?php
                                                if ($model->fecha_nacimiento != null) {
                                                    $model->fecha_nacimiento = date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_nacimiento)));
                                                }
                                                echo $form->field($model, 'fecha_nacimiento')->widget(DatePicker::class, [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    'layout' => '{picker}{input}{remove}',
                                                    'options' => [
                                                        'id' => 'fecha_nacimiento',
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
                                                ])->label('Fecha de Nacimiento'); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?= $form->field($model, 'nacionalidad')->dropdownList(
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_NACIONALIDAD, false),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),
                                                    [
                                                        'placeholder' => 'Seleccionar Nacionalidad ...',
                                                        "disabled" => "true"
                                                    ],
                                                );
                                                ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form->field($model, 'sexo')->dropdownList(
                                                    ArrayHelper::map(
                                                        Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_GENERO, false),
                                                        'idconfiguracion',
                                                        'descripcion'
                                                    ),
                                                    [
                                                        'placeholder' => 'Seleccionar Genero ...',
                                                        "disabled" => "true"
                                                    ],
                                                );
                                                ?>
                                            </div>
                                            <div class="col-md-4">
                                                <?= $form->field($model, 'localidad')->dropdownList(
                                                    ArrayHelper::map(
                                                        Sds_com_localidad::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                                        'idlocalidad',
                                                        'descripcion'
                                                    ),
                                                    ['placeholder' => 'Seleccionar Localidad ...', "disabled" => "true"],
                                                );
                                                ?>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <p><b>Atención:</b> Sin Documento</p>
                                        <?= $form->field($model, 'persona_datos')->textarea(['rows' => 2]) ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" id="accordion_detalle">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_detalle" href="#detalle">
                                        Detalle Situación
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle" class="accordion-body collapse in">
                                <div class="panel-body" id="detalle_content">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'edad')->textInput(['disabled' => true])->label('Edad que dice tener') ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'beneficio')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label">Motivo de situación de calle</label>
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?php echo $model->causa_situacion;  ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'sabe_leer')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'nivel_estudio')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::ESTUDIO_SIN_DATOS => 'Sin Datos',
                                                Sds_800_atencion::ESTUDIO_PRIMARIO_INCOMPLETO => 'Primario Incompleto',
                                                Sds_800_atencion::ESTUDIO_PRIMARIO_COMPLETO => 'Primario Completo',
                                                Sds_800_atencion::ESTUDIO_SECUNDARIO_INCOMPLETO => 'Secundario Incompleto',
                                                Sds_800_atencion::ESTUDIO_SECUNDARIO_COMPLETO => 'Secundario Completo',
                                                Sds_800_atencion::ESTUDIO_TERCIARIO_OTRO_INCOMPLETO => 'Terciario/Otro Incompleto',
                                                Sds_800_atencion::ESTUDIO_TERCIARIO_OTRO_COMPLETO => 'Terciario/Otro Completo'
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'trabajo')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::TRABAJO_NO => "No",
                                                Sds_800_atencion::TRABAJO_FORMAL => "Formal",
                                                Sds_800_atencion::TRABAJO_INFORMAL => "Informal"
                                            ]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'trabajo_detalle')->textarea(['rows' => 2, 'disabled' => true]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'antiguedad')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::ANTIGUEDAD_SIN_DATOS => 'Sin Datos',
                                                Sds_800_atencion::ANTIGUEDAD_MENOS_1 => 'menos de 1 años',
                                                Sds_800_atencion::ANTIGUEDAD_1_5 => 'entre 1 y 5 años',
                                                Sds_800_atencion::ANTIGUEDAD_MAS_5 => 'mas de 5 años'
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'ubicacion_anterior')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::UBICACION_SIN_DATOS => 'Sin Datos',
                                                Sds_800_atencion::UBICACION_FAMILIAR => 'En la casa de un familiar',
                                                Sds_800_atencion::UBICACION_CUENTA_PROPIA => 'Alquilaba por cuenta propia',
                                                Sds_800_atencion::UBICACION_ESTADO => 'Le alquilaba algún efector del estado',
                                                Sds_800_atencion::UBICACION_OTRO => 'Otro'
                                            ]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'ubicacion_anterior_detalle')->textarea(['rows' => 2, 'disabled' => true]) ?>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'atencion_anterior')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'atencion_anterior_institucion')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'atencion_anterior_profesional')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'asistencia_estado')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'asistencia_estado_detalle')->textarea(['rows' => 2, 'disabled' => true])->label('¿Cuál?') ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'familia')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::FAMILIA_SIN_DATOS => 'Sin Datos',
                                                Sds_800_atencion::FAMILIA_TIENE_VINCULO => 'Si tiene y con vínculos adecuados',
                                                Sds_800_atencion::FAMILIA_TIENE_SIN_VINCULO => 'Si tiene y sin vínculos adecuados',
                                                Sds_800_atencion::FAMILIA_NO_TIENE => 'No tiene'
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'sentimiento')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::SENTIMIENTO_SIN_DATOS => 'Sin Datos',
                                                Sds_800_atencion::SENTIMIENTO_BIEN => 'Bien',
                                                Sds_800_atencion::SENTIMIENTO_MAL => 'Mal',
                                                Sds_800_atencion::SENTIMIENTO_ELECCION => 'Es una eleccion de vida',
                                            ]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'orientado')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'evaluacion_funcional')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::FUNCIONAL_SIN_DATOS => 'Sin Datos',
                                                Sds_800_atencion::FUNCIONAL_DEPENDIENTE => 'Totalmente Dependiente',
                                                Sds_800_atencion::FUNCIONAL_CASI_DEPENDIENTE => 'Dependiente en Algunas o Varias Actividades',
                                                Sds_800_atencion::FUNCIONAL_INDEPENDIENTE => 'Independiente',
                                                Sds_800_atencion::FUNCIONAL_OTRO => 'Otro'
                                            ]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'evaluacion_funcional_detalle')->textarea(['rows' => 2, 'disabled' => true]) ?>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'intoxicado')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'alucinaciones')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'violentado')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'expresar')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                    </div>




                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'tratamiento')->dropDownList([
                                                null => "Seleccione una opción...",
                                                Sds_800_atencion::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                Sds_800_atencion::RESPUESTA_SI => "Si",
                                                Sds_800_atencion::RESPUESTA_NO => "No"
                                            ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'tratamiento_institucion')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'tratamiento_profesional')->textInput(['maxlength' => true, 'disabled' => true]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                        <label class="control-label">Observaciones</label>
                                            <div class="alert alert-detalle" role="alert">
                                                <p><?php echo $model->observaciones;  ?></p>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                    <?= $form->field($model, 'idusuario')->hiddenInput()->label(false) ?>

                    <?= $form->field($model, 'idllamada')->hiddenInput()->label(false) ?>

                    <?php ActiveForm::end(); ?>
                    <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>

                </div>
            </section>
        </div>
    </div>
</div>