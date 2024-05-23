<?php

use app\models\Mds_seg_usuario_status;
use app\models\Mds_conc_postulacion;
use yii\helpers\Html;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = "Ver estado #{$model->idseg_usuario_status}";
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

<div class="mds-seg-usuario-status-view">

    <?php if (!Yii::$app->request->isAjax) : ?>
        <header class="page-header">
            <h2><?= $this->title ?></h2>
            <div class="right-wrapper pull-right">
                <ol class="breadcrumbs">
                    <li>
                        <a href="index.html">
                            <i class="fa fa-home"></i>
                        </a>
                    </li>
                    <li><span><?= "Ver estado #{$model->idseg_usuario_status}" ?></span></li>
                </ol>
                <div class="sidebar-right-toggle"></div>
            </div>
        </header>
    <?php endif ?>

    <div class="row">
        <div class="col-md-6 form-group">
            <label class="form-label">Fecha de carga</label>
            <input type="text" class="form-control" value="<?= $model->fechaCarga ?>" readonly>
        </div>
        <div class="col-md-6 form-group">
            <label class="form-label">Estado:</label>
            <input type="text" class="form-control" value="<?= $model->estado ? $model->estado->descripcion : "" ?>" readonly>
        </div>
        <div class="col-md-6 form-group">
            <label class="form-label">Usuario:</label>
            <input type="text" class="form-control" value="<?= $model->idusuario ? strtoupper($model->usuario->apellido) . ' ' . strtoupper($model->usuario->nombre) . " (#{$model->usuario->idusuario})" : "" ?>" readonly>
        </div>
        <div class="col-md-6 form-group">
            <label class="form-label">Usuario de carga:</label>
            <input type="text" class="form-control" value="<?= $model->usuarioCarga ? strtoupper($model->usuarioCarga->apellido) . ' ' . strtoupper($model->usuarioCarga->nombre) . " (#{$model->usuarioCarga->idusuario})" : "" ?>" readonly>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) : ?>
        <br />
        <div class="row">
            <div class="col-md-12">
                <div class="card-footer" id="botones">
                    <a class="btn btn-info" href="index.php?r=mds_seg_usuario_status" title="Volver">Volver</a>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
</section>
</div>
</div>

</div>