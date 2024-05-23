<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Sds_gis_capa_item;
/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_inv_entregaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mds Inv Entregas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="mds-inv-entrega-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns2.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i>', 
                    ['create','idpersona'=>$idpersona],
                    ['role'=>'modal-remote','title'=> 'Crear nueva Entrega','class'=>'btn btn-default']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).                    
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => false,
                'before'=>'',
                'after'=>'<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    
    'options' => [
        "id"=>"ajaxCrudModal",
        'tabindex' => false 
    ], 
    "size"=>"modal-sm",
    "footer"=>"",// always need it for jquery plugin
    
])?>
<?php Modal::end(); ?>
