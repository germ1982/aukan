<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_entrega_solicitud */
?>
<div class="sds-stk-entrega-solicitud-update">
    <?= $this->render('_form', [
        'model' => $model,
        'persona' => $persona,
        'persona_datos' => (isset($persona_datos)?$persona_datos:null)

    ]) ?>

</div>
