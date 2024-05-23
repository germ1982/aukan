<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_llamada */
?>
<div class="sds-800-llamada-update">

    <?= $this->render('_form', [
        'model' => $model,
        'listProvincias' => $listProvincias,
        'selectNacionalidad' => $selectNacionalidad,
        'selectGenero' => $selectGenero,
        'selectSituacionTipo' => $selectSituacionTipo,
        'selectDerivacion' => $selectDerivacion,
        'listprofesionales' => $listprofesionales
    ]) ?>
</div>