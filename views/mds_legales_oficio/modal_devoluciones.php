<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="modal fade" id="modalDevoluciones" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Devuelto por</h4>
            </div>
            <div class="modal-body overflow-y">
                <div class="row">
                    <div class="col-md-12">
                        <?php if (!empty($devoluciones)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <ul>
                                    <?php 
                                    $nroRequerimiento = $oficio['idlegalesoficio'];
                                    foreach ($devoluciones as $index => $derivacion) : 
                                        $usuarioNombre = mb_strtoupper($derivacion->usuario->nombre);
                                        $usuarioApellido = mb_strtoupper($derivacion->usuario->apellido);
                                        $usuarioNombreCompleto = "$usuarioApellido, $usuarioNombre";
                                        $fechaRechazo = date('d/m/Y H:i', strtotime(str_replace('/', '-', $derivacion->fecha_usu_no_corresponde)));
                                        $usuarioRol = ($derivacion->supervisor == 1) ? " (<u>Supervisor/a</u>) " : " (<u>Generador/a de respuestas</u>)";
                                        $motivo = "<span style='display: block; margin-top: 10px; color: black; font-weight: normal;'>  $derivacion->observaciones </span>";
                                        $textoUsuarioDeriva = "";
                                        if ($derivacion->usuarioDeriva) {
                                            $usuarioDerivaNombre = mb_strtoupper($derivacion->usuarioDeriva->nombre);
                                            $usuarioDerivaApellido = mb_strtoupper($derivacion->usuarioDeriva->apellido);
                                            $usuarioDerivaNombreCompleto = "$usuarioDerivaApellido, $usuarioDerivaNombre";
                                            $fechaDerivacion = date('d/m/Y H:i', strtotime(str_replace('/', '-', $derivacion->fecha_derivacion)));
                                            $textoUsuarioDeriva = "derivado por <b>$usuarioDerivaNombreCompleto</b> el día <b>$fechaDerivacion</b>";
                                        }
                                        ?>
                                        <li><?= "El día <b>$fechaRechazo</b> el usuario <b>$usuarioNombreCompleto $usuarioRol</b> devolvió el requerimiento <b>#$nroRequerimiento</b> $textoUsuarioDeriva por el <b>siguiente motivo</b>: $motivo" ?></li>
                                        <?php
                                        $devolucionAdjuntos = $oficio->getAdjuntosByTipo('devolucion', $derivacion->idlegalesderivacion, 'mds_legales_derivacion');
                                        if (count($devolucionAdjuntos) > 0) : ?>
                                            <label>Archivos adjuntos:</label>
                                            <ul style="list-style: none">
                                                <?php
                                                foreach ($devolucionAdjuntos as $adjunto) :
                                                    if ($derivacion->idlegalesderivacion == $adjunto->id_objeto) :
                                                ?>
                                                        <li><a><i class="fas fa-paperclip"></i><?= Html::a($adjunto->nombre, Url::base() . "/" . $adjunto->path, ['target' => '_blank', 'class' => 'box_button fl download_link']) ?></a></li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif ?>
                                        <?php if ($index !== (count($devoluciones) - 1)) : ?>
                                            <hr />
                                        <?php endif; ?>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php else : ?>
                            <p>No hubo devoluciones</p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>