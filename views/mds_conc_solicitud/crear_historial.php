<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_conc_solicitud */

?>
<div class="mds-conc-solicitud-create">
    <?= $this->render('_form_historial', [
        'model' => $model,
        'estadosTipos' => $estadosTipos
    ]) ?>
</div>