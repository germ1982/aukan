<?php

use app\models\Mds_conc_vacante;
use yii\helpers\Html;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = "Ver Vacante #{$model->idvacante}";
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

<div class="mds-conc-vacante-view">
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
                    <li><span><?= "Ver vacante #{$model->idvacante}" ?></span></li>
                </ol>
                <div class="sidebar-right-toggle"></div>
            </div>
        </header>
    <?php endif ?>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <section class="panel">
                <div class="panel-body">
                    <?= $this->render('components/flash_messages') ?>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label">Concurso</label>
                            <input type="text" class="form-control" value="<?= $model->concurso ? $model->concurso->descripcion : null ?>" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Categoría</label>
                            <input type="text" class="form-control" value="<?= $model->categoria0 ? $model->categoria0->descripcion : null ?>" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">Cantidad</label>
                            <input type="text" class="form-control" value="<?= $model->cantidad ?>" readonly>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label">¿Requiere título?</label>
                            <input type="text" class="form-control" value="<?= $model->requiere_titulo ? 'Si' : 'No' ?>" readonly>
                        </div>
                    </div>
                    <?php if (!Yii::$app->request->isAjax) : ?>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-footer" id="botones">
                                    <a class="btn btn-info" href="index.php?r=mds_conc_vacante" title="Volver">Volver</a>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </section>
        </div>
    </div>
</div>