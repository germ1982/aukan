<?php

/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion_direccion */

?>
<div class="mds-certificacion-direccion-create">
    <?= $this->render('_form', [
        'model' => $model,
        //'model_director' => $model_director,
        'listDirecciones' => $listDirecciones,
        'listDirectores' => $listDirectores,
        'listNivelAutorizacion' => $listNivelAutorizacion,
        'listFuncion' => $listFuncion
    ]) ?>
</div>