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
$urlIndex = "index.php?r=mds_reproam_registro/index";

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
                <?php $form = ActiveForm::begin(['id' => 'formOficio', 'action' => ['mds_reproam_registro/dashboard', 'inscripto' => $inscripto], 'options' => ['enctype' => 'multipart/form-data', 'form-oficio']]); ?>
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
                <?= $this->render('../components/flash_messages') ?>
                <hr>
                <div class="row ">
                    <div class="col-md-6 col-sm-12 placeholder">
                        <h4 class="subtitulo-dashboard"><b>TOTAL REGISTROS:</b></h4>
                        <a href="<?= $urlIndex ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= count($totalRegistros); ?></span></strong></a>
                    </div>
                </div>
                <?php if (!empty($arrayTotales)) : ?>
                    <div class="row">
                        <?php foreach ($arrayTotales as $total) : ?>
                            <div class="col-md-6 col-sm-12 placeholder">
                                <h4 class="subtitulo-dashboard"> <?= "<b>{$total['titulo']}</b> <br />" ?></h4>
                                <a href="<?= $urlIndex ?><?= "&tipo={$total['tipo']}" ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $total['total']; ?></span></strong></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($arrayIndicadores)) : ?>
                    <?php
                    $indicadorAnterior = null;
                    $existeTitulo = false;
                    foreach ($arrayIndicadores as $index => $indicador) :
                        $esUltimaPosicion = $index == (count($arrayIndicadores) - 1);
                        if ($existeTitulo && $indicadorAnterior && ($indicadorAnterior['titulo'] != $indicador['titulo'])) :
                            $existeTitulo = false; ?>
            </div>
        <?php endif;
                        if ($indicador['cantidadRegistros'] > 0) : ?>
            <?php if (!$existeTitulo) : ?>
                <hr>
                <div class="row">
                    <h2 class="titulo-dashboard"><?= $indicador['titulo'] ?>:</h2>
                <?php
                                $existeTitulo = true;
                            endif;
                            $descripcion = mb_strtoupper($indicador['descripcion']); ?>
                <div class="col-md-6 col-sm-12 placeholder">
                    <h4 class="subtitulo-dashboard"> <?= "<b>$descripcion</b> <br />" ?></h4>
                    <a href="<?= $urlIndex . $indicador['url'] ?><?= ($fechaInicio) ? "&fechaInicio=$fechaInicio" : '' ?><?= ($fechaFin) ? "&fechaFin=$fechaFin" : '' ?>" target="_blank"><strong style="font-size: 40px"><span><?= $indicador['cantidadRegistros']; ?></span></strong></a>
                </div>
            <?php endif;
                        if ($existeTitulo && $esUltimaPosicion) : ?>
                </div>
        <?php endif;
                        $indicadorAnterior = $indicador;
                    endforeach; ?>
    <?php endif; ?>
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