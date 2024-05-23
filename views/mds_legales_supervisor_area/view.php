<?php

use johnitvn\ajaxcrud\CrudAsset;

$this->title = "Supervisor/a de área";
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
                        <label class="form-label">Área</label>
                        <input type="text" class="form-control" value="<?php echo $model->area->descripcion ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usuario</label>
                        <input type="text" class="form-control" value="<?php echo $model->usuario->nombre ?>" readonly>
                    </div>
                </div>
                <br>
                <div class="card-footer" id="botones">
                    <a class="btn btn-info" href="index.php?r=mds_legales_supervisor_area/index">Volver </a>
                </div>
            </div>
        </section>
    </div>
</div>