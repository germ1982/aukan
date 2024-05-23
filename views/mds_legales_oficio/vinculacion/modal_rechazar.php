<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="modal fade" id="modal_rechazar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-rechazar">Devolver respuesta</h4>
            </div>
            <div class="modal-body">
                <p>Observación:</p>
                <?php $form = ActiveForm::begin(['action' => ['mds_legales_respuesta_estado/rechazar'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <input type="hidden" name="idlegalesrespuesta_para_rechazar" id="idlegalesrespuesta_para_rechazar">
                <div class="row">
                    <div class="col-md-12" class="form-group">
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="6"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton("Devolver", ['class' => 'btn btn-danger pull-left', 'id' => 'btnDevolver']) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>