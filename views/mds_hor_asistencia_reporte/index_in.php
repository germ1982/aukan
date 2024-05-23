<?php

use app\models\Mds_org_contacto;
use app\models\Mds_org_dispositivo;
use app\models\Mds_org_organismo;
use app\models\Sds_com_periodo;
use app\models\Sds_com_persona;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_hor_asistencia_reporteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reporte de' . ($searchModel->inasistencias == 0 ? ' Asistencias' : ' Inasistencias');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$user = Yii::$app->user->identity;
$idusuario = $user != null ? $user->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app->getResponse()->redirect([
        'site/login',
        'model' => $model
    ]);
}

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
                <div class="mds-hor-asistencia-reporte-search" style="padding-top: 16px;">
                    <?php
                    $form = ActiveForm::begin([
                        'action' => ['index', 'inasistencias' => $searchModel->inasistencias],
                        'method' => 'get',
                    ]);
                    $searchModel->desde = $searchModel->desde == -1 ? date('n/Y', strtotime("-1 month")) : $searchModel->desde;
                    ?>
                    <div class="col-md-7" style="padding-top: 30px;">
                        <div class="row">
                            <div class="col-md-2">
                                <?= $form->field($searchModel, 'desde')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        Sds_com_periodo::find()->all(),
                                        'periodo',
                                        'periodo'
                                    ),
                                    'options' => [
                                        'id' => 'cmb_periodo',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => false
                                    ],
                                ]); ?>
                            </div>
                            <div class="col-md-5">
                                <?php
                                echo  $form->field($searchModel, 'iddispositivo')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        Mds_org_dispositivo::find()->orderBy(['descripcion' => SORT_ASC])->all(),
                                        'iddispositivo',
                                        function ($searchModel) {
                                            $organismo = Mds_org_organismo::findOne($searchModel->idorganismo);
                                            return $searchModel->descripcion . " - " . $organismo->descripcion;
                                        }
                                    ),
                                    'options' => [
                                        'placeholder' => 'Seleccionar Dispositivo ...',
                                        'id' => 'cmb_dispositivo',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label("Dispositivo");
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($searchModel, 'eventuales')->dropDownList(
                                    [null => 'Todos', '0' => 'Planta Política', '1' => 'Eventuales'],
                                );
                                ?>
                            </div>
                            <div class="col-md-2" style="padding-top:26px">
                                <div class="form-group">
                                    <?= Html::submitButton(
                                        'Buscar',
                                        [
                                            'id' => 'btn_buscar',
                                            'class' => 'btn btn-primary',
                                            'disabled' => true
                                        ]
                                    ) ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?= ($form->field($searchModel, 'organismo_search')
                                    ->textInput(['placeholder' => 'Buscar...', 'id' => 'buscar_arbol'])
                                    ->label("Organismo")) ?>
                            </div>
                        </div>
                        <?= $form->field($searchModel, 'idorganismo')->hiddenInput(['id' => 'idorganismo'])->label(''); ?>
                        <div class="row">
                            <div id="tree_organismos" class="col-md-12">

                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mds-hor-asistencia-reporte-index">
                            <div id="ajaxCrudDatatable">
                                <?php
                                $url =  Url::to([
                                    '/mds_hor_asistencia_reporte/reporte_inasistencia',
                                    'idorganismo' => $searchModel->idorganismo,
                                    'iddispositivo' => $searchModel->iddispositivo,
                                    'periodo' => $searchModel->desde,
                                    'estado' => $searchModel->estado,
                                    'eventuales' => $searchModel->eventuales
                                ]);
                                //Mds_hor_asistencia_reporteSearch%5Bperiodo%5D=3%2F2022&
                                //Mds_hor_asistencia_reporteSearch%5Beventuales%5D=&Mds_hor_asistencia_reporteSearch%5B
                                //organismo_search%5D=&Mds_hor_asistencia_reporteSearch%5Bidorganismo%5D=1
                                //inasistencias=1&Mds_hor_asistencia_reporteSearch%5Bperiodo%5D=2%2F2022&
                                //Mds_hor_asistencia_reporteSearch%5Biddispositivo%5D=&Mds_hor_asistencia_reporteSearch%5B
                                //eventuales%5D=&Mds_hor_asistencia_reporteSearch%5Borganismo_search%5D=&Mds_hor_asistencia_reporteSearch
                                //%5Bidorganismo%5D=103
                                $url_todos = null; /* Url::to([
                                    '/mds_hor_asistencia_reporte/index',
                                    'inasistencias' => 1,
                                    'idorganismo' => null,
                                    'iddispositivo' => null,
                                    'periodo' => $searchModel->periodo,
                                    'eventuales' => null
                                ]); */
                                echo GridView::widget([
                                    'id' => 'crud-datatable',
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'pjax' => true,
                                    'columns' => require(__DIR__ . ($searchModel->inasistencias == 1 ? '/_columns_in.php' : '/_columns.php')),
                                    'toolbar' => [
                                        ['content' => Html::a('<i class="far fa-file-excel"></i> Buscar Todos', $url_todos, [
                                            'id' => 'btn_buscar_todos',
                                            'class' => 'btn btn-success',
                                            'title' => "Buscar Todos",
                                            'role' => 'post', 'data-pjax' => 0,
                                            'data-toggle' => 'tooltip',
                                        ]) . ($searchModel->inasistencias == 1 ? Html::a('<span class= "fas fa-print"></span> Ver Reporte', $url, [
                                            'class' => 'btn btn-default',
                                            'title' => "Imprimir",
                                            'role' => 'post', 'data-pjax' => 0, 'target' => '_blank',
                                            'data-toggle' => 'tooltip',
                                        ]) . ' ' : '') .
                                            Html::a(
                                                '<i class="glyphicon glyphicon-repeat"></i>',
                                                [''],
                                                ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reset Grid']
                                            ) .
                                            '{toggleData}' .
                                            '{export}'],
                                    ],
                                    'striped' => true,
                                    'condensed' => true,
                                    'responsive' => false,
                                    'panel' => [
                                        'type' => 'primary',
                                        'heading' => false,
                                        'after' => '<div class="clearfix"></div>',
                                    ]
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
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'size' => Modal::SIZE_LARGE,
    "footer" => "", // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>
<?php
$script = <<<  JS

$('#tree_organismos').jstree({
    'core' : {
        'themes' : {
            'responsive': false
        }
    },
    'types' : {
        'default' : {
            'icon' : 'fa fa-folder'
        },
        'file' : {
            'icon' : 'fa fa-file'
        }
    },
    'plugins': ['types']
});

$(document).ready(function() {            
    cargarArbol();        
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
    $('#cmb_dispositivo').change(function(){
        $("#btn_buscar").prop("disabled", $("select#cmb_dispositivo").val()==null);
    });    
});

$('#btn_buscar_todos').click(function(){
    exportarTodos()
    });

$('#buscar_arbol').keyup(function(){        
    cargarArbol();
});

$('#w0').submit(function(){
    $("#btn_buscar").html("<i class=\"fas fa-spinner fa-pulse\"></i>");
    $("#btn_buscar").prop("disabled", true);
});

//<i class="fas fa-spinner fa-pulse"></i>
function cargarArbol() {
    var descripcion = $('#buscar_arbol').val();
    var idusuario = "$idusuario"!="" ? "$idusuario":0;
    $.post("index.php?r=mds_org_organismo/reload_organigrama&descripcion=" + descripcion + "&usuario="+idusuario, function(data) {
        $("#tree_organismos").jstree(true).settings.core.data = data;
        $("#tree_organismos").jstree(true).refresh();                    
    });
}

function cargarDispositivos(idorganismo) {
    $("select#mds_org_dispositivo-idorganismo").val(idorganismo).trigger("change");
    $("#idorganismo").val(idorganismo);
    //console.log(idorganismo);
    //$("select#mds_org_dispositivo-idorganismo").attr('disabled', 'disabled');
    $.post("index.php?r=mds_org_dispositivo/cmb_dispositivo&idorganismo=" + idorganismo, function(data) {
            $("select#cmb_dispositivo").html(data);    /* 
            var iddispositivo = "$searchModel->iddispositivo"!="" ? "$searchModel->iddispositivo":0; */
            $("select#cmb_dispositivo").val(null);
    });
}

$('#tree_organismos').on('activate_node.jstree', function (e, data) {
        if (data == undefined || data.node == undefined || data.node.id == undefined)
                return;
        var idorganismo=data.node.id;
        cargarDispositivos(idorganismo);
        $("#btn_buscar").html("Buscar");
        $("#btn_buscar").prop("disabled", false);
    }
);       

function exportarTodos(){
    var periodo = $("select#cmb_periodo").val();
    $('#loading').show();
    $.post("index.php?r=mds_hor_asistencia_reporte/xls_todos&periodo=" + periodo, function(data) {
        //aca deberia hacer un controller que traiga en formato json el datasource del search
        //con los datos de todos.
        /* this line is only needed if you are not adding a script tag reference */
        if(typeof XLSX == 'undefined') XLSX = require('xlsx');
        /* make the worksheet */
        var ws = XLSX.utils.json_to_sheet(data);
        var wscols = [
            {wch:20},
            {wch:50},
            {wch:10},
            {wch:120},
        ];

        ws['!cols'] = wscols;
        /* add to workbook */
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Empleados");
        /* generate an XLSX file */
        XLSX.writeFile(wb, "inasistencia_todos.xlsx");
        $('#loading').hide();
    });
}

JS;

$this->registerJs($script);
