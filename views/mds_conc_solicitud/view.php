<?php

use app\models\Mds_conc_solicitud;
use app\models\Mds_conc_postulacion;
use yii\helpers\Html;
use johnitvn\ajaxcrud\CrudAsset;

$this->title = "Ver Solicitud #{$model->idsolicitud}";
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

<div class="mds-conc-solicitud-view">

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
                    <li><span><?= "Ver solicitud #{$model->idsolicitud}" ?></span></li>
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
                    <div>
                        <div class="row">
                            <div class="col-md-12" style="display: flex;">
                                <h5 style="margin: 0 auto 0 0;"><b>Fecha de carga: </b><?= $model->fechaCarga ?></h5>
                                <h5 style="margin: 0 0 0 auto;"><b>Usuario: </b><?= $model->idusuario ? strtoupper($model->usuarioCarga->apellido) . ' ' . strtoupper($model->usuarioCarga->nombre) . " (#{$model->usuarioCarga->idusuario})" : "" ?></h5>
                            </div>
                        </div>
                        <div style="margin-top: 10px;">
                            <?= Html::a('Exportar PDF', ['/mds_conc_solicitud/reporte', 'ids' => $model->idsolicitud], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
                        </div>
                    </div>
                    <br>
                    <div class="panel-group" id="accordion_solicitante">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_solicitante" href="#solicitante">
                                        Datos Personales
                                    </a>
                                </h4>
                            </div>
                            <div id="solicitante" class="accordion-body collapse in">
                                <div class="panel-body" id="solicitante_content">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Apellido</label>
                                            <input type="text" class="form-control" value="<?= $model->apellido ?>" readonly>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Nombre</label>
                                            <input type="text" class="form-control" value="<?= $model->nombre ?>" readonly>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Documento</label>
                                            <input type="text" class="form-control" value="<?= $model->documento ?>" readonly>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Legajo</label>
                                            <input type="text" class="form-control" value="<?= $model->legajo ?>" readonly>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" value="<?= $model->telefono ?>" readonly>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="form-label">Domicilio Fiscal</label>
                                            <input type="text" class="form-control" value="<?= $model->domicilio_fiscal ?>" readonly>
                                        </div>

                                        <div class="col-md-12 form-group">
                                            <label class="form-label">Correo electrónico</label>
                                            <input type="text" class="form-control" value="<?= $model->mail ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $rhsur = $model->getConcRhSur();
                    $tituloAccordionRhSur = $rhsur ? "RH SUR al $rhsur->mes/$rhsur->anio"  : "RH SUR"; ?>
                    <div class="panel-group" id="accordion_rhsur">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_rhsur" href="#rhsur">
                                        <?= $tituloAccordionRhSur ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="rhsur" class="accordion-body collapse in">
                                <div class="panel-body" id="rhsur_content">
                                    <?php if ($rhsur) : ?>
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Mes</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->mes ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Año</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->anio ?>" readonly>
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Legajo</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->legajo ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Unidad Operativa</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->idunidadoperativa ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Categoría Actual</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->categoria ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Apellido Nombre</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->apellido_nombre ?>" readonly>
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Sexo</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->sexo ? "MASCULINO" : "FEMENINO" ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">DNI</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->dni ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">CUIL</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->cuil ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Fecha Nacimiento</label>
                                                <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($rhsur->fecha_nacimiento)) ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Fecha Ingreso</label>
                                                <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($rhsur->fecha_ingreso)) ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Antigüedad Administrativa</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->antiguedad_administrativa ?>" readonly>
                                            </div>

                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Antigüedad Privada</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->antiguedad_privada ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Antigüedad Total</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->antiguedad_total ?>" readonly>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="form-label">Eventual</label>
                                                <input type="text" class="form-control" value="<?= $rhsur->eventual ? 'Si' : 'No' ?>" readonly>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        La persona no posee datos en RH SUR.
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" id="accordion_vacantes">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_vacantes" href="#vacantes">
                                        Postulaciones
                                    </a>
                                </h4>
                            </div>
                            <div id="vacantes" class="accordion-body collapse in">
                                <div class="panel-body" id="vacantes_content">

                                    <?php $postulaciones = $model->getPostulaciones(); ?>
                                    <?php if (count($postulaciones) > 0) : ?>
                                        <ul>
                                            <?php foreach ($postulaciones as $postulacion) :
                                                $motivosImpugnacionString = '';
                                                if ($postulacion->estado === Mds_conc_solicitud::ESTADO_NO_ADMITIDO || $postulacion->estado === Mds_conc_solicitud::ESTADO_IMPUGNADO) {
                                                    $motivosImpugnacion = Mds_conc_postulacion::getMotivosImpugnacionByIdPostulacion($postulacion->idpostulacion);
                                                    if (count($motivosImpugnacion) > 0) {
                                                        foreach ($motivosImpugnacion as $key => $motivo) {
                                                            $motivosImpugnacionString .=  $key + 1 === count($motivosImpugnacion) ? "{$motivo['descripcion']}" : "{$motivo['descripcion']}, ";
                                                        }
                                                    }
                                                } ?>
                                                <li>
                                                    <?php if (isset($postulacion->vacante)) : ?>
                                                        <p>
                                                            <?php if (isset($postulacion->vacante->categoria0)) : ?>
                                                                <strong>Categoría:</strong> <?= $postulacion->vacante->categoria0->descripcion ?><br />
                                                            <?php endif; ?>
                                                            <?php if (isset($postulacion->estado0)) : ?>
                                                                <strong>Estado actual:</strong> <?= $postulacion->estado0->descripcion ?><br />
                                                            <?php endif; ?>
                                                            <?php if (!is_null($postulacion->puntaje)) : ?>
                                                                <strong>Puntaje:</strong> <?= $postulacion->puntaje ?><br />
                                                            <?php endif; ?>
                                                            <?php if ($motivosImpugnacionString) : ?>
                                                                <strong>Motivos impugnación:</strong> <?= $motivosImpugnacionString ?><br />
                                                            <?php endif; ?>
                                                            <?= Html::a('Estados', ['/mds_conc_historial/index', 'idpostulacion' => $postulacion->idpostulacion], ['class' => 'btn btn-primary']) ?>
                                                        </p>
                                                        <hr>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>

                                        </ul>
                                    <?php else : ?>
                                        La solicitud no tiene asociada ninguna postulación.
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-group" id="accordion_adjunto">
                        <div class="panel panel-accordion">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_adjunto" href="#adjunto">
                                        Documentación
                                    </a>
                                </h4>
                            </div>
                            <div id="adjunto" class="accordion-body collapse in">
                                <div class="panel-body" id="adjunto_content">
                                    <div class="row">
                                        <?php if ($model->deudores_morosos) : ?>
                                            <div class='col-md-6' align="center" ;>
                                                Deudor Moroso
                                                <?php if (Mds_conc_solicitud::getExtension($model->deudores_morosos) == 'pdf') : ?>
                                                    <object width="90%" height="500px" type="application/pdf" data="<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->deudores_morosos" ?>">
                                                        <p>Archivo Adjunto no disponible.</p>
                                                    </object>
                                                <?php else : ?>
                                                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:90%;height=500px' id='base64image' src='<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->deudores_morosos" ?>' />
                                                <?php endif; ?>
                                                <?= Html::a(
                                                    'Ampliar',
                                                    env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->deudores_morosos",
                                                    [
                                                        'target' => '_blank',
                                                        'data-pjax' => '0',
                                                        'class' => 'btn btn-success',
                                                        'style' => 'width:80%; width:213px;',
                                                    ]
                                                ) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($model->registro_violencia) : ?>
                                            <div class='col-md-6' align="center" ;>
                                                Registro de Violencia
                                                <?php if (Mds_conc_solicitud::getExtension($model->registro_violencia) == 'pdf') : ?>
                                                    <object width="90%" height="500px" type="application/pdf" data="<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->registro_violencia" ?>">
                                                        <p>Archivo Adjunto no disponible.</p>
                                                    </object>
                                                <?php else : ?>
                                                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:90%;height=500px;' id='base64image' src='<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->registro_violencia" ?>' />
                                                <?php endif; ?>
                                                <?= Html::a(
                                                    'Ampliar',
                                                    env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->registro_violencia",
                                                    [
                                                        'target' => '_blank',
                                                        'data-pjax' => '0',
                                                        'class' => 'btn btn-success',
                                                        'style' => 'width:80%; width:213px;',
                                                    ]
                                                ) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <?php if ($model->antecedente_nacional) : ?>
                                            <div class='col-md-6' align="center">
                                                Antecedente Nacional
                                                <?php if (Mds_conc_solicitud::getExtension($model->antecedente_nacional) == 'pdf') : ?>
                                                    <object width="90%" height="500px" type="application/pdf" data="<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->antecedente_nacional" ?>">
                                                        <p>Archivo Adjunto no disponible.</p>
                                                    </object>
                                                <?php else : ?>
                                                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:90%;height=500px;' id='base64image' src='<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->antecedente_nacional" ?>' />
                                                <?php endif; ?>
                                                <?= Html::a(
                                                    'Ampliar',
                                                    env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->antecedente_nacional",
                                                    [
                                                        'target' => '_blank',
                                                        'data-pjax' => '0',
                                                        'class' => 'btn btn-success',
                                                        'style' => 'width:80%; width:213px;',
                                                    ]
                                                ) ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($model->titulo) : ?>
                                            <div class='col-md-6' align="center">
                                                Título <br>
                                                <?php if (Mds_conc_solicitud::getExtension($model->titulo) == 'pdf') : ?>
                                                    <object width="90%" height="500px" type="application/pdf" data="<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->titulo" ?>">
                                                        <p>Archivo Adjunto no disponible.</p>
                                                    </object>
                                                <?php else : ?>
                                                    <img style='display:block; border: ridge 1px; padding: 8px; border-color:#E6E6E6; width:90%;height=500px;' id='base64image' src='<?= env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->titulo" ?>' />
                                                <?php endif; ?>
                                                <?= Html::a(
                                                    'Ampliar',
                                                    env('ENDPOINT_BACKEND_SUR_FILE') . "/$model->titulo",
                                                    [
                                                        'target' => '_blank',
                                                        'data-pjax' => '0',
                                                        'class' => 'btn btn-success',
                                                        'style' => 'width:80%; width:213px;',
                                                    ]
                                                ) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!Yii::$app->request->isAjax) : ?>
                        <br />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-footer" id="botones">
                                    <a class="btn btn-info" href="index.php?r=mds_conc_solicitud" title="Volver">Volver</a>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            </section>
        </div>
    </div>

</div>