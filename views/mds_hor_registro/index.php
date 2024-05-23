<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_hor_registroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registros Horarios';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<style>
    .table>thead>tr>td.danger,
    .table>tbody>tr>td.danger,
    .table>tfoot>tr>td.danger,
    .table>thead>tr>th.danger,
    .table>tbody>tr>th.danger,
    .table>tfoot>tr>th.danger,
    .table>thead>tr.danger>td,
    .table>tbody>tr.danger>td,
    .table>tfoot>tr.danger>td,
    .table>thead>tr.danger>th,
    .table>tbody>tr.danger>th,
    .table>tfoot>tr.danger>th {
        color: #111;
        background-color: #dee !important;
    }

    .pagination {
        padding-left: 100px;
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
                <div class="col-md-12" id="contadores" style="padding-top: 5px;padding-bottom: 15px; border-radius:5px; height: 35px; background-color:#ededed; margin-bottom: 3px;">
                    <h4 style="padding-bottom:5px; margin:0;">Cargando contadores...</h4>
                </div>
                <div class="mds-hor-registro-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'filterUrl' => ['mds_hor_registro/index'],
                            'filterSelector' => '#aply_filter',
                            'filterOnFocusOut' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>
                                Html::button(
                                    '<i class="glyphicon glyphicon-filter"></i>',
                                    [
                                        'class' => 'btn btn-info',
                                        'id' => 'aply_filter',
                                        'style' => 'margin-right:5px;',
                                        //'data-toggle'=>"tooltip",
                                        //'data-placement'=>"left",
                                        'title' => "Filtrar Datos"
                                    ]
                                ) .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-import"></i> Importar Excel',
                                        //['importar_excel_javascrip'],
                                        ['importar_horarios_excel_yii'],
                                        ['role' => 'modal-remote', 'title' => 'Importar Excel', 'class' => 'btn btn-success']
                                    ) .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-import"></i> Importar Txt/Dat',
                                        ['importacion'],
                                        ['role' => 'modal-remote', 'title' => 'Importar Registros', 'class' => 'btn btn-primary']
                                    ) .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        ['set_masivo'],
                                        ['class' => 'btn btn-default', 'title' => 'Cargar Registro']
                                    ) .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                        [''],
                                        ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Recargar Grilla']
                                    ) .
                                    '{toggleData}' .
                                    '{export}'],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => false,
                            'panel' => [
                                'type' => 'default',
                                'heading' => false,
                                'before' => ' <div id="licencias">
                                </div>',
                                'after' => BulkButtonWidget::widget([
                                    'buttons' => Html::a(
                                        '<i class="glyphicon glyphicon-trash"></i>&nbsp; Eliminar seleccionados',
                                        ["bulk-delete"],
                                        [
                                            "class" => "btn btn-danger btn-xs",
                                            'role' => 'modal-remote-bulk',
                                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                            'data-request-method' => 'post',
                                            'data-confirm-title' => 'Eliminar Registros Horarios',
                                            'data-confirm-message' => 'La acción que está por realizar no es reversible. ¿Está seguro de continuar?'
                                        ]
                                    ),
                                ]) .
                                    '<div class="clearfix"></div>' .
                                    LinkPager::widget([
                                        'pagination' => $pagination,
                                        'maxButtonCount' => 25
                                    ]),
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
$script = <<<  JS
$('#ajaxCrudModal').on('hidden.bs.modal', function() {
    location.reload();
});
var aux =''
if($('#mds_hor_registrosearch-idcontacto').val()>0){    
    aux=$('#mds_hor_registrosearch-idcontacto').val();    
    $('#mds_hor_registrosearch-idcontacto').html('');    
}

$(document).ready(()=>{
    /*
    $('#aply_filter').focus(() =>{
        setTimeout(() => {
            $('#aply_filter').removeAttr('data-toggle');
            $('#aply_filter').removeAttr('data-placement');
            $('#aply_filter').removeAttr('data-original-title');
            $('#aply_filter').attr('title', 'Filtrar Datos');
            $('#aply_filter').blur();
        }, 4000);
    });
    $('#aply_filter').trigger("focus");
    */
    
    $.post("index.php?r=mds_hor_registro/get_contactos_activos", (data)=>{
        $('#mds_hor_registrosearch-idcontacto').attr('placeholder', 'Empleado...');
        if(data!=''){
            //Seteo al Select empleados la respuesta del servicio
            var options='<option value=""></option>';
            for(const property in data){//Recorro el objeto devuelto
                if(aux==data[property]['idcontacto']){//Para mantener seleccionado el idcontacto si está seteado
                    options+='<option selected value="'+data[property]['idcontacto']+'">'+data[property]['contacto']+'</option>';
                }else{
                    options+='<option value="'+data[property]['idcontacto']+'">'+data[property]['contacto']+'</option>';
                }
            }
        }else{
           var options='';
        }
        $('#mds_hor_registrosearch-idcontacto').html(options);
    });

    $.post("index.php?r=mds_hor_registro/get_contadores_fichadas", (data)=>{
        if(data){
            const cont = '<div class="row text-md">'+
            /*
            <div class="col-md-2"><b> Activos Ciclo: '+data.activos_ciclo+'</b></div> 
            <div class="col-md-2">Ciclo (hoy): '+data.hoy_ciclo+'</div>
            <div class="col-md-2">Reloj (hoy): '+data.hoy_reloj+'</div> */
            '<div class="col-md-2 col-md-offset-8">Manual (hoy): '+data.hoy_manual+'</div>'+
            '<div class="col-md-2">Guardia (hoy): '+data.hoy_guardia+'</div></div>';
            $('#contadores').html(cont);
            $('#contadores').css('background', '#ffffff');
        }
    });

    cargarLicencia();
});

function cargarLicencia(){
    $.post("index.php?r=mds_hor_registro/get_licencia&idcontacto="+aux, (data)=>{
        if(data){
            const cont = '<div class="col-md-6" "><b>Última Licencia: </b> Desde el '+
            formatearFecha(data.desde)+' hasta el '+formatearFecha(data.hasta)+' <i>('+data.detalle+')</i></div>';
            $('#licencias').html(cont);
            $('#licencias').css('background', '#ffffff');
        }
    });    
}

function formatearFecha(fecha) {     
    var day = fecha.substring(8, 10);
    var month = fecha.substring(5, 7);
    var year = fecha.substring(0, 4);
    fecha = day + "/" + month + "/" + year;
    return fecha;
}

$('#aply_filter').click(()=>{
    $('#crud-datatable').yiiGridView('applyFilter');    
});
JS;
$this->registerJs($script);
?>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    //'size' => Modal::SIZE_LARGE,
    /*
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    */
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>