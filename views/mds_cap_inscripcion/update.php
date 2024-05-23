<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_inscripcion */
?>
<div class="mds-cap-inscripcion-update">

    <?= $this->render('_form', [
        'model' => $model,
        'filterInstancias' => $filterInstancias
    ]) ?>

</div>
