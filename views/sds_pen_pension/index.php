<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use yii\helpers\Url;
$url_pensiones_index =  Url::to(['/sds_pen_pension/index']);
$url_pensiones_informes =  Url::to(['/sds_pen_pension/index', 'informes' => 'informe']);

if (isset($_GET['informes']))
    {
        $this->title = 'Grilla Para Informes Pensiones Ley 809';
        $destino = require(__DIR__ . '/_columns_informes.php');


        $url_informe_localidad =  Url::to(['/sds_pen_pension/imprimir_informe',
                                             'php' => 'imprimir_informe_localidad',    
                                            'idlocalidad' => $searchModel['idlocalidad'] ? $searchModel['idlocalidad'] : 0 ,
                                            'idbarrio' => $searchModel['idbarrio'] ? $searchModel['idbarrio'] : 0 ,
                                            'programa' => $searchModel['programa'] ? $searchModel['programa'] : 0 ,
                                            'estado' => $searchModel['estado'] ? $searchModel['estado'] : 0 ,
                                            'titulo' => 'INFORME DE PENSIONADOS LEY 809 POR LOCALIDAD',
                                            ]);
        $url_informe_barrio =  Url::to(['/sds_pen_pension/imprimir_informe',
                                            'php' => 'imprimir_informe_barrio',    
                                           'idlocalidad' => $searchModel['idlocalidad'] ? $searchModel['idlocalidad'] : 0 ,
                                           'idbarrio' => $searchModel['idbarrio'] ? $searchModel['idbarrio'] : 0 ,
                                           'programa' => $searchModel['programa'] ? $searchModel['programa'] : 0 ,
                                           'estado' => $searchModel['estado'] ? $searchModel['estado'] : 0 ,
                                           'titulo' => 'INFORME DE PENSIONADOS LEY 809 POR BARRIO',
                                           ]);

        $botones =  
            Html::a(
                '<i class="glyphicon glyphicon-arrow-left"></i> Volver ',
                $url_pensiones_index,
                ['role' => 'post', 'title' => 'Volver', 'class' => 'btn btn-default']).
            Html::a(
                '<i class="glyphicon glyphicon-list-alt"></i> Informe por Localidad',
                $url_informe_localidad, [
                'title' => "Informe por Localidad",
                'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                'data-toggle' => 'tooltip',
                'class' => 'btn btn-default']).
            Html::a(
                '<i class="glyphicon glyphicon-list-alt"></i> Informe por Barrio',
                $url_informe_barrio, [
                'title' => "Informe por Barrio",
                'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                'data-toggle' => 'tooltip',
                'class' => 'btn btn-default']).
                Html::a
                    (
                        '<i class="glyphicon glyphicon-repeat"></i>',
                        $url_pensiones_informes,
                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                ).
                '{toggleData}'.
                '{export}';
    }
else
    {
        $title_for_new = 'Nueva Pension';
        $this->title = 'Pensiones Ley 809';
        $destino = require(__DIR__ . '/_columns.php');
        $botones =  
                    Html::a(
                        '<i class="glyphicon glyphicon-plus"></i>',
                        ['create'],
                        ['role' => 'modal-remote', 'title' => $title_for_new, 'class' => 'btn btn-default']
                    ).
                    Html::a(
                        '<i class="glyphicon glyphicon-list-alt"></i> Informes ',
                        $url_pensiones_informes,
                        ['role' => 'post', 'title' => 'Informes', 'class' => 'btn btn-default']
                    ).
                    Html::a
                        (
                            '<i class="glyphicon glyphicon-repeat"></i>',
                            $url_pensiones_index,
                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Refrescar Grilla']
                    ).
                    '{toggleData}'.
                    '{export}';
    }



$this->params['breadcrumbs'][] = $this->title;
$clase = 'sds_pen_pensiones-index';




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
                            'columns' => $destino,
                            'toolbar' => 
                                [
                                    ['content' => $botones],
                                ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'panel' => [
                                'type' => 'default',
                                'heading' => "",
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
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>


<script>

$('#sds_pen_pensionsearch-idlocalidad').change(function() {
        alert("algo")
            //alert($("#cmb_localidad option:selected").text());
            //alert($("#cmb_localidad").val());
            //MostrarBarrios($("#cmb_localidad").val());
            
        });
    function Informe_Localidad()
        {
            
            alert(aux);

        }
    function Imprimir_Localidad() 
        {
            aux = $('#sds_pen_pensionsearch-idlocalidad').val();
            alert(aux);
            

            /* $.post("index.php?r=sds_pen_pension/imprimir_informe_localidad" , function(data) {
                data = $.parseJSON(data);
                if (data.length === 0) 
                    {
                        alert('nada');
                    } 
                else 
                    {
                        //console.log(data);
                        alert(data);

                    }
            
            }); */


        }

</script>