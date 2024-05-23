<?php

use app\models\Mds_org_informe;
use app\models\Mds_seg_item;
use \app\models\Mds_legales_oficio;


$modulo_org_informes = true; // Ahora lo visualizan todos asique se asigna en true
$modulo_legales_notificaciones = false;
$informes = array();
$notificacionesLegales['total'] = 0;
$notificacionesLegales['notificaciones'] = [];
$informes = Mds_org_informe::getInformesNoVistosByIdUsuario()->all();

$indexPermisos = 0;
$permisosLength = count($permisos);
//De agregarse mas casos al switch, comentar el while y descomentar el foreach
// foreach ($idPermisos as $r) :
while ($indexPermisos < $permisosLength && !$modulo_legales_notificaciones) {
    $r = $permisos[$indexPermisos];
    switch ($r->iditem) {
            // case Mds_seg_item::MODULO_ORG_INFORMES:
            //     $modulo_org_informes = true;
            //     break;
        case Mds_seg_item::MODULO_LEGALES_NOTIFICACIONES:

            //if (!$modulo_legales_notificaciones) {
            try {
                $notificacionesLegales = Mds_legales_oficio::getNotificaciones('layout');
            } catch (\Throwable $e) {
            }

            $modulo_legales_notificaciones = true;
            //}
            break;
    }
    $indexPermisos++;
}
// endforeach;

$tieneRolVinculacion = Mds_legales_oficio::tieneRol(Mds_legales_oficio::ID_ROL_VINCULACION);
$urlIndex = 'index.php?r=mds_legales_oficio%2Findex';
if ($tieneRolVinculacion) {
    $urlIndex = 'index.php?r=mds_legales_oficio/vinculacionenviar';
}

?>

<style>
    .overflow-y {
        overflow-y: auto;
        max-height: 450px;
    }
</style>

<ul class="notifications">
    <li style="margin-right:20px; display:<?= $modulo_legales_notificaciones ? "block" : "none" ?>">
        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
            <i class="fa fa-balance-scale"></i>
            <span class="badge">
                <?php echo $notificacionesLegales['total'] ?>
            </span>
        </a>
        <div class="dropdown-menu notification-menu">
            <div class="notification-title">
                Actividad
            </div>
            <div class="content overflow-y">
                <?php if ($notificacionesLegales['total'] == 0) : ?>
                    <div class="text-center">
                        No hay actividad
                    </div>
                <?php else : ?>
                    <?php
                    $notificaciones = $notificacionesLegales['notificaciones'];

                    $mensajeNotificaciones = [
                        'oficiosConRespuestasVistas' => 'Tiene requerimientos con <b>respuestas vistas</b>',
                        'requerimientosConObservacionFinal' => 'Tiene requerimientos con <b>observación por Equipo de Supervisión Final</b>',
                        'vencimientoPlazoOficios' => 'Tiene requerimientos <b>próximos a vencer / vencidos recientemente</b>',
                        'oficiosSinRespuestas' => 'Tiene requerimientos para <b>responder</b>',
                        'respuestasObservadas' => 'Tiene requerimientos con <b>respuestas observadas</b>',
                        'respuestasSinSupervisar' => 'Tiene requerimientos para <b>supervisar</b>',
                        'oficiosRespuestasAprobadasNoEnviadas' => 'Tiene requerimientos para <b>supervisar</b>',
                        'oficiosSinDerivarAUsuarios' => 'Tiene requerimientos para <b>derivar</b>',
                        'oficiosParaReDerivar' => 'Tiene requerimientos para <b>re-derivar</b>',
                        'respuestasRechazadas' => 'Tiene requerimientos devueltos por <b>Equipo de Supervisión Final</b>',
                        'oficiosParaReDerivarASupervisor' => 'Tiene requerimientos para <b>re-derivar a supervisión</b>',
                    ];

                    $mensajeNotificaciones = array_intersect_key($mensajeNotificaciones, $notificaciones); //Me quedo solo con los mensajes de las notificaciones que correspondan al rol del usuario
                    $onlyKeys = array_keys($mensajeNotificaciones);
                    $lastKey = end($onlyKeys);

                    foreach ($mensajeNotificaciones as $notificacion => $mensaje) :
                        if (array_key_exists($notificacion, $notificaciones) && $notificaciones[$notificacion]) : ?>
                        <a href="index.php?r=mds_notificacion%2Findex&modulo=legales" class="clearfix" style="color: black;"><?= $mensaje ?> </a>
                            <?php if ($notificacion !== $lastKey) : ?>
                                <hr>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </li>
</ul>
<ul class="notifications">
    <li style="display:<?= $modulo_org_informes ? "block" : "none" ?>">
        <a href="#" class="dropdown-toggle notification-icon" data-toggle="dropdown">
            <i class="fa fa-envelope"></i>
            <span class="badge">
                <?php echo sizeof($informes) ?>
            </span>
        </a>

        <div class="dropdown-menu notification-menu">
            <div class="notification-title">
                Informes sin leer
            </div>

            <div class="content overflow-y">
                <?php if (sizeof($informes) == 0) : ?>
                    <p class="text-center">No hay informes sin leer</p>

                    <hr>

                    <div class="text-right">
                        <a href="index.php?r=mds_org_informe" class="view-more">Ver informes recibidos</a>
                    </div>
                <?php else : ?>
                    <ul>
                        <?php foreach ($informes as $informe) : ?>
                            <li>
                                <a href="index.php?r=mds_org_informe%2Fview&id=<?php echo $informe->idinforme ?>" class="clearfix">
                                    <table>
                                        <td style="width: 95%">
                                            <b class="title"><?php echo $informe->getNombrePersona($informe->idusuario) ?></b>
                                            <small><?php echo date_format(date_create($informe->fecha), 'd-m-Y') ?></small>
                                        </td>
                                        <td style="width: 5%">
                                            <i style="display:<?= $informe->archivo_adjunto ? "block" : "none" ?>" title="Tiene archivo adjunto" class="glyphicon glyphicon-paperclip"></i>
                                        </td>
                                    </table>

                                    <span class="message"><?php echo $informe->asunto ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <hr>

                    <div class="text-right">
                        <a href="index.php?Mds_org_informeSearch[idinforme]=&Mds_org_informeSearch[fdesde]=&Mds_org_informeSearch[fhasta]=&Mds_org_informeSearch[tipo]=&Mds_org_informeSearch[asunto]=&Mds_org_informeSearch[iddispositivo]=&Mds_org_informeSearch[visto]=1&r=mds_org_informe" class="view-more">Ver informes no leidos</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </li>
</ul>