<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_contacto */

?>
<div class="mds-org-contacto-create">
    <?= $this->render('_form', [
        'model' => $model,
        'organigrama' => isset($organigrama) ? $organigrama:0,
    ]) ?>
</div>
