<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_rum_cat_nov */
?>
<div class="mds-rum-cat-nov-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'descripcion',
            'activo',
        ],
    ]) ?>

</div>
