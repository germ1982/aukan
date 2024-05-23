<div class="modal fade" id="modalSupervisores" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Encargados/as de supervisar respuestas</h4>
            </div>
            <div class="modal-body overflow-y">
                <div class="row">
                    <div class="col-md-12">
                        <?php if (!empty($supervisores)) : ?>
                            <div class="alert alert-info" role="alert">
                                <ul>
                                    <?php 
                                    $nroRequerimiento = $oficio['idlegalesoficio'];
                                    foreach ($supervisores as $index => $supervisor) : 
                                        $supervisorApellidoMayuscula = mb_strtoupper($supervisor->usuario->apellido);
                                        $supervisorNombreMayuscula = mb_strtoupper($supervisor->usuario->nombre);
                                        $supervisorNombreCompleto = "$supervisorApellidoMayuscula, $supervisorNombreMayuscula";
                                        $textoUsuarioDeriva = "Se ";
                                        if ($supervisor->usuarioDeriva) {
                                            $usuarioDerivaApellidoMayuscula = mb_strtoupper($supervisor->usuarioDeriva->apellido);
                                            $usuarioDerivaNombreMayuscula = mb_strtoupper($supervisor->usuarioDeriva->nombre);
                                            $usuarioDerivaNombreCompleto = "$usuarioDerivaApellidoMayuscula, $usuarioDerivaNombreMayuscula";
                                            $textoUsuarioDeriva = "El usuario <b>$usuarioDerivaNombreCompleto</b>";
                                        }
                                        $año = substr($supervisor['fecha_derivacion'], 2, 2);
                                        $mes = substr($supervisor['fecha_derivacion'], 5, 2);
                                        $dia = substr($supervisor['fecha_derivacion'], 8, 2);
                                        $hora = substr($supervisor['fecha_derivacion'], 11, 5);
                                        $fecha = "$dia/$mes/$año $hora";
                                        ?>
                                        <li><?= "$textoUsuarioDeriva derivó el requerimiento <b>#$nroRequerimiento</b> el día <b>$fecha</b> al supervisor/a <b>$supervisorNombreCompleto</b>" ?></li>
                                        <?php if ($index !== (count($supervisores) - 1)) : ?>
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