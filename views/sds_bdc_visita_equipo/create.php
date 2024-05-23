<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_bdc_visita_equipo */

?>
<div class="sds-bdc-visita-equipo-create">
    <?= $this->render('_form', [
        'model' => $model,
        'equipos' => $equipos,
        'responsables' => $responsables,
    ]) ?>
</div>
