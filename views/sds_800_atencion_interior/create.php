<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sds_800_atencion_interior */

?>
<div class="sds-800-atencion-interior-create">
    <?= $this->render('_form', [
        'model' => $model,
        'listProvincias' => $listProvincias,
        'listLocalidad' => $listLocalidad,
        'listLocalidad1' => $listLocalidad1,
        'listNacionalidad' => $listNacionalidad,
        'listGenero' => $listGenero,
        'listParentesco' => $listParentesco,
    ]) ?>
</div>