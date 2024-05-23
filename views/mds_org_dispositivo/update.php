<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_dispositivo */
?>
<div class="mds-org-dispositivo-update">

    <?= $this->render('_form', [
        'model' => $model,
        'organigrama' => isset($organigrama) ? $organigrama:false,
    ]) ?>

</div>