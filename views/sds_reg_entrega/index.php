<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use yii\helpers\Url;

$title_for_new = 'Nueva Entrega';

if (isset($_GET['idregistro']))
    {
        $idregistro = $_GET['idregistro'];
        $this->title = "Entregas vinculadas al registro numero: $idregistro";
        $url =  Url::to(['/sds_reg_entrega/create', 'idregistro' => $idregistro]);
        $boton = Html::a//aca tengo que ver como le paso como parametro el idregistro
        (
            '<i class="glyphicon glyphicon-plus"></i>',
            $url,
            ['role' => 'modal-remote', 'title' => "$title_for_new vinculada al registro numero $idregistro", 'class' => 'btn btn-default']
        ) ;
    }
else
    {
        
        $idregistro = '';
        $this->title = 'Entregas';
        $boton ='';
    }



$this->params['breadcrumbs'][] = $this->title;
$clase = 'sds_reg_entrega-index';




CrudAsset::register($this);

?>
<style>
    .table>thead>tr>td.info,
    .table>tbody>tr>td.info,
    .table>tfoot>tr>td.info,
    .table>thead>tr>th.info,
    .table>tbody>tr>th.info,
    .table>tfoot>tr>th.info,
    .table>thead>tr.info>td,
    .table>tbody>tr.info>td,
    .table>tfoot>tr.info>td,
    .table>thead>tr.info>th,
    .table>tbody>tr.info>th,
    .table>tfoot>tr.info>th {
        color: #777;
        background-color: #fafafa !important;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
    }
</style>

<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span><?= $this->title ?></span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="<?= $clase ?>">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => 
                                [
                                    [
                                        'content' =>
                                        $boton.
                                            Html::a
                                                (
                                                    '<i class="glyphicon glyphicon-repeat"></i>',
                                                    [''],
                                                    ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                                ) .
                                            '{export}'
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
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })"
);
?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
