<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_ent_solicitud_intermedia */
if(!isset($messageOk)){
    $messageOk=false;
}
if(!isset($receptorEntrega)){
    $receptorEntrega='';
}
if(!isset($urlEntrega)){
    $urlEntrega='';
}
if(!isset($permiso_entrega)){
    $permiso_entrega=null;
}
?>
<div class="sds-ent-solicitud-intermedia-update">

    <?= $this->render('_form', [
        'model' => $model,
        'messageOk' => $messageOk,
        'permiso_entrega' => $permiso_entrega,
        'receptorEntrega' => $receptorEntrega,
        'urlEntrega' => $urlEntrega
    ]) ?>

</div>
