<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_acomp_asistenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Acompañar Asistencias';
$this->params['breadcrumbs'][] = $this->title;
$usuarioAuth = Yii::$app->user->identity;

$string = "";

$string .= "{create}";
$string .= "{view}";
$string .= "{update}";
$string .= "{print}";

if ($hasRolGlobal || $hasRolAdminGeneral) {
    $string .= "{delete}";
    $string .= "{reactivate}";
}

$url_reporte_asistencia = Url::to(
    [
        '/mds_acomp_asistencia/reporte_asistencia',
        'idasistencia' => $searchModel['idasistencia'] ?  $searchModel['idasistencia'] : 0,
        'localidad' => $searchModel['idlocalidad'] ?  $searchModel['idlocalidad'] : 0,
        'localidadIngreso' => $searchModel['idlocalidad_ingreso'] ?  $searchModel['idlocalidad_ingreso'] : 0,
        'riesgo' => $searchModel['idriesgo'] ?  $searchModel['idriesgo'] : 0,
        'periodo_desde' => $searchModel['periodo_desde'] ? armarDateParaMySql($searchModel['periodo_desde']) : '0000-00-00',
        'periodo_hasta' => $searchModel['periodo_hasta'] ? armarDateParaMySql($searchModel['periodo_hasta']) : '0000-00-00',
        'activo' => ($searchModel['deleted_at'] != null) ?  $searchModel['deleted_at'] : 1,
    ]
);

$botonManual = Html::button('Manual de Usuario', ['id' => 'boton-manual', 'type' => "button", 'class' => 'btn btn-primary pull-left btnManual']);


function armarDateParaMySql($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 6, 4);
    $mes  = substr($fecha, 3, 2);
    $dia = substr($fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}

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

    .btnPrint {
        background: grey !important;
        color: white !important;
    }
</style>



<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">

        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
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
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-certificaciones-index table-responsive">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'toolbar' => [
                                ['content' =>
                                Html::a(
                                    '<i class="glyphicon glyphicon-save"></i> Reporte Asistencias',
                                    $url_reporte_asistencia,
                                    [
                                        'role' => 'modal-remote', 'title' => 'Reporte Asistencias',
                                        'class' => 'btn btnPrint',
                                        'style' => 'margin-right: 10px',
                                        'target' => '_blank',
                                    ]
                                ) .
                                    Html::a(
                                        '<i class="glyphicon glyphicon-plus"></i>',
                                        ['create'],
                                        [
                                            'data-pjax' => 0, 'role' => 'post', 'title' => 'Nuevo solicitud',
                                            'class' => 'btn btn-success',
                                            'style' => 'margin-right: 10px',
                                        ]
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
                                'type' => 'default',
                                'heading' => '',
                                'after' => '<div class="clearfix"></div>',
                                'before' => $botonManual
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
    "
$('#crud-datatable-filters').children('td').children().css('z-index', '0');

$('#boton-manual').click(function() {
    $.ajax({
            type: 'POST',
            url: '" . Url::to(['/mds_acomp_asistencia/guardarlogmanualusuario']) . "', 
            data: { },

            success: function (success) {
                if (success) {
                    window.open('" . Url::base() . "/instructivos/instructivo_acompanar.pdf', '_blank');
                } else {
                    console.log(errormessage);
                    alert('Ocurrió un error');
                }
            },
            error: function (errormessage) {
                console.log(errormessage);
                alert('Ocurrió un error');
            }
        });
})
"
);
