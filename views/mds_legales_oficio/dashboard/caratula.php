<?php

use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_legales_oficioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seguimiento carátulas';
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);

$urlIndex = "index.php?r=mds_legales_oficio/index";
$urlCaratulas = "index.php?r=mds_legales_caratula/index";

$caratulaAnterior = null;
$cantidadRequerimientosPorRow = 0;
$ultimaPosicionCaratulasConMasRequerimientos = count($caratulasConMasRequerimientos) - 1;
$colAbierto = false;
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

    .titulo-dashboard {
        padding-left: 15px;
        margin: 0 0 20px 0;
        text-decoration: underline;
    }

    .placeholder {
        margin-bottom: 10px;
    }

    .subtitulo-dashboard {
        margin-bottom: 20px;
        text-align: justify;
        font-weight: bold;
    }

    .fecha-container {
        margin-bottom: 2rem;
    }
</style>
<header class="page-header">
    <h2><?= $this->title ?></h2>

    <div class="right-wrapper pull-right">
        s
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
                <div class="alert alert-info" role="alert">
                    Si lo desea, puede seleccionar un <b>periodo de días</b>.
                    <ul>
                        <li>
                            Si solo se selecciona "<b>Fecha inicio</b>" se buscarán los requerimientos creados <b>a partir de esa fecha</b>.
                        </li>
                        <li>
                            Si solo se selecciona "<b>Fecha fin</b>" se buscarán los requerimientos creados <b>hasta esa fecha</b>.
                        </li>
                        <li>
                            Si se selecciona "<b>Fecha inicio</b>" y "<b>Fecha fin</b>" se buscarán los requerimientos creados <b>entre esas dos fechas</b>.
                        </li>
                        <li>
                            Si no se selecciona <b>ninguna fecha</b> se buscarán <b>todos los requerimientos</b>.
                        </li>
                    </ul>
                </div>
                <?php $form = ActiveForm::begin(['id' => 'formOficio', 'action' => ['mds_legales_oficio/dashboard_caratula'], 'options' => ['enctype' => 'multipart/form-data', 'form-oficio']]); ?>
                <div class="row fecha-container">
                    <div class="col-md-4 col-sm-12">
                        <div class="col-md-3 required">
                            <label class="col-form-label" for="FECHA_INICIO">Fecha inicio:</label>
                        </div>
                        <div class="col-md-9">
                            <input class="form-control" type="date" name="FECHA_INICIO" id="FECHA_INICIO" value="<?= $fechaInicio ?>">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="col-md-3 required">
                            <label class="col-form-label" for="FECHA_FIN">Fecha fin:</label>
                        </div>
                        <div class="col-md-9">
                            <input class="form-control" type="date" name="FECHA_FIN" id="FECHA_FIN" value="<?= $fechaFin ?>">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-12">
                        <?= Html::submitButton("Buscar", ['class' => 'btn btn-success', 'id' => 'btnSave']) ?>
                        <button type="button" class='btn btn-info' id="boton-limpiar-fechas">Limpiar</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
                <hr>
                <div class="row">
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard">TOTAL CARÁTULAS</h4>
                        <a href="<?= $urlCaratulas ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $cantidadCaratulas; ?></span></strong></a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="subtitulo-dashboard">CARÁTULAS CON MAYOR CANTIDAD DE REQUERIMIENTOS (<?= $limiteCaratulasMayorRequerimientos ?> PRIMERAS):</h4>
                    </div>
                    <?php foreach ($caratulasConMasRequerimientos as $indexCaratula => $caratula) :
                        $caratulaDescripcion = $caratula['idlegalescaratula'] ? "<b>Carátula #{$caratula['idlegalescaratula']}</b>" : '';
                        $nroExpediente = $caratula['numero_expediente'] ? "<b>Nro. Exp.:</b> {$caratula['numero_expediente']}" : '';
                        $caso = $caratula['caso'] ? "<b>Caso:</b> {$caratula['caso']}" : '';
                        $anio = $caratula['anio_expediente'] ? "<b>Año:</b> {$caratula['anio_expediente']}" : '';
                        $guionExpediente = $caratula['caratula'] ? ' - ' : '';
                        $guionCaso = $caratula['caratula'] || $caratula['numero_expediente'] ? ' - ' : '';
                        $guionAnio = $caratula['caratula'] || $caratula['numero_expediente'] || $caratula['caso'] ? ' - ' : '';
                        $nroExpedienteDescripcion = $caratula['numero_expediente'] ? "$guionExpediente$nroExpediente" : '';
                        $casoDescripcion = $caratula['caso'] ? "$guionCaso$caso" : '';
                        $anioDescripcion = $caratula['anio_expediente'] ? "$guionAnio$anio" : '';
                        $optionCaratulaDescripcion = "$caratulaDescripcion $nroExpedienteDescripcion $casoDescripcion $anioDescripcion";

                        //Para el accordion de caratulas
                        $accordionCaratulaDetalle = $caratula['caratula'] ? "<p><b>Carátula:</b> {$caratula['caratula']}</p>" : '';
                        $accordionNroExpediente = $nroExpediente ? "<p>$nroExpediente</p>" : '';
                        $accordionCaso = $caso ? "<p>$caso</p>" : '';
                        $accordionAnio = $anio ? "<p>$anio</p>" : '';

                        //Si la cantidad de requerimientos es 0 creo un row
                        if ($cantidadRequerimientosPorRow == 0 && !$colAbierto) : ?>
                            <div class="row" style="margin: 0">
                            <?php endif;

                        //Si es la primer posicion o la cantidad de la caratula anterior es distinta a la caratula actual, debo crear el titulo
                        if ($indexCaratula == 0 || ($caratulaAnterior && $caratulaAnterior['cantidadRequerimientos'] != $caratula['cantidadRequerimientos'])) :
                            $colAbierto = true; ?>
                                <div class="col-md-6 col-sm-12 placeholder">
                                    <p style="font-size: 2rem; text-decoration:underline">
                                        <?= $caratula['cantidadRequerimientos']; ?> requerimientos:
                                    </p>
                                <?php endif; ?>

                                <div class="panel-group">
                                    <div class="panel panel-accordion" id="accordion_caratula_<?= $caratula['idlegalescaratula'] ?>">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_caratula_<?= $caratula['idlegalescaratula'] ?>" href="#detalle_caratula_<?= $caratula['idlegalescaratula'] ?>">
                                                    <?= $optionCaratulaDescripcion ?>
                                                    <i class="glyphicon glyphicon-menu-down"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="detalle_caratula_<?= $caratula['idlegalescaratula'] ?>" class="accordion-body collapse">
                                            <div class="panel-body">
                                                <?= "$accordionCaratulaDetalle $accordionNroExpediente $accordionCaso $accordionAnio" ?>
                                                <a href="<?= $urlIndex ?>&idLegalesCaratula=<?= $caratula['idlegalescaratula'] ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank">Ver listado de requerimientos</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                //Si es la ultima posicion del arreglo o la caratula siguiente tiene una cantidad distinta de requerimientos, debo cerrar el div del col
                                if (($indexCaratula == $ultimaPosicionCaratulasConMasRequerimientos) || ($indexCaratula < $ultimaPosicionCaratulasConMasRequerimientos && $caratula['cantidadRequerimientos'] != $caratulasConMasRequerimientos[$indexCaratula + 1]['cantidadRequerimientos'])) :
                                    $colAbierto = false;
                                    $cantidadRequerimientosPorRow++; ?>
                                </div>
                            <?php endif;

                                //Si es la ultima posicion del arreglo o la cantidad de requerimientos dentro del row es 2, debo cerrar el div del row
                                if (!$colAbierto && ($indexCaratula == $ultimaPosicionCaratulasConMasRequerimientos || $cantidadRequerimientosPorRow == 2)) :
                                    $cantidadRequerimientosPorRow = 0; ?>
                            </div>
                        <?php endif;
                                $caratulaAnterior = $caratula; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
$this->registerJs(
    "$('#ajaxCrudModal').on('hidden.bs.modal', function() {
            location.reload();
        })

    $('#boton-limpiar-fechas').click(function() {
        $('#FECHA_INICIO').val('');
        $('#FECHA_FIN').val('');
    })
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