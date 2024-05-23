<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_relevamiento_respuesta */

$this->title = 'Update Mds Relevamiento Respuesta: ' . $model->idrelevamientorespuesta;
$this->params['breadcrumbs'][] = ['label' => 'Mds Relevamiento Respuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idrelevamientorespuesta, 'url' => ['view', 'id' => $model->idrelevamientorespuesta]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mds-relevamiento-respuesta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
