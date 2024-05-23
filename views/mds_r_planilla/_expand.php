<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use app\models\Mds_r_variable_dimension;
use app\models\Mds_r_variable_dimensionSearch;
use  yii\web\Request;
use yii\bootstrap\Modal;


 
?>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <div class="panel-body">
                <?php                     
                    $searchModel_var = new Mds_r_variable_dimensionSearch();                                
                    $dataProvider_var = $searchModel_var->search2(Yii::$app->request->queryParams,$model->idplanilla);
                    $dataProvider_var->pagination->pageSize = 40;
            
                ?>                
                <div class="mds-r-_variable_dimension-create">


                <?php 
                //echo Yii::$app->request->baseUrl; echo "<br>";
               // echo Yii::$app->homeUrl ;echo "<br>";
                //$path1=Yii::$app->request->hostInfo ;
                //echo $path1;
                //echo "<br>";
                //echo  dirname( __FILE__ ) . '/path-to/my-file';
                //$namegrid=generate_string( 20);
                $namegrid=$model->idplanilla;
                $namegrid2='ajaxCrudDatatable'.$namegrid;               
                ?>

                <div class="mds-r-variable-dimension-index">
                        <div id=<?=$namegrid2?>>
                            <?=GridView::widget([
                                'id'=>'crud-datatable'.$namegrid,
                                'dataProvider' => $dataProvider_var,
                                'filterModel' => $searchModel_var,                                  
                                'pjax'=>true,
                                //'columns' => require('C:\xampp\htdocs\mds\views\mds_r_variable_dimension\_columns.php'),
                                'columns' => require(Yii::$app->basePath.'/views/mds_r_variable_dimension/_columns.php'),
                                
                                'toolbar'=> [
                                    ['content'=>
                                        Html::a('<i class="glyphicon glyphicon-plus"></i>', ['/mds_r_variable_dimension/create','idplanilla' => $model->idplanilla],
                                        ['role'=>'modal-remote','title'=> 'Crear nueva Variable Dimensión','class'=>'btn btn-default']).
                                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                                        ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid'])                    
                                        
                                    ],
                                ],          
                                'striped' => true,
                                'condensed' => true,
                                'responsive' => true,          
                                'panel' => [
                                    'type' => null, 
                                    'heading' => false,
                                    'before'=>'Variables Dimensión de la Planilla correspondiente al <b>-mes: '.$model->mes.'    -año: '.$model->anio.'</b>',
                                    'after'=>false,
                                ]
                            ])?>
                        </div>
                </div>




            
                </div>
            </div>
        </section>
    </div>
</div>
