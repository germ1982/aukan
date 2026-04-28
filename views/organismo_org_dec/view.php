<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrganismoOrgDec */
?>
<div class="organismo-org-dec-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idorganismo',
            'iddecreto',
        ],
    ]) ?>

</div>
