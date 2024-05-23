<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mds_relevamiento_respuesta */

$this->title = $model->idrelevamientorespuesta;
$this->params['breadcrumbs'][] = ['label' => 'Mds Relevamiento Respuestas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mds-relevamiento-respuesta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->idrelevamientorespuesta], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->idrelevamientorespuesta], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idrelevamientorespuesta',
            'idrelevamientoregistro',
            'iditem',
            'posee',
            'detalle:ntext',
            'idusuario_carga',
            'idusuario_borra',
            'created_at',
            'updated_at',
            'deleted_at',
        ],
    ]) ?>

</div>
