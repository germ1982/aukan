<?php

use kartik\helpers\Html;
use yii\helpers\Url;
use app\models\Mds_legales_respuesta_estado;

$idEstadoPendiente = Mds_legales_respuesta_estado::ESTADO_PENDIENTE_AUTORIZACION;
$idEstadoObservada = Mds_legales_respuesta_estado::OBSERVADA;
$idEstadoAprobado = Mds_legales_respuesta_estado::APROBADA;
$idEstadoRechazado = Mds_legales_respuesta_estado::RECHAZADA;
$idEstadoEnviado = Mds_legales_respuesta_estado::ENVIADA;
?>

<style>
    .panel-heading-vinculacion {
        background: darkgrey !important;
        border-color: darkgrey !important;
        color: black !important;
    }

    .alert-respuesta {
        color: black;
        background-color: #efefef;
        border-color: lightgray;
    }
</style>

<div style='padding-left:  40px; '>
    <div style='border: 1px solid #ccc; border-radius: 4px;'>

        <div class='row'>
            <?php
            $oficio = \app\models\Mds_legales_oficio::find()->where(['idlegalesoficio' => $model['idlegalesoficio']])->one();
            $respuestas = $oficio->getRespuestasAprobadasSinOtrosEstados();
            ?>
            <input type="hidden" id="idoficio" value="<?= $oficio['idlegalesoficio'] ?>" />
            <div class="col-md-12">
                <?php foreach ($respuestas as $respuesta) : ?>
                    <?php if ($respuesta->ultimoEstado->estado == $idEstadoAprobado || $respuesta->ultimoEstado->estado == $idEstadoEnviado) : ?>
                        <div class="panel panel-default">
                            <div class="panel-heading panel-heading-vinculacion">
                                <h4>
                                    <?= DateTime::createFromFormat('Y-m-d H:i:s', $respuesta->ultimoEstado->fecha_inicio)->format('d-m-Y H:i') . " - " . mb_strtoupper($respuesta->ultimoEstado->usuario->apellido) . ', ' . mb_strtoupper($respuesta->ultimoEstado->usuario->nombre) .  " -"  ?>
                                    <span class="label label-<?= $respuesta->ultimoEstado->labelColorEstado($respuesta->ultimoEstado);  ?>" style='font-size: 85%; margin-left: .5rem;'><?= $respuesta->ultimoEstado->estadoRespuesta->descripcion; ?></span>
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
                                            $labelEstado = "<span class='label label-{$estado->labelColorEstado($estado)}' style='font-size: 85%; padding: 0em 0.6em 0.15em;'>{$estado->estadoRespuesta->descripcion}</span>";
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
                                    <h5><strong><?= DateTime::createFromFormat('Y-m-d H:i:s', $respuesta->fecha_carga)->format('d-m-Y H:i') .  " - " . mb_strtoupper($respuesta->usuario->apellido) . ', ' . mb_strtoupper($respuesta->usuario->nombre) . " : " ?></strong></h5>
                                    <p><?= $respuesta->texto_repuesta;  ?></p>
                                    <?php
                                    $profesionales = $respuesta->getProfesionalesIntervinientes();
                                    if (!empty($profesionales)) { ?>
                                        <br />
                                        <label class="form-label">Agentes Intervinientes:</label>
                                        <ul>
                                            <?php foreach ($profesionales as $profesional) : ?>
                                                <li><?= mb_strtoupper($profesional->usuario->apellido) . ', ' . mb_strtoupper($profesional->usuario->nombre) ?></li>
                                            <?php endforeach ?>
                                        </ul>
                                    <?php } ?>

                                    <?php
                                    $oficioAdjunto = $oficio->getAdjuntosByTipo('oficio');
                                    if (!empty($oficioAdjunto)) : ?>
                                        <br />
                                        <label>Adjunto requerimiento:</label>
                                        <ul style="list-style: none">
                                            <?php
                                            foreach ($oficioAdjunto as $adjunto) : ?>
                                                <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif ?>
                                    <?php $otrosAdjuntos = $oficio->getAdjuntosByTipo('otros');
                                    if (!empty($otrosAdjuntos)) : ?>
                                        <br />
                                        <label>Otros documentos:</label>
                                        <ul style="list-style: none">
                                            <?php
                                            foreach ($otrosAdjuntos as $adjunto) : ?>
                                                <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif ?>

                                    <?php if (!empty($respuesta->adjuntos)) { ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <br />
                                                <label>Archivos adjuntos por generador/a de respuestas:</label>
                                                <?php foreach ($respuesta->adjuntos as $adjunto) { ?>
                                                    <ul style="list-style: none">
                                                        <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto['nombre'], Url::base() . "/" . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                                    </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="alert alert-success" role="alert">
                                    <?php
                                    $textoObservacion = ".";
                                    $parrafoObservacion = "";
                                    if (!empty($respuesta->ultimoEstadoAprobado->observaciones)) {
                                        $textoObservacion = " con la siguiente observación:";
                                        $parrafoObservacion = "<p>{$respuesta->ultimoEstadoAprobado->observaciones}</p>";
                                    }
                                    ?>
                                    <h5><strong><?= date('d/m/Y H:i', strtotime($respuesta->ultimoEstadoAprobado->fecha_inicio)) . " -  El supervisor/a {$respuesta->ultimoEstadoAprobado->usuario->apellido}, {$respuesta->ultimoEstadoAprobado->usuario->nombre} aprobó la respuesta$textoObservacion" ?></strong></h5>
                                    <?= $parrafoObservacion ?>
                                    <?php
                                    $respuesta_supervisorAdjuntos = $respuesta->getAdjuntosRespuestaSupervisor();
                                    if (!empty($respuesta_supervisorAdjuntos)) : ?>
                                        <br />
                                        <label>Archivos adjuntos por supervisor/a:</label>
                                        <ul style="list-style: none">
                                            <?php
                                            foreach ($respuesta_supervisorAdjuntos as $adjunto) : ?>
                                                <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <?php if ($respuesta->ultimoEstado->estado == $idEstadoAprobado) { ?>
                                    <?= Html::a('<span class="btn-label">Descargar Respuesta en PDF</span>', ['mds_legales_respuesta_estado/descargar', 'idlegalesrespuesta' => $respuesta->idlegalesrespuesta], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
                                    <button data-toggle="modal" href="#modal_file_nota" data-idlegalesrespuesta="<?= $respuesta->idlegalesrespuesta ?>" data-nro_nota_dependencia="<?= $respuesta->nro_nota_dependencia ?>" data-nro_vinculacion_judicial="<?= $respuesta->nro_vinculacion_judicial ?>" data-nota="<?= $respuesta->nota ?>" data-idoficio="<?= $oficio['idlegalesoficio'] ?>" class="btn btn-success btn-file btn-subir-nota">Subir nota</button>
                                    <button data-toggle="modal" href="#modal_file" data-idlegalesrespuesta="<?= $respuesta->idlegalesrespuesta ?>" data-idoficio="<?= $oficio['idlegalesoficio'] ?>" class="btn btn-success btn-file btn-subir-comprobante">Subir comprobante</button>
                                    <button data-toggle="modal" href="#modal_aprobar" data-idlegalesrespuesta="<?= $respuesta->idlegalesrespuesta ?>" data-idoficio="<?= $oficio['idlegalesoficio'] ?>" class="btn btn-success btn-file btn-aprobar">Aprobar <small>(solo con comprobante cargado)</small></button>
                                    <button data-toggle="modal" href="#modal_rechazar" data-idlegalesrespuesta="<?= $respuesta->idlegalesrespuesta ?>" data-idoficio="<?= $oficio['idlegalesoficio'] ?>" class="btn btn-danger btn-estado btn-rechazar-rta">Devolver</button>
                                <?php } ?>
                                <?php if ($respuesta->nota) { ?>
                                    <button type="button" id="btn-ver-nota" style="margin-right: 5px;" class="btn btn-success"><?= Html::a('Ver nota', Url::base() . '/uploads/legales/notas/' . $respuesta->nota, ['target' => '_blank', 'class' => 'box_button fl download_link', 'style' => 'color:white']) ?></button>
                                <?php } ?>
                                <?php if ($respuesta->comprobante) { ?>
                                    <button type="button" id="btn-ver-comprobante" style="margin-right: 5px;" class="btn btn-success"><?= Html::a('Ver Comprobante', Url::base() . '/uploads/legales/comprobantes/' . $respuesta->comprobante, ['target' => '_blank', 'class' => 'box_button fl download_link', 'style' => 'color:white']) ?></button>
                                <?php } ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(
    "$(document).on('click','.btn-subir-comprobante',function(){
        const idlegalesrespuesta = $(this).data('idlegalesrespuesta');
        const idoficio = $(this).data('idoficio');
        $('#titulo-modal-comprobante').html('Subir comprobante a la respuesta del requerimiento #' + idoficio);
        $('#idrespuesta_para_comprobante').val(idlegalesrespuesta);
        $('#adjunto_comprobante-container').hide();
    }) 

    $(document).on('click','.btn-aprobar',function(){
        const idlegalesrespuesta = $(this).data('idlegalesrespuesta');
        const idoficio = $(this).data('idoficio');
        $('#idrespuesta_para_aprobar').val(idlegalesrespuesta);
        $('#titulo-modal-aprobar').html('Aprobar respuesta del requerimiento #' + idoficio);
    }) 

    $(document).on('click','.btn-subir-nota',function(){
        const idoficio = $(this).data('idoficio');
        $('#titulo-modal-nota').html('Adjuntar nota a la respuesta del requerimiento #' + idoficio);
        const idlegalesrespuesta = $(this).data('idlegalesrespuesta');
        const nro_nota_dependencia = $(this).data('nro_nota_dependencia');
        const nro_vinculacion_judicial = $(this).data('nro_vinculacion_judicial');
        const nota = $(this).data('nota');

        $('#idrespuesta_para_comprobante_nota').val(idlegalesrespuesta);
        $('#nro_nota_dependencia').val(nro_nota_dependencia);
        $('#nro_vinculacion_judicial').val(nro_vinculacion_judicial);
        
        if(nota==''){
            $('#adjunto_nota').hide();
        }else{
            $('#nota_link').attr('href', 'uploads/legales/notas/'+nota);
        }
    });

    $(document).on('click','.btn-rechazar-rta',function(){
        const idoficio = $(this).data('idoficio');
        $('#titulo-modal-rechazar').html('Devolver respuesta del requerimiento #' + idoficio);
        let idlegalesrespuesta = $(this).data('idlegalesrespuesta');
        $('#idlegalesrespuesta_para_rechazar').val(idlegalesrespuesta);
    }) 

    $(document).on('click','.btn-ver-comprobante',function(){
        e.stopPropagation();
    });
       
    $(document).on('click','#btn-ver-nota',function(){
        e.stopPropagation();
    });
    "
);
?>