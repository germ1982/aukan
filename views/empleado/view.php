<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Empleado $model */

$this->title = $model->idempleado;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="empleado-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'idempleado' => $model->idempleado], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'idempleado' => $model->idempleado], [
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
            'idempleado',
            'idpersona',
            'iddispositivo',
            'legajo',
            'email:email',
            'telefono',
            'foto',
            'activo',
            'categoria',
            'antiguedad_legal',
            'antiguedad_total',
            'ingreso_real',
            'ingreso_administrativo',
            'contratacion',
            'cuil',
            'funcion',
            'fichado',
            'afiliacion',
        ],
    ]) ?>

</div>
