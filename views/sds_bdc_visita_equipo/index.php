<?php

use app\models\Sds_bdc_visita_equipo;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

https://fontawesome.com/icons/plus?f=classic&s=solid&an=beat&sz=lg;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Sds_bdc_visita_equipoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $visita->sector_descripcion." - ". date('d/m/y', strtotime($visita->fecha));

$mobTit = $visita->sector_descripcion;
$mobFec = date('d/m/y', strtotime($visita->fecha));



$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>
<style>
@media only screen and (max-width: 576px) {
    body{
        background-color: #f5f5ff;
    }
}
</style>

<header class="page-header hidden-xs">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.html">
                    <i class="fa fa-home
                    +"></i>
                </a>
            </li>
            <li><span><u><?= $this->title ?></u></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="sds-bdc-visita-equipo-index">
    <div id="ajaxCrudDatatable" class="hidden-xs">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Agregar Equipo', Url::to(['create', 'idvisita'=>$searchModel->idvisita]),
                    ['role'=>'modal-remote','title'=> 'Cargar Nueva Visita','class'=>'btn btn-success']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']),
                    '{toggleData}'.
                    '{export}'
                ]
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
            'type' => 'primary', 
            'heading' => false,
            ]])?>
    </div>

    <div id="it" class="hidden-md" style="min-height:200px; background-color: #f5f5ff">
        <div class="papa_titulo" style="padding:10px">
        <?=Html::a('<i class="glyphicon glyphicon-plus" style="color:rgb(18, 66, 127);position:absolute; padding: 11px; right:5px; font-size:25px;"></i>', Url::to(['create', 'idvisita'=>$searchModel->idvisita]),
                    ['role'=>'modal-remote','title'=> 'Cargar Nueva Visita','clas'=>'btn btn-success'])?>

        <div class="tetas" style="text-align:center;padding:10px">    
                <h3 class="." style=" font-weight:550;   text-align:center; color: rgb(18, 66, 127); margin-top: 0; font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif"><?=$mobTit?></h3>
                <h3 class="." style="text-align:center; color: rgb(18, 66, 127); margin-top: 0; font-family:'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif; font-size:19px;  font-weight:560; "><?=$mobFec?></h3>
        </div>    
                <i class="glyphicon glyphicon-filter" onClick="openFilter()" data-toggle="modal" data-target="#myModal" style="color:rgb(18, 66, 127); position:absolute; padding: 11px; left:5px;font-size:25px;"></i>
        </div>
        
        <?php $modelos = $dataProvider->getModels();
        foreach($modelos as $model){?>
            <!-- COMIENZO DIV PAPA -->
            <div class="papa col-xs-12" style="background-color: rgb(221 233 251); height:70px; position:relative; margin-bottom: 20px; border:1px solid #d3e1fd; border-radius:5px;">
                <div  class="cuadradito" style="background-color: rgb(201 214 241); border-radius:10px; width: 80px; height:80px; position:absolute; left:15px; top:-5px; border: 1px solid #d3e1fd; margin-bottom: 5px; box-shadow: inset -88px -91px 80px -117px #004fa5;" >
                    <div class="Numeral" style="text-align:center; padding-top:18px">
                        <h4 style="color:black; font-size:16px"><?="#".str_pad($model->idequipo,6,"0", STR_PAD_LEFT);?></h4>
                    </div>
                </div>
                <!-- responsables, ip y comentarios -->
                <div style=" position:absolute; text-align: left; padding-left:90px;">
                    <h5 class="col-xs-9" style=" color:black; font-size:12px"><i style="color:rgb(18, 66, 127);" class="glyphicon glyphicon-user"></i> <?=$model->responsable?></h5>
                    <h5 class="col-xs-9" style="text-align: left; color:black; margin: 0; font-size:13px"><i style="color:rgb(18, 66, 127);" class="glyphicon glyphicon-map-marker"></i> <?=$model->ip?></h5>
                </div>
                <i data-toggle="modal" data-target="#myModal" onClick="openModal('<?= $model->observaciones?>')" class="glyphicon glyphicon-comment" style="color:rgb(118, 150, 202); position:absolute; padding: 18px; margin:-5px; right:10px; font-size:40px;"></i>
            </div>
            <!-- FIN DIV PAPA -->
        <?php
        }
        ?>

        <div class="modal fade"  id="myModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 id="titulo-modal" class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div id="obs"></div>
                        <div id="filter">
                            <?php $form = ActiveForm::begin(['method'=>'get']);

                                //$searchModel=new Sds_bdc_visita_equipo();?>
                                <?= $form->field($searchModel, 'idequipo')->widget(Select2::class,[
                                    'data' => ArrayHelper::map(
                                        $equipos,
                                        'idequipo',
                                        function ($model){
                                            return "#".str_pad($model->idequipo,6,"0", STR_PAD_LEFT)." - $model->tipo_descripcion $model->marca_descripcion".($model->matricula!=null ? " | Mat.: $model->matricula":"");
                                        }
                                    ),
                                    'options' => [
                                        'placeholder' => '- Seleccionar Equipo -',
                                        "id" => 'filter_equipo',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ]
                                ]);?>

                                
                                <?= $form->field($searchModel, 'ip_filtro')->textInput(['maxlength' => true, 'attern'=>'^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$', 'placeholder'=>'111.111.111.111']) ?>
                                <?= $form->field($searchModel, 'idresponsable')->widget(Select2::class,[
                                        'data' => ArrayHelper::map(
                                            $responsables,
                                            'idcontacto',
                                            function($model){
                                                return "$model->legajo - $model->nombre";
                                            }
                                        ),
                                        'options' => [
                                            'placeholder' => '- Seleccionar Responsable -',
                                            "id" => 'filter_responsable',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                            ]
                                    ]);?>                       
                                <?= $form->field($searchModel, 'observaciones')->textInput() ?>
                            <?php ActiveForm::end();?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Cerrar</button>
                        <div id="btn-submit" class="pull-right">
                            <i class="fas fa-magic btn-filter" style="color:rgb(18, 66, 127); position:absolute; bottom:30px; right:35px; font-size:25px;"> </i>
                            <h6 class="btn-filter" style="font-size:11px; font-weight:bold;position:absolute; padding:15px; right:2px;">Aplicar Filtro</h6>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
</div>

<style>
@media only screen and (min-width: 576px) {
  /* Estilos para pantallas mayores a 768px */
.hidden-md {
    display: none;
}
}
</style>

<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })"
);
?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    'options' => [
        'tabindex' => false, // important for Select2 to work properly
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>

<script>
    function openModal(observacion){
        $("#titulo-modal").html('Observaciones');
        $("#obs").html(observacion);
        $("#filter").hide();
        $(".btn-filter").hide();
    }

    function openFilter(){
        $("#titulo-modal").html('Filtrar');
        $("#obs").hide();
    }
</script>

<?php
$script = <<<  JS
    $(document).ready(()=>{
        $("#btn-submit").click(()=>{
            $("#w3").submit();
        });
    });
JS;
$this->registerJs($script);?>