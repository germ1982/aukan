<?php

use app\models\Mds_conc_historial;
use app\models\Mds_conc_solicitud;
use app\models\Mds_conc_postulacion;
?>

<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img src="https://desasur.neuquen.gov.ar/familia/web/img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
        <div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
            <h4 style="margin: 0; font-weight: bold;">REPORTE DE SOLICITUD CONCURSO</h4>
            <p><span> Solicitud Concurso </span></p>
            <hr style="margin: 0 0 10px 0">
        </div>

        <?php
        $size = count($solicitudes);
        $pages = 1;
        ?>

        <?php foreach ($solicitudes as $dato) {
            $solicitud = $dato['solicitud'];
            $postulaciones = $dato['postulaciones'];
            $renaper = $dato['renaper'];
            $proneu = $dato['proneu'];
            $rhsur = $dato['rhsur'];
        ?>
            <table style="border-spacing: 0 5px; border-collapse: separate;">
                <tr style="background-color: #dddddd">
                    <th colspan="2" class="titulo" style="margin:30px">
                        <h5>DATOS DE SOLICITUD #<?= $solicitud->idsolicitud ?>:</h5>
                    </th>
                </tr>

                <tr>
                    <td colspan="1" valign="top"><b>Fecha de carga: </b><span><?= $solicitud->fechaCarga ?></span></td>
                    <td colspan="1" valign="top"><b>Usuario de carga: </b><span><?= $solicitud->idusuario ? strtoupper($solicitud->usuarioCarga->apellido) . ' ' . strtoupper($solicitud->usuarioCarga->nombre) . " (#{$solicitud->usuarioCarga->idusuario})" : ""  ?></span></td>
                </tr>
                <tr style="background-color: #dddddd;">
                    <th colspan="2" class="titulo">
                        <h5>DATOS PERSONALES: </h5>
                    </th>
                </tr>
                <tr>
                    <td colspan="1" valign="top"><b>Nombre: </b><span><?= mb_strtoupper($solicitud->nombre) ?></span></td>
                    <td colspan="1" valign="top"><b>Apellido: </b><span><?= mb_strtoupper($solicitud->apellido) ?></span></td>
                </tr>
                <tr>
                    <td colspan="1" valign="top"><b>Documento: </b><span><?= $solicitud->documento ?></span></td>
                    <td colspan="1" valign="top"><b>Legajo: </b><span><?= $solicitud->legajo ?></span></td>
                </tr>
                <tr>
                    <td colspan="1" valign="top"><b>Correo electrónico: </b><span><?= $solicitud->mail ?></span></td>
                    <td colspan="1" valign="top"><b>Teléfono: </b><span><?= $solicitud->telefono ?></span></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><b>Domicilio Fiscal: </b><span><?= $solicitud->domicilio_fiscal ?></span></td>
                </tr>

                <tr style="background-color: #dddddd;">
                    <th colspan="2" class="titulo">
                        <h5>POSTULACIÓN: </h5>
                    </th>
                </tr>

                <?php if (count($postulaciones) > 0) :
                    $cantIndexPostulaciones = count($postulaciones) - 1; ?>
                    <?php foreach ($postulaciones as $key => $postulacion) :
                        $motivosImpugnacionString = '';
                        if ($postulacion->estado === Mds_conc_solicitud::ESTADO_NO_ADMITIDO || $postulacion->estado === Mds_conc_solicitud::ESTADO_IMPUGNADO) {
                            $motivosImpugnacion = Mds_conc_postulacion::getMotivosImpugnacionByIdPostulacion($postulacion->idpostulacion);
                            if (count($motivosImpugnacion) > 0) {
                                foreach ($motivosImpugnacion as $key => $motivo) {
                                    $motivosImpugnacionString .=  $key + 1 === count($motivosImpugnacion) ? "{$motivo['descripcion']}" : "{$motivo['descripcion']}, ";
                                }
                            }
                        } ?>
                        <?php if (isset($postulacion->vacante)) : ?>
                            <?php if (count($postulaciones) != 1) : ?>
                                <tr>
                                    <td colspan="2" style="text-align: center"><b>POSTULACIÓN <?= $key + 1 ?></b></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <?php if (isset($postulacion->vacante->categoria0)) : ?>
                                    <td colspan="1" valign="top"><b>Categoría: </b><?= $postulacion->vacante->categoria0->descripcion ?><span></span></td>
                                <?php endif; ?>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <?php if (isset($postulacion->estado)) : ?>
                                <td colspan="1" valign="top"><b>Estado actual: </b><?= $postulacion->estado0->descripcion ?><span></span></td>
                            <?php endif; ?>
                            <?php if (isset($postulacion->puntaje)) : ?>
                                <td colspan="1" valign="top"><b>Puntaje: </b> <?= $postulacion->puntaje ?><span></span></td>
                            <?php endif; ?>
                        </tr>
                        <?php if ($motivosImpugnacionString) : ?>
                            <tr>
                                <td colspan="2" valign="top"><b>Motivos impugnación: </b> <?= $motivosImpugnacionString ?><span></span></td>
                            </tr>
                        <?php endif; ?>

                        <?php if (count($postulacion->historial) > 0) : ?>
                            <?php foreach ($postulacion->historial as $keyHistorial => $historial) :
                                $motivosImpugnacionHistorialString = '';
                                if ($historial->anteriorEstado && ($historial->anteriorEstado->idconfiguracion === Mds_conc_solicitud::ESTADO_NO_ADMITIDO || $historial->anteriorEstado->idconfiguracion === Mds_conc_solicitud::ESTADO_IMPUGNADO)) {
                                    $motivosImpugnacionHistorial = array();
                                    if ($historial->anteriorEstado->idconfiguracion === Mds_conc_solicitud::ESTADO_NO_ADMITIDO) {
                                        $motivosImpugnacionHistorial = Mds_conc_historial::getMotivosImpugnacionByIdHistorial($historial->idhistorial);
                                    } else {
                                        $motivosImpugnacionHistorial = Mds_conc_postulacion::getMotivosImpugnacionByIdPostulacion($historial->idpostulacion);
                                    }
                                    if (count($motivosImpugnacionHistorial) > 0) {
                                        foreach ($motivosImpugnacionHistorial as $key => $motivo) {
                                            $motivosImpugnacionHistorialString .=  $key + 1 === count($motivosImpugnacionHistorial) ? "{$motivo['descripcion']}" : "{$motivo['descripcion']}, ";
                                        }
                                    }
                                } ?>
                                <tr>
                                    <td colspan="1" valign="top"><b>Estado anterior: </b> <?= $historial->anteriorEstado ? $historial->anteriorEstado->descripcion : ''  ?><span></span></td>
                                    <td colspan="1" valign="top"><b>Fecha: </b> <?= $historial->created_at ? $historial->fechaCarga : ''  ?><span></span></td>
                                </tr>
                                <tr>
                                    <td colspan="2" valign="top"><b>Usuario cambio de estado: </b> <?= mb_strtoupper("{$historial->usuarioCarga->nombre} {$historial->usuarioCarga->apellido}") ?><span></span></td>
                                </tr>
                                <?php if ($motivosImpugnacionHistorialString) : ?>
                                    <tr>
                                        <td colspan="2" valign="top" style="text-align: justify;"><b>Motivo impugnación: </b> <?= $motivosImpugnacionHistorialString ?></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (isset($historial->observacion)) : ?>
                                    <tr>
                                        <td colspan="2" valign="top" style="text-align: justify;"><b>Observación: </b> <?= $historial->observacion ?><span></span></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (isset($historial->observacion_publica)) : ?>
                                    <tr>
                                        <td colspan="2" valign="top" style="text-align: justify;"><b>Observación pública: </b> <?= $historial->observacion_publica ?><span></span></td>
                                    </tr>
                                <?php endif; ?>
                                <?php if ($keyHistorial < count($postulacion->historial) - 1) : ?>
                                    <hr style="width: 50%;">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($key != $cantIndexPostulaciones) : ?>
                            <tr>
                                <td colspan="2">
                                    <hr style="margin:0">
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2" valign="top">La solicitud no tiene asociada ninguna postulación.</td>
                    </tr>
                <?php endif; ?>

                <tr style="background-color: #dddddd;">
                    <th colspan="2" class="titulo">
                        <h5>DOCUMENTACIÓN: </h5>
                    </th>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 15px;" valign="top" colspan="4">
                        <label><?= $solicitud->deudores_morosos ?  '&#x2713;' :  '&#x2717;' ?> Deudor Moroso</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 15px;" valign="top" colspan="4">
                        <label><?= $solicitud->registro_violencia ?  '&#x2713;' :  '&#x2717;' ?> Registro de Violencia</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 15px;" valign="top" colspan="4">
                        <label><?= $solicitud->antecedente_nacional ?  '&#x2713;' :  '&#x2717;' ?> Antecedente Nacional</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-left: 15px;" valign="top" colspan="4">
                        <label><?= $solicitud->titulo ?  '&#x2713;' :  '&#x2717;' ?> Título</label>
                    </td>
                </tr>
                <?php if ($rhsur) : ?>
                    <tr style="background-color: #dddddd;">
                        <th colspan="2" class="titulo">
                            <h5>RH SUR AL <?= $rhsur->mes ?>/<?= $rhsur->anio ?>: </h5>
                        </th>
                    </tr>
                    <tr>
                        <?php if ($rhsur->mes) : ?>
                            <td colspan="1" valign="top"><b>Mes: </b><?= $rhsur->mes ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($rhsur->anio) : ?>
                            <td colspan="1" valign="top"><b>Año: </b> <?= $rhsur->anio ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($rhsur->legajo) : ?>
                            <td colspan="1" valign="top"><b>Legajo: </b><?= $rhsur->legajo ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($rhsur->idunidadoperativa) : ?>
                            <td colspan="1" valign="top"><b>Unidad Operativa: </b> <?= $rhsur->idunidadoperativa ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($rhsur->categoria) : ?>
                            <td colspan="1" valign="top"><b>Categoría: </b><?= $rhsur->categoria ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($rhsur->apellido_nombre) : ?>
                            <td colspan="1" valign="top"><b>Apellido Nombre: </b> <?= $rhsur->apellido_nombre ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td colspan="1" valign="top"><b>Sexo: </b><?= $rhsur->sexo ? "MASCULINO" : "FEMENINO" ?><span></span></td>
                        <?php if ($rhsur->dni) : ?>
                            <td colspan="1" valign="top"><b>DNI: </b> <?= $rhsur->dni ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($rhsur->cuil) : ?>
                            <td colspan="1" valign="top"><b>CUIL: </b><?= $rhsur->cuil ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($rhsur->fecha_nacimiento) : ?>
                            <td colspan="1" valign="top"><b>Fecha Nacimiento: </b> <?= date('d/m/Y', strtotime($rhsur->fecha_nacimiento)) ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($rhsur->fecha_ingreso) : ?>
                            <td colspan="1" valign="top"><b>Fecha Ingreso: </b><?= date('d/m/Y', strtotime($rhsur->fecha_ingreso)) ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($rhsur->antiguedad_administrativa) : ?>
                            <td colspan="1" valign="top"><b>Antigüedad Administrativa: </b> <?= $rhsur->antiguedad_administrativa ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($rhsur->antiguedad_privada) : ?>
                            <td colspan="1" valign="top"><b>Antigüedad Privada: </b><?= $rhsur->antiguedad_privada ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($rhsur->antiguedad_total) : ?>
                            <td colspan="1" valign="top"><b>Antigüedad Total: </b> <?= $rhsur->antiguedad_total ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <td colspan="1" valign="top"><b>Eventual: </b><?= $rhsur->eventual ? 'Si' : 'No' ?><span></span></td>
                    </tr>
                <?php endif; ?>
                <?php if ($proneu) : ?>
                    <tr style="background-color: #dddddd;">
                        <th colspan="2" class="titulo">
                            <h5>RH PRONEU AL 20/09/2023: </h5>
                        </th>
                    </tr>
                    <tr>
                        <?php if ($proneu->apellido) : ?>
                            <td colspan="1" valign="top"><b>Apellidos: </b><?= $proneu->apellido ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->nombre) : ?>
                            <td colspan="1" valign="top"><b>Nombres: </b> <?= $proneu->nombre ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($proneu->tipo_doc) : ?>
                            <td colspan="1" valign="top"><b>Tipo Documento: </b><?= $proneu->tipo_doc ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->nro_doc) : ?>
                            <td colspan="1" valign="top"><b>DNI: </b> <?= $proneu->nro_doc ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($proneu->fecha_nacimiento) : ?>
                            <td colspan="1" valign="top"><b>Fecha Nacimiento: </b><?= $proneu->fecha_nacimiento ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->sexo) : ?>
                            <td colspan="1" valign="top"><b>Sexo: </b> <?= $proneu->sexo ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($proneu->estado_civil) : ?>
                            <td colspan="1" valign="top"><b>Estado Civil: </b><?= $proneu->estado_civil ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->conyuge) : ?>
                            <td colspan="1" valign="top"><b>Conyuge: </b> <?= $proneu->conyuge ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($proneu->estado_puesto) : ?>
                            <td colspan="1" valign="top"><b>Estado Puesto: </b><?= $proneu->estado_puesto ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->estado) : ?>
                            <td colspan="1" valign="top"><b>Estado: </b> <?= $proneu->estado ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($proneu->titulo) : ?>
                            <td colspan="1" valign="top"><b>Título: </b><?= $proneu->titulo ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->empleado_activo) : ?>
                            <td colspan="1" valign="top"><b>Empleado Activo: </b> <?= $proneu->empleado_activo ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($proneu->servicio) : ?>
                            <td colspan="1" valign="top"><b>Servicio: </b><?= $proneu->servicio ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->legajo) : ?>
                            <td colspan="1" valign="top"><b>Legajo: </b> <?= $proneu->legajo ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($proneu->categoria) : ?>
                            <td colspan="1" valign="top"><b>Categoría: </b><?= $proneu->categoria ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->relacion_laboral) : ?>
                            <td colspan="1" valign="top"><b>Relación Laboral: </b> <?= $proneu->relacion_laboral ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($proneu->convenio_nro) : ?>
                            <td colspan="1" valign="top"><b>Convenio Nro: </b><?= $proneu->convenio_nro ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->convenio_des) : ?>
                            <td colspan="1" valign="top"><b>Convenio Descripción: </b> <?= $proneu->convenio_des ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($proneu->fecha_alta) : ?>
                            <td colspan="1" valign="top"><b>Fecha Alta: </b><?= $proneu->fecha_alta ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($proneu->fecha_baja) : ?>
                            <td colspan="1" valign="top"><b>Fecha Baja: </b> <?= $proneu->fecha_baja ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                <?php endif; ?>
                <?php if ($renaper) : ?>
                    <tr style="background-color: #dddddd;">
                        <th colspan="2" class="titulo">
                            <h5>RENAPER AL 20/09/2023: </h5>
                        </th>
                    </tr>
                    <tr>
                        <?php if ($renaper->apellido) : ?>
                            <td colspan="1" valign="top"><b>Apellidos: </b><?= $renaper->apellido ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->nombres) : ?>
                            <td colspan="1" valign="top"><b>Nombres: </b> <?= $renaper->nombres ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($renaper->dni) : ?>
                            <td colspan="1" valign="top"><b>DNI: </b><?= $renaper->dni ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->cuil) : ?>
                            <td colspan="1" valign="top"><b>CUIL: </b><?= $renaper->cuil ?><span></span></td>
                        <?php endif; ?>

                    </tr>
                    <tr>
                        <?php if ($renaper->genero) : ?>
                            <td colspan="1" valign="top"><b>Género: </b> <?= $renaper->genero ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->fecha_nacimiento) : ?>
                            <td colspan="1" valign="top"><b>Fecha de nacimiento: </b> <?= $renaper->fecha_nacimiento ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($renaper->calle) : ?>
                            <td colspan="1" valign="top"><b>Calle: </b><?= $renaper->calle ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->numero) : ?>
                            <td colspan="1" valign="top"><b>Número: </b> <?= $renaper->numero ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($renaper->piso) : ?>
                            <td colspan="1" valign="top"><b>Piso: </b><?= $renaper->piso ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->departamento) : ?>
                            <td colspan="1" valign="top"><b>Departamento: </b> <?= $renaper->departamento ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($renaper->codigo_postal) : ?>
                            <td colspan="1" valign="top"><b>Código postal: </b><?= $renaper->codigo_postal ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->barrio) : ?>
                            <td colspan="1" valign="top"><b>Barrio: </b> <?= $renaper->barrio ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($renaper->monoblock) : ?>
                            <td colspan="1" valign="top"><b>Monoblock: </b><?= $renaper->monoblock ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->ciudad) : ?>
                            <td colspan="1" valign="top"><b>Ciudad: </b> <?= $renaper->ciudad ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($renaper->municipio) : ?>
                            <td colspan="1" valign="top"><b>Municipio: </b><?= $renaper->municipio ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->provincia) : ?>
                            <td colspan="1" valign="top"><b>Provincia: </b> <?= $renaper->provincia ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($renaper->pais) : ?>
                            <td colspan="1" valign="top"><b>País: </b><?= $renaper->pais ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->nacionalidad) : ?>
                            <td colspan="1" valign="top"><b>Nacionalidad: </b> <?= $renaper->nacionalidad ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($renaper->codigo_fallecido) : ?>
                            <td colspan="1" valign="top"><b>Código fallecido: </b><?= $renaper->codigo_fallecido ?><span></span></td>
                        <?php endif; ?>
                        <?php if ($renaper->mensaje_fallecido) : ?>
                            <td colspan="1" valign="top"><b>Mensaje fallecido: </b> <?= $renaper->mensaje_fallecido ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                    <tr>
                        <?php if ($renaper->fecha_fallecimiento) : ?>
                            <td colspan="1" valign="top"><b>Fecha fallecimiento: </b><?= $renaper->fecha_fallecimiento ?><span></span></td>
                        <?php endif; ?>
                    </tr>
                <?php endif; ?>
            </table>

            <?php if ($pages < $size) { ?>
                <div class="saltopagina"></div>
            <?php }
            $pages++;
            ?>
        <?php } ?>
    </div>
</body>

</html>