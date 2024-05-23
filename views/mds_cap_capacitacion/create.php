<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_cap_capacitacion */

?>
<div class="mds-cap-capacitacion-create">
    <?= $this->render('_form', [
        'model' => $model,
        'filterOrganismos' => $filterOrganismos,
        'interno' => $interno
    ]) ?>
</div>
