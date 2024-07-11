<?php

use app\models\Empleado;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\EmpleadoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Empleados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="empleado-index">

    <h1><?= Html::encode($this->title) ?></h1>
aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    <p>
        <?= Html::a('Create Empleado', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idempleado',
            'idpersona',
            'iddispositivo',
            'legajo',
            'email:email',
            //'telefono',
            //'foto',
            //'activo',
            //'categoria',
            //'antiguedad_legal',
            //'antiguedad_total',
            //'ingreso_real',
            //'ingreso_administrativo',
            //'contratacion',
            //'cuil',
            //'funcion',
            //'fichado',
            //'afiliacion',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Empleado $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'idempleado' => $model->idempleado]);
                 }
            ],
        ],
    ]); ?>


</div>
