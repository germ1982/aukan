<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_informe */
/* @var $form yii\widgets\ActiveForm */


$this->title = $model->isNewRecord ? 'Nuevo Informe' : "Modificar Informe N° $model->idinforme";
$this->params['breadcrumbs'][] = $this->title;
$colorAlerta = $model->isNewRecord || (!$model->isNewRecord && $cantMaxCompartidos) ? "info" : "warning";
?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }

    .campo {
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .alert-detalle {
        color: #555555;
        background-color: #efefef;
        border-color: lightgray;
    }

    .select2,
    .select2-container,
    .select2-container--krajee {
        z-index: 0 !important;
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

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-org-informe-form">
                    <div class="alert alert-<?= $colorAlerta ?>" role="alert">
                        <ul>
                            <?php if ($model->isNewRecord) : ?>
                                <li>Una vez guardado <b>NO se podrá editar la información registrada</b>. Solamente se podrá <b>compartir con más usuarios</b> mediante el botón <b>"Editar"</b> (desde el listado de informes).</li>
                            <?php endif; ?>
                            <?php if ($cantMaxCompartidos) : ?>
                                <li>Se puede compartir a un <b>máximo de <?= $cantMaxCompartidos ?> usuarios</b>.</li>
                            <?php else : ?>
                                <li><b>Se alcanzó la cantidad máxima de usuarios a compartir.</b></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php $form = ActiveForm::begin(); ?>
                    <input type="hidden" id="adjuntos" name="Mds_org_informe[adjuntos]">
                    <input type="hidden" id="adjuntos_eliminados" name="adjuntos_eliminados">
                    <div class="row">
                        <?php if (!$model->isNewRecord) : ?>
                            <div class="col-md-12" style="margin-bottom: 15px;">
                                <label class="form-label">Usuario:</label>
                                <input type="text" class="form-control" value="<?= $model->getNombrePersona($model->idusuario)  ?>" readonly>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'tipo')->widget(Select2::class, [
                                'data' => $tiposInforme,
                                'options' => [
                                    'prompt' => 'Seleccione Tipo...',
                                    'disabled' => $model->isNewRecord ? false : true
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                            <?= $form->field($model, 'idorganismo')->widget(Select2::class, [
                                'data' => $organismos,
                                'options' => [
                                    'placeholder' => 'Seleccione Organismo...',
                                    'id' => 'cmb_organismo',
                                    'onchange' =>   'cargarDispositivos();',
                                    'disabled' => $model->isNewRecord ? false : true
                                ],

                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                            <?= $form->field($model, 'iddispositivo')->widget(Select2::class, [
                                'data' => ['' => ''],
                                'options' => [
                                    'prompt' => 'Seleccione Dispositivo...',
                                    'id' => 'cmb_dispositivo',
                                    'onchange' =>   'selectDispositivo();',
                                    'disabled' => $model->isNewRecord ? false : true
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                            ?>
                            <?php
                            $labelUsuarios = 'Compartido con:';
                            if (!$model->isNewRecord) :
                                $labelUsuarios = 'Agregar nuevos usuarios:'; ?>
                                <?php if (count($compartidos) > 0) : ?>
                                    <div class="form-group">
                                        <label class="form-label">Compartido con:</label>
                                        <ul>
                                            <?php foreach ($compartidos as $compartido) : ?>
                                                <?php if ($compartido->idusuario0) :
                                                    $visto = "";
                                                    if ($compartido->visto == 2) {
                                                        $vistoFecha = $compartido->visto_fecha ? date('d/m/Y H:i', strtotime($compartido->visto_fecha)) : null;
                                                        $visto = $vistoFecha ? " (visto el día: $vistoFecha)" : " (visto)";
                                                    } ?>
                                                    <li>
                                                        <?= mb_strtoupper($compartido->idusuario0->apellido) . ', ' . mb_strtoupper($compartido->idusuario0->nombre) .  "<b>$visto</b>" ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($cantMaxCompartidos) : ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?=
                                        $form->field($model, 'informes')->widget(Select2::class, [
                                            'data' => $usuarios,
                                            'options' => ['id' => 'informes', 'placeholder' => '', 'multiple' => true,],
                                            'size' => Select2::MEDIUM,
                                            'pluginOptions' => [
                                                'tags' => true,
                                                'allowClear' => true,
                                                'maximumSelectionLength' => $cantMaxCompartidos
                                            ],
                                            'showToggleAll' => false,
                                        ])->label($labelUsuarios);
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?= $form->field($model, 'asunto')->textInput([
                                'maxlength' => true, 'disabled' => $model->isNewRecord ? false : true
                            ]) ?>
                        </div>
                    </div>
                    <?php if ($model->isNewRecord) : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'detalle')->widget(\bizley\quill\Quill::class, [
                                    'options' => [
                                        'style' => 'height: 30rem;',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Detalle:</label>
                                <div class="alert alert-detalle" role="alert">
                                    <p><?= $model->detalle;  ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($model->isNewRecord) : ?>
                        <label><strong>Documentos (adjuntar de a UN archivo a la vez)</strong></label>
                        <div>
                            <div class="dropzone needsclick dz-clickable" id="adjuntos" name="mainFileUploader">
                                <div class="fallback">
                                    <input name="file" type="file" />
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <?php if (!empty($model->adjuntos)) { ?>
                            <div class="col-md-12" style="margin: 1rem 0 2rem 0;">
                                <label>Archivos adjuntos:</label>
                                <?php
                                foreach ($model->adjuntos as $adjunto) { ?>
                                    <ul style="list-style: none">
                                        <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto['nombre'], Url::base() . '/uploads/informes/' . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                    </ul>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    <?php endif; ?>
                    <div class="row justify-content-between">
                        <br>
                        <div class="col-md-6">
                            <?php if ($urlAnterior) : ?>
                                <a class="btn btn-info" href="<?= $urlAnterior ?>">Volver</a>
                            <?php endif; ?>
                        </div>
                        <?php if (!Yii::$app->request->isAjax && $cantMaxCompartidos) : ?>
                            <div class="col-md-6 text-right">
                                <div class="form-group">
                                    <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => 'btn btn-success']) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
$this->registerJs(
    "$(document).ready(function() {    
        preCargarDispositivos();
    });
    "
);
?>
<script>
    function cargarDispositivos() {
        if ($("#cmb_organismo").val()) {
            $.post("index.php?r=mds_org_dispositivo/cmb_dispositivo&idorganismo=" + $("#cmb_organismo").val(), function(data) {
                $("select#cmb_dispositivo").html(data);
                $("#cmb_dispositivo").val(null).trigger('change');
            });
        }
    }

    function selectDispositivo() {
        if ($("#cmb_dispositivo").val()) {
            $.post("index.php?r=mds_org_informe/get_idorganismo&iddispositivo=" + $("#cmb_dispositivo").val(), function(data) {
                $("#cmb_organismo").val(data);
            });
        }
    }

    function preCargarDispositivos() {
        if (<?php echo $model->iddispositivo ?> > 0) {
            $.post("index.php?r=mds_org_dispositivo/cmb_dispositivo&idorganismo=" + $("#cmb_organismo").val(), function(data) {
                $("select#cmb_dispositivo").html(data);
                $("#cmb_dispositivo").val(<?php echo $model->iddispositivo ?>);
            });
        }
    }
</script>