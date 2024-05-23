<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="modal fade" id="modal_file_nota" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-nota">Adjuntar nota</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['action' => ['mds_legales_respuesta_estado/nota'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <input type="hidden" name="idrespuesta_para_comprobante_nota" id="idrespuesta_para_comprobante_nota">
                <input type="hidden" id="nota" name="nota">
                <div class="row form-group">
                    <div class="col-md-12">
                        <label>Número nota de la dependencia</label>
                        <input name="nro_nota_dependencia" id="nro_nota_dependencia" class="form-control">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label>Número de Vinculacion Judicial</label>
                        <input name="nro_vinculacion_judicial" id="nro_vinculacion_judicial" class="form-control">
                    </div>
                </div>
                <br>
                <div class="row form-group" id="dropzone-nota-container">
                    <div class="col-md-12">
                        <div class="dropzone needsclick dz-clickable" id="nota" name="mainFileUploader">
                            <div class="fallback">
                                <input name="file" type="file" />
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="row" id="adjunto_nota">
                    <div class="col-md-12">
                        <label>Nota adjunta actual:</label>
                        <ul style="list-style: none">
                            <li>
                                <a><i class="fas fa-paperclip"></i>
                                    <a id="nota_link" name="nota_link" href="" target="_blank"> Nota </a>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
                <div class="modal-footer">
                    <div class="row">
                        <?= Html::submitButton("Subir nota", ['class' => 'btn btn-success pull-left', 'id' => 'btn-subir-nota']) ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>