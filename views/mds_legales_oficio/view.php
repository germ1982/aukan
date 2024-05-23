<?php

use yii\helpers\Html;
use johnitvn\ajaxcrud\CrudAsset;
use yii\helpers\Url;
use app\models\Mds_legales_respuesta_estado;

$this->title = "Ver requerimiento #{$oficio->idlegalesoficio}";
$this->params['breadcrumbs'][] = $this->title;
$idusuario = Yii::$app->user->identity->idusuario;
$idEstadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;
$idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
$idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;
$idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
$idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
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

    .panel-heading {
        background: darkgrey !important;
        border-color: darkgrey !important;
        color: black !important;
    }

    .hr-respuestas {
        background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
        margin: 0 auto 35px auto;
        width: 60%;
    }

    .alert-respuesta {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
    }

    .alert-observaciones {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
        max-height: 300px;
        overflow-y: auto;
    }

    .primer-oficio {
        margin-top: 34px;
    }

    .overflow-y {
        max-height: 450px;
        overflow-y: auto;
    }

    .boton-modal-default {
        display: inline-block;
        margin-bottom: 0;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        line-height: 1.42857143;
        border-radius: 4px;
        border-color: #adadad;
    }

    .boton-modal-default:hover {
        color: #333;
        background-color: #e6e6e6;
    }

    @media screen and (max-width: 991px) {
        .encargado-margin {
            margin-bottom: 1rem;
        }

        .primer-oficio {
            margin: 10px 0;
        }
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

            <div class="panel-heading">Requerimiento #<?php echo $oficio['idlegalesoficio'] ?></div>

            <div class="panel-body">
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="form-label">Emisor órgano superior</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->emisor->descripcion ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Entidad requirente</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->lugar_libramiento ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Localidad</label>
                        <input type="text" class="form-control" id="donde_se_tramita" value="<?php echo $oficio->donde_tramita ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-4">
                        <label class="form-label">Responsable de la entidad requirente</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->doctor_a_cargo ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha recepción</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->fecha_recepcion ? date('d/m/Y', strtotime(str_replace('/', '-', $oficio->fecha_recepcion))) :  null ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Fecha requerimiento</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->fecha_oficio ? date('d/m/Y', strtotime(str_replace('/', '-', $oficio->fecha_oficio))) :  null ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="form-label">Carátula</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caratulaModel ? $oficio->caratulaModel->caratula : '' ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Personas vinculadas</label>
                        <?php if ($oficio->dni_legajo_vinculado) : ?>
                            <textarea class="form-control" rows="3" readonly><?php echo $oficio->dni_legajo_vinculado ?></textarea>
                            <br>
                        <?php endif; ?>
                        <?= $oficio->listapersonasvinculadas; ?>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label class="form-label">Plazo (días)</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->tiempo_respuesta ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha vencimiento</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->fecha_plazo ? date('d/m/Y', strtotime(str_replace('/', '-', $oficio->fecha_plazo))) :  null ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="form-label">Número expediente</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caratulaModel ? $oficio->caratulaModel->numero_expediente : '' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Caso</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caratulaModel ? $oficio->caratulaModel->caso : '' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Año</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->caratulaModel ? $oficio->caratulaModel->anio_expediente : '' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Número trámite / cédula / oficio</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->tramite_simple ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <label class="form-label">Motivo de solicitud</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->motivo_solicitud ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Providencia</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->providencia ?>" readonly>
                    </div>
                    <div class="col-md-3 primer-oficio">
                        <input type="checkbox" id="primer_oficio" <?= $oficio->primer_oficio == 1 ? 'checked' : '' ?> disabled> <label for="primer_oficio">Es primer requerimiento</label>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tipo de requerimiento</label>
                        <input type="text" class="form-control" value="<?php echo $oficio->tipoOficio->descripcion ?>" readonly>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-12">
                        <label class="form-label">Derivación a:</label>
                        <input type="text" class="form-control" value="<?php echo ($oficio->areaOficio) ? $oficio->areaOficio->descripcion : '' ?>" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Observaciones</label>
                        <div class="alert alert-observaciones" role="alert">
                            <p><?= $oficio->observaciones; ?></p>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-3 encargado-margin">
                        <label class="form-label"><strong>Requerimiento creado por:</strong></label>
                        <?php
                        $fechaCarga = $oficio['fecha_carga'];
                        $año = substr($fechaCarga, 2, 2);
                        $mes = substr($fechaCarga, 5, 2);
                        $dia = substr($fechaCarga, 8, 2);
                        $hora = substr($fechaCarga, 11, 5);
                        $fecha = "<span class='text-muted'>$dia/$mes/$año $hora</span>";
                        $apellidoMayuscula = mb_strtoupper($oficio->usuario->apellido);
                        $nombreMayuscula = mb_strtoupper($oficio->usuario->nombre);
                        ?>
                        <ul>
                            <li><?= "$fecha - $apellidoMayuscula, $nombreMayuscula" ?></li>
                        </ul>
                    </div>
                    <div class="col-md-3 encargado-margin">
                        <label class="form-label"><strong><button id="boton-supervisores" type="button" class="boton-modal-default" data-toggle="modal" data-target="#modalSupervisores">Encargados/as de supervisar respuestas:</button></strong></label>
                        <?php
                        $supervisores = $oficio->getSupervisores();
                        usort($supervisores, "ordenarByApellido");
                        if (!empty($supervisores)) :
                        ?>
                            <ul>
                                <?php foreach ($supervisores as $supervisor) :
                                    $año = substr($supervisor['fecha_derivacion'], 2, 2);
                                    $mes = substr($supervisor['fecha_derivacion'], 5, 2);
                                    $dia = substr($supervisor['fecha_derivacion'], 8, 2);
                                    $hora = substr($supervisor['fecha_derivacion'], 11, 5);
                                    $fecha = "<span class='text-muted'>$dia/$mes/$año $hora</span>";
                                    $apellidoMayuscula = mb_strtoupper($supervisor->usuario->apellido);
                                    $nombreMayuscula = mb_strtoupper($supervisor->usuario->nombre);
                                ?>
                                    <li><?= "$fecha - $apellidoMayuscula, $nombreMayuscula" ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php else : ?>
                            <p>No existen derivaciones activas</p>
                        <?php endif ?>
                    </div>
                    <div class="col-md-3 encargado-margin">
                        <label class="form-label"><strong><button id="boton-generadores-respuesta" type="button" class="boton-modal-default" data-toggle="modal" data-target="#modalGeneradoresRespuesta">Encargados/as de generar respuestas:</button></strong></label>
                        <?php
                        $derivacionesReceptores = $oficio->getReceptores();
                        usort($derivacionesReceptores, "ordenarByApellido");
                        if (!empty($derivacionesReceptores)) :
                        ?>
                            <ul>
                                <?php foreach ($derivacionesReceptores as $derivacion) :
                                    $año = substr($derivacion['fecha_derivacion'], 2, 2);
                                    $mes = substr($derivacion['fecha_derivacion'], 5, 2);
                                    $dia = substr($derivacion['fecha_derivacion'], 8, 2);
                                    $hora = substr($derivacion['fecha_derivacion'], 11, 5);
                                    $fecha = "<span class='text-muted'>$dia/$mes/$año $hora</span>";
                                    $apellidoMayuscula = mb_strtoupper($derivacion->usuario->apellido);
                                    $nombreMayuscula = mb_strtoupper($derivacion->usuario->nombre);
                                ?>
                                    <li><?= "$fecha - $apellidoMayuscula, $nombreMayuscula" ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php else : ?>
                            <p>No existen derivaciones activas</p>
                        <?php endif ?>
                    </div>
                    <div class="col-md-3 encargado-margin">
                        <label class="form-label"><strong><button id="boton-devoluciones" type="button" class="boton-modal-default" data-toggle="modal" data-target="#modalDevoluciones">Devuelto por:</button></strong></label>
                        <?php
                        $usuariosRechazo = $oficio->getUsuariosDerivacionRechazo();
                        if (!empty($usuariosRechazo)) :
                        ?>
                            <ul>
                                <?php foreach ($usuariosRechazo as $usuarioRechazo) :
                                    $fechaCarga = $usuarioRechazo['fecha_usu_no_corresponde'];
                                    $anio = substr($fechaCarga, 2, 2);
                                    $mes = substr($fechaCarga, 5, 2);
                                    $dia = substr($fechaCarga, 8, 2);
                                    $hora = substr($fechaCarga, 11, 5);
                                    $fecha = "<span class='text-muted'>$dia/$mes/$anio $hora</span>";
                                    $tipoUsuario = '';
                                    if ($usuarioRechazo['supervisor'] == 0) {
                                        $tipoUsuario = '<b>Generador/a de respuestas</b>';
                                    } else {
                                        $tipoUsuario = '<b>Supervisor/a</b>';
                                    }
                                    $apellidoMayuscula = mb_strtoupper($usuarioRechazo->usuario->apellido);
                                    $nombreMayuscula = mb_strtoupper($usuarioRechazo->usuario->nombre);
                                ?>
                                    <li><?= "$fecha - $apellidoMayuscula, $nombreMayuscula -  $tipoUsuario" ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php else : ?>
                            <p>No hubo devoluciones</p>
                        <?php endif ?>
                    </div>
                </div>

                <br />
                <?php
                $oficioAdjunto = $oficio->getAdjuntosByTipo('oficio');
                if (!empty($oficioAdjunto)) { ?>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Adjunto requerimiento:</label>
                            <ul style="list-style: none">
                                <li><a><i class="fas fa-paperclip"></i><?= Html::a($oficioAdjunto[0]->nombre, Url::base() . "/" .  $oficioAdjunto[0]->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <br />
                <?php }
                $otrosAdjuntos = $oficio->getAdjuntosByTipo('otros');
                if (!empty($otrosAdjuntos)) : ?>
                    <label>Otros documentos:</label>
                    <ul style="list-style: none">
                        <?php foreach ($otrosAdjuntos as $adjunto) : ?>
                            <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" .  $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <br />
                <?php endif ?>
            </div>
        </section>
    </div>
</div>

<?php
if ($oficio->sugerencia) : ?>
    <div class="alert alert-info" role="alert">
        <?php
        $sugerenciaApellidoMayuscula = ($oficio->sugerenciaUsuario) ? mb_strtoupper($oficio->sugerenciaUsuario->apellido) : '';
        $sugerenciaNombreMayuscula = ($oficio->sugerenciaUsuario) ? mb_strtoupper($oficio->sugerenciaUsuario->nombre) : '';
        $sugerenciaFecha = $oficio['sugerencia_fecha'];
        if ($sugerenciaFecha) {
            $sugerenciaAnio = substr($sugerenciaFecha, 2, 2);
            $sugerenciaMes = substr($sugerenciaFecha, 5, 2);
            $sugerenciaDia = substr($sugerenciaFecha, 8, 2);
            $sugerenciaHora = substr($sugerenciaFecha, 11, 5);
            $sugerenciaFecha = "$sugerenciaDia/$sugerenciaMes/$sugerenciaAnio $sugerenciaHora";
        }
        ?>

        <label><strong> <?= ($sugerenciaFecha) ? "$sugerenciaFecha - " : "" ?> Observaciones / instrucciones del supervisor: <?= ($sugerenciaApellidoMayuscula) ? "$sugerenciaApellidoMayuscula, $sugerenciaNombreMayuscula" : '' ?></strong></label>

        <p>
            <?= $oficio->sugerencia ?>
        </p>
        <br />

        <?php
        $sugerenciaAdjuntos = $oficio->getAdjuntosByTipo('sugerencia');
        if (!empty($sugerenciaAdjuntos)) : ?>
            <label>Archivos adjuntos:</label>
            <ul style="list-style: none">
                <?php
                foreach ($sugerenciaAdjuntos as $adjunto) : ?>
                    <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif ?>
    </div>
<?php endif ?>

<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <h3>Respuestas al requerimiento </h3>
                <br />
                <?php if (empty($respuestasOficio)) { ?>
                    <h5>Aún no se han registrado respuestas.</h5>
                    <br />
                <?php
                }

                foreach ($respuestasOficio as $index => $respuesta) { ?>
                    <?php if ($respuesta) {
                        $respuesta_supervisorAdjuntos = $respuesta->getAdjuntosRespuestaSupervisor();
                        $htmlAdjuntosSupervisor = '';
                        if (!empty($respuesta_supervisorAdjuntos)) {
                            $htmlAdjuntosSupervisor = "<label>Archivos adjuntos por supervisor/a:</label><ul style='list-style: none'>";
                            foreach ($respuesta_supervisorAdjuntos as $adjunto) {
                                $htmlAdjuntosSupervisor .= "<li><a><i class='fas fa-paperclip'></i>" . Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) . "</a></li>";
                            }
                            $htmlAdjuntosSupervisor .= "</ul>";
                        }
                    } ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $respuesta->fecha_carga)->format('d-m-Y H:i') . " - " . mb_strtoupper($respuesta->usuario->apellido) . ", " . mb_strtoupper($respuesta->usuario->nombre) . " - ";  ?>
                                <span class="label label-<?php echo $respuesta->ultimoEstado->labelColorEstado($respuesta->ultimoEstado);  ?>"><?php echo $respuesta->ultimoEstado->estadoRespuesta->descripcion; ?></span>
                            </h4>
                        </div>
                        <div class="panel-body" style="word-break: break-all" ;>
                            <div class="alert alert-info" style="margin-top: 2rem" role="alert">
                                <h4>Trazabilidad de estados de la respuesta:</h4>
                                <ul>
                                    <?php foreach ($respuesta->estados as $estado) :
                                        $estadoDescripcion = $estado->estadoRespuesta['descripcion'];
                                        $usuarioNombre = mb_strtoupper($estado->usuario['apellido']) . ', ' . mb_strtoupper($estado->usuario['nombre']);
                                        $respuestaEstadoFechaInicio = date('d/m/Y H:i', strtotime($estado['fecha_inicio']));
                                        $labelEstado = "<span class='label label-{$estado->labelColorEstado($estado)}' style='font-size: 85%;'>{$estado->estadoRespuesta->descripcion}</span>";
                                        $estadosSupervisor = $estado['estado'] === $idEstadoObservada || $estado['estado'] === $idEstadoAprobado;
                                        $rolUsuario = ($estado['estado'] === $idEstadoPendiente) ? 'Generador/a de respuestas' : (($estadosSupervisor) ? 'Supervisor/a' : 'Equipo de Supervisión Final');
                                    ?>
                                        <li>
                                            <?= "$respuestaEstadoFechaInicio - <b>$usuarioNombre</b> ($rolUsuario) - $labelEstado" ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="alert alert-respuesta" role="alert">
                                <?php if (!empty($respuesta->vistos)) : ?>
                                    <h5><b>Visto por:</b></h5>
                                    <?php foreach ($respuesta->vistos as $visto) :
                                        $usuarioNombre = mb_strtoupper($visto->usuario['apellido']) . ', ' . mb_strtoupper($visto->usuario['nombre']);
                                        $respuestaEstadoFechaCarga = date('d/m/Y H:i', strtotime($visto['fecha_carga']));
                                        $rolUsuario = 'Supervisor/a';
                                    ?>
                                        <li>
                                            <?= "$respuestaEstadoFechaCarga - <b>$usuarioNombre</b> ($rolUsuario)" ?>
                                        </li>
                                    <?php endforeach; ?>
                                    <hr>
                                <?php endif; ?>
                                <h5><b>Respuesta:</b></h5>
                                <p><?php echo $respuesta->texto_repuesta;  ?></p>
                                <br />

                                <?php $profesionales = $respuesta->getProfesionalesIntervinientes();
                                if (!empty($profesionales)) { ?>
                                    <label class="form-label">Agentes Intervinientes:</label>
                                    <ul style="margin-bottom: 2rem">
                                        <?php foreach ($respuesta->getProfesionalesIntervinientes() as $profesional) : ?>
                                            <li><?= mb_strtoupper($profesional->usuario->apellido) . ', ' . mb_strtoupper($profesional->usuario->nombre) ?></li>
                                        <?php endforeach ?>
                                    </ul>
                                <?php } ?>

                                <?php if (!empty($respuesta->adjuntos)) { ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Archivos adjuntos por generador/a de respuestas:</label>
                                            <?php
                                            foreach ($respuesta->adjuntos as $adjunto) { ?>
                                                <ul style="list-style: none">
                                                    <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto['nombre'], Url::base() . "/" . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                                </ul>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <br />
                                <?php } ?>

                                <?php if (!$respuesta->yaTieneEstado() && $htmlAdjuntosSupervisor) : ?>
                                    <?= $htmlAdjuntosSupervisor ?>
                                    <br />
                                <?php elseif ($respuesta->ultimoEstado->estado == $idEstadoEnviado) : ?>
                                    <?php if ($htmlAdjuntosSupervisor) {
                                        echo $htmlAdjuntosSupervisor;
                                    } ?>
                                    <br />
                                    <div class="card-footer">
                                        <?php if ($respuesta->nota) { ?>
                                            <button type="button" id="btn-ver-nota" style="margin-right: 5px;" class="btn btn-success"><?= Html::a('Ver nota', Url::base() . '/uploads/legales/notas/' . $respuesta->nota, ['target' => '_blank', 'class' => 'box_button fl download_link', 'style' => 'color:white']) ?></button>
                                        <?php } ?>
                                        <?php if ($respuesta->comprobante) { ?>
                                            <button type="button" id="btn-ver-comprobante" class="btn btn-success"><?= Html::a('Ver Comprobante', Url::base() . '/uploads/legales/comprobantes/' . $respuesta->comprobante, ['target' => '_blank', 'class' => 'box_button fl download_link', 'style' => 'color:white']) ?></button>
                                        <?php } ?>
                                    </div>
                                <?php elseif ($respuesta->ultimoEstado->estado == $idEstadoRechazado && $htmlAdjuntosSupervisor) : ?>
                                    <?= $htmlAdjuntosSupervisor ?>
                                    <br />
                                <?php endif ?>
                            </div>

                            <?php if ($respuesta->ultimoEstado->estado == $idEstadoRechazado) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <h5><strong><?= date('d/m/Y H:i', strtotime($respuesta->ultimoEstado->fecha_inicio)) . ' - '  ?>El Equipo de Supervisión Final (<?= "{$respuesta->ultimoEstado->usuario->apellido}, {$respuesta->ultimoEstado->usuario->nombre}" ?>) devolvió una respuesta por el siguiente motivo:</strong></h5>
                                    <p><?= $respuesta->ultimoEstado->observaciones ?></p>
                                    <br />
                                </div>
                            <?php endif ?>

                            <?php if ($respuesta->ultimoEstado->estado == $idEstadoAprobado && strlen($respuesta->ultimoEstado->observaciones) > 0) { ?>
                                <div class="alert alert-success" style="margin-bottom: 0;" role="alert">
                                    <h5><strong><?= date('d/m/Y H:i', strtotime($respuesta->ultimoEstado->fecha_inicio)) . ' - '  ?>El supervisor/a <?= "{$respuesta->ultimoEstado->usuario->apellido}, {$respuesta->ultimoEstado->usuario->nombre}" ?> aprobó la respuesta con la siguiente observación:</strong></h5>
                                    <p><?php echo  $respuesta->ultimoEstado->observaciones ?></p>
                                    <?php if ($htmlAdjuntosSupervisor) : ?>
                                        <br />
                                        <?= $htmlAdjuntosSupervisor ?>
                                    <?php endif ?>
                                </div>
                            <?php } ?>

                            <?php if ($respuesta->ultimoEstado->estado == $idEstadoObservada) { ?>
                                <div class="alert alert-danger" style="margin-bottom: 0;" role="alert">
                                    <?php
                                    $respuestaRechazada = $respuesta->getUltimaRespuestaEstadoByEstadoId($idEstadoRechazado);
                                    if ($respuestaRechazada) : ?>
                                        <h5><strong><?= date('d/m/Y H:i', strtotime($respuestaRechazada->fecha_inicio)) . ' - '  ?>El Equipo de Supervisión Final (<?= "{$respuestaRechazada->usuario->apellido}, {$respuestaRechazada->usuario->nombre}" ?>) devolvió una respuesta por el siguiente motivo:</strong></h5>
                                        <p><?= $respuestaRechazada->observaciones ?></p>
                                        <hr />
                                    <?php endif; ?>
                                    <h5><strong><?= date('d/m/Y H:i', strtotime($respuesta->ultimoEstado->fecha_inicio)) . ' - '  ?>El supervisor/a <?= "{$respuesta->ultimoEstado->usuario->apellido}, {$respuesta->ultimoEstado->usuario->nombre}" ?> realizó una observación por el siguiente motivo:</strong></h5>
                                    <p><?= $respuesta->ultimoEstado->observaciones ?></p>
                                    <?php if ($htmlAdjuntosSupervisor) : ?>
                                        <br />
                                        <?= $htmlAdjuntosSupervisor ?>
                                    <?php endif ?>
                                </div>
                            <?php } ?>

                            <?php if ($respuesta->ultimoEstado->estado == $idEstadoEnviado && $respuesta->observacion_final) { ?>
                                <div class="alert alert-danger" style="margin-bottom: 0;" role="alert">
                                    <h5><strong><?= date('d/m/Y H:i', strtotime($respuesta->ultimoEstado->fecha_inicio)) . ' - '  ?>El Equipo de Supervisión Final (<?= "{$respuesta->ultimoEstado->usuario->apellido}, {$respuesta->ultimoEstado->usuario->nombre}" ?>) realizó una observación por el siguiente motivo:</strong></h5>
                                    <?= $respuesta->observacion_final ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($index !== count($respuestasOficio) - 1) : ?>
                        <hr class="hr-respuestas">
                    <?php endif; ?>
                <?php
                }
                ?>
                <div class="card-footer" id="botones">
                    <?php if (empty($consultaVinculacion)) : ?>
                        <a class="btn btn-info" href="index.php?r=mds_legales_oficio/index">Volver </a>
                    <?php else : ?>
                        <a class="btn btn-info" href="index.php?r=mds_legales_oficio/vinculacionenviar">Volver </a>
                    <?php endif ?>
                </div>
            </div>
        </section>
    </div>
</div>

<?php
require 'modal_devoluciones.php';
require 'modal_supervisores.php';
require 'modal_generadores_respuesta.php';

function ordenarByApellido($a, $b)
{
    return strcmp(strtoupper($a["usuario"]["apellido"]), strtoupper($b["usuario"]["apellido"]));
}
?>