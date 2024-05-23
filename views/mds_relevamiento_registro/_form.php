<?php

use kartik\select2\Select2;
use yii\bootstrap\Collapse;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;

?>
<style>
    div.required label:after {
        content: " *";
        color: red;
    }

    textarea {
        outline: none !important;
        border: 1px solid #e5e7e9;
    }
</style>
<div class="mds-relevamiento-registro-form">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="panel-body">
                <?php echo Collapse::widget([]); ?>
                <div class="panel-group">
                    <div class="panel panel-accordion">
                        <div class="accordion-body collapse in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'idcapaitem')->widget(
                                            Select2::class,
                                            [
                                                'data' => $edificiosFilter,
                                                'options' => [
                                                    'placeholder' => 'Seleccione',
                                                    'id' => 'cmb_capa'
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ]
                                            ]
                                        )->label('<b>Edificio</b>');
                                        ?>
                                    </div>
                                    <div class="col-md-6">
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
                                        echo $form->field($model, 'fecha')->widget(DatePicker::class, [
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
                                        ])->label('<b>Fecha</b>');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php foreach ($agrupadores as $agrupador) { ?>
                    <div class="panel-group">
                        <div class="panel panel-accordion" id="accordion_<?= $agrupador['descripcion'] ?>">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_<?= $agrupador['descripcion'] ?>" href="#detalle_<?= $agrupador['descripcion'] ?>">
                                        <i class="glyphicon glyphicon-menu-down"></i>
                                        <?= $agrupador['titulo'] ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="detalle_<?= $agrupador['descripcion'] ?>" class="accordion-body collapse <?= $model->isNewRecord ? '' : 'in' ?>">
                                <div class="panel-body" id="detalle_content">
                                    <!--- aca entramos si es nuevo registro  -->
                                    <?php if (!is_array($model_respuesta)) { ?>
                                        <?php foreach ($model->getItem($agrupador['idconfiguraciontipo']) as $item) { ?>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6">
                                                    <label><b><?= $item['descripcion'] ?>:</b></label>
                                                    <select name="respuesta[<?= $item['idconfiguracion'] ?>]" id="respuesta[<?= $item['idconfiguracion'] ?>]" style="width: 100%">
                                                        <option value="">Seleccione</option>
                                                        <option value="1">Si</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                    <label><b>Detalle</b></label>
                                                    <textarea name="detalle[<?= $item['idconfiguracion'] ?>]" rows="2" placeholder="Ingresar detalle" id="detalle[<?= $item['idconfiguracion'] ?>]" style="width:100%;"></textarea>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <!--- aca entramos para actualizar  -->
                                        <?php foreach ($model_respuesta as $item) { ?>
                                            <?php if ($agrupador['idconfiguraciontipo'] == $item['idconfiguraciontipo']) { ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6">
                                                        <label><b><?= $item['descripcion'] ?>:</b></label>
                                                        <select name="respuesta[<?= $item['iditem'] ?>]" id="respuesta[<?= $item['iditem'] ?>]" style="width:100%;">
                                                            <option value="" <?php echo ($item['posee'] === null) ? 'selected' : ($item['posee'] == 1 ? '' : '') ?>>Seleccione</option>
                                                            <option value="1" <?php echo $item['posee'] === null ? '' : ($item['posee'] == 1 ? 'Selected' : '') ?>>Si</option>
                                                            <option value="0" <?php echo $item['posee'] === null ? '' : ($item['posee'] == 1 ? '' : 'Selected') ?>>No</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <label><b>Detalle</b></label>
                                                        <textarea name="detalle[<?= $item['iditem'] ?>]" rows="2" placeholder="Ingresar detalle" id="detalle[<?= $item['iditem'] ?>]" style="width:100%;"><?php echo $item['detalle'] ?></textarea>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="panel-group">
                    <div class="panel panel-accordion">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Observaciones
                                </a>
                            </h4>
                        </div>
                        <div class="accordion-body collapse in">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'observaciones')->textarea(array('rows' => 10, 'placeholder' => 'ingrese'))->label(''); ?>
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
                                    Documentación Adjunta
                                    <i class="glyphicon glyphicon-menu-down"></i>
                                </a>
                            </h4>
                        </div>
                        <div id="adjuntos" class="accordion-body collapse in">
                            <div class="panel-body" id="adjuntos_content">
                                <div class="row" id="adjunto_container">
                                    <div class="col-md-12">
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
                        </div>
                    </div>
                </div>

                <a class="btn btn-info" href="index.php?r=mds_relevamiento_registro/index">Volver</a>
                |
                <?= Html::submitButton($model->isNewRecord ? 'Guardar' : 'Actualizar', ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
            </div>
        </div>
    </div>
</div>
</section>
<?php
Modal::begin([
    'header' => '<h4 id="header_abm"></h4>',
    'id' => 'modal_abm',
    'size' => 'modal-md',
]);

echo "<div id='content_abm'></div>";

Modal::end();


?>