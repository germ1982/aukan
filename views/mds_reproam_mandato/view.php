<?php

use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Html;


$this->title = "Ver Mandato #{$model->idmandato}";
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;

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

    .panel-primary .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
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
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Registro</label>
                        <input type="text" class="form-control" value="<?php echo $model->registro->nombre ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Carácter</label>
                        <input type="text" class="form-control" value="<?php echo ($model->titular === 1) ? 'Titular' : 'Suplente' ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Fecha Desde</label>
                        <input type="text" class="form-control" value="<?php echo $model->fecha_desde ? date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_desde))) :  null ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha Hasta</label>
                        <input type="text" class="form-control" value="<?php echo $model->fecha_hasta ? date('d/m/Y', strtotime(str_replace('/', '-', $model->fecha_hasta))) :  null ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" style="min-height: 30vh" readonly><?php echo $model->observaciones ?></textarea>
                    </div>
                </div>
                <br>
                <div class="card-footer" id="botones">
                    <a class="btn btn-info" href="index.php?r=mds_reproam_mandato/index">Volver </a>
                </div>
            </div>
        </section>
    </div>
</div>