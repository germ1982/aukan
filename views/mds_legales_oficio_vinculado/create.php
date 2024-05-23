<?php

/* @var $this yii\web\View */
/* @var $model app\models\Mds_legales_oficio_vinculado */

?>
<div class="mds_legales_oficio_vinculado-create">
    <?= $this->render('_form', [
        'model' => $model,
        'listParentesco' => $listParentesco,
        'listTipoDocumento' => $listTipoDocumento,
        'idlegalesoficio' => $idlegalesoficio,
        'tipoGenero' => $tipoGenero,
        'tipoNacionalidad' => $tipoNacionalidad,
    ]) ?>
</div>