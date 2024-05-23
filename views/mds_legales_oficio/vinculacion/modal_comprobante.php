<?php

use yii\widgets\ActiveForm;
use \bizley\quill\Quill;
use yii\helpers\Html;
?>

<div class="modal fade" id="modal_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-comprobante">Subir comprobante</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['action' => ['mds_legales_respuesta_estado/comprobante'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <input type="hidden" name="idrespuesta_para_comprobante" id="idrespuesta_para_comprobante">
                <input type="hidden" id="comprobante" name="comprobante">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Número de nota</label>
                            <input name="nro_nota" id="nro_nota" class="form-control">
                        </div>
                    </div>
                </div>
                <br>
                <div class="form-group" id="dropzone-comprobante-container">
                    <div class="dropzone needsclick dz-clickable" id="adjunto_comprobante" name="mainFileUploader">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </div>
                    <br>
                </div>
                <div class="row" id="observacion-comprobante-container">
                    <div class="col-md-12" class="form-group">
                        <label>Observación (supervisión final): </label>
                        <?= Quill::widget([
                            // 'allowResize' => true,
                            'name' => 'observacion_final',
                            'options' => [
                                'style' => 'height: 125px;',
                                'id' => 'observacion_final',
                            ],
                        ]) ?>
                    </div>
                    <br>
                </div>
                <div class="row" id="adjunto_comprobante-container">
                    <div class="col-md-12">
                        <label>Comprobante adjunto:</label>
                        <ul style="list-style: none">
                            <li>
                                <a><i class="fas fa-paperclip"></i>
                                    <a id="comprobante_link" name="comprobante_link" href="" target="_blank"> Comprobante </a>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
                <div class="modal-footer">
                    <div class="row">
                        <?= Html::submitButton("Subir Archivo", ['class' => 'btn btn-success pull-left', 'id' => 'btn-subir-archivo']) ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>