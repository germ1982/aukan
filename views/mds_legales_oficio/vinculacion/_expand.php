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
            $respuestas = $oficio->respuestas;
            ?>
            <div class="col-md-12">
                <?php foreach ($respuestas as $respuesta) : ?>
                    <?php if ($respuesta->ultimoEstado->estado == $idEstadoEnviado) : ?>
                        <div class="panel panel-default">
                            <div class="panel-heading panel-heading-vinculacion">
                                <h4>
                                    <?= DateTime::createFromFormat('Y-m-d H:i:s', $respuesta->ultimoEstado->fecha_inicio)->format('d-m-Y H:i') . " - " . mb_strtoupper($respuesta->ultimoEstado->usuario->apellido) . ', ' . mb_strtoupper($respuesta->ultimoEstado->usuario->nombre) . ' -' ?>
                                    <span class="label label-<?php echo $respuesta->ultimoEstado->labelColorEstado($respuesta->ultimoEstado);  ?>" style='font-size: 85%; margin-left: .5rem;'><?= $respuesta->ultimoEstado->estadoRespuesta->descripcion; ?><?php ?> </span>
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
                                    <h5><strong><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $respuesta->fecha_carga)->format('d-m-Y H:i') .  " - " . mb_strtoupper($respuesta->usuario->apellido) . ', ' .  mb_strtoupper($respuesta->usuario->nombre) . ' : ' ?></strong></h5>
                                    <p><?php echo $respuesta->texto_repuesta;  ?></p>
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

                                    <?php $oficioAdjunto = $oficio->getAdjuntosByTipo('oficio');
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
                                                <?php
                                                foreach ($respuesta->adjuntos as $adjunto) { ?>
                                                    <ul style="list-style: none">
                                                        <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto['nombre'], Url::base() . "/" . $adjunto['path'], ['target' => '_blank', 'class' => 'box_button fl download_link btn-ver-archivo', 'id' => 'btn-ver-archivo']) ?></a></li>
                                                    </ul>
                                                <?php
                                                }
                                                ?>
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
                                <?php if ($respuesta->nota) { ?>
                                    <button data-toggle="modal" href="#modal_file_nota" data-nro_nota_dependencia="<?= $respuesta->nro_nota_dependencia ?>" data-nro_vinculacion_judicial="<?= $respuesta->nro_vinculacion_judicial ?>" data-nota="<?= $respuesta->nota ?>" data-idoficio="<?= $respuesta->idlegalesoficio ?>" class="btn btn-success btn-file btn-ver-nota">Ver nota</button>
                                <?php } ?>
                                <?php if ($respuesta->comprobante) { ?>
                                    <button data-toggle="modal" href="#modal_file" data-nro_nota="<?= $respuesta->nro_nota ?>" data-comprobante="<?= $respuesta->comprobante ?>" data-idoficio="<?= $respuesta->idlegalesoficio ?>" class="btn btn-success btn-file btn-ver-comprobante">Ver comprobante</button>
                                <?php }
                                echo Html::a('<span class="btn-label">Descargar Respuesta en PDF</span>', ['mds_legales_respuesta_estado/descargar', 'idlegalesrespuesta' => $respuesta->idlegalesrespuesta], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
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
    "$(document).on('click','.btn-ver-comprobante',function(){
        $('#dropzone-comprobante-container, #observacion-comprobante-container, #btn-subir-archivo').hide();
        const idoficio = $(this).data('idoficio');
        $('#titulo-modal-comprobante').html('Ver comprobante de la respuesta del requerimiento #' + idoficio);
        const comprobante = $(this).data('comprobante');
        const nro_nota = $(this).data('nro_nota');

        $('#nro_nota').val(nro_nota);
        $('#nro_nota').prop('readonly', true);
        if(comprobante==''){
            $('#adjunto_comprobante-container').hide();
        }else{
            $('#comprobante_link').attr('href', 'uploads/legales/comprobantes/'+comprobante);
        }
    });
    
    $(document).on('click','.btn-ver-nota',function(){
        $('#dropzone-nota-container, #btn-subir-nota').hide();
        const idoficio = $(this).data('idoficio');
        $('#titulo-modal-nota').html('Ver nota de la respuesta del requerimiento #' + idoficio);
        const nro_nota_dependencia = $(this).data('nro_nota_dependencia');
        const nro_vinculacion_judicial = $(this).data('nro_vinculacion_judicial');
        const nota = $(this).data('nota');
        
        $('#nro_nota_dependencia').val(nro_nota_dependencia);
        $('#nro_vinculacion_judicial').val(nro_vinculacion_judicial);
        $('#nro_nota_dependencia, #nro_vinculacion_judicial').prop('readonly', true);
        
        if(nota==''){
            $('#adjunto_nota').hide();
        }else{
            $('#nota_link').attr('href', 'uploads/legales/notas/'+nota);
        }
    });

      $('#btn-ver-archivo').click(function(e){
            e.stopPropagation();
      });
      "
);
?>