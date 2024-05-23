<?php

use app\models\Mds_org_organismo;
use app\models\Sds_com_configuracion;
use app\models\Sds_com_configuracion_tipo;
use app\models\Sds_ent_entrega;
use app\models\Sds_ent_tipo;
use johnitvn\ajaxcrud\CrudAsset;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Árbol de Entregas';
CrudAsset::register($this);

$usuario = Yii::$app->user->identity;
$idusuario = $usuario != null ? $usuario->idusuario : null;
if (!isset($idusuario) || $idusuario == null) {
    $model = new \app\models\LoginForm();
    return Yii::$app
        ->getResponse()
        ->redirect(['site/login', 'model' => $model]);
}

$responsables = $usuario->responsable_todos
    ? Sds_com_configuracion::getConfiguraciones(
        Sds_com_configuracion_tipo::TIPO_RESPONSABLE_ENTREGA
    )
    : Sds_com_configuracion::find()
        ->addSelect('idconfiguracion,descripcion')
        ->where(
            'idconfiguracion in (select idresponsable from 
    mds_seg_usuario_responsable where idusuario=' .
                $usuario->idusuario .
                ')'
        )
        ->all();
?>

<header class="page-header" style="margin-bottom: 10px !important; padding-left:0px !important;">

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
<div class="row" style="overflow: auto">
    <div class="col-md-6">
        <section class="panel">
            <div class="panel-body">
                <?php
                $model = new Sds_ent_entrega();
                $model->idusuario = $usuario->idusuario;
                $model->receptor = $usuario->responsable;
                if (empty($responsables)) {
                    array_push(
                        $responsables,
                        Sds_com_configuracion::findOne($usuario->responsable)
                    );
                }
                $form = ActiveForm::begin(['enableClientValidation' => false]);
                ?>
                <div class="row">
                    <div class="col-md-4">
                        <?= $form
                            ->field($model, 'idtipo')
                            ->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(
                                    Sds_ent_tipo::find()
                                        ->where(
                                            'idtipo in (select idtipo from mds_seg_usuario_entrega_tipo ut where ut.idusuario=' .
                                                $model->idusuario .
                                                ')'
                                        )
                                        ->orderBy(['descripcion' => SORT_ASC])
                                        ->all(),
                                    'idtipo',
                                    'descripcion'
                                ),
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'placeholder' => 'Tipo...',
                                ],
                            ]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form
                            ->field($model, 'receptor')
                            ->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(
                                    $responsables,
                                    'idconfiguracion',
                                    'descripcion'
                                ),
                                'pluginOptions' => [
                                    'allowClear' => $usuario->responsable_todos,
                                    'placeholder' => 'Receptor...',
                                ],
                            ]) ?>
                    </div>
                    <div class="col-md-2">
                        <label class="control-label" for="cmbAnio">Año</label>
                        <select class="form-control" id="cmbAnio" name="cmbAnio" style="padding-left: 2px;padding-right: 2px;"></select>
                    </div>
                    <div class="col-md-2" style="padding-top: 27px;">
                        <?= Html::button(
                            '<i class="glyphicon glyphicon-search"></i>',
                            [
                                'class' => 'btn btn-primary btn-flat',
                                'name' => 'btn_buscar',
                                'id' => 'btn_buscar',
                                'title' => Yii::t('app', 'Buscar'),
                            ]
                        ) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <div id="treeBasic" class="col-md-12" style="display: none;">

                </div>
            </div>
        </section>
    </div>
    <div class="col-md-6">
        <section class="panel">
            <div class="panel-body">
                <div id="entregas_finales">
                    <h4>Entregas Finales</h4>
                    <p>Seleccione una entrega de la izquierda.</p>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
$this->registerJs(
    //$(document).ready(function(){
    "   $('#treeBasic').on('activate_node.jstree', function (e, data) {
                if (data == undefined || data.node == undefined || data.node.id == undefined)
                        return;
                document.getElementById('entregas_finales').innerHTML='<h4>Entregas Finales</h4><p>Seleccione una entrega de la izquierda.</p>';        
                var identregas=data.node.id;                
                verEntregasFinales(identregas,data.node.text);
            }
        );
        "
    //}
    //);
);
/* $this->registerJs("$('#sds_ent_entrega-idtipo').change(function(){        
        cargarArbol();
    });");
$this->registerJs("$('#sds_ent_entrega-receptor').change(function(){        
        cargarArbol();
    });");*/
$this->registerJs("$('#btn_buscar').click(function(){        
        cargarArbol();
    });");
$this->registerJs(
    "$(document).ready(function () {
        cargarComboAnio(" .
        Sds_ent_entrega::getPrimerAnio() .
        ");
        //cargarArbol();
    });"
);
?>
<script>
    function cargarArbol() {
        $('#loading').show();
        $('#treeBasic').hide();
        var idtipo = $('#sds_ent_entrega-idtipo').val();
        if (!idtipo) {
            idtipo = -1;
        }
        var receptor = $('#sds_ent_entrega-receptor').val();
        if (!receptor) {
            receptor = -1;
        }
        var externo = "<?= $externo ?>";
        var anio = $('#cmbAnio').val();
        $.post("index.php?r=sds_ent_entrega/reload_arbol_entregas&idtipo=" + idtipo + "&receptor=" + receptor + "&anio=" + anio + "&externo=" + externo, function(data) {
            $("#treeBasic").jstree(true).settings.core.data = data;
            $("#treeBasic").jstree(true).refresh();
            $('#loading').hide();
            $('#treeBasic').show();
        });
    }

    function verEntregasFinales(identregas, datosEntrega) {
        $.post("index.php?r=sds_ent_entrega/entregas_finales&codsEntregas=" + identregas, function(data) {
            data = $.parseJSON(data);
            var entregas = data[0];
            var total = data[1];
            var cantSel = data[2];
            var externo = "<?= $externo ?>";
            var html_entregas = '';
            var identreganodo = (identregas.includes(",") ? identregas.substring(0, identregas.indexOf(",")) : identregas);            
            var html_botones = '<a class="btn btn-default" style="margin-left: 1%;margin-bottom: 1%;" href="index.php?r=sds_ent_entrega/reporte_rendicion&identregas=' + identregas + '&externo=' + externo + '&detalle=false' +
                '" role="post" data-pjax="0" target="_blank" data-toggle="tooltip" data-original-title="" title="Imprimir resumen de entregas">' +
                '<i class="glyphicon glyphicon-print"></i> Resumen</a>' +
                '<a class="btn btn-default" style="margin-left: 1%;margin-bottom: 1%;" href="index.php?r=sds_ent_entrega/reporte_rendicion&identregas=' + identregas + '&externo=' + externo + '&detalle=true' +
                '" role="post" data-pjax="0" target="_blank" ' +
                'data-toggle="tooltip" data-original-title="" title="Imprimir detalle de entregas"><i class="glyphicon glyphicon-print"></i> Detalle</a>' +
                '<a class="btn btn-default" style="margin-left: 1%;margin-bottom: 1%;" href="index.php?r=sds_ent_entrega/reporte_rendicion_tc&identregas=' + identregas + '&externo=' + externo + '&intermedias=0' +
                '" role="post" data-pjax="0" target="_blank" ' +
                'data-toggle="tooltip" data-original-title="" title="Imprimir Rendición para Tribunal de Cuentas"><i class="glyphicon glyphicon-print"></i> Rendición</a>' +
                (' <a class="btn btn-default" style="margin-left: 1%;margin-bottom: 1%;" href="index.php?r=sds_ent_cierre/reporte_cierre&identrega=' + identreganodo +
                    '" role="post" data-pjax="0" target="_blank" ' +
                    'data-toggle="tooltip" data-original-title="" title="Rendición de Cierre"><i class="fas fa-users"></i> Rendición Cierre</a>') +
                '<a class="btn btn-default" style="margin-left: 1%;margin-bottom: 1%;" href="index.php?r=sds_ent_entrega/reporte_rendicion_tc&identregas=' + identregas + '&externo=' + externo + '&intermedias=1' +
                '" role="post" data-pjax="0" target="_blank" ' +
                'data-toggle="tooltip" data-original-title="" title="Imprimir Rendición para Tribunal de Cuentas"><i class="glyphicon glyphicon-print"></i> Rendición C/Interm.</a>' +
                ' <a class="btn btn-default" style="margin-left: 1%;margin-bottom: 1%;" href="index.php?r=sds_ent_entrega/index&estado=<?= Sds_ent_entrega::ESTADO_INTERMEDIA ?>&idemisor=' + identreganodo +
                '" role="post" data-pjax="0" target="_blank" ' +
                'data-toggle="tooltip" data-original-title="" title="Detalle de entrega"><i class="fas fa-eye"></i> Entregas</a>' +
                ' <a class="btn btn-default" style="margin-left: 1%;margin-bottom: 1%;" href="index.php?r=sds_ent_entrega/index&estado=<?= Sds_ent_entrega::ESTADO_DEUDOR ?>&idemisor=' + identreganodo +
                '" role="post" data-pjax="0" target="_blank" ' +
                'data-toggle="tooltip" data-original-title="" title="Detalle de Deudores"><i class="fas fa-users"></i> Deudores</a>';
            if (total == 0) {
                html_entregas = html_botones + '<br><br><h4>Entregas Finales</h4><p>No hay datos de entregas finales registrados.</p>';
            } else {
                let i = 0;
                //ANOTEZE: Cambio lista de items por paneles
                //cad_disp = '<ul id="listadispositivos">';   
                entregas.forEach(function(entrega, index) {
                    var numero = entrega['numero'];
                    html_entregas = html_entregas + "<section style=\"margin-bottom:2%;\" class=\"panel panel-featured-left panel-featured-info\" >" +
                        "<div id='" + entrega['identrega'] + "' style='cursor: pointer;' class='panel-body'>" +
                        (numero != null ? "<div class=\"col-md-12\">" +
                            "<b>Número: </b>" + numero +
                            "</div>" : "") +
                        "<div class=\"col-md-6\">" +
                        "<b>Emisor: </b>" + entrega['emisor'] +
                        "</div>" +
                        "<div class=\"col-md-6\">" +
                        "<b>Fecha: </b>" + formatearFecha(entrega['fecha_hora']) +
                        "</div>" +
                        "<div class=\"col-md-6\">" +
                        "<b>DNI: </b>" + entrega['dni'] +
                        "<br><b>Cantidad: </b>" + entrega['cantidad'] +
                        "</div>" +
                        "<div class=\"col-md-6\">" +
                        "<b>Ape. Nom.: </b>" + entrega['apellido'] + ", " + entrega['nombre'] +
                        "</div>" +
                        "<div class=\"col-md-6\">" +
                        "<b>Observaciones: </b>" + entrega['observaciones'] +
                        "</div>" +
                        "</div>" +
                        "</section>";
                });
                var texto_titulo = 'Entregas Finales - ' + entregas[0]['tipo'] + ' ( Total: ' + total + ' | Saldo: ' + (cantSel - total) + ') ';
                var texto_reporte = 'Entrega de ' + datosEntrega + '<br>Listado de entregas finales ( Total: ' + total + ' | Saldo: ' + (cantSel - total) + ') ';
                html_entregas = html_botones + '<br><br><h4>' + texto_titulo + '</h4>' + html_entregas;
            }
            $('#entregas_finales').html(html_entregas);
        });
    }

    function formatearFecha(fecha) {
        var day = fecha.substring(8, 10);
        var month = fecha.substring(5, 7);
        var year = fecha.substring(0, 4);
        var today = day + "/" + month + "/" + year;
        return today;
    }

    function cargarComboAnio(primerAnio) {
        var anioActual = (new Date).getFullYear();
        var html = '';
        for (var i = anioActual + 1; i >= primerAnio; i--) {
            html = html + '<option value="' + i + '">' + i + '</option>';
        }
        $("#cmbAnio").html(html);
        $("#cmbAnio").val(anioActual);
    }
</script>