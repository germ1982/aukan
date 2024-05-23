<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<div class="modal fade" id="modal_visto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-warning">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal">Confirmar</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['id' => 'form-response', 'action' => ['mds_certificacion_visto/store', 'llamadoDesde' => 'view']]); ?>
                <input type="hidden" id="idcertificacion_visto" name="idcertificacion_visto" value="">
                <input type="hidden" name="sector_visto" id="sector_visto" value="<?= $area ?>">
                <div class="row" style="padding:15px">
                    ¿Está seguro que desea marcar como <b>"visto"</b> esta certificación?
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton("De acuerdo", ['class' => 'btn btn-warning pull-left', 'id' => 'btnStoreVisto']) ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>