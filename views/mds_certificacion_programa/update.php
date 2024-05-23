<?php

use yii\widgets\ActiveForm;

$this->title = "Actualizar registro #{$model->idcertificacionprograma}";
?>
<?php $form = ActiveForm::begin(['action' => ["mds_certificacion_programa/update", 'id' => $model->idcertificacionprograma], 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="mds-certificacion-programa-update">
    <?= $this->render('_form', [
        'form' => $form,
        'model' => $model,
        'listDirecciones' => $listDirecciones,
        'listProgramas' => $listProgramas,
        'listTipoSubsidio' => $listTipoSubsidio,
        'model_certificacion_programa_monto' => $model_certificacion_programa_monto,
        'listTipoAdjuntos' => $listTipoAdjuntos,
        'selectAdjuntos' => $selectAdjuntos,
        'listRequisitos' => $listRequisitos,
        'selectRequisitos' => $selectRequisitos,
        'cantidadNiveles' => $cantidadNiveles,
        'selectAdjuntosSugeridos' => $selectAdjuntosSugeridos,
    ]) ?>
</div>
<?php ActiveForm::end(); ?>
