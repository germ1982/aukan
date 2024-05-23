<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_hor_registroSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\Mds_hor_registro;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Reporte de Fichadas';
$this->params['breadcrumbs'][] = $this->title;

//CrudAsset::register($this);
?>
<style>
    .content-body{
        padding-top: 10px;
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
            <li>
                <a href="index.php?r=mds_hor_registro">
                    Registro Horario
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
                <div class="mds-hor-registro-index" >
                    <?php
                    if($contactos!=null){
                        foreach($contactos as $contacto){
                            //print_r($contacto->attributes);
                            echo "($contacto->legajo) $contacto->apellido , $contacto->nombre - Fichadas: $contacto->fichadas |  Edificio: $contacto->edificio";
                            echo '<br>----------------------------------------------------------------------------------------------------------------------------------------------------------------<br>';
                        };
                    }
                    ?>
                    <?php $form = ActiveForm::begin(); ?>
                        <div class="row">
                            <div class="col-md-3 col-md-offset-1">
                                <?=$form->field($model, 'fdesde')->widget(DatePicker::class, [
                                    'id'=>'periodo',
                                    'name' => 'check_issue_date',
                                    'language' => 'es',
                                    'layout' => '{picker}{input}{remove}',
                                    'options' => [
                                        'id' => 'fecha_registro',
                                        'class' => 'form-control input-md'   
                                    ],
                                    'pluginOptions' => [
                                        'value' => null,
                                        'format' => 'mm/yyyy',
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                        'startView' => 'months',
                                        'minViewMode'=>'months',
                                    ]
                                ])->label('Periodo'); ?>
                            </div>
                            <div class="col-md-3" style="padding-top: 25px;">
                                <div class="input-group">
                                    <!--Incluir Certificados-->
                                    <span class="input-group-addon">
                                        <input type="checkbox" aria-label="" id="certificados">
                                    </span>
                                    <input type="text" class="form-control" aria-label="" disabled value="Incluir Certificados">
                                </div>
                            </div>
                            <div class="col-md-4" style="margin-top:24px;">
                                <?= Html::button('Exportar a Excel',
                                    ['id' => 'generate', 
                                    'class' => 'btn btn-success col-md-12'
                                    ]
                                );?>
                            </div>
                        </div>
                    <?php ActiveForm::end(); ?>
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

$script = <<<  JS

$(document).ready(function(){
    $('#generate').click(function(){
        console.log('Call');
        exportarTodos();
    });
});
function exportarTodos(){
    console.log('Go');
    var periodo = $("#fecha_registro").val();
    console.log(periodo);
    var certificados = $("#certificados");
    $('#loading').show();
    $.post("index.php?r=mds_hor_registro/reporte_fichadas&periodo="+periodo+'&certificados='+(certificados.is(":checked")?1:0), function(data) {
        console.log("Aca recibiendo la data");
        console.log(data);
        //aca deberia hacer un controller que traiga en formato json el datasource del search
        //con los datos de todos.
        /* this line is only needed if you are not adding a script tag reference */
        
        if(typeof XLSX == 'undefined') XLSX = require('xlsx');
        /* make the worksheet*/
        var ws = XLSX.utils.json_to_sheet(data);
        var wscols = [
            {wch:7},
            {wch:10},
            {wch:25},
            {wch:25},
            {wch:40},
            {wch:7},
        ];

        ws['!cols'] = wscols;
        /* add to workbook */
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Fichadas "+periodo.replace('/', '-'));
        /* generate an XLSX file */
        XLSX.writeFile(wb, "fichadas.xlsx");
        $('#loading').hide();
    });
}
JS;

$this->registerJs($script);










?>
<?php 
/*Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    //'size' => Modal::SIZE_LARGE,
    /*
    'clientOptions' => [
        'backdrop' => 'static'
    ],
    *
    "footer" => "", // always need it for jquery plugin
]) 
 Modal::end(); */?>