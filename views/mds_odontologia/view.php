<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Registro odontológico";
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
                    <div class="col-md-6">
                        <label class="form-label"><b>Usuario que lo cargó</b></label>
                        <?php $usuario_carga = "{$model->usuariocarga->apellido} {$model->usuariocarga->nombre} ({$model->usuariocarga->dni})" ?>
                        <input type="text" class="form-control" value="<?= $usuario_carga ?>" readonly>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label"><b>Persona</b></label>
                        <?php $persona = "{$model->persona->apellido} {$model->persona->nombre} ({$model->persona->documento})" ?>
                        <input type="text" class="form-control" value="<?= $persona ?>" readonly>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label"><b>Fecha de nacimiento</b></label>
                        <input type="text" class="form-control" value="<?php
                                                                        $fa = date_create($model->persona->fecha_nacimiento);
                                                                        $fa = date_format($fa, 'd-m-Y');
                                                                        echo $fa ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label"><b>Tipo de intervención</b></label>
                        <input type="text" class="form-control" value="<?= $model->tipointervencion->descripcion ?>" readonly>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label"><b>Fecha de atención</b></label>
                        <input type="text" class="form-control" value="<?php
                                                                        $fa = date_create($model->fecha_atencion);
                                                                        $fa = date_format($fa, 'd-m-Y');
                                                                        echo $fa ?>" readonly>
                    </div>
                    <?php if ($model->dispositivo) { ?>
                        <div class="col-md-4">
                            <label class="form-label"><b>Institución/Dispositivo</b></label>
                            <input type="text" class="form-control" value="<?= ($model->dispositivo) ? mb_strtoupper($model->dispositivo->descripcion) : '' ?>" readonly>
                        </div>
                    <?php } ?>
                    <?php if ($model->escolaridad) { ?>
                        <div class="col-md-4">
                            <label class="form-label"><b>Escolaridad</b></label>
                            <input type="text" class="form-control" value="<?= ($model->escolaridad) ? $model->escolaridad->descripcion : '' ?>" readonly>
                        </div>
                    <?php } ?>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label"><b>Teléfono</b></label>
                        <input type="text" class="form-control" value="<?= $model->telefono ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><b>Vacunas obligatorias</b></label>
                        <input type="text" class="form-control" value="<?= ($model->vacunas_obligatorias == 1) ? 'Si' : ($model->vacunas_obligatorias == 0 ? 'No' : 'Se desconoce') ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"><b>Vacunas COVID19</b></label>
                        <input type="text" class="form-control" value="<?= ($model->vacunacovid19) ? $model->vacunacovid19->descripcion : '' ?>" readonly>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <?php if ($model->tipovisita) { ?>
                        <div class="col-md-3">
                            <label class="form-label"><b>Tipo de visita</b></label>
                            <input type="text" class="form-control" value="<?= ($model->tipovisita) ? $model->tipovisita->descripcion : '' ?>" readonly>
                        </div>
                    <?php } ?>


                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label"><b>Dientes permanentes</b></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Cantidad de dientes</label>
                        <input type="text" class="form-control" value="<?= $model->cant_dientes ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cantidad de caries</label>
                        <input type="text" class="form-control" value="<?= $model->cant_caries ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cantidad de obturados</label>
                        <input type="text" class="form-control" value="<?= $model->cant_obturados ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cantidad de perdidos</label>
                        <input type="text" class="form-control" value="<?= $model->cant_perdidos ?>" readonly>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label"><b>Dientes temporales</b></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Cantidad de dientes</label>
                        <input type="text" class="form-control" value="<?= $model->cant_dientes_temporales ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cantidad de caries</label>
                        <input type="text" class="form-control" value="<?= $model->cant_caries_temporales ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cantidad de obturados</label>
                        <input type="text" class="form-control" value="<?= $model->cant_obturados_temporales ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cantidad de perdidos</label>
                        <input type="text" class="form-control" value="<?= $model->cant_perdidos_temporales ?>" readonly>
                    </div>
                </div>
                <br>
                <?php if ($model->enfermedad_periodontal) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label"><b>Enfermedad periodontal</b></label>
                            <div class="alert alert-light " role="alert">
                                <p><?= $model->enfermedad_periodontal;  ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($model->enfermedad_periodontal) { ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label"><b>Enfermedad base</b></label>
                            <div class="alert alert-light " role="alert">
                                <p><?= $model->enfermedad_base;  ?></p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label"><b>Observaciones</b></label>
                        <div class="alert alert-light " role="alert">
                            <p><?= $model->observaciones;  ?></p>
                        </div>
                    </div>
                </div>
                <hr>
                <?php
                $adjuntos = $model->getAdjuntos();
                ?>
                <?php
                if (count($adjuntos) > 0) : ?>
                    <div class="row">
                        <div class="col-md-12">
                            <br />
                            <label><b>Archivos adjuntos</b></label>
                            <ul style="list-style: none">
                                <?php
                                foreach ($adjuntos as $adjunto) : ?>
                                    <li><a><i class="fas fa-paperclip">&nbsp &nbsp</i><?= Html::a($adjunto->nombre, Url::base() . '/' . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                            <br>
                        </div>
                    </div>
                <?php endif ?>
                <br>
                <div class="card-footer" id="botones">
                    <a class="btn btn-info" href="index.php?r=mds_odontologia/index">Volver</a>
                    <button type="button" class="btn btnPrint">
                        <?php $url =  Url::to(['/mds_odontologia/detalle_registro', 'id' => $model->idodontologia]); ?>
                        <a href="<?php echo $url ?>" target="_blank">Exportar PDF</a>
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>