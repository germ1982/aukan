<?php

use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Mds_org_informe */

$this->title = 'Ver Informe N° ' . $model->idinforme;
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    #base64image {
        display: block;
        border: ridge 1px;
        padding: 8px;
        border-color: #E6E6E6;
        max-width: 40%;
    }

    .campo {
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .alert-detalle {
        color: #555555;
        background-color: #efefef;
        border-color: lightgray;
        margin-bottom: 15px;
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

                    <div class="col-md-12 form-group">
                        <label class="form-label">Fecha de Informe:</label>
                        <input type="text" class="form-control" value="<?= date_format(date_create($model->fecha), 'd/m/Y')  ?>" readonly>
                    </div>

                    <div class="col-md-12 form-group">
                        <label class="form-label">Usuario:</label>
                        <input type="text" class="form-control" value="<?= $model->getNombrePersona($model->idusuario)  ?>" readonly>
                    </div>

                    <div class="col-md-12 form-group">
                        <label class="form-label">Tipo de Informe:</label>
                        <input type="text" class="form-control" value="<?= $model->tipo0 ? $model->tipo0->descripcion : ""  ?>" readonly>
                    </div>

                    <div class="col-md-12 form-group">
                        <label class="form-label">Organismo:</label>
                        <input type="text" class="form-control" value="<?= $model->iddispositivo0->organismo ? $model->iddispositivo0->organismo['descripcion'] : ""  ?>" readonly>
                    </div>

                    <div class="col-md-12 form-group">
                        <label class="form-label">Dispositivo:</label>
                        <input type="text" class="form-control" value="<?= $model->iddispositivo0 ? $model->iddispositivo0->descripcion : ""  ?>" readonly>
                    </div>

                    <?php if (count($compartidos) > 0) : ?>
                        <div class="col-md-12 form-group">
                            <label class="form-label">Compartido con:</label>
                            <ul>
                                <?php foreach ($compartidos as $compartido) : ?>
                                    <?php if ($compartido->idusuario0) :
                                        $visto = "";
                                        if ($compartido->visto == 2) {
                                            $vistoFecha = $compartido->visto_fecha ? date('d/m/Y H:i', strtotime($compartido->visto_fecha)) : null;
                                            $visto = $vistoFecha ? " (visto el día: $vistoFecha)" : " (visto)";
                                        } ?>
                                        <li>
                                            <?= mb_strtoupper($compartido->idusuario0->apellido) . ', ' . mb_strtoupper($compartido->idusuario0->nombre) .  "<b>$visto</b>" ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-12 form-group">
                        <label class="form-label">Asunto:</label>
                        <input type="text" class="form-control" value="<?= $model->asunto ?>" readonly>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Detalle:</label>
                        <div class="alert alert-detalle" role="alert">
                            <p><?= $model->detalle;  ?></p>
                        </div>
                    </div>
                </div>

                <?php if (!empty($model->adjuntos)) { ?>
                    <div class="col-md-12" style="margin: 1rem 0 2rem 0;">
                        <label>Archivos adjuntos:</label>
                        <?php
                        foreach ($model->adjuntos as $adjunto) { ?>
                            <ul style="list-style: none">
                                <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto['nombre'], Url::base() . '/uploads/informes/' . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                            </ul>
                        <?php } ?>
                    </div>
                <?php } ?>
                <br>

                <div class="card-footer">
                    <?php if ($urlAnterior) : ?>
                        <a class="btn btn-info" href="<?= $urlAnterior ?>">Volver</a>
                    <?php endif; ?>
                    <button type="button" class="btn btnPrint">
                        <?php $url =  Url::to(['/mds_org_informe/reporte_informe', 'idinforme' => $model->idinforme]); ?>
                        <a href="<?= $url ?>" target="_blank">Exportar PDF</a>
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>