<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_stk_entrega_solicitud */

?>
<div class="sds-stk-entrega-solicitud-create">

<?php 
//no tocar
if(isset($persona)){
    echo $this->render('_form', [
        'model' => $model,
        'persona' => $persona
    ]);
}else{
    echo $this->render('_form', [
        'model' => $model
    ]);
}
?>
</div>
