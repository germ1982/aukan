<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_relevamiento_respuesta */

$this->title = 'Create Mds Relevamiento Respuesta';
$this->params['breadcrumbs'][] = ['label' => 'Mds Relevamiento Respuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-relevamiento-respuesta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
