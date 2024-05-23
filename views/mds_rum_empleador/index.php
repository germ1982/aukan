<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Mds_seg_usuario_rol;
use app\components\AccessRule;
use yii\filters\AccessControl;
use app\models\Mds_seg_item;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_rum_empleadorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RUMBO:: Empleadores';
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
<div class="mds-rum-empleador-index">
    <div id="ajaxCrudDatatable">
        <?php
            $el_usuario = Yii::$app->user->identity;
            // analizando el rol
            $un_rol_usuario=Mds_seg_usuario_rol::find()                                                              
           ->where(['idusuario' => $el_usuario->idusuario])
           ->andWhere(["idrol"=> 38] )
           ->one();  
           if ($un_rol_usuario == null)
           { 
                echo GridView::widget([
                    'id'=>'crud-datatable',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'pjax'=>true,
                    'columns' => require(__DIR__.'/_columns.php'),
                    'toolbar'=> [
                        ['content'=>
        
                            Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                            ['role'=>'modal-remote','title'=> 'Create new Mds Rum Empleadors','class'=>'btn btn-default']).
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                            ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                            '{toggleData}'.
                            '{export}'
                        ],
                    ],          
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,          
                    'panel' => [
                        'type' => 'default', 
                        'heading' => '',
                        'before'=>'',
                        'after'=>'<div class="clearfix"></div>',
                    ]
                ]);
           }
           else
           {
                echo 
                GridView::widget([
                    'id'=>'crud-datatable',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'pjax'=>true,
                    'columns' => require(__DIR__.'/_columns.php'),
                    'toolbar'=> [
                        ['content'=>
        
                            
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                            ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                            '{toggleData}'.
                            '{export}'
                        ],
                    ],          
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,          
                    'panel' => [
                        'type' => 'default', 
                        'heading' => '',
                        'before'=>'',
                        'after'=>'<div class="clearfix"></div>',
                    ]
                ]);

           }
        ?>        
    </div>
</div>
</div>
</section>
</div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
    'size' => Modal::SIZE_LARGE,
])?>
<?php Modal::end(); ?>
