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

use app\models\Mds_rum_postulacion;//nuevo

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_rum_postulacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

CrudAsset::register($this);
//$model_postulados = Mds_rum_postulacion::findOne($searchModel->padre);// debe ser el id que se envia desde intervencion 
//$this->title = ($model_padre->nombre . ' ' . $model_padre->apellido) . ' - Listado de Hijos ';

$this->title = 'RUMBO::Postulaciones a Ofertas Laborales'; 
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="mds-rum-postulacion-index">
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
                'columns' => require(__DIR__.'/_columns_post.php'),
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i>', 
                        ['create2','id_oferta'=> $searchModel->id_oferta],
                        ['role'=>'modal-remote','title'=> 'Crear Nueva Postulacion','class'=>'btn btn-default']).                    
    
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
                    'type' => 'primary', 
                    'heading' => false,
                    'before'=>'',
                    'after'=>'<div class="clearfix"></div>',
                ]
            ]);
           }else
           {
            echo GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'columns' => require(__DIR__.'/_columns_post.php'),
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
                    'type' => 'primary', 
                    'heading' => false,
                    'before'=>'',
                    'after'=>'<div class="clearfix"></div>',
                ]
            ]);

           }

     ?>   
    </div>
</div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [  // esto es diferente
        'backdrop' => 'static'
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
