<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sds_com_configuracion_tipo */
?>
<div class="sds-com-configuracion-tipo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idconfiguraciontipo',
            'descripcion',
            'activo',
        ],
    ]) ?>

</div>
