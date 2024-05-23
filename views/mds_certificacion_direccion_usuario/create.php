<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_certificacion_direccion_usuario */

?>
<div class="mds-certificacion-direccion-usuario-create">
    <?= $this->render('_form', [
        'model' => $model,
        'listDirecciones' => $listDirecciones,
        'listUsuarios' => $listUsuarios
    ]) ?>
</div>