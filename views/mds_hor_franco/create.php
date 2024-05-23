<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mds_hor_franco */

?>
<div class="mds-hor-franco-create">
    <?= $this->render('_form', [
        'model' => $model,
        'tipos_franco' => $tipos_franco,
        'errors' => (isset($errors)?$errors:null)
    ]) ?>
</div>
