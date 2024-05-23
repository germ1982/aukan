<?php

use app\controllers\SiteController;
use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_com_periodo;
use app\models\Sds_com_persona;
use app\models\View_franco;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

$this->title = 'Exportar Francos' ;
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$searchModel = new View_franco();
?>

<header class="page-header">
    <h2><?= $this->title ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li><a href="index.html"><i class="fa fa-home"></i></a></li>
            <li><span><?= $this->title ?></span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="mds-hor-asistencia-reporte-search">
                    <?php
                        $form = ActiveForm::begin([]);
                    ?>
                        <div class="col-md-offset-4 row" >
                                <div class="col-md-3">
                                    <?= SiteController::actionGet_input_fecha($form,$searchModel,'desde','fecha_desde','Desde') ?>
                                </div>
                                <div class="col-md-3">
                                    <?= SiteController::actionGet_input_fecha($form,$searchModel,'hasta','fecha_hasta','Hasta') ?>
                                </div>
                        </div>
                        <div class="row">        
                            <div class=" col-md-offset-4 col-md-4">
                                <?=SiteController::actionGet_input_select2($form, $searchModel,'tipo','cmb_tipo',Sds_com_configuracion::getConfiguraciones(Sds_com_configuracion_tipo::FRANCO_TIPO),'idconfiguracion','descripcion','Tipo');?>
                            </div>
                        </div>
                        <div class="row">  
                            <div class="col-md-offset-5 col-md-2" style="padding-left:60px;padding-top:20px">
                                <div class="form-group">
                                    <?= Html::button('<i class="far fa-file-excel"></i> Exportar',[
                                        'id' => 'btn_buscar_todos',
                                        'class' => 'btn btn-success',
                                        'title' => "Exportar",

                                    ]) ?>
                                </div>
                            </div>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>


            </div>
        </section>
    </div>
</div>


<?php
$script = <<<  JS



$('#btn_buscar_todos').click(function(){
    exportarTodos()
    });  

function exportarTodos(){
    var desde = $('#fecha_desde').val() ? $('#fecha_desde').val():'';
    var hasta = $('#fecha_hasta').val() ? $('#fecha_hasta').val():'';
    var tipo = $('#cmb_tipo').val() ? $('#cmb_tipo').val():'';

    console.log(desde);console.log(hasta);console.log(tipo);

    $('#loading').show();
    $.post("index.php?r=mds_hor_franco/xls_francos&desde=" + desde + "&hasta=" + hasta + "&tipo=" + tipo, function(data) {
        console.log(data);
        if(typeof XLSX == 'undefined') XLSX = require('xlsx');
        var ws = XLSX.utils.json_to_sheet(data);
        var wscols = [
            {wch:8},//idfranco
            {wch:10},//fecha
            {wch:5},//anio
            {wch:8},//idcontacto
            {wch:8},//legajo
            {wch:10},//documento
            {wch:25},//nombre
            {wch:15},//apellido
            {wch:25},//dispositivo
            {wch:35},//organismo
            {wch:5},//tipo
            {wch:10},//tipo_descripcion
            {wch:40},//descripcion
        ];

        ws['!cols'] = wscols;

        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Francos");

        const hoy = new Date();
        let hoy_aux = hoy.getDate() + '-' + ( hoy.getMonth() + 1 ) + '-' + hoy.getFullYear() + ' ' + hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
        
        let nombre_archivo = "Francos_exportacion_" + hoy_aux + ".xlsx";
        console.log(nombre_archivo);

        XLSX.writeFile(wb, nombre_archivo);

        $('#loading').hide();
    });
}

JS;

$this->registerJs($script);