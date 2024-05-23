<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_relevamiento_respuestaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Relevamiento Respuestas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mds-relevamiento-respuesta-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Mds Relevamiento Respuesta', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idrelevamientorespuesta',
            'idrelevamientoregistro',
            'iditem',
            'posee',
            'detalle:ntext',
            //'idusuario_carga',
            //'idusuario_borra',
            //'created_at',
            //'updated_at',
            //'deleted_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
