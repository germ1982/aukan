<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_data_categoria */
?>
<div class="mds-data-categoria-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idcategoria',
            'nombre',
            'descripcion',
            'icono',
            'imagen_fondo',
        ],
    ]) ?>

</div>
