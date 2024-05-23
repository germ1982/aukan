<?php

/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion_programa */

?>
<div class="mds-certificacion-programa-create">
    <?= $this->render('_form', [
        'model' => $model,
        'listDirecciones' => $listDirecciones,
        'listProgramas' => $listProgramas,
        'listTipoSubsidio' => $listTipoSubsidio,
        'model_certificacion_programa_monto' => $model_certificacion_programa_monto,
        'listTipoAdjuntos' => $listTipoAdjuntos,
        'listRequisitos' => $listRequisitos,
        'cantidadNiveles' => $cantidadNiveles,
    ]) ?>
</div>