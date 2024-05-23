<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_seg_usuario */
?>
<div class="mds-seg-usuario-update">

    <?= $this->render('_form', [
        'model' => $model,
        'listDireccionesCertificaciones' => $listDireccionesCertificaciones
    ]) ?>

</div>
