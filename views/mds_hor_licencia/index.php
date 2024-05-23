<script type="text/javascript">
    function AbrirExcell() {
        aux = document.getElementById("VarIdUsuarioCarga").value;
        location.href = "../web/AutoSolicitud/Exell/index.php?idusuario=" + aux;
    }
</script>

<?php
//$this->registerJsFile('xlsx.full.min.js');

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

$idusuario = Yii::$app->user->identity->idusuario;
echo "<input type='hidden' id='VarIdUsuarioCarga' name='VarIdUsuarioCarga' value='$idusuario'>";
/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_hor_licenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Licencias';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
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
                <div class="mds-hor-licencia-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>
                                    Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i> Lic. S.G.H',
                                        ['create_lsgh'],
                                        ['role' => 'modal-remote','title' => 'Nueva Licencia Sin Goce de Haberes', 'class' => 'btn btn-primary','label'=>'algo']
                                    ).
                                    Html::a(
                                        '<i class="glyphicon glyphicon-import"></i> Importar Excel',
                                        ['create'],
                                        ['role' => 'modal-remote','title' => 'Importar Licencias', 'class' => 'btn btn-default','label'=>'algo']
                                    ). 
                                    Html::a(
                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                        [''],
                                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']
                                    ) .
                                    '{toggleData}' .
                                    '{export}'],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'panel' => [
                                'type' => 'primary',
                                'heading' =>' ',
                                'after' => '<div class="clearfix"></div>',
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'size'=> modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer" => "", // always need it for jquery plugin
    
]) ?>
<?php Modal::end(); ?>