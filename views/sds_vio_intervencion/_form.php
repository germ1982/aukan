<?php

use app\models\Mds_seg_item;
use app\models\Mds_seg_permiso;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_localidad;
use app\models\Sds_vio_intervencion;
use kartik\widgets\FileInput;
use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;
use yii\helpers\Url;

function botonAltaConfiguracion($model, $tipo)
{
    //Creo un botón reutilizable para todas las configuraciones. Se muestra el sector de ABM configuración y se llena
    //con lo que devuelve el método del controller 'actionCreate_ext'. Que sería como un create externo. Se usa también en risneu pero llenando un modal.
    return Html::button('<i class="glyphicon glyphicon-plus"></i>', [
        'value' => Url::to([
            '//sds_com_configuracion/create_ext',
            'tipo' => $tipo,
        ]),
        'class' => 'btn btn-success btn-flat',
        'id' => 'btn_config_' . $tipo,
        'style' => 'margin-top:25px',
        'tabIndex' => '-1',
        // "disabled" => !$model->isNewRecord,
        'onclick' => '
            $("#modal_abm").modal("show")
            .find("#content_abm")
            .load($(this).attr("value"));
            $("#header_abm").html("Agregar Tipo");
        ',
    ]);
}

$permiso_violencia = Mds_seg_permiso::findBySql(
    "select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=" .
        $model->idusuario .
        ")
                                                and (iditem=" .
        Mds_seg_item::MODULO_VIO_VIOLENCIA .
        ' or iditem=' .
        Mds_seg_item::MODULO_VIO_EXTERNO .
        ')'
)->one();
$modificar_persona =
    $permiso_violencia->modifica &&
    $permiso_violencia->iditem == Mds_seg_item::MODULO_VIO_VIOLENCIA;
$idllamada = isset($_GET['idllamada']) ? $_GET['idllamada'] : '';
//$dniRisneu = isset($_GET['dni']) ? $_GET['dni'] : '';
$origen = isset($_GET['origen']) ? $_GET['origen'] : '';
?>

<!--
<script>
    $('#nombre_victima').prop("disabled", true);
    $('#apellido_victima').prop("disabled", true);
    $('#sexo_victima').prop("disabled", true);
    $('#telefono_victima').prop("disabled", true);
    $('#domicilio_victima').prop("disabled", true);
    $('#localidad_victima').prop("disabled", true);
    $('#referente_telefono').prop("disabled", true);
    $('#referente_nombre').prop("disabled", true);
    $('#referente_vinculo').prop("disabled", true);
    $('#agresor_dni').prop("disabled", true);
    $('#agresor_nombre').prop("disabled", true);
    $('#agresor_apellido').prop("disabled", true);

    $('#fecha').prop("disabled", true);
    $('#ingreso').prop("disabled", true);
    $('#tipo_situacion').prop("disabled", true);
    $('#tipo_intervencion').prop("disabled", true);
    $('#boton_tipo_intervencion').prop("disabled", true);
    $('#derivacion_intervencion').prop("disabled", true);
    $('#boton_derivacion_intervencion').prop("disabled", true);
    $('#check_denuncia').prop("disabled", true);
    $('#juzgado').prop("disabled", true);
    $('#localidad_hecho').prop("disabled", true);
    $('#detalle').prop("disabled", true);
</script>
-->

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
<div class="sds-vio-intervencion-form" id="dif_formulario_intervencion">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?php echo Collapse::widget([]); ?>
                    <div class="panel-group" id="accordion_persona">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_persona" href="#persona">
                                        Persona en situación de violencia
                                    </a>
                                </h4>
                            </div>
                            <div id="persona" class="accordion-body collapse in">
                                <div class="panel-body" id="llamante_content">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="hidden" id="idllamada" name="idllamada" value="<?php echo $idllamada ?>">
                                            <!--ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el seleccionar dni-->
                                            <?= $form
                                                ->field($model, 'dni')
                                                ->textInput([
                                                    'id' => 'txtDNI',
                                                    'disabled' => !$model->isNewRecord,
                                                ]) ?>
                                        </div>
                                        <div class="col-md-2" style="padding-top:25px;">
                                            <?php echo Html::a(
                                                '<i class="glyphicon glyphicon-search"></i>',
                                                null,
                                                [
                                                    'name' => 'btn_dni',
                                                    'id' => 'btn_dni',
                                                    'data-request-method' =>
                                                    'post',
                                                    'data-toggle' => 'tooltip',
                                                    'class' =>
                                                    'btn btn-primary',
                                                    'title' => Yii::t(
                                                        'app',
                                                        'Consultar DNI'
                                                    ),
                                                    //ANOTEZE: Aca pregunto si no es un nuevo registro (edición), deshabilito el botón de buscar dni
                                                    'disabled' => !$model->isNewRecord,
                                                    //TODO cargar el form con los datos de com persona y vio_persona si existe
                                                    'onclick' =>
                                                    'validarDocumento()',
                                                ]
                                            ) .
                                                Html::a(
                                                    '<img src="img/PUI_logo_tiny.png" height="34px" alt="Consulta PUI">',
                                                    null,
                                                    [
                                                        'name' => 'btn_pui',
                                                        'id' => 'btn_pui',
                                                        'data-request-method' =>
                                                        'post',
                                                        'data-toggle' =>
                                                        'tooltip',
                                                        'style' =>
                                                        'padding:0px;padding-left:2px;',
                                                        'class' => 'btn',
                                                        'title' => Yii::t(
                                                            'app',
                                                            'Consulta a Portal Unificado'
                                                        ),
                                                    ]
                                                ) .
                                                Html::a(
                                                    '<span>Actualizar RISNeu N°</span>',
                                                    null,
                                                    [
                                                        'name' => 'btn_risneu',
                                                        'id' => 'btn_risneu',
                                                        'data-request-method' =>
                                                        'post',
                                                        'data-toggle' =>
                                                        'tooltip',
                                                        'style' =>
                                                        'padding:0px;padding-left:2px;',
                                                        'class' => 'btn',
                                                    ]
                                                ); ?>
                                        </div>
                                        <div class="col-md-3 required">
                                            <?= $form->field($model, 'apellido')->textInput(["disabled" => true]) ?>
                                        </div>
                                        <div class="col-md-4 required">
                                            <?= $form->field($model, 'nombre')->textInput(["disabled" => true]) ?>
                                        </div>
                                        <?= $form->field($model, 'idpersona')->hiddenInput()->label(false) ?>
                                        <?= $form->field($model, 'idllamada')->hiddenInput()->label(false) ?>
                                        <div class="row">
                                            <div class="col-md-5" style="padding-top:30px;" id="txt_mensaje"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 required">
                                            <?= $form->field($model, 'sexo')->dropdownList(
                                                $sexoOptions,
                                                [
                                                    'id' => 'sexo_victima', 'tabindex' => '1',
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'genero_autopercibido')->dropdownList(
                                                $generoOptions,
                                                [
                                                    'id' => 'genero_autopercibido', 'tabindex' => '1',
                                                    'prompt' => [
                                                        'text' => 'Seleccione Género ...    ',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ],
                                                ],
                                            )
                                            ?>
                                        </div>

                                        <div class="col-md-4">
                                            <?= $form
                                                ->field($model, 'telefono')
                                                ->textInput([
                                                    'id' => 'telefono_victima',
                                                ]) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'domicilio')->textInput(["id" => "domicilio_victima"]) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'provincia')->widget(
                                                Select2::class,
                                                [
                                                    'data' => $listProvincias,
                                                    'options' => [
                                                        'placeholder' => 'Seleccionar Provincia ...',
                                                        'id' => 'cmb_provincia',
                                                        'onchange' =>   'cargarLocalidades();'
                                                    ],

                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ]
                                            )->label('Provincia');
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'localidad')->widget(Select2::class, [
                                                'data' => $listLocalidades,
                                                'options' => [
                                                    'placeholder' => 'Seleccionar ...',
                                                    'id' => 'cmb_localidad'
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label("Localidad");
                                            ?>
                                            <input type="hidden" id="idLocalidadSelected" name="idLocalidadSelected">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'provincia_oriunda')->widget(
                                                Select2::class,
                                                [
                                                    'data' => $listProvincias,
                                                    'options' => [
                                                        'placeholder' => 'Seleccionar Provincia ...',
                                                        'id' => 'cmb_provincia_oriunda',
                                                        'onchange' => 'cargarLocalidadesOriunda();'
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ]
                                                ]
                                            )->label('Provincia oriunda');
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'localidad_oriunda')->widget(Select2::class, [
                                                'data' => $listLocalidadesOriunda,
                                                'options' => [
                                                    'placeholder' => 'Seleccionar Localidad ...',
                                                    'id' => 'cmb_localidad_oriunda'
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ]
                                            ]);
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'nacionalidad_origen')->dropdownList(
                                                $nacionalidadOptions,
                                                [
                                                    'prompt' => 'Seleccionar Nacionalidad ...',
                                                ]
                                            );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_referente">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_referente" href="#referente">
                                        Referente Terciario
                                    </a>
                                </h4>
                            </div>
                            <div id="referente" class="accordion-body collapse in">
                                <div class="panel-body" id="llamante_content">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <?= $form->field($model, 'referente_telefono')->textInput(['id' => 'referente_telefono']) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'referente_nombre')->textInput(['id' => 'referente_nombre']) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= $form->field($model, 'referente_vinculo')->textInput(['id' => 'referente_vinculo']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_abordaje">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_abordaje" href="#abordaje">
                                        Abordaje
                                    </a>
                                </h4>
                            </div>
                            <div id="abordaje" class="accordion-body collapse in">
                                <div class="panel-body" id="llamante_content">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?php
                                            if ($model->fecha != null) {
                                                $model->fecha = date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        str_replace(
                                                            '/',
                                                            '-',
                                                            $model->fecha
                                                        )
                                                    )
                                                );
                                            }
                                            echo $form
                                                ->field($model, 'fecha')
                                                ->widget(DatePicker::class, [
                                                    'name' => 'check_issue_date',
                                                    'language' => 'es',
                                                    'readonly' => false,
                                                    'layout' =>
                                                    '{picker}{input}{remove}',
                                                    'options' => [
                                                        'id' => 'fecha',
                                                        'class' =>
                                                        'form-control input-md',
                                                        'disabled' => false,
                                                    ],
                                                    'pluginOptions' => [
                                                        'value' => null,
                                                        'format' => 'dd/mm/yyyy',
                                                        'endDate' => date('d/m/Y'),
                                                        'todayHighlight' => true,
                                                        'autoclose' => true,
                                                    ],
                                                ]);
                                            ?>
                                        </div>
                                        <div class="col-md-2" style="padding-top:35px">
                                            <?= $form
                                                ->field($model, 'ingreso')
                                                ->checkBox([
                                                    'id' => 'ingreso',
                                                    'checked' => true,
                                                    'onchange' =>
                                                    'ocultar_div_ingreso_nuevo()',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form
                                                ->field($model, 'tipo_situacion')
                                                ->dropdownList(
                                                    [
                                                        Sds_vio_intervencion::TIPO_SITUACION_CODIGO_A =>
                                                        'Código A',
                                                        Sds_vio_intervencion::TIPO_SITUACION_CODIGO_B =>
                                                        'Código B',
                                                        Sds_vio_intervencion::TIPO_SITUACION_ASESORAMIENTO =>
                                                        'Asesoramiento',
                                                    ],
                                                    [
                                                        'id' => 'tipo_situacion',
                                                        'placeholder' =>
                                                        'Seleccionar Tipo de Situación ...',
                                                    ]
                                                ) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <?= $form
                                                    ->field($model, 'tipo')
                                                    ->dropdownList(
                                                        $tipoIntervOptions,
                                                        [
                                                            'id' =>
                                                            'config_' .
                                                                Sds_com_configuracion_tipo::TIPO_INTERVENCION_TIPO,
                                                            'prompt' =>
                                                            '-- Seleccione una opción --'
                                                        ]
                                                    ) ?>
                                                <span class="input-group-btn">
                                                    <?= botonAltaConfiguracion(
                                                        $model,
                                                        Sds_com_configuracion_tipo::TIPO_INTERVENCION_TIPO
                                                    ) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="div_ingreso_nuevo">
                                        <div class="col-md-5">
                                            <div class="input-group">

                                                <?= $form->field($model, 'derivacion')->widget(Select2::class, [
                                                    'data' => $derivacionOptions,
                                                    'options' => [
                                                        'placeholder' => '-- Seleccione una opción --',
                                                        'id' => 'config_' . Sds_com_configuracion_tipo::TIPO_INTERVENCION_DERIVACION,
                                                    ],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                ])->label('Proveniente de');
                                                ?>
                                                <span class="input-group-btn">
                                                    <?= botonAltaConfiguracion(
                                                        $model,
                                                        Sds_com_configuracion_tipo::TIPO_INTERVENCION_DERIVACION
                                                    ) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2" style="padding-top:35px">
                                            <?= $form
                                                ->field($model, 'denuncia')
                                                ->checkBox([
                                                    'id' => 'check_denuncia',
                                                    'checked' => $model->denuncia == 1 ? true : false,
                                                    'onchange' =>
                                                    'ocultar_div_juzgado()',
                                                ]) ?>
                                        </div>
                                        <div class="col-md-5" id="div_juzgado">
                                            <?= $form->field($model, 'juzgado')->textInput(['id' => 'juzgado', 'maxlength' => true]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- //------------------------------------------------------------------------------------------------------ -->
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'provincia_hecho')->widget(Select2::class, [
                                                'data' => $listProvincias,
                                                'options' => [
                                                    'placeholder' => 'Seleccionar Provincia ...',
                                                    'id' => 'cmb_provincia_hecho',
                                                    'onchange' => 'cargarLocalidadesHecho();'
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label('Provincia del Hecho');
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($model, 'localidad_hecho')->widget(Select2::class, [
                                                'data' => $listLocalidadesHecho,
                                                'options' => [
                                                    'placeholder' => 'Seleccionar Localidad ...',
                                                    'id' => 'localidad_hecho'
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ]);
                                            ?>
                                        </div>

                                        <div class="col-md-3">
                                            <?= $form->field($model, 'tipo_modalidad')->dropdownList(
                                                $modalidadOptions,
                                                [
                                                    'prompt' => '-- Seleccione una opción --',
                                                ]
                                            );
                                            ?>
                                        </div>
                                        <div class="col-md-3" style="padding-top:35px">
                                            <?= $form->field($model, 'consumo_problematico')->checkBox(['id' => 'consumo_problematico']) ?>
                                        </div>
                                        <!-- //------------------------------------------------------------------------------------------------------ -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12" id="detalle_texto_container">
                                            <?= $form->field($model, 'detalle')->widget(\bizley\quill\Quill::class, [
                                                // 'allowResize' => true,
                                                'options' => [
                                                    'style' => 'height: 125px;',
                                                    'id' => 'detalle_texto',
                                                ],
                                            ])->label("Detalle") ?>
                                        </div>
                                        <div class="col-md-12">
                                            <?= $form
                                                ->field($model, 'detalle_plataforma')
                                                ->textarea(['rows' => 5, 'readonly' => false]) ?>
                                        </div>
                                        <div class="col-md-12">
                                            <?= $form
                                                ->field($model, 'profesionales_intervinientes')
                                                ->textarea(['rows' => 5]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!---------------------------------------------------  tipos de violencia ------------------------------------------------------------------------------ -->
                    <div class="panel-group">
                        <div class="panel panel-accordion" id="accordion_vio_fisica">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_fisica" href="#detalle_vio_fisica">
                                        Violencia física
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_fisica" class="accordion-body collapse <?= $model->tipo_violencia_fisica ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioFisicaSelectOptions as $tipoFisica) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $fisica) {
                                                        if ($fisica->idviolenciatipo == $tipoFisica->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "
                                        <div class='form-group'>
                                            <label>
                                                <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoFisica->idconfiguracion}' $checked > 
                                                {$tipoFisica->descripcion}
                                              </label>
                                        </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($modelFrecuencia, 'tipoFisica[frecuencia]')->dropdownList(
                                                $vioFrecuenciaSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Frecuencia</b>');
                                            ?>
                                            <?= $form->field($modelFrecuencia, 'tipoFisica[ocurrencia]')->dropdownList(
                                                $vioOcurrenciasSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]

                                                ]
                                            )->label('<b>Ocurrencia</b>');
                                            ?>
                                            <?=
                                            $form->field($modelFrecuencia, 'tipoFisica[vigencia]')->dropdownList(
                                                [
                                                    1 => "Si",
                                                    0 => "No",
                                                ],
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Vigencia</b>')
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vio_psicologica">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_psicologica" href="#detalle_vio_psicologica">
                                        Violencia psicológica
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_psicologica" class="accordion-body collapse <?= $model->tipo_violencia_psicologica ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioPsicologicaSelectOptions as $tipoPsicologica) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $psicologica) {
                                                        if ($psicologica->idviolenciatipo == $tipoPsicologica->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "<div class='form-group'>
                                            <label>
                                                <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoPsicologica->idconfiguracion}' $checked > 
                                                {$tipoPsicologica->descripcion}
                                                </label>
                                            </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($modelFrecuencia, 'tipoPsicologica[frecuencia]')->dropdownList(
                                                $vioFrecuenciaSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]

                                                ]
                                            )->label('<b>Frecuencia</b>');
                                            ?>
                                            <?= $form->field($modelFrecuencia, 'tipoPsicologica[ocurrencia]')->dropdownList(
                                                $vioOcurrenciasSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]

                                                ]
                                            )->label('<b>Ocurrencia</b>');
                                            ?>
                                            <?=
                                            $form->field($modelFrecuencia, 'tipoPsicologica[vigencia]')->dropdownList(
                                                [
                                                    1 => "Si",
                                                    0 => "No",
                                                ],
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ],
                                                ]
                                            )->label('<b>Vigencia</b>')
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" id="accordion_vio_sexual">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_sexual" href="#detalle_vio_sexual">
                                        Violencia sexual
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_sexual" class="accordion-body collapse <?= $model->tipo_violencia_sexual ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioSexualSelectOptions as $tipoSexual) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $sexual) {
                                                        if ($sexual->idviolenciatipo == $tipoSexual->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "<div class='form-group'>
                                                <label>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoSexual->idconfiguracion}' $checked > 
                                                    {$tipoSexual->descripcion}
                                                </label>
                                            </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($modelFrecuencia, 'tipoSexual[frecuencia]')->dropdownList(
                                                $vioFrecuenciaSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Frecuencia</b>');
                                            ?>
                                            <?= $form->field($modelFrecuencia, 'tipoSexual[ocurrencia]')->dropdownList(
                                                $vioOcurrenciasSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Ocurrencia</b>');
                                            ?>
                                            <?=
                                            $form->field($modelFrecuencia, 'tipoSexual[vigencia]')->dropdownList(
                                                [
                                                    1 => "Si",
                                                    0 => "No",
                                                ],
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Vigencia</b>')
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vio_economicapatrimonial">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_economicapatrimonial" href="#detalle_vio_economicapatrimonial">
                                        Violencia económica - patrimonial
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_economicapatrimonial" class="accordion-body collapse <?= $model->tipo_violencia_economica_patrimonial ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioEconomicapatrimonialSelectOptions as $tipoEconomicaPatrimonial) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $economica) {
                                                        if ($economica->idviolenciatipo == $tipoEconomicaPatrimonial->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "<div class='form-group'>
                                                <label>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoEconomicaPatrimonial->idconfiguracion}' $checked > 
                                                    {$tipoEconomicaPatrimonial->descripcion}
                                                </label>
                                            </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($modelFrecuencia, 'tipoEconomicaPatrimonial[frecuencia]')->dropdownList(
                                                $vioFrecuenciaSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Frecuencia</b>');
                                            ?>
                                            <?= $form->field($modelFrecuencia, 'tipoEconomicaPatrimonial[ocurrencia]')->dropdownList(
                                                $vioOcurrenciasSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Ocurrencia</b>');
                                            ?>
                                            <?=
                                            $form->field($modelFrecuencia, 'tipoEconomicaPatrimonial[vigencia]')->dropdownList(
                                                [
                                                    1 => "Si",
                                                    0 => "No",
                                                ],
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Vigencia</b>')
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-accordion" id="accordion_vio_simbolica">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_simbolica" href="#detalle_vio_simbolica">
                                        Violencia simbólica
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_simbolica" class="accordion-body collapse <?= $model->tipo_violencia_simbolica ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioSimbolicaSelectOptions as $tipoSimbolica) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $simbolica) {
                                                        if ($simbolica->idviolenciatipo == $tipoSimbolica->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "<div class='form-group'>
                                                <label>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoSimbolica->idconfiguracion}' $checked> 
                                                    {$tipoSimbolica->descripcion}
                                                </label>
                                            </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($modelFrecuencia, 'tipoSimbolica[frecuencia]')->dropdownList(
                                                $vioFrecuenciaSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Frecuencia</b>');
                                            ?>
                                            <?= $form->field($modelFrecuencia, 'tipoSimbolica[ocurrencia]')->dropdownList(
                                                $vioOcurrenciasSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Ocurrencia</b>');
                                            ?>
                                            <?=
                                            $form->field($modelFrecuencia, 'tipoSimbolica[vigencia]')->dropdownList(
                                                [
                                                    1 => "Si",
                                                    0 => "No",
                                                ],
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Vigencia</b>')
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_vio_negligenciaabandono">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_negligenciaabandono" href="#detalle_vio_negligenciaabandono">
                                        Violencia negligencia - abandono
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_negligenciaabandono" class="accordion-body collapse <?= $model->tipo_violencia_negligencia_abandono ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioNegligenciaAbandonoSelectOptions as $tipoNegligenciaAbandono) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $negligencia) {
                                                        if ($negligencia->idviolenciatipo == $tipoNegligenciaAbandono->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "<div class='form-group'>
                                                <label>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoNegligenciaAbandono->idconfiguracion}' $checked > 
                                                    {$tipoNegligenciaAbandono->descripcion}
                                                </label>
                                            </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($modelFrecuencia, 'tipoNegligenciaAbandono[frecuencia]')->dropdownList(
                                                $vioFrecuenciaSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Frecuencia</b>');
                                            ?>
                                            <?= $form->field($modelFrecuencia, 'tipoNegligenciaAbandono[ocurrencia]')->dropdownList(
                                                $vioOcurrenciasSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Ocurrencia</b>');
                                            ?>
                                            <?=
                                            $form->field($modelFrecuencia, 'tipoNegligenciaAbandono[vigencia]')->dropdownList(
                                                [
                                                    1 => "Si",
                                                    0 => "No"
                                                ],
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Vigencia</b>')
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group">
                        <div class="panel panel-accordion" id="accordion_vio_ambiental">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vio_ambiental" href="#detalle_vio_ambiental">
                                        Violencia ambiental
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_vio_ambiental" class="accordion-body collapse <?= $model->tipo_violencia_ambiental ? 'in' : '' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            foreach ($vioAmbientalSelectOptions as $tipoAmbiental) {
                                                $checked = "";
                                                if ($vioChecked) {
                                                    foreach ($vioChecked as $ambiental) {
                                                        if ($ambiental->idviolenciatipo == $tipoAmbiental->idconfiguracion) {
                                                            $checked = "checked";
                                                            break;
                                                        }
                                                    }
                                                }
                                                echo "<div class='form-group'>
                                                <label>
                                                    <input type='checkbox' tabindex='1' name='Sds_violencia[item][]' value='{$tipoAmbiental->idconfiguracion}' $checked > 
                                                    {$tipoAmbiental->descripcion}
                                                </label>
                                            </div>";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3">
                                            <?= $form->field($modelFrecuencia, 'tipoAmbiental[frecuencia]')->dropdownList(
                                                $vioFrecuenciaSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Frecuencia</b>');
                                            ?>
                                            <?= $form->field($modelFrecuencia, 'tipoAmbiental[ocurrencia]')->dropdownList(
                                                $vioOcurrenciasSelect,
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Ocurrencia</b>');
                                            ?>
                                            <?=
                                            $form->field($modelFrecuencia, 'tipoAmbiental[vigencia]')->dropdownList(
                                                [
                                                    1 => "Si",
                                                    0 => "No",
                                                ],
                                                [
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción ...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ]
                                                ]
                                            )->label('<b>Vigencia</b>')
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-group" id="accordion_complementario">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_complementario" href="#complementario">
                                        Abordajes Complementarios
                                    </a>
                                </h4>
                            </div>
                            <div id="complementario" class="accordion-body collapse in">
                                <div class="panel-body" id="llamante_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'abordaje_complementario'
                                                )
                                                ->textarea([
                                                    'rows' => 5,
                                                    'id' => 'detalle',
                                                ])
                                                ->label('') ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-md-6'>

                                            <?php if (
                                                $model->archivo_adjunto1 == null
                                            ) : ?>
                                                <?= $form
                                                    ->field(
                                                        $model,
                                                        'temp_archivo_adjunto1',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(FileInput::class, [
                                                        'options' => [
                                                            'accept' => 'image/*,.pdf',
                                                        ],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => [
                                                                'jpg',
                                                                'jpeg',
                                                                'gif',
                                                                'png',
                                                                'bmp',
                                                                'pdf',
                                                            ],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' =>
                                                            'input-group-sm',
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialCaption' => false,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ],
                                                        ],
                                                    ]) ?>
                                                <?php
                                                // identify if you are sending preview data only and not the raw markup // image is the default and can be overridden in config below
                                                // identify if you are sending preview data only and not the raw markup
                                                // image is the default and can be overridden in config below
                                                ?><?php else : ?>
                                                <?= $form
                                                        ->field(
                                                            $model,
                                                            'temp_archivo_adjunto1',
                                                            [
                                                                'enableClientValidation' => true,
                                                                'enableAjaxValidation' => false,
                                                            ]
                                                        )
                                                        ->widget(FileInput::class, [
                                                            'options' => [
                                                                'accept' => 'image/*,.pdf',
                                                            ],
                                                            'language' => 'es',
                                                            'pluginOptions' => [
                                                                'allowedFileExtensions' => [
                                                                    'jpg',
                                                                    'jpeg',
                                                                    'gif',
                                                                    'png',
                                                                    'bmp',
                                                                    'pdf',
                                                                ],
                                                                'showCaption' => false,
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                                'showClose' => false,
                                                                'mainClass' =>
                                                                'input-group-sm',
                                                                'maxFileSize' => 52428800, // 50MB
                                                                'previewFileType' => 'file',
                                                                'initialPreview' => [
                                                                    Url::to(
                                                                        '@web/uploads/violencia/' .
                                                                            $model->archivo_adjunto1,
                                                                        true
                                                                    ),
                                                                    [
                                                                        'class' =>
                                                                        'file-preview-image',
                                                                        'style' =>
                                                                        'width:100%',
                                                                    ],
                                                                ],
                                                                'initialPreviewAsData' => true,
                                                                'initialPreviewFileType' => Sds_vio_intervencion::getExtension(
                                                                    $model->archivo_adjunto1
                                                                ),
                                                                'overwriteInitial' => true,
                                                                'autoReplace' => true,
                                                                'fileActionSettings' => [
                                                                    'showRemove' => false,
                                                                    'showUpload' => false,
                                                                ],
                                                            ],
                                                            'pluginEvents' => [
                                                                'fileclear' =>
                                                                "function() { console.log('fileclear'); $('#borraradjunto1').val(true);}",
                                                                'filereset' =>
                                                                'function() {  }',
                                                            ],
                                                        ]) ?>
                                            <?php endif; ?>


                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'borrar_archivo_adjunto1'
                                                )
                                                ->hiddenInput([
                                                    'id' => 'borraradjunto1',
                                                ])
                                                ->label(false) ?>
                                        </div>
                                        <div class='col-md-6'>
                                            <?php
                                            if ($model->archivo_adjunto2 == null) {

                                                echo $form->field($model, 'temp_archivo_adjunto2', ['enableClientValidation' => true, 'enableAjaxValidation' => false])
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
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialCaption' => false,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ]
                                                        ],
                                                    ]);
                                            } else {
                                                echo $form
                                                    ->field(
                                                        $model,
                                                        'temp_archivo_adjunto2',
                                                        [
                                                            'enableClientValidation' => true,
                                                            'enableAjaxValidation' => false,
                                                        ]
                                                    )
                                                    ->widget(FileInput::class, [
                                                        'options' => [
                                                            'accept' => 'image/*,.pdf',
                                                        ],
                                                        'language' => 'es',
                                                        'pluginOptions' => [
                                                            'allowedFileExtensions' => [
                                                                'jpg',
                                                                'jpeg',
                                                                'gif',
                                                                'png',
                                                                'bmp',
                                                                'pdf',
                                                            ],
                                                            'showCaption' => false,
                                                            'showRemove' => true,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' =>
                                                            'input-group-sm',
                                                            'uploadUrl' => Url::to([
                                                                '/sds_vio_intervencion/update',
                                                            ]),
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',

                                                            'initialPreview' => [
                                                                Url::to(
                                                                    '@web/uploads/violencia/' .
                                                                        $model->archivo_adjunto2,
                                                                    true
                                                                ),
                                                                [
                                                                    'class' =>
                                                                    'file-preview-image',
                                                                    'style' =>
                                                                    'width:100%',
                                                                ],
                                                            ],
                                                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                                            'initialPreviewFileType' => Sds_vio_intervencion::getExtension(
                                                                $model->archivo_adjunto1
                                                            ), // image is the default and can be overridden in config below
                                                            'overwriteInitial' => true,
                                                            'autoReplace' => true,
                                                            'initialCaption' =>
                                                            $model->archivo_adjunto2,
                                                            'fileActionSettings' => [
                                                                'showRemove' => true,
                                                                'showUpload' => false,
                                                            ],
                                                        ],
                                                        'pluginEvents' => [
                                                            'fileclear' =>
                                                            "function() { console.log('fileclear'); $('#borraradjunto2').val(true);}",
                                                            'filereset' =>
                                                            'function() {  }',
                                                        ],
                                                    ]);
                                            } ?>
                                            <?= $form
                                                ->field(
                                                    $model,
                                                    'borrar_archivo_adjunto2'
                                                )
                                                ->hiddenInput([
                                                    'id' => 'borraradjunto2',
                                                ])
                                                ->label(false) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (!Yii::$app->request->isAjax) { ?>
                            <div class="row"><br />
                                <div class="col-md-12">
                                    <?php if ($origen) { ?>
                                        <a class="btn btn-info" href="index.php?r=sds_800_llamada&area=4">Volver</a>
                                    <?php } else { ?>
                                        <a class="btn btn-info" href="index.php?r=sds_vio_intervencion/index">Volver</a>
                                    <?php } ?>

                                    <?= Html::submitButton(
                                        $model->isNewRecord
                                            ? 'Guardar'
                                            : 'Editar',
                                        [
                                            'class' => $model->isNewRecord
                                                ? 'btn btn-success'
                                                : 'btn btn-success',
                                            'id' => 'btnGuardar'
                                        ]
                                    ) ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
</section>


<?php $this->registerJs(
    "$('#btn_pui').click(function(){        
        var dni_campo = $('#txtDNI').val();        
        window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo, '_blank');
    });
    $('#sexo_victima').prop('disabled', true);

    $('#btn_risneu').hide();
    $('#btn_risneu').click(function(){ 
        var dni = $('#txtDNI').val();       
        getIdRisneu(dni);
    });
    
    $('#btn_pui_agresor').click(function(){        
        var dni_campo_agresor = $('#agresor_dni').val();        
        window.open('https://pui.neuquen.gov.ar/sessions/signin?iframe=true&documento='+dni_campo_agresor, '_blank');
    });

    $('#btn_dni_agresor').click(function(){        
        datos_persona(false);
    });

    $('#btnGuardar').click(function(e){

        const checkedAnyItem = $('input[name=\"Sds_violencia[item][]\"]:checked').val();  // Verificamos si selecciono algun check
        const detalle_texto =  $('#detalle_texto').val();
        const parser = new DOMParser();
        const { textContent } = parser.parseFromString(detalle_texto, 'text/html').documentElement;
        detalleTextoTextoSinHTML = textContent.trim();
        
        if (!detalle_texto || detalle_texto.length < 1 || !detalleTextoTextoSinHTML || !checkedAnyItem ){
            
            if (checkedAnyItem){
                alert('\"Detalle\" en \"Abordaje\" no puede estar vacío.');
            } else {
                alert('Debe tildar algún tipo de violencia');
            }
            e.preventDefault();
        }
    });
    
    ocultar_div_juzgado();
    "
); ?>

<script>
    function getIdRisneu(dni) {
        $.post("index.php?r=sds_vio_intervencion/get_id_risneu&dni=" + dni, function(data) {
            data = $.parseJSON(data);
            window.open('<?php echo Url::base(); ?>/index.php?r=sds_ris_risneu%2Fupdate&finalizar=0&dni=' + dni + '&id=' + data + '&idllamada=' + $('#idllamada').val(), '_blank');
        });
    }

    var modificar_persona = <?= $modificar_persona ? 1 : 0 ?>;


    function ordenarSelect(id_componente) {
        //alta burbuja que encontre en la internet
        var selectToSort = jQuery('#' + id_componente);
        var optionActual = selectToSort.val();
        selectToSort.html(selectToSort.children('option').sort(function(a, b) {
            return a.text === b.text ? 0 : a.text < b.text ? -1 : 1;
        })).val(optionActual);
    }


    function limpiarDatos() {
        habilitar_controles();
        $("#nombre_victima").val('');
        $("#apellido_victima").val('');
        $("#sexo_victima").val('');
        $("#telefono_victima").val("");
        $("#domicilio_victima").val("");
        $("#localidad_victima").val("");
        /* $("#renaper_foto").attr("src", ''); */
        $("#hidden_nueva_persona").val('0');
    }

    function cargarLocalidades() {
        if ($("#cmb_provincia").val()) {
            $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia").val(), function(data) {
                $("select#cmb_localidad").html(data);
                $("#cmb_localidad").val($("#idLocalidadSelected").val()).trigger("change");
            });
        } else {
            $("#cmb_localidad").val(null).trigger("change");
        }
    }

    function cargarLocalidadesOriunda() {
        if ($("#cmb_provincia_oriunda").val()) {
            $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia_oriunda").val(), function(data) {
                $("select#cmb_localidad_oriunda").html(data);
                $("#cmb_localidad_oriunda").val(null).trigger("change");
            });
        } else {
            $("#cmb_localidad_oriunda").val(null).trigger("change");
        }
    }

    function cargarLocalidadesHecho() {
        $.post("index.php?r=sds_com_localidad/cmb_localidad&idprovincia=" + $("#cmb_provincia_hecho").val(), function(data) {
            $("select#localidad_hecho").html(data);
            $("#localidad_hecho").val(null).trigger("change");
        });
    }


    function corregir_palabra(palabra) {
        palabra = palabra.replace("ï¿½", "É");
        palabra = palabra.replace(/_/g, " ");
        palabra = palabra.replace("É?", "Á");
        palabra = palabra.replace("ï¿½?", "Ñ");
        palabra = palabra.replace("�", "");
        return palabra;
    }

    function ocultar_div_ingreso_nuevo() {
        if ($('#ingreso').prop('checked')) {
            $('#div_ingreso_nuevo').show();
        } else {
            $('#config_ <?= Sds_com_configuracion_tipo::TIPO_INTERVENCION_DERIVACION ?> ').val("");


            $('#check_denuncia').prop("checked", false);
            ocultar_div_juzgado();
            $('#div_ingreso_nuevo').hide();
        }
    }

    function ocultar_div_juzgado() {
        if ($('#check_denuncia').prop('checked')) {
            $('#div_juzgado').show();
        } else {
            $('#juzgado').val("");
            $('#div_juzgado').hide();
        }
    }

    function habilitar_controles() {
        $('#nombre_victima').prop("disabled", false);
        $('#apellido_victima').prop("disabled", false);
        $('#telefono_victima').prop("disabled", false);
        $('#domicilio_victima').prop("disabled", false);
        $('#localidad_victima').prop("disabled", false);

        $('#referente_telefono').prop("disabled", false);
        $('#referente_nombre').prop("disabled", false);
        $('#referente_vinculo').prop("disabled", false);
        $('#agresor_dni').prop("disabled", false);
        $('#agresor_nombre').prop("disabled", false);
        $('#agresor_apellido').prop("disabled", false);

        $('#fecha').prop("disabled", false);
        $('#ingreso').prop("disabled", false);
        $('#tipo_situacion').prop("disabled", false);
        $('#tipo_intervencion').prop("disabled", false);
        $('#boton_tipo_intervencion').prop("disabled", false);
        $('#derivacion_intervencion').prop("disabled", false);
        $('#boton_derivacion_intervencion').prop("disabled", false);
        $('#check_denuncia').prop("disabled", false);
        $('#juzgado').prop("disabled", false);
        $('#localidad_hecho').prop("disabled", false);
        $('#detalle').prop("disabled", false);
    }

    function validarDocumento() {
        $.post("index.php?r=sds_vio_intervencion/validar_dni&dni=" + $("#txtDNI").val() + "&idllamada=" + $("#idllamada").val(), function(data) {
            if (data) {
                data = $.parseJSON(data);
                $("#idcomponentequemuestramensaje").val(data.mensaje);
                $("#sds_vio_intervencion-idpersona").val(data[1].idpersona).change();
                $("#sds_vio_intervencion-apellido").val(data[1].apellido);
                $("#sds_vio_intervencion-nombre").val(data[1].nombre);
                $("#sds_vio_intervencion-nacionalidad_origen").val(data[1]?.nacionalidad);
                $("#sexo_victima").val(data[1].genero);
                if (data[2]) {
                    $("#genero_autopercibido").val(data[2].genero_autopercibido);
                    $("#telefono_victima").val(data[2].telefono);
                    $("#sds_vio_intervencion-localidad").val(data[2].idlocalidad);
                    $("#sds_vio_intervencion-telefono").val(data[2].telefono);
                }
                $("#cmb_provincia").val(data["idprovincia"]).trigger("change");
                if (data["idlocalidad"]) {
                    $("#idLocalidadSelected").val(data["idlocalidad"]);
                }
                $("#btn_risneu").text('Actualizar RISNeu N°' + data[0].idrisneu).show();
            } else {
                $("#btn_risneu").text('Actualizar RISNeu').hide();
            }
        });
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