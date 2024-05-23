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
if(!isset($id_solicitud)){
    $id_solicitud=null;
}
?>
<div class="sds-ent-solicitud-intermedia-create">
    <?= $this->render('_form', [
        'model' => $model,
        'messageOk' => $messageOk,
        'permiso_entrega' => $permiso_entrega,
        'receptorEntrega' => $receptorEntrega,
        'urlEntrega' => $urlEntrega,
        'id_solicitud' => $id_solicitud
    ]) ?>
</div>