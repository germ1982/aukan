<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PersonaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="persona-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Persona', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'idpersona',
            'documento',
            'documento_tipo',
            'nacionalidad',
            'genero',
            //'fecha_nacimiento',
            //'nombre',
            //'apellido',
            //'padre',
            //'conviviente',
            //'domicilio',
            //'domicilio_calle',
            //'domicilio_numero',
            //'idlocalidad',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
