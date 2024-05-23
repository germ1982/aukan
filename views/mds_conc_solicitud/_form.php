<?php

use app\models\Mds_conc_solicitud;
use yii\helpers\Html;
use kartik\widgets\FileInput;
use yii\helpers\Url;

?>

<style>
    div.required label:after {
        content: " *";
        color: red;
    }
</style>

<div class="mds-conc-solicitud-form">

    <?php if (!Yii::$app->request->isAjax) : ?>
        <header class="page-header">
            <h2><?= $this->title ?></h2>

            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="index.php">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span><?= $this->title  ?></span></li>
                </ol>
                <div class="sidebar-right-toggle"></div>
            </div>
        </header>
    <?php endif ?>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">

                    <?php if (!$model->isNewRecord) { ?>
                        <div class="row">
                            <div class="col-md-12" style="display: flex;">
                                <h5 style="margin: 0 auto 0 0;"><b>Fecha de carga: </b><?= $model->fechaCarga ?></h5>
                                <h5 style="margin: 0 0 0 auto;"><b>Usuario de carga: </b><?= $model->idusuario ? strtoupper($model->usuarioCarga->apellido) . ' ' . strtoupper($model->usuarioCarga->nombre) : "" ?></h5>
                            </div>
                        </div>
                        <br>
                    <?php } ?>
                    <div class="panel-group" id="accordion_solicitante">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_solicitante" href="#solicitante">
                                        Datos Personales
                                    </a>
                                </h4>
                            </div>
                            <div id="solicitante" class="accordion-body collapse in">
                                <div class="panel-body" id="solicitante_content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= $form->field($model, 'idconcurso')->dropdownList(
                                                $concursoOptions,
                                                [
                                                    'id' => 'idconcurso',
                                                    'prompt' => [
                                                        'text' => 'Seleccione opción...',
                                                        'options' => ['disabled' => true, 'selected' => true]
                                                    ],
                                                    'disabled' => !$model->isNewRecord
                                                ]
                                            )
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'apellido')->textinput(['readonly' => !$model->isNewRecord, 'onkeyup' => 'this.value = this.value.toUpperCase();',]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'nombre')->textinput(['readonly' => !$model->isNewRecord, 'onkeyup' => 'this.value = this.value.toUpperCase();',]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'documento')->textInput(['readonly' => !$model->isNewRecord, 'maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'legajo')->textInput(['readonly' => !$model->isNewRecord, 'maxlength' => true]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'telefono')->textInput(['readonly' => !$model->isNewRecord, 'maxlength' => true]) ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'domicilio_fiscal')->textInput(['readonly' => !$model->isNewRecord, 'maxlength' => true]) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?= $form->field($model, 'mail')->textInput(['readonly' => !$model->isNewRecord, 'maxlength' => true]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!$model->isNewRecord) : ?>
                        <div class="panel-group" id="accordion_vacantes">
                            <div class="panel panel-accordion">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vacantes" href="#vacantes">
                                            Vacantes
                                        </a>
                                    </h4>
                                </div>
                                <div id="vacantes" class="accordion-body collapse in">
                                    <div class="panel-body" id="vacantes_content">
                                        <?php $postulaciones = $model->getPostulaciones(); ?>
                                        <?php if (count($postulaciones) > 0) : ?>
                                            <ul>
                                                <?php foreach ($postulaciones as $postulacion) : ?>
                                                    <li>
                                                        <?php if (isset($postulacion->vacante)) : ?>
                                                            <p>
                                                                <?php if (isset($postulacion->vacante->categoria0)) : ?>
                                                                    <strong>Categoría:</strong> <?= $postulacion->vacante->categoria0->descripcion ?><br />
                                                                <?php endif; ?>
                                                                <?php if (isset($postulacion->estado0)) : ?>
                                                                    <strong>Estado actual:</strong> <?= $postulacion->estado0->descripcion ?><br />
                                                                <?php endif; ?>
                                                                <?php if (!is_null($postulacion->puntaje)) : ?>
                                                                    <strong>Puntaje:</strong> <?= $postulacion->puntaje ?><br />
                                                                <?php endif; ?>
                                                            </p>
                                                            <hr>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>

                                            </ul>
                                        <?php else : ?>
                                            La solicitud no tiene asociada ninguna postulación.
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="panel-group" id="accordion_adjunto">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_adjunto" href="#adjunto">
                                        Documentación
                                    </a>
                                </h4>
                            </div>
                            <div id="adjunto" class="accordion-body collapse in">
                                <div class="panel-body" id="adjunto_content">
                                    <div class="row">
                                        <div class='col-md-6'>
                                            <?=
                                            $form
                                                ->field(
                                                    $model,
                                                    'deudores_morosos'
                                                )
                                                ->widget(
                                                    FileInput::class,
                                                    [
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
                                                                'pdf',
                                                            ],
                                                            'showCaption' => false,
                                                            'showRemove' => false,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialPreview' => array_key_exists('deudores_morosos', $initialPreview) ? $initialPreview['deudores_morosos'] : '',
                                                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                                            'initialPreviewFileType' => array_key_exists('extension_deudor', $initialPreview) ? $initialPreview['extension_deudor'] : '', // image is the default and can be overridden in config below
                                                            'initialPreviewDownloadUrl' => array_key_exists('deudores_morosos', $initialPreview) ? $initialPreview['deudores_morosos'] : '',
                                                            'overwriteInitial' => true,
                                                            'autoReplace' => true,
                                                            'fileActionSettings' => [
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                            ],
                                                        ],
                                                    ]
                                                );
                                            ?>
                                        </div>
                                        <div class='col-md-6'>
                                            <?=
                                            $form
                                                ->field(
                                                    $model,
                                                    'registro_violencia'
                                                )
                                                ->widget(
                                                    FileInput::class,
                                                    [
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
                                                                'pdf',
                                                            ],
                                                            'showCaption' => false,
                                                            'showRemove' => false,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialPreview' => array_key_exists('registro_violencia', $initialPreview)  ? $initialPreview['registro_violencia'] : '',
                                                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                                            'initialPreviewFileType' => array_key_exists('extension_violencia', $initialPreview) ? $initialPreview['extension_violencia'] : '', // image is the default and can be overridden in config below
                                                            'initialPreviewDownloadUrl' => array_key_exists('registro_violencia', $initialPreview) ? $initialPreview['registro_violencia'] : '',
                                                            'overwriteInitial' => true,
                                                            'autoReplace' => true,
                                                            'fileActionSettings' => [
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                            ],
                                                        ],
                                                    ]
                                                );
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class='col-md-6'>
                                            <?=
                                            $form
                                                ->field(
                                                    $model,
                                                    'antecedente_nacional'
                                                )
                                                ->widget(
                                                    FileInput::class,
                                                    [
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
                                                                'pdf',
                                                            ],
                                                            'showCaption' => false,
                                                            'showRemove' => false,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialPreview' => array_key_exists('antecedente_nacional', $initialPreview) ? $initialPreview['antecedente_nacional'] : '',
                                                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                                            'initialPreviewFileType' => array_key_exists('extension_antecedente', $initialPreview) ? $initialPreview['extension_antecedente'] : '', // image is the default and can be overridden in config below
                                                            'initialPreviewDownloadUrl' => array_key_exists('antecedente_nacional', $initialPreview) ? $initialPreview['antecedente_nacional'] : '',
                                                            'overwriteInitial' => true,
                                                            'autoReplace' => true,
                                                            'fileActionSettings' => [
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                            ],
                                                        ],
                                                    ]
                                                );
                                            ?>
                                        </div>
                                        <div class='col-md-6' id="titulo_div">
                                            <?=
                                            $form
                                                ->field(
                                                    $model,
                                                    'titulo'
                                                )
                                                ->widget(
                                                    FileInput::class,
                                                    [
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
                                                                'pdf',
                                                            ],
                                                            'showCaption' => false,
                                                            'showRemove' => false,
                                                            'showUpload' => false,
                                                            'showClose' => false,
                                                            'mainClass' => 'input-group-sm',
                                                            'maxFileSize' => 52428800, // 50MB
                                                            'previewFileType' => 'file',
                                                            'initialPreview' => array_key_exists('titulo', $initialPreview)  ? $initialPreview['titulo'] : '',
                                                            'initialPreviewAsData' => true, // identify if you are sending preview data only and not the raw markup
                                                            'initialPreviewFileType' => array_key_exists('extension_titulo', $initialPreview) ? $initialPreview['extension_titulo'] : '', // image is the default and can be overridden in config below
                                                            'initialPreviewDownloadUrl' => array_key_exists('titulo', $initialPreview) ? $initialPreview['titulo'] : '',
                                                            'overwriteInitial' => true,
                                                            'autoReplace' => true,
                                                            'fileActionSettings' => [
                                                                'showRemove' => false,
                                                                'showUpload' => false,
                                                            ],
                                                        ],
                                                    ]
                                                );
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!Yii::$app->request->isAjax) { ?>
                        <div class="row"><br />
                            <div class="col-md-12">
                                <a class="btn btn-info" href="index.php?r=mds_conc_solicitud" title="Volver">Volver</a> |
                                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </section>
        </div>
    </div>
</div>

<?php
$this->registerJs(
    "$(document).ready(function() {
        tituloRequerido();
        $('.kv-file-remove').hide();
    });
    "
);
?>

<script>
    function tituloRequerido() {
        $("#titulo_div").removeClass('required');
        if ('<?= $tituloRequerido ?>') {
            $("#titulo_div").addClass('required');
        }
    }
</script>