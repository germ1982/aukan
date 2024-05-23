<?php
    $menu_ubicacion='';
    $menu_actividad='';
    $activo=$_SESSION['menu_capa'];
    switch ($activo) {
    case 1:
        $menu_ubicacion='class="active"';
        break;
    case 2:
        $menu_actividad='class="active"';
        break;
    default:
        break;
    }
    echo '<div class="col-md-3 col-sm-2">
                <section id="sidebar">
                    <header><h3>Configurar GIS</h3></header>
                    <aside>
                        <ul class="sidebar-navigation">
                            <li '.$menu_ubicacion.'><a href="ubicacion_listado.php"><i class="fa fa-map-marker"></i><span>Ubicaciones</span></a></li>
                            <!--<li '.$menu_actividad.'><a href="actividad_listado.php"><i class="fa fa-home"></i><span>Actividades</span></a></li>
                            <li><a href="ubicacion_listado.php"><i class="fa fa-heart"></i><span>Bookmarked Properties</span></a></li>-->
                        </ul>
                    </aside>
                </section><!-- /#sidebar -->
            </div>';
    
