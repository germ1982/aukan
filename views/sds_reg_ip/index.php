<script>

    function cargar_ips()   
        {
            $.post("index.php?r=sds_reg_ip/get_listado_ips", function(data) {
                ips = data.split(',');
                for(i=0;i<ips.length;i++)
                    {
                        ip = ips[i];
                        ip_ = ip.replaceAll('.','_')
                        aux_ip_div = 'div_ip_'+ip_;
                        setear_ip(ip,aux_ip_div);
                    }
            }); 
        }


    function setear_ip(ip_addr,ip_div) 
        {
            gif = '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';
            var aux = '<img src="data:image/gif;base64,'+gif+'" alt=""  height="10px" />'+ip_addr;
            $('#'+ip_div).html(aux);


            var parametros = {
                "ip_addr": ip_addr,
            };
            $.ajax({
                data: parametros, 
                url: 'consultas/sds_reg_ip_ping.php', 
                type: 'post', 
                success: function(response) {
                    var obj = jQuery.parseJSON(response); 
                    ping='<span class="glyphicon glyphicon-signal" style="color:red;"></span>';
                    if(obj.respuesta==1)
                        {
                            ping='<span class="glyphicon glyphicon-signal" style="color:#3CFF33;"></span>' + ip_addr;
                        }
                    else
                        {
                            ping='<span class="glyphicon glyphicon-signal" style="color:red;"></span>' + ip_addr;
                        }
                    $('#'+ip_div).html(ping);
                }
            });
        }
</script>
<?php

use app\models\Sds_reg_ip;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

$script = <<<  JS

$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })

//$(document).ready(function () {cargar_ips();});


JS;
$this->registerJs($script);

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_cap_capacitacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Control de Ips';
$this->params['breadcrumbs'][] = $this->title;

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
    <h2>
        <?= $this->title ?>
    </h2>
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
                <div class="sds-vio-intervencion-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'rowOptions' => function($model, $key, $index, $column){

                                        if ($model->idequipo){
                                            return ['myurl' => $model->idip,'style' => 'background-color: #DFFFE0; color: #000; cursor: pointer'];
                                        }
                                    
                            },
                            'toolbar' => [
                                ['content' =>
                                Html::button(
                                    '<i class="fas fa-network-wired"></i>', 
                                [
                                    'title' => "Ping Masivo",
                                    //'data-toggle' => 'tooltip',
                                    //'class' => 'btn btn-link',
                                    'class' => 'btn btn-default',
                                    'onclick' => "cargar_ips()",
                                ]).
                                Html::a(
                                    '<i class="glyphicon glyphicon-plus"></i>',
                                    ['create'],
                                    ['role' => 'modal-remote', 'title' => 'Nueva intervencion', 'class' => 'btn btn-default']
                                ) .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                        [''],
                                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                                    ) .
                                    '{toggleData}' .
                                    '{export}'],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'panel' => [
                                'type' => 'primary',
                                'heading' => ' ',
                                /* 'heading' => '<i class="glyphicon glyphicon-list"></i> Sds Vio Intervencions listing',
                                'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>', */
                                /* 'after'=>BulkButtonWidget::widget([
                                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                                            ["bulk-delete"] ,
                                            [
                                                "class"=>"btn btn-danger btn-xs",
                                                'role'=>'modal-remote-bulk',
                                                'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                                'data-request-method'=>'post',
                                                'data-confirm-title'=>'Are you sure?',
                                                'data-confirm-message'=>'Are you sure want to delete this item'
                                            ]),
                                ]).   */
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
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>


