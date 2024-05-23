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

$cantidadPorRow = 0;
$categoriaAnterior = null;
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

    .titulo-concurso {
        font-size: 3rem;
        text-align: center;
        text-decoration: underline;
        margin-top: 0;
    }

    .estados-vacante {
        padding: 10px;
        margin: 30px;
        background-color: #f1f1f1;
        border-radius: 10px;
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
                            Si solo se selecciona "<b>Fecha inicio</b>" se buscarán las postulaciones creadas <b>a partir de esa fecha</b>.
                        </li>
                        <li>
                            Si solo se selecciona "<b>Fecha fin</b>" se buscarán las postulaciones creadas <b>hasta esa fecha</b>.
                        </li>
                        <li>
                            Si se selecciona "<b>Fecha inicio</b>" y "<b>Fecha fin</b>" se buscarán las postulaciones creadas <b>entre esas dos fechas</b>.
                        </li>
                        <li>
                            Si no se selecciona <b>ninguna fecha</b> se buscarán <b>todas las postulaciones</b>.
                        </li>
                    </ul>
                </div>
                <?php $form = ActiveForm::begin(['id' => 'formCertificacion', 'action' => ['mds_conc_postulacion/dashboard'], 'options' => ['enctype' => 'multipart/form-data', 'form-postulacion']]); ?>
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
                <?php if (count($concursos) > 0) : ?>
                    <?php foreach ($concursos as $concurso) :
                        $postulaciones = $concurso['postulaciones'];
                        $arrayIndicadores = $concurso['indicadores'];
                        $categorias = $concurso['categorias'];
                    ?>
                        <div class="row" style="margin: 0">
                            <h1 class="titulo-concurso"><b><?= $concurso['descripcion'] ?></b></h1>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 placeholder">
                                    <h4 class="subtitulo-dashboard"><b>TOTAL POSTULACIONES:</b></h4>
                                    <strong style="font-size: 40px"><span><?= count($postulaciones); ?></span></strong>
                                </div>
                            </div>
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
                                    if (isset($indicador['cantidadRegistros']) && $indicador['cantidadRegistros'] > 0) : ?>
                        <?php if (!$existeTitulo) : ?>
                            <hr>
                            <div class="row">
                                <h2 class="titulo-dashboard"><?= $indicador['titulo'] ?>:</h2>
                                <div class="row estados-vacante">
                                <?php
                                            $existeTitulo = true;
                                        endif;
                                        $descripcion = mb_strtoupper($indicador['descripcion']); ?>
                                <div class="col-md-6 col-sm-12 placeholder">
                                    <h4 class="subtitulo-dashboard" style="color: <?= isset($indicador['color']) ? $indicador['color'] : '#777' ?>"> <?= "<b>$descripcion</b> <br />" ?></h4>
                                    <p><strong style="font-size: 40px"><span><?= $indicador['cantidadRegistros']; ?></span></strong></p>
                                </div>
                            <?php endif;
                                    if ($existeTitulo && $esUltimaPosicion) : ?>
                                </div>
                            </div>
                    <?php endif;
                                    $indicadorAnterior = $indicador;
                                endforeach; ?>
                <?php endif; ?>
                <?php if (!empty($categorias)) : ?>
                    <hr>
                    <div class="row">
                        <h2 class="titulo-dashboard">Por categorías:</h2>
                        <?php foreach ($categorias as $index => $categoria) :
                                if (isset($categoria['estados'])) : ?>
                                <div class="row estados-vacante">
                                    <h3 class="titulo-dashboard"><?= $categoria['descripcion'] ?></h3>
                                    <?php foreach ($categoria['estados'] as $estado) : ?>
                                        <div class="col-md-6 col-sm-12 placeholder">
                                            <h4 class="subtitulo-dashboard" style="color : <?= isset($estado['color']) ? $estado['color'] : '#777' ?>"> <?= "<b>{$estado['descripcion']}</b> <br />" ?></h4>
                                            <p><strong style="font-size: 40px"><span><?= $estado['cantidadRegistros']; ?></span></strong></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <h3>No existen postulaciones</h3>
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