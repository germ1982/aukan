<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_r_diagnosticoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Diagnósticos';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="mds-r-diagnostico-index">
    <div id="ajaxCrudDatatable13">
        <?=GridView::widget([
            'id'=>'crud-datatable13',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>',                     
                    ['create','idvardimension'=>$idvardimension],
                    ['role'=>'modal-remote','title'=> 'Crear nuevo diagnóstico','class'=>'btn btn-default']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid'])
                    
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'default',  
                'heading' => null,
                'before'=>'',
                'after'=>null,
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModaldiag2",
    'size' => Modal::SIZE_LARGE,
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>


