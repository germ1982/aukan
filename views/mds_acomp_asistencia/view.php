<?php

use yii\helpers\Html;
use yii\helpers\Url;


$this->title = "Ver Asistencia #{$model->idasistencia}";


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

    .btnPrint {
        background: grey !important;
    }

    .btnPrint a {
        color: white !important;
        text-decoration: none !important;
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
                    <div class="col-md-12">
                        <label class="form-label">Beneficiario</label>
                        <?php $beneficiario = $model->beneficiario->apellido . " " . $model->beneficiario->nombre . " (" . $model->beneficiario->documento . ")" ?>
                        <input type="text" class="form-control" value="<?php echo $beneficiario ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Localidad</label>
                        <input type="text" class="form-control" value="<?php echo $model->localidad->descripcion ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Localidad ingreso</label>
                        <input type="text" class="form-control" value="<?php echo $model->localidadIngreso->descripcion ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Perido Desde</label>

                        <input type="text" class="form-control" <?php
                                                                if (($model->periodo_desde) != null) { ?> value="<?php
                                                                        $fr = date_create($model->periodo_desde);
                                                                        $fr = date_format($fr, 'd-m-Y');
                                                                        echo $fr ?>" <?php } ?> readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Perido Hasta</label>

                        <input type="text" class="form-control" <?php
                                                                if (($model->periodo_hasta) != null) { ?> value="<?php
                                                                        $fr = date_create($model->periodo_hasta);
                                                                        $fr = date_format($fr, 'd-m-Y');
                                                                        echo $fr ?>" <?php } ?> readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Riesgos</label>
                        <input type="text" class="form-control" value="<?php echo $model->riesgo->descripcion ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usuario de carga</label>
                        <?php $usuario = $model->usuarioCarga->apellido . " " . $model->usuarioCarga->nombre ?>
                        <input type="text" class="form-control" value="<?php echo $usuario ?>" readonly>
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
                    <a class="btn btn-info" href="index.php?r=mds_acomp_asistencia/index">Volver </a>
                    <button type="button" class="btn btnPrint">
                        <?php $url =  Url::to(['/mds_acomp_asistencia/detalle_asistencia', 'id' => $model->idasistencia]); ?>
                        <a href="<?php echo $url ?>" target="_blank">Exportar PDF</a>
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>