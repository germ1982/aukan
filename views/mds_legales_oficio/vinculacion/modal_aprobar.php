<?php

use yii\widgets\ActiveForm;
use \bizley\quill\Quill;
use yii\helpers\Html;
?>

<div class="modal fade" id="modal_aprobar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-aprobar">Aprobar respuesta</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['action' => ['mds_legales_respuesta_estado/aprobar'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <input type="hidden" name="idrespuesta_para_aprobar" id="idrespuesta_para_aprobar">
                <div class="row">
                    <div class="col-md-12">
                        <p class="aprobar">¿Está seguro que desea aprobar la respuesta del requerimiento?</p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12" class="form-group">
                        <label>Observación (supervisión final): </label>
                        <?= Quill::widget([
                            'name' => 'observacion_final_aprobado',
                            'options' => [
                                'style' => 'height: 125px;',
                                'id' => 'observacion_final_aprobado',
                            ],
                        ]) ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <?= Html::submitButton("Aprobar", ['class' => 'btn btn-success pull-left', 'id' => 'btn-subir-archivo']) ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>