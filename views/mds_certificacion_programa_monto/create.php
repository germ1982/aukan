<?php
/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion_programa_monto */
$this->title = "Nuevo registro";

?>
<div class="mds-certificacion-programa-monto-create">
    <?= $this->render('_form', [
        'model' => $model,
        'listDirecciones'=> $listDirecciones,
    ]) ?>
</div>
