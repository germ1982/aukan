<?php

use app\models\Mds_conc_cronograma;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\ActiveForm;

date_default_timezone_set('America/Argentina/Buenos_Aires');
$this->title = "Ver Etapa #{$model->idetapa}";
$this->params['breadcrumbs'][] = $this->title;
CrudAsset::register($this);

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
</style>

<div class="mds-conc-etapa-view">

    <?php if (!Yii::$app->request->isAjax) : ?>
        <header class="page-header">
            <h2><?= $this->title ?></h2>
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="index.php">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span><?= "Ver etapa #{$model->idetapa}" ?></span></li>
                </ol>
                <div class="sidebar-right-toggle"></div>
            </div>
        </header>
    <?php endif ?>


    <div class="mds-conc-historial-view">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-sm-12 col-md-6 form-group">
                <label class="form-label">Concurso</label>
                <input type="text" class="form-control" value="<?= $model->concurso ? $model->concurso->descripcion : null ?>" readonly>
            </div>
            <div class="col-sm-12 col-md-6 form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" value="<?= $model->nombre ?>" readonly />
            </div>
            <div class="col-sm-12 col-md-6 form-group">
                <label>Orden</label>
                <input type="text" class="form-control" value="<?= $model->orden ?>" readonly />
            </div>
            <div class="col-sm-12 col-md-6 form-group">
                <label>Estado</label>
                <input type="text" class="form-control" value="<?= $model->estado ? 'Si' : 'No' ?>" readonly />
            </div>
            <div class="col-sm-12 col-md-6 form-group">
                <label>Fecha inicio</label>
                <input type="text" class="form-control" value="<?= $fechaInicio ?>" readonly />
            </div>
            <div class="col-sm-12 col-md-6 form-group">
                <label>Fecha fin</label>
                <input type="text" class="form-control" value="<?= $fechaFin ?>" readonly />
            </div>
            <div class="col-md-12 form-group">
                <label class="form-label">Detalle</label>
                <textarea class="form-control" rows="3" readonly><?php echo $model->detalle ?></textarea>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>