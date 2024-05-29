<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


?>
<div class="usuarios-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email:email',
            'avatar',
            'status',
            'password',
            'activo',
            'idpersona',
        ],
    ]) ?>

</div>
