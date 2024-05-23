<?php

use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel app\models\Mds_legales_oficioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seguimiento';
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);

$urlIndex = "index.php?r=mds_legales_oficio/index";
$urlVencidos = "index.php?r=mds_legales_oficio/requerimientosvencidos";
$urlCaratulas = "index.php?r=mds_legales_caratula/index";
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
                <?php $form = ActiveForm::begin(['id' => 'formOficio', 'action' => ['mds_legales_oficio/dashboard'], 'options' => ['enctype' => 'multipart/form-data', 'form-oficio']]); ?>
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
                <div class="row ">
                    <h2 class="titulo-dashboard">Dirección General de Registro y Vinculación Judicial:</h2>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard"><b>TOTAL REQUERIMIENTOS</b></h4>
                        <a href="<?= $urlIndex ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= count($totalOficios); ?></span></strong></a>
                    </div>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard" style="color:#d2322d"><b>TOTAL VENCIDOS</b></h4>
                        <a href="<?= $urlVencidos ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= count($oficiosFueraDeTermino); ?></span></strong></a>
                    </div>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard"><b>PORCENTAJE DE VENCIDOS</b></h4>
                        <strong style="font-size: 40px; color: #08c"><span><?= round(count($oficiosFueraDeTermino) / count($totalOficios) * 100, 2) ?>%</span></strong></a>
                    </div>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard"><b>TOTAL CARÁTULAS</b></h4>
                        <a href="<?= $urlCaratulas ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $cantidadCaratulas; ?></span></strong></a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h2 class="titulo-dashboard">Dirección Provincial:</h2>
                    <?php
                    $usuarioAnterior = null;
                    foreach ($arrayUsuariosDashboard as $usuario) :
                        if ($usuarioAnterior && ($usuario['tipo'] != $usuarioAnterior['tipo'])) : ?>
                </div>
                <hr>
                <div class="row">
                    <h2 class="titulo-dashboard">Dirección General:</h2>
                <?php endif;
                        $usuarioAnterior = $usuario; ?>
                <?php if ($usuario['tipo'] == 'PROVINCIAL') : ?>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard"> <?= "<b>{$usuario['titulo']}</b>  <br /> <small>(ÁREA)</small>" ?></h4>
                        <a href="<?= $urlIndex ?>&idArea=<?= $usuario['idArea'] ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px;"><span><?= $usuario['totalRequerimientosArea']; ?></span></strong></a>
                    </div>
                <?php endif; ?>
                <?php if ($usuario['totalRequerimientosUsuario'] > 0) : ?>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard"> <?= "<b>{$usuario['titulo']}</b> <br /> <small>({$usuario['nombre']})</small>" ?></h4>
                        <a href="<?= $urlIndex ?>&idUsuario=<?= $usuario['idUsuario'] ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $usuario['totalRequerimientosUsuario']; ?></span></strong></a>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
                </div>
                <hr>
                <div class="row">
                    <h2 class="titulo-dashboard">Supervisor/a - Generador/a de Respuesta:</h2>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard" style="color:#47a447"><b> CON RESPUESTAS ENVIADAS</b></h4>
                        <a href="<?= $urlIndex ?>&idEstado=<?= $estadoAprobado ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $totalRequerimientosAprobados; ?></span></strong></a>
                    </div>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard" style="color:#ed9c28"><b>PENDIENTES DE SUPERVISIÓN</b></h4>
                        <a href="<?= $urlIndex ?>&idEstado=<?= $estadoPendiente ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $totalRequerimientosPendientesSupervision; ?></span></strong></a>
                    </div>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard" style="color:#d2322d"><b>SIN RESPUESTAS</b></h4>
                        <a href="<?= $urlIndex ?>&tipo=sin_respuesta<?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= count($totalOficiosSinResponder); ?></span></strong></a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h2 class="titulo-dashboard">Equipo de Supervisión Final:</h2>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard" style="color:#5bc0de"><b>APROBADOS</b></h4>
                        <a href="<?= $urlIndex ?>&idEstado=<?= $estadoEnviada ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $totalRequerimientosEnviados; ?></span></strong></a>
                    </div>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard" style="color:#ed9c28"><b>PENDIENTES DE SUPERVISIÓN</b></h4>
                        <a href="<?= $urlIndex ?>&tipo=pendiente_supervision_final<?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $totalRequerimientosPendientesSupervisionFinal; ?></span></strong></a>
                    </div>
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard" style="color:#d2322d"><b>DEVUELTOS Y SIN RECTIFICACIÓN</b></h4>
                        <a href="<?= $urlIndex ?>&tipo=devueltos_sin_rectificacion<?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $totalRequerimientosDevueltosSupervisionFinal; ?></span></strong></a>
                    </div>
                </div>
                <?= $this->render('../components/flash_messages') ?>
                <br>

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