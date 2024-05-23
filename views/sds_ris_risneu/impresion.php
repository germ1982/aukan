<?php

?>
<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img style="margin-top: 0; padding-top: 0" src="img/header_desa_social_azul.jpg" width="100%" alt="Subsecretaría de Desarrollo Social">
        <!--<div class="row" style="padding-top: 1%;">
        <div class="col-xs-offset-7 col-xs-5" style="text-align: right;">Neuquén</div>
    </div>-->
        <div class="row" style="padding-top: 1%;">

            <table class="tabla-principal">
                <thead>

                </thead>
                <tbody>
                    <tr>
                        <td><small><strong>RISNeu N°: </strong><?php echo $risneu->idrisneu  ?> </small></td>
                        <td><small><strong>Fecha: </strong><?php $fecha = date_create($risneu->fecha);
                                                            echo date_format($fecha, 'd-m-Y'); ?> </small></td>
                        <td><small><strong>Encuestador: </strong><?php echo ucwords(mb_strtolower($risneu->encuestador0->descripcion)) ?> </small></td>
                        <td><small><strong>Realizado por: </strong><?php
                                                                    $realizadoPorPointStart = strpos($risneu->realizadoPor->descripcion, ".") ? strpos($risneu->realizadoPor->descripcion, ".") + 1 : 0;
                                                                    echo substr($risneu->realizadoPor->descripcion, $realizadoPorPointStart) ?> </small></td>

                        <!-- <td><small><strong>Encuestador: </strong><?php echo ucfirst(mb_strtolower($risneu->usuario->apellido)) . " " . ucfirst(mb_strtolower($risneu->usuario->nombre)) ?> </small></td>-->

                    </tr>
                    <tr>
                        <td><small><strong>Area: </strong><?php echo
                                                            $areaPointStart = strpos($risneu->area0->descripcion, ".") ? strpos($risneu->area0->descripcion, ".") + 1 : 0;
                                                            substr($risneu->area0->descripcion, $areaPointStart) ?> </small></td>
                        <td><small><strong>Localidad: </strong><?php echo $risneu->idbarrio0->localidad->descripcion . ' (' . $risneu->idbarrio0->localidad->codigo_postal . ')' ?> </small></td>
                        <td><small><strong>Barrio: </strong><?php echo $risneu->idbarrio0->nombre ?> </small></td>
                        <td>
                            <small><strong>Calle: </strong><?php echo $risneu->calle0->descripcion . " " .  $risneu->calle_numero;
                                                            echo ($risneu->calleInterseccion) ? " (" . $risneu->calleInterseccion->descripcion . ")" : ''  ?> </small>
                            <?= $risneu->casa ? "<small><strong>Casa:</strong> $risneu->casa</small>" : '' ?>
                            <?= $risneu->torre ? "<small><strong>Torre:</strong> $risneu->torre</small>" : '' ?>
                            <?= $risneu->piso ? "<small><strong>Piso:</strong> $risneu->piso</small>" : '' ?>
                            <?= $risneu->depto ? "<small><strong>Depto:</strong> $risneu->depto</small>" : '' ?>
                            <?= $risneu->manzana ? "<small><strong>Manzana:</strong> $risneu->manzana</small>" : '' ?>
                            <?= $risneu->parcela ? "<small><strong>Parcela:</strong> $risneu->parcela</small>" : '' ?>
                            <?= $risneu->lote ? "<small><strong>Lote:</strong> $risneu->lote</small>" : '' ?>
                            <?= $risneu->pilar ? "<small><strong>Pilar:</strong> $risneu->pilar</small>" : '' ?>
                        </td>

                    </tr>
                </tbody>

            </table>

        </div>
        <br>
        <div class="row">
            <table class="tabla_risneu">
                <thead>
                    <tr>
                        <th width="9%">Ap y Nombre</th>
                        <th>DNI</th>
                        <th>Sexo</th>
                        <th width="6%">Nac</th>
                        <th>F.Nac</th>
                        <th width="8%">Parentesco</th>
                        <th>S.Conyugal</th>
                        <th>Escolaridad</th>
                        <th>Ultimo año</th>
                        <th width="6%">Trabajo</th>
                        <th>Vínculo</th>
                        <th width="7%">Tipo</th>
                        <th width="8%">Salud</th>
                        <th>CUD</th>
                        <th width="13%">Enfermedades</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($risneu->sdsRisPersonas as $personaRis) {
                        if ($personaRis->activo == 1) {
                    ?>
                            <?php
                            $persona = \app\models\Sds_com_persona::find()->where(['idpersona' => $personaRis['idpersona']])->one();
                            $enfermedades = \app\models\Sds_ris_persona_enfermedad::find()->where("idpersonarisneu = {$personaRis['idpersonarisneu']} AND deleted_at IS NULL")->all();
                            $discapacidades = \app\models\Sds_ris_persona_discapacidad::find()->where("idpersonarisneu = {$personaRis['idpersonarisneu']} AND deleted_at IS NULL")->all();
                            ?>
                            <tr>
                                <td class="letra"><small><?php echo ucwords(mb_strtolower($persona->apellido)) . " " . ucwords(mb_strtolower($persona->nombre)); ?></small></td>
                                <td class="letra"><small><?php echo $persona->documento; ?></small></td>
                                <td><?php echo $persona->genero0->descripcion[0] ?></td>
                                <td class="letra"><small><?php
                                                            $nacionalidadPointStart = strpos($persona->nacionalidad0->descripcion, ".") ? strpos($persona->nacionalidad0->descripcion, ".") + 1 : 0;
                                                            echo substr($persona->nacionalidad0->descripcion, $nacionalidadPointStart); ?></small></td>
                                <td class="letra"><small><?php $fecha = date_create($persona->fecha_nacimiento);
                                                            echo date_format($fecha, 'd-m-Y'); ?></small></td>
                                <td class="letra"><small><?php
                                                            $parentescoPointStart = strpos($personaRis->parentezco0->descripcion, ".") ? strpos($personaRis->parentezco0->descripcion, ".") + 1 : 0;
                                                            echo substr($personaRis->parentezco0->descripcion, $parentescoPointStart) ?></small></td>
                                <td class="letra"><small><?php
                                                            $situacionConyugalPointStart = strpos($personaRis->situacionConyugal->descripcion, ".") ? strpos($personaRis->situacionConyugal->descripcion, ".") + 1 : 0;
                                                            echo substr($personaRis->situacionConyugal->descripcion, $situacionConyugalPointStart); ?></small></td>
                                <td class="letra">
                                    <small><strong>Escolaridad:</strong></small>
                                    <ul>
                                        <li><small><?php
                                                    $escolaridadPointStart = strpos($personaRis->escolaridad0->descripcion, ".") ? strpos($personaRis->escolaridad0->descripcion, ".") + 1 : 0;
                                                    echo substr($personaRis->escolaridad0->descripcion, $escolaridadPointStart) ?></small></li>

                                    </ul>
                                    <small><strong>T.Establecimiento:</strong></small>
                                    <ul>
                                        <li><small><?php
                                                    $tipoEstablecimientoEducativoPointStart = strpos($personaRis->tipoEstablecimientoEducativo->descripcion, ".") ? strpos($personaRis->tipoEstablecimientoEducativo->descripcion, ".") + 1 : 0;
                                                    echo substr($personaRis->tipoEstablecimientoEducativo->descripcion, $tipoEstablecimientoEducativoPointStart) ?></small></li>
                                    </ul>
                                </td>
                                <td class="letra"><small><?php
                                                            $ultimoAnoAprobadoPointStart = strpos($personaRis->ultimoAnoAprobado->descripcion, ".") ? strpos($personaRis->ultimoAnoAprobado->descripcion, ".") + 1 : 0;
                                                            echo substr($personaRis->ultimoAnoAprobado->descripcion, $ultimoAnoAprobadoPointStart) ?></small></td>
                                <td class="letra">
                                    <p><small><strong>Tbj:</strong> <?php
                                                                    $trabajoPointStart = strpos($personaRis->trabajo0->descripcion, ".") ? strpos($personaRis->trabajo0->descripcion, ".") + 1 : 0;
                                                                    echo substr($personaRis->trabajo0->descripcion, $trabajoPointStart) ?></small></p>
                                    <p><small><strong>Horas:</strong> <?php echo $personaRis->trabajo_horas ?></small></p>
                                    <p><small><strong>Dias:</strong><?php echo $personaRis->trabajo_dias ?></small></p>
                                </td>
                                <td class="letra">
                                    <p><small><?php
                                                $vinculoContractualPointStart = strpos($personaRis->vinculoContractual->descripcion, ".") ? strpos($personaRis->vinculoContractual->descripcion, ".") + 1 : 0;
                                                echo substr($personaRis->vinculoContractual->descripcion, $vinculoContractualPointStart) ?></small></p>
                                    <small><strong>Ingreso Mensual:</strong> <?php echo ($personaRis->ingreso == 1) ? "Si" : "No" ?></small>
                                </td>
                                <td class="letra">
                                    <small><?php
                                            $trabajoTipoPointStart = strpos($personaRis->trabajoTipo->descripcion, ".") ? strpos($personaRis->trabajoTipo->descripcion, ".") + 1 : 0;
                                            echo substr($personaRis->trabajoTipo->descripcion, $trabajoTipoPointStart) ?></small>
                                </td>
                                <td class="letra">
                                    <?php if (!empty($discapacidades)) : ?>
                                        <small><strong>Discapacidad:</strong></small>
                                        <?php
                                        $discapacidadesString = "<li><small>Sin datos</small></li>";
                                        $discapacidadesString = "";
                                        foreach ($discapacidades as $key => $discapacidad) {
                                            $discapacidadRelPointStart = strpos($discapacidad->discapacidadRel->descripcion, ".") ? strpos($discapacidad->discapacidadRel->descripcion, ".") + 1 : 0;
                                            $discapacidadesString .= "<li><small>" . substr($discapacidad->discapacidadRel->descripcion, $discapacidadRelPointStart) . "</small></li>";
                                        }
                                        ?>
                                        <ul>
                                            <?= $discapacidadesString ?>
                                        </ul>
                                    <?php endif ?>
                                    <small><strong>Cob.Salud:</strong></small>
                                    <ul>
                                        <li><small><?php
                                                    $coberturaSaludPointStart = strpos($personaRis->coberturaSalud->descripcion, ".") ? strpos($personaRis->coberturaSalud->descripcion, ".") + 1 : 0;
                                                    echo substr($personaRis->coberturaSalud->descripcion, $coberturaSaludPointStart) ?></small></li>
                                    </ul>
                                </td>
                                <td class="letra">
                                    <?php echo ($personaRis->cud) ? "<small>Si</small>" : "<small>No</small>"  ?>
                                </td>
                                <td class="letra"><?php $lastkeyElementArray = array_key_last($enfermedades)  ?>
                                    <?php
                                    if ($enfermedades == null) {
                                        // echo "<small>Sin datos</small>";
                                        echo "";
                                    }
                                    foreach ($enfermedades as $key => $enfermedad) { ?>
                                        <small><?php
                                                $enfermedadRelPointStart = strpos($enfermedad->enfermedadRel->descripcion, ".") ? strpos($enfermedad->enfermedadRel->descripcion, ".") + 1 : 0;
                                                echo substr($enfermedad->enfermedadRel->descripcion, $enfermedadRelPointStart) ?></small>
                                    <?php
                                        if ($lastkeyElementArray != $key) {
                                            echo ",";
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
        <?php
        if ($risneu->sdsRisRisneuAlimentacions) { ?>
            <div class="row">
                <h4>Alimentación:</h4>
                <?php
                foreach ($risneu->sdsRisRisneuAlimentacions as $alimentacion) {
                    $alimentacionPointStart = strpos($alimentacion->alimentacion0->descripcion, ".") ? strpos($alimentacion->alimentacion0->descripcion, ".") + 1 : 0;
                ?>
                    <small style="display: inline"><?php echo "-" . substr($alimentacion->alimentacion0->descripcion, $alimentacionPointStart) ?></small>

                <?php

                }
                ?>
            </div>

        <?php
        }
        ?>

        <div class="row">
            <h4>Vivienda:</h4>
            <table class="tabla-vivienda">
                <thead>
                    <tr>
                        <th width="25%"></th>
                        <th width="25%"></th>
                        <th width="33%"></th>
                        <th width="15%"></th>
                    </tr>

                </thead>
                <tbody>
                    <tr>
                        <td><small><strong>La vivienda es:</strong> <?php
                                                                    $viviendaUsoPointStart = strpos($risneu->viviendaUso->descripcion, ".") ? strpos($risneu->viviendaUso->descripcion, ".") + 1 : 0;
                                                                    echo ($risneu->viviendaUso->idconfiguracion != 1) ?  substr($risneu->viviendaUso->descripcion, $viviendaUsoPointStart) : $risneu->viviendaUso->descripcion  ?> </small></td>
                        <td><small><strong>Esta ubicada en:</strong> <?php
                                                                        $viviendaUbicacionPointStart = strpos($risneu->viviendaUbicacion->descripcion, ".") ? strpos($risneu->viviendaUbicacion->descripcion, ".") + 1 : 0;
                                                                        echo ($risneu->viviendaUbicacion->idconfiguracion != 1) ? substr($risneu->viviendaUbicacion->descripcion, $viviendaUbicacionPointStart) :  $risneu->viviendaUbicacion->descripcion ?> </small></td>
                        <td><small><strong>Propiedad:</strong> <?php
                                                                $viviendaPropiedadPointStart = strpos($risneu->viviendaPropiedad->descripcion, ".") ? strpos($risneu->viviendaPropiedad->descripcion, ".") + 1 : 0;
                                                                echo ($risneu->viviendaPropiedad->idconfiguracion != 1) ?  substr($risneu->viviendaPropiedad->descripcion, $viviendaPropiedadPointStart) : $risneu->viviendaPropiedad->descripcion  ?> </small></td>
                        <td><small><strong>Cant. Habitaciones:</strong> <?php echo  $risneu->vivienda_habitaciones ?></small></td>

                    </tr>
                    <tr>
                        <td><small><strong>Tipo Vivienda:</strong> <?php
                                                                    $viviendaTipoPointStart = strpos($risneu->viviendaTipo->descripcion, ".") ? strpos($risneu->viviendaTipo->descripcion, ".") + 1 : 0;
                                                                    echo ($risneu->viviendaTipo->idconfiguracion != 1) ?  substr($risneu->viviendaTipo->descripcion, $viviendaTipoPointStart) : $risneu->viviendaTipo->descripcion ?> </small></td>
                        <td><small><strong>Piso:</strong> <?php
                                                            $viviendaPisoPointStart = strpos($risneu->viviendaPiso->descripcion, ".") ? strpos($risneu->viviendaPiso->descripcion, ".") + 1 : 0;
                                                            echo ($risneu->viviendaPiso->idconfiguracion != 1) ?  substr($risneu->viviendaPiso->descripcion, $viviendaPisoPointStart) : $risneu->viviendaPiso->descripcion  ?> </small></td>
                        <td><small><strong>Obtiene el agua:</strong> <?php
                                                                        $viviendaAguaObtienePointStart = strpos($risneu->viviendaAguaObtiene->descripcion, ".") ? strpos($risneu->viviendaAguaObtiene->descripcion, ".") + 1 : 0;
                                                                        echo ($risneu->viviendaAguaObtiene->idconfiguracion != 1) ?  substr($risneu->viviendaAguaObtiene->descripcion, $viviendaAguaObtienePointStart) : $risneu->viviendaAguaObtiene->descripcion  ?> </small></td>
                        <td><small><strong>Tiene Agua:</strong> <?php
                                                                $vivendaAguaPointStart = strpos($risneu->vivendaAgua->descripcion, ".") ? strpos($risneu->vivendaAgua->descripcion, ".") + 1 : 0;
                                                                echo ($risneu->vivendaAgua->idconfiguracion != 1) ?  substr($risneu->vivendaAgua->descripcion, $vivendaAguaPointStart) :  $risneu->vivendaAgua->descripcion ?></small></td>
                    </tr>
                    <tr>
                        <td><small><strong>Baño:</strong> <?php
                                                            $viviendaBanoPointStart = strpos($risneu->viviendaBano->descripcion, ".") ? strpos($risneu->viviendaBano->descripcion, ".") + 1 : 0;
                                                            echo ($risneu->viviendaBano->idconfiguracion != 1) ?  substr($risneu->viviendaBano->descripcion, $viviendaBanoPointStart) : $risneu->viviendaBano->descripcion  ?> </small></td>
                        <td><small><strong>Desagüe:</strong> <?php
                                                                $viviendaDesaguePointStart = strpos($risneu->viviendaDesague->descripcion, ".") ? strpos($risneu->viviendaDesague->descripcion, ".") + 1 : 0;
                                                                echo ($risneu->viviendaDesague->idconfiguracion != 1) ?  substr($risneu->viviendaDesague->descripcion, $viviendaDesaguePointStart) :  $risneu->viviendaDesague->descripcion ?> </small></td>
                        <td><small><strong>Iluminación:</strong> <?php
                                                                    $viviendaIluminacionPointStart = strpos($risneu->viviendaIluminacion->descripcion, ".") ? strpos($risneu->viviendaIluminacion->descripcion, ".") + 1 : 0;
                                                                    echo ($risneu->viviendaIluminacion->idconfiguracion != 1) ? substr($risneu->viviendaIluminacion->descripcion, $viviendaIluminacionPointStart) : $risneu->viviendaIluminacion->descripcion  ?></small></td>
                        <td><small><strong>Medidor:</strong> <?php
                                                                $viviendaMedidorPointStart = strpos($risneu->viviendaMedidor->descripcion, ".") ? strpos($risneu->viviendaMedidor->descripcion, ".") + 1 : 0;
                                                                echo ($risneu->viviendaMedidor->idconfiguracion != 1) ?  substr($risneu->viviendaMedidor->descripcion, $viviendaMedidorPointStart) : $risneu->viviendaMedidor->descripcion  ?></small></td>
                    </tr>
                    <tr>
                        <td><small><strong>Calefacción:</strong> <?php
                                                                    $vivendaCombustibleCalefaccionPointStart = strpos($risneu->vivendaCombustibleCalefaccion->descripcion, ".") ? strpos($risneu->vivendaCombustibleCalefaccion->descripcion, ".") + 1 : 0;
                                                                    echo ($risneu->vivendaCombustibleCalefaccion->idconfiguracion != 1) ?  substr($risneu->vivendaCombustibleCalefaccion->descripcion, $vivendaCombustibleCalefaccionPointStart)  : $risneu->vivendaCombustibleCalefaccion->descripcion ?> </small></td>
                        <td><small><strong>Cocina:</strong> <?php
                                                            $viviendaCombustibleCocinaPointStart = strpos($risneu->viviendaCombustibleCocina->descripcion, ".") ? strpos($risneu->viviendaCombustibleCocina->descripcion, ".") + 1 : 0;
                                                            echo ($risneu->viviendaCombustibleCocina->idconfiguracion != 1) ? substr($risneu->viviendaCombustibleCocina->descripcion, $viviendaCombustibleCocinaPointStart) : $risneu->viviendaCombustibleCocina->descripcion  ?> </small></td>
                        <td><small><strong>Techo:</strong> <?php
                                                            $viviendaTechoPointStart = strpos($risneu->viviendaTecho->descripcion, ".") ? strpos($risneu->viviendaTecho->descripcion, ".") + 1 : 0;
                                                            echo ($risneu->viviendaTecho->idconfiguracion != 1) ? substr($risneu->viviendaTecho->descripcion, $viviendaTechoPointStart) : $risneu->viviendaTecho->descripcion  ?></small></td>
                        <td><small><strong>Paredes:</strong> <?php
                                                                $viviendaParedesPointStart = strpos($risneu->viviendaParedes->descripcion, ".") ? strpos($risneu->viviendaParedes->descripcion, ".") + 1 : 0;
                                                                echo ($risneu->viviendaParedes->idconfiguracion != 1) ? substr($risneu->viviendaParedes->descripcion, $viviendaParedesPointStart)  : $risneu->viviendaParedes->descripcion ?></small></td>
                    </tr>
                </tbody>


            </table>


        </div>
        <?php
        if ($risneu->observaciones) { ?>
            <h4>Observaciones:</h4> <small><?php echo $risneu->observaciones ?></small>
        <?php
        }
        ?>

    </div>
</body>

</html>