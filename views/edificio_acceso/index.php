<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EdificioAccesoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Edificio Accesos';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<style>
    .custom-grid {
        font-size: 13px;
        /* Cambia el tamaño según tus necesidades */
    }

    .kv-grid-toolbar .btn {
        height: 30px;
        /* Ajusta la altura de todos los botones */
        line-height: 1.42857143;
        /* Esto centra el contenido verticalmente */


    }

    .kv-grid-toolbar {

        display: flex;
        /* background-color: red; */

    }

    .btn-toolbar {
        width: 100%;

    }

    .btn-group {
        width: 100%;

    }

    .botones_a {
        text-align: left;
        display: flex;
        gap: 10px;
    }

    .botones_b {
        justify-content: flex-end;
        /* Alineación derecha */
        display: flex;
        width: 100%;
        /* Asegura que el contenedor ocupe todo el ancho de la columna */
    }
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="neon fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>


<div class="edificio-acceso-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'custom-grid'],
            'filterModel' => $searchModel,
            'pjax' => false,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                [
                    'content' =>
                    '<div class="row">' .
                        '<div class="col-md-9"> 
                                    <div class="botones_a">' .
                        Html::a(
                            'Edificio',
                            ['/edificio'],
                            ['title' => 'Edificio', 'class' => 'btn btn-primary neon']
                        ) .

                        '</div>
                                </div>' .
                        '<div class="col-md-3"> 
                                    <div class="botones_b">' .

                        Html::a(
                            '<i class="glyphicon glyphicon-plus"></i>',
                            ['create'],
                            ['role' => 'modal-remote', 'title' => 'Nuevo', 'class' => 'btn btn-default']
                        ) .



                        Html::a(
                            '<i class="glyphicon glyphicon-repeat"></i>',
                            [''],
                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                        ) .
                        '{toggleData}' .
                        '{export}' .
                        '</div>
                                </div>' .
                        '</div>'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => false,
            'panel' => [
                'type' => 'primary',
                'heading' => false,
                'after' => '<div class="clearfix"></div>',
            ]
        ]); ?>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>