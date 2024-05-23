<?php

use app\models\Sds_800_am_recreacion;
use app\models\Sds_800_atencion;
use app\models\Sds_800_atencion_am;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion_am */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Atención / Intervención Adultos Mayores';
$this->params['breadcrumbs'][] = $this->title;

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
                <a href="/">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="sds-800-atencion-am-form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="p-0 col-md-6">
                            <h5><b>Fecha de Atención: </b>
                                <?php echo date_format(date_create($model->fecha_hora), 'd/m/Y H:i') ?></h5>
                        </div>
                    </div>
                    <?php //Trampita para que anden los accordion del template con yii ;)
                    echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_persona">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_persona" href="#persona">
                                        Datos de la Persona
                                    </a>
                                </h4>
                            </div>
                            <div id="persona" class="accordion-body collapse in">
                                <div class="panel-body" id="persona_content">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <!--ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el seleccionar dni-->
                                            <?= $form->field($model, 'dni')->textInput(["id" => "txtDNI", "disabled" => !$model->isNewRecord]); ?>
                                        </div>
                                        <div class="col-md-2" style="padding-top:25px;">
                                            <?php
                                            echo Html::a('<i class="glyphicon glyphicon-search"></i>', null, [
                                                'name' => 'btn_dni',
                                                'id' => 'btn_dni',
                                                'data-request-method' => 'post',
                                                'data-toggle' => 'tooltip',
                                                'class' => 'btn btn-primary',
                                                'title' => Yii::t('app', 'Consultar DNI'),
                                                //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el botón de buscar dni
                                                "disabled" => !$model->isNewRecord,
                                                'onclick' => '$.post("index.php?r=sds_800_atencion_am/validar_dni&dni=' .
                                                    '"+$("#txtDNI").val()+"&idllamada=' . $model->idllamada . '", function(data){                                                                                                              
                                                        var data = $.parseJSON(data);
                                                        $("#idcomponentequemuestramensaje").val(data.mensaje);
                                                        $("#sds_800_atencion_am-apellido").val(data[1].apellido);
                                                        $("#sds_800_atencion_am-nombre").val(data[1].nombre);
                                                        $("#sds_800_atencion_am-idpersona").val(data[1].idpersona);  
                                                        if(data.length > 0) {
                                                            $("#btn_risneu").show();
                                                        } else {
                                                            $("#btn_risneu").hide();
                                                            $("#btn_risneu").prop("href", "");
                                                        }        
                                                    });'
                                            ]) .
                                                Html::a('<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">', null, [
                                                    'name' => 'btn_pui',
                                                    'id' => 'btn_pui',
                                                    'data-request-method' => 'post',
                                                    'data-toggle' => 'tooltip',
                                                    'style' => 'padding:0px;padding-left:2px;',
                                                    'class' => 'btn',
                                                    'title' => Yii::t('app', 'Consulta a Portal Unificado'),
                                                ]) .
                                                Html::a('<span>Actualizar RISNeu</span>', null, [
                                                    'name' => 'btn_risneu',
                                                    'id' => 'btn_risneu',
                                                    'data-request-method' => 'post',
                                                    'data-toggle' => 'tooltip',
                                                    'style' => 'padding:0px;padding-left:2px;',
                                                    'class' => 'btn'
                                                ]);
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'apellido')->textInput() ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'nombre')->textInput() ?>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'telefono')->textInput() ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'localidad')->widget(Select2::class, [
                                                'data' => ArrayHelper::map(
                                                    Sds_com_localidad::getLocalidadesMostrar(),
                                                    'idlocalidad',
                                                    'descripcion'
                                                ),
                                                'options' => [
                                                    'placeholder' => 'Seleccionar Localidad ...',
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'telefono_referente')->textInput() ?>
                                        </div>
                                    </div>
                                    <?= $form->field($model, 'idpersona')->hiddenInput()->label(false) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" id="accordion_intervencion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_intervencion" href="#intervencion">
                                        Datos de la atención
                                    </a>
                                </h4>
                            </div>
                            <div id="intervencion" class="accordion-body collapse in">
                                <div class="panel-body" id="intervencion_content">
                                    <div class="row">
                                        <div class="col-md-12" id="demanda_texto_container">
                                            <?= $form->field($model, 'demanda')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'demanda_texto',
                                                ],
                                            ])->label("Motivo de la demanda") ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'atencion_previa')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Estuvo atendido por algun equipo profesional previo a esta llamada?')
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'institucion')->textarea(['rows' => 2, 'disabled' => $model->atencion_previa != 1])->label('¿De qué institución?') ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'profesionales')->textarea(['rows' => 2, 'disabled' => $model->atencion_previa != 1])->label('¿Qué profesionales?') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vivienda">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vivienda" href="#vivienda">
                                        Datos de la vivienda
                                    </a>
                                </h4>
                            </div>
                            <div id="vivienda" class="accordion-body collapse in">
                                <div class="panel-body" id="vivienda_content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'basura')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Servicio de recolección de basura?')
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'cable')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Servicio de cable?')
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'internet')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Servicio de Internet?')
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_situacion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_situacion" href="#situacion">
                                        Situación del Adulto Mayor
                                    </a>
                                </h4>
                            </div>
                            <div id="situacion" class="accordion-body collapse in">
                                <div class="panel-body" id="situacion_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'familiares')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::NO_TIENE => "No tiene",
                                                    Sds_800_atencion_am::FAMILIAR_SIN_VINCULO => "Si tiene y sin vínculos",
                                                    Sds_800_atencion_am::FAMILIAR_CON_VINCULO => "Si tiene y con vínculos"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Tiene red de familiares?')
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'sociales')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::SOCIAL_NO_TIENE => "No tiene",
                                                    Sds_800_atencion_am::SOCIAL_VECINOS => "Vecinos",
                                                    Sds_800_atencion_am::SOCIAL_ALQUILER => "Propietario de alquiler",
                                                    Sds_800_atencion_am::SOCIAL_OSCPM => "Ref. institucional de OSCPM ",
                                                    Sds_800_atencion_am::SOCIAL_IGLESIA => "Iglesia",
                                                    Sds_800_atencion_am::SOCIAL_OBRA_SOCIAL => "Obra Social",
                                                    Sds_800_atencion_am::SOCIAL_BARRIAL => "Barrial",
                                                    Sds_800_atencion_am::SOCIAL_OTRA => "Otros"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Tiene red de sociales?')
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'sociales_detalle')->textarea(['rows' => 2, 'disabled' => $model->sociales <= 0])->label('¿Cúal?') ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'emergente')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RED_FAMILIAR => "Red Familiar",
                                                    Sds_800_atencion_am::RED_SOCIAL => "Red Social",
                                                    Sds_800_atencion_am::RED_OTRO => "Otra",
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('Si le sucede algún emergente, ¿A quién acudo de lo antes mencionado?')
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'emergente_detalle')->textarea(['rows' => 2, 'disabled' => $model->emergente != 2])->label('Detalle') ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'psicologico')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Se encuentra realizando tratamiento psicológico?')
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'psiquiatrico')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Se encuentra realizando tratamiento psiquiátrico?')
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'administra_dinero')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Administra su dinero?')
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'detalle_dinero')->textarea(['rows' => 2, 'disabled' => $model->administra_dinero != 2])->label('En caso de no, ¿Quién lo administra?') ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'plan')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Recibe algun PLAN o PROGRAMA del estado provincial?')
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'detalle_plan')->textarea(['rows' => 2, 'disabled' => $model->plan != 1])->label('En caso afirmativo, ¿Cuál?') ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'centro')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Le gustaría participar en algún centro de personas mayores?')
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'recreacion')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Participa de alguna actividad lúdico-recreativa?')
                                            ?>
                                        </div>
                                    </div>
                                    <b>¿En que actividad o actividades le gustaría participar?</b>
                                    <div class="row">
                                        <?php
                                        $tipo_recreacion = Sds_com_configuracion::getConfiguracionesActivas(Sds_com_configuracion_tipo::TIPO_0800_RECREACION);
                                        $atencion_recre = Sds_800_am_recreacion::find()->where(['idatencionam' => $model->idllamada])->all();
                                        foreach ($tipo_recreacion as $tipo_re) {
                                            $checked = "";
                                            foreach ($atencion_recre as $ate_recre) {
                                                if ($ate_recre->recreacion == $tipo_re->idconfiguracion) {
                                                    $checked = "checked";
                                                    break;
                                                }
                                            }
                                            echo '<div class="col-md-4">
                                                 <div class="form-group ">
                                                    <label><input type="checkbox" tabindex="1" 
                                                    name="Sds_800_atencion_am[tipo_re][]" value=' . $tipo_re->idconfiguracion . ' ' . $checked . '> 
                                                    ' . $tipo_re->descripcion . '</label>
                                                </div>';
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_evaluacion">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_evaluacion" href="#evaluacion">
                                        Evaluacíon Funcional
                                    </a>
                                </h4>
                            </div>
                            <div id="evaluacion" class="accordion-body collapse in">
                                <div class="panel-body" id="evaluacion_content">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'orientado')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Se encuentra orientado en tiempo y espacio?')
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'dependiente')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::TOTALMENTE_DEPENDIENTE => "Totalmente dependiente",
                                                    Sds_800_atencion_am::SEMI_DEPENDIENTE => "Dependiente en algunas o varias actividades",
                                                    Sds_800_atencion_am::INDEPENDIENTE => "Independiente"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Es dependiente o independiente?')
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'intoxicado')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Se encuentra intoxicado?')
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'delirios')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Presenta delirios y/o alucinaciones?')
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'violentado')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Se encuentra violentado?')
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'expresion')->dropDownList(
                                                [
                                                    Sds_800_atencion_am::RESPUESTA_SIN_DATOS => "Sin Datos",
                                                    Sds_800_atencion_am::RESPUESTA_SI => "Si",
                                                    Sds_800_atencion_am::RESPUESTA_NO => "No"
                                                ],
                                                ['prompt' => '-- Seleccione una opción --']
                                            )->label('¿Se expresa de manera clara?')
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="observaciones_texto_container">
                                            <?= $form->field($model, 'observaciones')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'observaciones_texto',
                                                ],
                                            ])->label("Observaciones") ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?php
                                            if ($model->archivo_seguridad == null) {
                                                echo $form->field($model, 'temp_archivo_seguridad', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    ->widget(FileInput::class, [
                                                        'options' => ['accept' => 'image/*,.pdf'],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf'],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'uploadUrl' => Url::to(['/mds_com_intervencion/update']),
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialCaption' => $model->archivo_seguridad,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                    ]);
                                            } else {
                                                echo $form->field($model, 'temp_archivo_seguridad', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    ->widget(FileInput::class, [
                                                        'options' => ['accept' => 'image/*,.pdf'],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf'],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'uploadUrl' => Url::to(['/sds_800_atencion/update']),
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialPreview' => [
                                                                Html::img($model->archivo_seguridad, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                                            ],
                                                            'overwriteInitial' => true,
                                                            'autoReplace' => true,
                                                            'initialCaption' => $model->archivo_seguridad,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                        'pluginEvents' => [
                                                            "fileclear" => "function() { console.log('fileclear'); $('#borrarSeg').val(true);}",
                                                            "filereset" => "function() {  }",
                                                        ]
                                                    ]);
                                            }
                                            ?>
                                            <?= $form->field($model, 'borrar_adjunto_seguridad')->hiddenInput(['id' => 'borrarSeg'])->label(false) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-md-12'>
                                            <?php
                                            if ($model->archivo_salud == null) {
                                                echo $form->field($model, 'temp_archivo_salud', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    ->widget(FileInput::class, [
                                                        'options' => ['accept' => 'image/*,.pdf'],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf'],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'uploadUrl' => Url::to(['/mds_com_intervencion/update']),
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialCaption' => $model->archivo_salud,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                    ]);
                                            } else {
                                                echo $form->field($model, 'temp_archivo_salud', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
                                                    ->widget(FileInput::class, [
                                                        'options' => ['accept' => 'image/*,.pdf'],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'pdf'],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'uploadUrl' => Url::to(['/sds_800_atencion/update']),
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialPreview' => [
                                                                Html::img($model->archivo_salud, ['class' => 'file-preview-image', 'style' => 'width:100%; text-align: center']),
                                                            ],
                                                            'overwriteInitial' => true,
                                                            'autoReplace' => true,
                                                            'initialCaption' => $model->archivo_salud,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                        'pluginEvents' => [
                                                            "fileclear" => "function() { console.log('fileclear'); $('#borrarSalud').val(true);}",
                                                            "filereset" => "function() {  }",
                                                        ]
                                                    ]);
                                            }
                                            ?>
                                            <?= $form->field($model, 'borrar_adjunto_salud')->hiddenInput(['id' => 'borrarSalud'])->label(false) ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?= $form->field($model, 'idusuario')->hiddenInput()->label(false) ?>

                        <?= $form->field($model, 'idllamada')->hiddenInput()->label(false) ?>

                        <div class="row justify-content-between">
                            <div class="col-md-6">
                                <a class="btn btn-info" href="javascript:history.back(1)">Volver </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <?php if (!Yii::$app->request->isAjax) { ?>
                                    <div class="form-group">
                                        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'btnGuardar']) ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
            </section>
        </div>
    </div>
</div>

<?php
$this->registerJs(
    "$('#btn_pui').click(function(){        
            var dni_campo = $('#txtDNI').val();        
            window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
        });

        $('#sds_800_atencion_am-atencion_previa').change(function(){        
            $('#sds_800_atencion_am-institucion').prop('disabled', $('#sds_800_atencion_am-atencion_previa option:selected').val() != 1);
            $('#sds_800_atencion_am-profesionales').prop('disabled', $('#sds_800_atencion_am-atencion_previa option:selected').val() != 1);
        });

        $('#sds_800_atencion_am-sociales').change(function(){        
            $('#sds_800_atencion_am-sociales_detalle').prop('disabled', $('#sds_800_atencion_am-sociales option:selected').val() <=0);
        });

        $('#sds_800_atencion_am-emergente').change(function(){        
            $('#sds_800_atencion_am-emergente_detalle').prop('disabled', $('#sds_800_atencion_am-emergente option:selected').val() !=2);
        });
        
        $('#sds_800_atencion_am-administra_dinero').change(function(){        
            $('#sds_800_atencion_am-detalle_dinero').prop('disabled', $('#sds_800_atencion_am-administra_dinero option:selected').val() !=2);
        });

        $('#sds_800_atencion_am-plan').change(function(){        
            $('#sds_800_atencion_am-detalle_plan').prop('disabled', $('#sds_800_atencion_am-plan option:selected').val() !=1);
        });

        $('#btn_risneu').hide();
        $('#btn_risneu').click(function(){ 
            var dni = $('#txtDNI').val();       
            var llamada = $('#sds_800_atencion_am-idllamada').val()
            getIdRisneu(dni, llamada);
        });

        $('#btnGuardar').click(function(e){
            const demanda_texto =  $('#demanda_texto').val();
            const parser = new DOMParser();
            const { textContent } = parser.parseFromString(demanda_texto, 'text/html').documentElement;
            demandaTextoSinHTML = textContent.trim();
        
            if (!demanda_texto || demanda_texto.length < 1 || !demandaTextoSinHTML){
                alert('\"Motivo de la demanda\" no puede estar vacío.');
                e.preventDefault();
            }
        });
        
        "
);
?>
<script>
    function getIdRisneu(dni, llamada) {
        $.post("index.php?r=sds_800_atencion_familia/get_id_risneu&dni=" + dni + "&llamada=" + llamada, function(data) {
            data = $.parseJSON(data);
            window.open('<?php echo Url::base() ?>/index.php?r=sds_ris_risneu%2Fupdate&finalizar=0&dni=' + dni + '&id=' + data, '_blank');
        });


    }
</script>