<div class="modal fade" id="modalGeneradoresRespuesta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Encargados/as de generar respuestas</h4>
            </div>
            <div class="modal-body overflow-y">
                <div class="row">
                    <div class="col-md-12">
                        <?php if (!empty($derivacionesReceptores)) : ?>
                            <div class="alert alert-info" role="alert">
                                <ul>
                                    <?php 
                                    $nroRequerimiento = $oficio['idlegalesoficio'];
                                    foreach ($derivacionesReceptores as $index => $receptor) : 
                                        $receptorApellidoMayuscula = mb_strtoupper($receptor->usuario->apellido);
                                        $receptorNombreMayuscula = mb_strtoupper($receptor->usuario->nombre);
                                        $receptorNombreCompleto = "$receptorApellidoMayuscula, $receptorNombreMayuscula";
                                        $textoUsuarioDeriva = "Se ";
                                        if ($receptor->usuarioDeriva) {
                                            $usuarioDerivaApellidoMayuscula = mb_strtoupper($receptor->usuarioDeriva->apellido);
                                            $usuarioDerivaNombreMayuscula = mb_strtoupper($receptor->usuarioDeriva->nombre);
                                            $usuarioDerivaNombreCompleto = "$usuarioDerivaApellidoMayuscula, $usuarioDerivaNombreMayuscula";
                                            $textoUsuarioDeriva = "El supervisor/a <b>$usuarioDerivaNombreCompleto</b> ";
                                        }
                                        $año = substr($receptor['fecha_derivacion'], 2, 2);
                                        $mes = substr($receptor['fecha_derivacion'], 5, 2);
                                        $dia = substr($receptor['fecha_derivacion'], 8, 2);
                                        $hora = substr($receptor['fecha_derivacion'], 11, 5);
                                        $fecha = "$dia/$mes/$año $hora";
                                        ?>
                                        <li><?= "$textoUsuarioDeriva derivó el requerimiento <b>#$nroRequerimiento</b> el día <b>$fecha</b> al generador/a de respuestas <b>$receptorNombreCompleto</b>" ?></li>
                                        <?php if ($index !== (count($derivacionesReceptores) - 1)) : ?>
                                            <hr />
                                        <?php endif; ?>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php else : ?>
                            <p>No existen derivaciones activas</p>
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