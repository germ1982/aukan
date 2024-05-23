<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

$this->registerJsFile('https://cdn.quilljs.com/1.3.7/quill.min.js', ['async' => true, 'defer' => true]);

$this->title = 'Certificaciones';
$this->params['breadcrumbs'][] = $this->title;

$string =
    "{create}
    {view}
    {update}
    {aprobar}
    {print}
    {print_history}
    {imprimirRisneu}
    {historial_responsables}
    {historial_estados}
    {historial_montos}
    {nota}
    {delete}
    {reactivate}";

CrudAsset::register($this);



$url_reporte_certificaciones = Url::to(
    [
        '/mds_certificacion/reporte_certificaciones',
        'idcertificacion' => $searchModel['idcertificacion'] ?  $searchModel['idcertificacion'] : 0,
        'monto' => $searchModel['monto'] ?  $searchModel['monto'] : 0,
        'nro_expediente' => $searchModel['nro_expediente'] ?  $searchModel['nro_expediente'] : 0,
        'idprograma' => $searchModel['idprograma'] ?  $searchModel['idprograma'] : 0,
        'iddireccion' => $searchModel['iddireccion'] ?  $searchModel['iddireccion'] : 0,
        'periodo_desde' => $searchModel['periodo_desde'] ? armarDateParaMySql($searchModel['periodo_desde']) : '0000-00-00',
        'periodo_hasta' => $searchModel['periodo_hasta'] ? armarDateParaMySql($searchModel['periodo_hasta']) : '0000-00-00',
    ]
);

$urlXlsReporte = Url::to(['/mds_certificacion/modal_xls_reporte', 'area' => $area]);

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

$botonManual = Html::button('<i class="glyphicon glyphicon-save"></i> Manual de Usuario', ['id' => 'boton-manual-certificacion', 'type' => "button", 'class' => 'btn btn-primary pull-left btnManual']);

$panelEstados = <<<HTML
    <br><br>
    <p >
        <b>Estado:</b>
        <span class="alert" style="padding: 1px 5px 1px 5px"> Pendiente</span>
        <span class="alert aprobada" style="padding: 1px 5px 1px 5px">Aprobada</span>
        <span class="alert rechazada" style="padding: 1px 5px 1px 5px">Rechazada</span>
        <span class="alert observada" style="padding: 1px 5px 1px 5px">Observada</span>
        <span class="alert enviada "style="padding: 1px 5px 1px 5px">Enviada</span>
        <span class="alert baja "style="padding: 1px 5px 1px 5px">Baja</span>
    </p>
HTML;
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

    .table>thead>tr>th {
        color: #0088cc;
    }

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
    }

    .btnPrint {
        background: grey !important;
        color: white !important;
    }

    .aprobada {
        background-color: #dff0d8 !important;
    }

    .observada {
        background-color: #fcf8e3 !important;
    }

    .rechazada {
        background-color: #f2dede !important;
    }

    .baja {
        background-color: #c9cedd !important;
    }

    .enviada {
        background-color: #b6effa !important;
    }

    .btnManual {
        margin-left: 5px !important;
    }
</style>

<header class="page-header">
    <h2><?= $this->title ?> <?= $nivelDescripcion ?></h2>
    <div class="right-wrapper pull-right">
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Certificaciones</span></li>
        </ol>
        <div class="sidebar-right-toggle"></div>
    </div>
</header>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <?= $this->render('components/flash_messages') ?>
                <div class="mds-certificacion-index">
                    <div id="ajaxCrudDatatable">
                        <?= GridView::widget([
                            'id' => 'crud-datatable',
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'pjax' => false,
                            'columns' => require(__DIR__ . '/_columns.php'),
                            'rowOptions'   => function ($model, $index, $widget, $grid) use ($idnivelUser) {
                                $situacion = $model->colorCertificacionSegunNivel($idnivelUser);
                                return ['class' => $situacion];
                            },
                            'toolbar' =>
                            [
                                [
                                    'content' => ($permissionExcel ? Html::a(
                                        '<i class="far fa-file-excel"></i> 
                                        Exportar Excel',
                                        $urlXlsReporte,
                                        [
                                            // 'id' => 'btn_buscar_todos',
                                            'class' => 'btn btn-success',
                                            'title' => "Exportar Excel",
                                            'role' => 'modal-remote',
                                            // 'data-pjax' => 0,
                                            'data-toggle' => 'tooltip',
                                        ]
                                    ) : "") .
                                        ($permissionImprimir ? Html::a(
                                            '<i class="glyphicon glyphicon-save"></i> 
                                            Reporte Certificaciones',
                                            $url_reporte_certificaciones,
                                            [
                                                'title' => 'Reporte Certificaciones',
                                                'class' => 'btn btnPrint',
                                                'target' => '_blank',
                                                'style' => 'margin-left:10px; margin-right:10px'
                                            ],
                                        ) : "") .
                                        ($permissionCreate ? Html::a(
                                            '<i class="glyphicon glyphicon-plus"></i>',
                                            ['create'],
                                            [
                                                'data-pjax' => 0, 'role' => 'post',
                                                'title' => 'Nueva solicitud',
                                                'class' => 'btn btn-success',
                                                'style' => 'margin-right:10px'
                                            ]
                                        ) : "") .
                                        ($permissionAutorizar ?
                                            Html::button(
                                                '<i class="glyphicon glyphicon-check"></i>',
                                                [
                                                    'name' => 'btn_apr_certif',
                                                    'id' => 'btn_apr_certif',
                                                    'type' => "button",
                                                    'class' => 'btn btn-success',
                                                    'title' => Yii::t('app', 'Aprobar certificaciones'),
                                                    'style' => 'margin-right:10px',
                                                    'disabled' => true,
                                                    'onclick' => "abrirModalCertificaciones('modal_aprobar','texto-modal-aprobar-certificaciones', 'aprobar')"
                                                ]
                                            ) : "") .
                                        ($permissionNota ?
                                            Html::button(
                                                '<i class="fas fa-check-double"></i>',
                                                [
                                                    'name' => 'btn_visto_certif',
                                                    'id' => 'btn_visto_certif',
                                                    'type' => "button",
                                                    'class' => 'btn btn-primary',
                                                    'title' => Yii::t('app', 'Marcar como vista/s la/s certificación/es'),
                                                    'style' => 'margin-right:10px',
                                                    'disabled' => true,
                                                    'onclick' => "abrirModalCertificaciones('modal_visto_index','texto-modal-visto-certificaciones', 'marcar como vista/s')"
                                                ]
                                            ) : "") .
                                        (($permissionImprimir) ?  Html::button(
                                            '<i class="glyphicon glyphicon-save"></i>',
                                            [
                                                'name' => 'btn_print_certif',
                                                'id' => 'btn_print_certif',
                                                'data-request-method' => 'post',
                                                'data-toggle' => 'tooltip',
                                                'class' => 'btn btn-info',
                                                'title' => Yii::t('app', 'Imprimir certificaciones'),
                                                'onclick' => 'printCertificaciones()',
                                                'style' => 'margin-right:10px',
                                                'disabled' => true
                                            ]
                                        ) : "") .
                                        ($permissionRead ? Html::a(
                                            '<i class="glyphicon glyphicon-repeat"></i>',
                                            Url::to(['/mds_certificacion', 'area' => $area]),
                                            [
                                                'data-pjax' => 1, 'class' => 'btn btn-default',
                                                'title' => 'Refrescar Grilla'
                                            ]
                                        ) : "") .
                                        '{toggleData}' .
                                        '{export}'
                                ],
                            ],
                            'striped' => true,
                            'condensed' => true,
                            'responsive' => true,
                            'panel' =>
                            [
                                'type' => 'default',
                                'responsive' => true,
                                // 'heading' => $botonManual,
                                'before' =>  $botonManual . $panelEstados,
                                'after' => '<div class="clearfix"></div>',
                            ]
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="modal fade" id="modal_aprobar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-archivo">Aprobar certificación/es</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding:20px">
                    <div class="row">
                        <div class="col-md-12">
                            <p id="texto-modal-aprobar-certificaciones">¿Está seguro que desea aprobar la/las certificación/es seleccionada/s?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" title="No, cancelar">No, cancelar</button>
                    <?= Html::Button("Si, aprobar", ['class' => 'btn btn-success', 'id' => 'btnAprobar', 'onclick' => 'aprobarCertificaciones()', 'title' => 'Si, aprobar']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_visto_index" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="titulo-modal-visto-certificaciones">Marcar como vista/s la/s certificación/es</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding:20px">
                    <div class="row">
                        <div class="col-md-12">
                            <p id="texto-modal-visto-certificaciones">¿Está seguro que desea marcar como vista/s la/s certificación/es seleccionada/s?</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" title="No, cancelar">No, cancelar</button>
                    <?= Html::Button("Si, marcar como vista/s", ['class' => 'btn btn-success', 'id' => 'btnVisto', 'onclick' => 'marcarVistoCertificaciones()', 'title' => 'Si, marcar como vista/s']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function abrirModalCertificaciones(idModal, idTextoModal, accionModal) {
        const idsCertificaciones = getIdCertificacionesSeleccionadas();
        $(`#${idTextoModal}`).html(`¿Está seguro que desea ${accionModal} la/s certificación/es: ${idsCertificaciones}?`);
        $(`#${idModal}`).modal("show");
    }

    function aprobarCertificaciones() {
        let valoresCheck = [];
        $("input[type=checkbox]:checked").each(function() {
            if (this.name != 'selection_all') {
                valoresCheck.push(this.value);
            }
        });
        $.post("index.php?r=mds_certificacion/aprobarindex&valores=" + valoresCheck, function(data) {});
    }

    function marcarVistoCertificaciones() {
        let valoresCheck = [];
        $("input[type=checkbox]:checked").each(function() {
            if (this.name != 'selection_all') {
                valoresCheck.push(this.value);
            }
        });
        $.post("index.php?r=mds_certificacion_visto/store&llamadoDesde=index&idCertificaciones=" + valoresCheck, function(data) {});
    }

    function printCertificaciones() {
        let getCheck = [];
        getCheck = $('#crud-datatable').yiiGridView('getSelectedRows');
        $("input:checkbox").prop('checked', false);
        $('#btn_print_certif').prop('disabled', true);
        window.open("index.php?r=mds_certificacion/certificacion_detalle&idcertificacion=" + getCheck, '_blank');
    }

    function getIdCertificacionesSeleccionadas() {
        let idsCertificaciones = "";
        const checkboxes = $("input[type=checkbox]:checked");
        const totalCheckboxes = checkboxes.length;
        $("input[type=checkbox]:checked").each(function(index) {
            if (this.name != 'selection_all') {
                idsCertificaciones += `<b>#${this.value}</b>`;
                if (index !== totalCheckboxes - 1) {
                    idsCertificaciones += ", "
                }
            }
        });

        return idsCertificaciones;
    }
</script>


<?php
$this->registerJs(
    "
    $('#crud-datatable-filters').children('td').children().css('z-index', '0');
    $('input:checkbox').change(function() {
        let cant = $('#crud-datatable').yiiGridView('getSelectedRows').length;
        $('#btn_print_certif').prop('disabled', true);
        $('#btn_apr_certif').prop('disabled', true);
        $('#btn_visto_certif').prop('disabled', true);
        if(cant > 0){
            $('#btn_print_certif').prop('disabled', false);
            $('#btn_apr_certif').prop('disabled', false);
            $('#btn_visto_certif').prop('disabled', false);
        }
    });
    
    $('#ajaxCrudModal').on('hidden.bs.modal', function() {
        location.reload();
    });

    $('#boton-manual-certificacion').click(function() {
        $.ajax({
                type: 'POST',
                url: '" . Url::to(['/mds_certificacion/guardarlogmanualusuario']) . "', 
                data: { },

                success: function (success) {
                    if (success) {
                        window.open('" . Url::base() . "/instructivos/instructivo_certificaciones.pdf', '_blank');
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
    });
    "
);
?>

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