<?php

//print_r($permisos);

use app\models\Menu;

?>
<ul class="nav nav-main">
    <?php
    //Arranco a armar el arbol de menu
    echo Menu::getArbolMenu();
    /*if ($modulo_ans_negativa || $modulo_ans_jubilacion || $modulo_ans_alimentar || $modulo_tar_tarjeta || $modulo_atp_solicitud) {
        '<li class="nav-parent">                                    
                                        <a>
                                            <i class="fas fa-university" aria-hidden="true"></i><span>Anses</span>
                                        </a>
                                        <ul class="nav nav-children">';
        if ($modulo_ans_negativa) {
            '<li>
                                                    <a href="index.php?r=mds_ans_negativa">
                                                    <i class="fas fa-user-check" aria-hidden="true"></i>
                                                        <span>Certificación Negativa ANSES</span>
                                                    </a>                                            
                                                </li>';
        }
        if ($modulo_ans_jubilacion) {
            '<li>
                                            <a href="index.php?r=mds_ans_jubilacion">
                                            <i class="fas fa-users" aria-hidden="true"></i>
                                                <span>Beneficiarios de Jubilación</span>
                                            </a>                                            
                                        </li>';
        }
        if ($modulo_ans_alimentar) {
            '<li>
                                                        <a href="index.php?r=mds_ans_alimentar">
                                                            <i class="fas fa-credit-card" aria-hidden="true"></i>
                                                             Tarjeta Alimentar
                                                        </a>
                                                    </li>';
        }
        if ($modulo_tar_tarjeta) {
            '<li>
                                                <a href="index.php?r=sds_tar_tarjeta">
                                                    <i class="fas fa-credit-card" aria-hidden="true"></i>
                                                    Entrega Subse
                                                </a>
                                            </li>';
        }
        if ($modulo_atp_solicitud) {
            '<li>
                                                <a href="index.php?r=mds_atp_solicitud">
                                                    <i class="fas fa-credit-card" aria-hidden="true"></i>
                                                    ATPCen
                                                </a>
                                            </li>';
        }
        '</ul>
                                    </li>';
    }
    if ($modulo_por_familia || $modulo_por_desempleo || $modulo_por_sst || $modulo_ent_entregas) {

        '<li class="nav-parent">                                    
                                            <a>
                                                <i class="fas fa-user-shield" aria-hidden="true"></i><span>Asistencia</span>
                                            </a>
                                            <ul class="nav nav-children">';
        if ($modulo_por_familia || $modulo_por_desempleo || $modulo_por_sst) {
            '<li class="nav-parent">
                                                        <a>
                                                        <i class="fas fa-users" aria-hidden="true"></i>
                                                            <span>Subsidios</span>
                                                        </a>
                                                        <ul class="nav nav-children">';
            if ($modulo_por_familia) {
                '<li>
                                                            <a href="index.php?r=mds_por_familia">
                                                                <span>Subsecretaría de Familia</span>
                                                            </a>
                                                        </li>';
            }
            if ($modulo_por_desempleo) {
                '<li>
                                                            <a href="index.php?r=mds_por_desempleo">
                                                                <span>Desempleo</span>
                                                            </a>
                                                        </li>';
            }
            if ($modulo_por_sst) {
                '<li>
                                                                <a href="index.php?r=mds_por_sst">
                                                                    <span>Social Transitorio</span>
                                                                </a>
                                                            </li>';
            }
            '</ul>
                                            </li>';
        }
        if ($modulo_ent_entregas) {
            '<li>
                                                    <a href="index.php?r=sds_ent_entrega">
                                                    <i class="fas fa-people-carry" aria-hidden="true"></i>
                                                        Entregas Finales
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="index.php?r=sds_ent_entrega&entidad=-1">
                                                    <i class="fas fa-people-carry" aria-hidden="true"></i>
                                                        Entregas Intermedias
                                                    </a>
                                                </li>';
            if ($modulo_ent_arbol) {
                '<li>
                                                    <a href="index.php?r=sds_ent_entrega/arbol_entregas">
                                                    <i class="fas fa-boxes" aria-hidden="true"></i>
                                                        Árbol de Entregas
                                                    </a>
                                                </li>';
            }
            '<li>
                                                    <a href="index.php?r=sds_ent_saldo">
                                                    <i class="fas fa-boxes" aria-hidden="true"></i>
                                                        Saldos por Responsable
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="index.php?r=sds_ent_solicitud">
                                                    <i class="fas fa-people-carry" aria-hidden="true"></i>
                                                        Solic. Entregas
                                                    </a>
                                                </li>';
        }
        '</ul>
                                        </li>';
    }
    if ($modulo_cap_capacitacion) {
        '<li class="nav-parent">
                                                <a>
                                                <i class="fas fa-user-graduate" aria-hidden="true"></i>
                                                    <span>Capacitaciones</span>
                                                </a>
                                                <ul class="nav nav-children">';
        '<li>
                                                            <a href="index.php?r=mds_cap_capacitacion">                                                
                                                                <span>Capacitaciones</span>
                                                            </a>
                                                        </li>';
        '<li>
                                                                <a href="index.php?r=mds_cap_instancia">                                                
                                                                    <span>Instancias</span>
                                                                </a>
                                                        </li>';
        '<li>
                                                                <a href="index.php?r=mds_cap_persona">
                                                                    <span>Inscriptos</span>
                                                                </a>
                                                        </li>';
        '<li>
                                                                <a href="index.php?r=mds_cap_inscripcion">                                                
                                                                    <span>Inscripciones</span>
                                                                </a>
                                                        </li>';
        '</ul>
                                            </li>';
    }
    '<li class="nav-parent">
                                            <a>
                                                <i class="fas fa-cogs" aria-hidden="true"></i>                                                
                                                <span>Configuración</span>
                                            </a>
                                            <ul class="nav nav-children">';
    if ($modulo_seg_seguridad) {
        '<li class="nav-parent">
                                                <a>
                                                    <i class="fa fa-lock" aria-hidden="true"></i>
                                                    <span>Seguridad</span>
                                                </a>
                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a href="index.php?r=mds_seg_usuario">
                                                            Usuarios
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="index.php?r=mds_seg_rol">
                                                            Roles
                                                        </a>
                                                    </li>                                                  
                                                </ul>
                                            </li>';
    }
    ' <li>
                                        <a href="index.php?r=sds_com_configuracion">
                                            <i class="fas fa-cog" aria-hidden="true"></i>      
                                            Configuraciones
                                        </a>
                                    </li>
                                    <li>
                                        <a href="index.php?r=sds_com_localidad">
                                            <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                                            Localidad
                                        </a>
                                    </li>
                               
                                </ul>
                            </li>';
    '<li class="nav-parent">                                    
                                        <a>
                                            <i class="fas fa-random" aria-hidden="true"></i><span>Cruce</span>
                                        </a>
                                        <ul class="nav nav-children">
                                            <li>
                                                <a href="index.php?r=site/cruce&unificado=0">
                                                    Múltiple
                                                </a>
                                            </li>
                                            <li>
                                                <a href="index.php?r=site/cruce&unificado=1">
                                                    Unificado
                                                </a>
                                            </li>
                                        </ul>
                                    </li>';
    if ($modulo_gis_capa || $modulo_gis_mapa) {
        '<li class="nav-parent">
                                            <a>
                                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                                <span>GIS</span>
                                            </a>
                                            <ul class="nav nav-children">';
        if ($modulo_gis_capa) {
            '<li>
                                                        <a href="index.php?r=sds_gis_capa">                                                    
                                                            <span>Capas</span>
                                                        </a>
                                                    </li>';
            '<li>
                                                        <a href="index.php?r=sds_gis_capa_item">                                                    
                                                            <span>Ubicaciones</span>
                                                        </a>
                                                    </li>';
        }
        if ($modulo_gis_mapa) {
            '<li>
                                                        <a href="index.php?r=sds_gis_mapa">                                                    
                                                            <span>Mapa</span>
                                                        </a>
                                                    </li>';
        }
        '</ul>
                                        </li>';
    }
    if ($modulo_not_notas || $modulo_reg_autosolicitud || $modulo_reg_registros || $modulo_reg_tipos || $modulo_cel_corp || $modulo_telefonia_vista) {
        '<li class="nav-parent">
                                            <a>
                                                <i class="fas fa-network-wired" aria-hidden="true"></i>
                                                <span>Informática</span>
                                            </a>
                                            <ul class="nav nav-children">';
        if ($modulo_reg_registros || $modulo_reg_tipos) {
            '<li class="nav-parent">
                                                <a>
                                                    <i class="fa fa-tasks" aria-hidden="true"></i>
                                                    <span>Registro Técnico</span>
                                                </a>
                                            <ul class="nav nav-children">';
            if ($modulo_reg_registros) {
                //$tipos_registros = Sds_reg_tipo::find()->orderBy(["descripcion" => SORT_ASC])->all();
                '<li>
                                                    <a href="index.php?r=sds_reg_registro&idtipo=0&soloincidencias=0">Trabajos Pendientes</a>
                                                </li>';
                /*foreach ($tipos_registros as $tipo_reg) {
                                                 '<li>
                                                                    <a href="index.php?r=sds_reg_registro&idtipo=' . $tipo_reg->idtipo . '">Pendientes ' .
                                                    $tipo_reg->descripcion .
                                                    '</a>
                                                                </li>';
                                            }
                                                '<li>
                                                                <a href="index.php?r=sds_reg_registro/incidencias">
                                                                    Pendientes Incidencias
                                                                </a>
                                                            </li>'; 
                '<li>
                                                        <a href="index.php?r=sds_reg_registro/create">
                                                            Cargar Nuevo Registro
                                                        </a>
                                                    </li>';
                '<li>
                                                        <a href="index.php?r=sds_reg_registro/cerrados">
                                                            Historial de Trabajo
                                                        </a>
                                                    </li>';
                '<li>
                                                    <a href="index.php?r=sds_reg_ip">
                                                        Control de Ips
                                                    </a>
                                                </li>';
            }
            if ($modulo_reg_tipos) {
                '<li>
                                                                <a href="index.php?r=sds_reg_tipo">
                                                                    Tipos de Registros
                                                                </a>
                                                            </li>';
            }
            '</ul>
                                                    </li>';
        }
        if ($modulo_reg_autosolicitud) {
            '<li>
                                                        <a href="index.php?r=sds_reg_registro_autosolicitud">
                                                            <i class="fas fa-tools" aria-hidden="true"></i>
                                                            <span>Solicitudes de Soporte</span>
                                                        </a>
                                                    </li>';
        }
        if ($modulo_cel_corp || $modulo_telefonia_vista) {
            '<li class="nav-parent">
                                                <a><i class="fas fa-mobile-alt" aria-hidden="true"></i>            
                                                    <span>Telefonía</span>
                                                </a>
                                                <ul class="nav nav-children">';
            if ($modulo_cel_corp) {
                '<li>
                                                                <a href="index.php?r=sds_cel_linea">
                                                                    Líneas Corporativas
                                                                </a>
                                                            </li>';
                '<li>
                                                        <a href="index.php?r=sds_cel_plan">
                                                            Planes Corporativos
                                                        </a>
                                                    </li>';
            }
            if ($modulo_telefonia_vista) {
                '<li>
                                                        <a href="index.php?r=telefonia_vista_abonos">
                                                            Abonos
                                                        </a>
                                                    </li>';
                '<li>
                                                        <a href="index.php?r=telefonia_vista_equipos">
                                                            Equipos
                                                        </a>
                                                    </li>';
                '<li>
                                                        <a href="index.php?r=telefonia_vista_linea">
                                                            Líneas
                                                        </a>
                                                    </li>';
                '<li>
                                                    <a href="index.php?r=telefonia_vista_integradora">
                                                        Optic
                                                    </a>
                                                </li>';
                '<li>
                                                    <a href="index.php?r=telefonia_vista_titular">
                                                        Titulares
                                                    </a>
                                                </li>';
            }
            '</ul>
                                            </li>';
        }
        if ($modulo_not_notas) {
            ' <li>
                                                    <a href="index.php?r=mds_not_nota">
                                                    <i class="far fa-file-alt" aria-hidden="true"></i>
                                                        Notas
                                                    </a>
                                                </li>';
        }
        '</ul>
                                        </li>';
    }
    if ($modulo_cor_intervencion || $modulo_ris_risneu || $modulo_800_llamada || $modulo_vio_violencia) {
        '<li class="nav-parent">
                                            <a>
                                            <i class="fas fa-restroom" aria-hidden="true"></i>                                          
                                                <span>Intervenciones</span>
                                            </a>
                                            <ul class="nav nav-children">';
        if ($modulo_cor_intervencion) {
            '<li>
                                                    <a href="index.php?r=mds_cor_intervencion">
                                                        <i class="fas fa-user-friends" aria-hidden="true"></i>
                                                        <span>Intervenciones Psicosoc.</span>
                                                    </a>
                                                </li>';
        }
        if ($modulo_ris_risneu) {
            '<li>
                                                <a href="index.php?r=sds_ris_risneu&oficial=1">
                                                <i class="far fa-id-card" aria-hidden="true"></i>
                                                    RisNeu Oficial
                                                </a>
                                            </li>';
            '<li>
                                                <a href="index.php?r=sds_ris_risneu&oficial=0">
                                                <i class="far fa-id-card" aria-hidden="true"></i>
                                                    RisNeu No Oficial
                                                </a>
                                            </li>';
        }
        if ($modulo_800_llamada) {
            '<li class="nav-parent">
                                                <a>
                                                    <i class="fas fa-phone-volume" aria-hidden="true"></i>
                                                    <span>LLamadas 0800</span>
                                                </a>
                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a href="index.php?r=sds_800_llamada">
                                                            Situación de Calle
                                                        </a>
                                                    </li>                                                
                                                    <li>
                                                        <a href="index.php?r=sds_800_derivacion">
                                                            Derivaciones
                                                        </a>
                                                    </li>                                                    
                                                </ul>
                                            </li>';
        }
        if ($modulo_vio_violencia) {
            '<li>
                                        <a href="index.php?r=sds_vio_intervencion">
                                        <i class="fas fa-female" aria-hidden="true"></i>
                                        Violencia
                                        </a>
                                    </li>';
        }
        '</ul>
                                        </li>';
    }
    '<li class="nav-parent">
                                        <a>
                                            <i class="fas fa-sitemap" aria-hidden="true"></i>
                                            <span>Estructura</span>
                                        </a>
                                        <ul class="nav nav-children">';
    if ($modulo_org_organismos) {
        '<li>
                                            <a href="index.php?r=mds_org_organismo">
                                                Organismos
                                            </a>
                                        </li>';
    }
    if ($modulo_org_contactos) {
        '<li>
                                            <a href="index.php?r=mds_org_contacto">
                                                Contactos
                                            </a>
                                        </li>';
    }
    if ($modulo_org_dispositivos) {
        '<li>
                                                <a href="index.php?r=mds_org_dispositivo">
                                                    Dispositivos
                                                </a>
                                            </li>';
    }
    if ($modulo_org_informes) {
        '<li>
                                                <a href="index.php?r=mds_org_informe">
                                                    Informes de dispositivos
                                                </a>
                                            </li>';
    }
    '<li>
                                           <a href="index.php?r=mds_org_organismo/organigrama">
                                                Organigrama
                                            </a>
                                        </li>';
    '</ul>
                                    </li>';
    if ($modulo_rrhh) {
        '<li class="nav-parent">
                                                <a>
                                                    <i class="fas fa-user-clock" aria-hidden="true"></i>                                          
                                                    <span>RRHH</span>
                                                </a>
                                                <ul class="nav nav-children">';
        '<li>
                                                <a href="index.php?r=mds_hor_registro">
                                                    Registros Horarios
                                                 </a>
                                             </li>';
        '<li>
                                                <a href="index.php?r=mds_hor_asistencia_reporte">
                                                    Reporte de Asistencia
                                                 </a>
                                             </li>';
        '<li>
                                                <a href="index.php?r=mds_org_documento">
                                                    Documentos
                                                 </a>
                                             </li>';
        '<li>
                                                <a href="index.php?r=mds_hor_franco">
                                                    Francos
                                                 </a>
                                             </li>';
        '<li>
                                                <a href="index.php?r=mds_hor_feriado">
                                                    Feriados
                                                 </a>
                                             </li>';
        '<li>
                                                <a href="index.php?r=mds_hor_licencia">
                                                    Licencias
                                                 </a>
                                             </li>';

        '</ul>
                                         </li>';
    }
    //LO QUE SIQUE ES PARA RUMBO
    if (
        $modulo_rum_oferta_laboral || $modulo_rum_postulacion || $modulo_rum_empleador
        || $modulo_rum_novedad  || $modulo_rum_institucional || $modulo_rum_cv
    ) {
        '<li class="nav-parent">
                                    <a>
                                        <i class="fas fa-file-alt" aria-hidden="true"></i>                                                
                                        <span>Rumbo</span>
                                    </a>
                                    <ul class="nav nav-children">';
        if ($modulo_rum_oferta_laboral) {
            '<li class="nav-parent">
                                                    <a>
                                                        <i class="fa fa-lock" aria-hidden="true"></i>
                                                        <span>Ofertas Laborales</span>
                                                    </a>
                                                    <ul class="nav nav-children">
                                                        <li>
                                                            <a href="index.php?r=mds_rum_oferta_laboral">
                                                                Administrar
                                                            </a>
                                                        </li>';
            if ($modulo_rum_postulacion) {
                '
                                                            <li>
                                                                <a href="index.php?r=mds_rum_postulacion">
                                                                    Postulaciones
                                                                </a>
                                                            </li>';
            }

            '
                                                    </ul>
                                                </li>';
        }
        if ($modulo_rum_empleador) {
            '<li class="nav-parent">
                                            <a>
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                <span>Empresas</span>
                                            </a>
                                            <ul class="nav nav-children">
                                                <li>
                                                <a href="index.php?r=mds_rum_empleador">
                                                        Administrar
                                                    </a>
                                                </li>                                                                                                                                                                                        
                                            </ul>
                                            </li>';
        }
        if ($modulo_rum_novedad) {
            '<li class="nav-parent">
                                            <a>
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                <span>Novedades</span>
                                            </a>
                                            <ul class="nav nav-children">
                                                <li>
                                                    <a href="index.php?r=mds_rum_novedad">
                                                        Administrar
                                                    </a>
                                                </li>                                                                                                                                                                                              
                                            </ul>
                                            </li>';
        }
        if ($modulo_rum_institucional) {
            '<li class="nav-parent">
                                            <a>
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                <span>Institucional</span>
                                            </a> 

                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a href="index.php?r=mds_rum_institucional">
                                                            Administrar
                                                        </a>
                                                    </li> 
                                                </ul>
                                            </li>';
        }
        if ($modulo_rum_cv) {
            '<li class="nav-parent">
                                            <a>
                                                <i class="fa fa-lock" aria-hidden="true"></i>
                                                <span>Perfiles</span>
                                            </a> 

                                                <ul class="nav nav-children">
                                                    <li>
                                                        <a href="index.php?r=mds_rum_persona">
                                                            CVs
                                                        </a>
                                                    </li> 
                                                </ul>
                                            </li>';
        }
        '</ul>
                                    </li>';
    }*/
    //FIN CODIGO RUMBO                                    
    ?>
    <!-- Hasta aca se reemplaza por dinamico -->
</ul>