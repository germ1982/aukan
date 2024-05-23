<div class="navigation" style="height: 50px;">
    <div class="container">
        <header class="navbar" id="top" role="banner">
            <div class="navbar-header">
                <div class="navbar-brand nav" id="brand">
                    <span style="position: fixed;left:10;cursor:pointer;" onclick="openNav()"><i class="fas fa-bars" style="font-size: 28px;"></i></span>
                    <a style="position: fixed;left:50;cursor:pointer;" href="index.php"><img src="assets/img/logo.png" alt="brand"></a>
                </div>
            </div>
        </header>
    </div>
</div>
                
<?php
/*
    include 'includes/bd.php';
    $contenido= 
        '<div class="navigation">
        <div class="container">
            <header class="navbar" id="top" role="banner">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-brand nav" id="brand">
                        <a href="index.php"><img src="assets/img/logo.png" alt="brand"></a>
                    </div>
                </div>
                <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php"><b>Mostrar Todo</b></a>
                        <li><a style="cursor: pointer;"><b>Norte</b></a>
                            <ul class="child-navigation">';
    $sql = "SELECT i.nombre,p.latitud,p.longitud
            FROM CDD_GIS_punto p,CDD_GIS_capa_item i,CDD_GIS_capa c
            WHERE p.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa and c.tipo=1 and c.idCapa=7 and padre=272
            order by i.nombre";
    
    foreach ($conexion->query($sql) as $fila) {
        $lat=$fila['latitud'];
        $lon=$fila['longitud'];
        $nombre=$fila['nombre'];
        $nombre_q="'".$fila['nombre']."'";
        $contenido.='
            <li><a style="cursor: pointer;" onclick="cambiarCentro('.$lat.','.$lon.')">'.$nombre.'</a></li>';
    }
    
    $contenido.='</ul>
                        </li>
                        <li><a style="cursor: pointer;"><b>Sur</b></a>
                        <ul class="child-navigation">';
    $sql = "SELECT i.nombre,p.latitud,p.longitud
            FROM CDD_GIS_punto p,CDD_GIS_capa_item i,CDD_GIS_capa c
            WHERE p.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa and c.tipo=1 and c.idCapa=7 and padre=273
            order by i.nombre";
    foreach ($conexion->query($sql) as $fila) {
        $lat=$fila['latitud'];
        $lon=$fila['longitud'];
        $nombre=$fila['nombre'];
        $nombre_q="'".$fila['nombre']."'";
        $contenido.='
            <li><a style="cursor: pointer;" onclick="cambiarCentro('.$lat.','.$lon.')">'.$nombre.'</a></li>';
    }              
    
    $contenido.='</ul>
                        </li>
                        <li><a style="cursor: pointer;"><b>Este</b></a>
                        <ul class="child-navigation">';
    $sql = "SELECT i.nombre,p.latitud,p.longitud
            FROM CDD_GIS_punto p,CDD_GIS_capa_item i,CDD_GIS_capa c
            WHERE p.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa and c.tipo=1 and c.idCapa=7 and padre=274
            order by i.nombre";
    foreach ($conexion->query($sql) as $fila) {
        $lat=$fila['latitud'];
        $lon=$fila['longitud'];
        $nombre=$fila['nombre'];
        $nombre_q="'".$fila['nombre']."'";
        $contenido.='
            <li><a style="cursor: pointer;" onclick="cambiarCentro('.$lat.','.$lon.')">'.$nombre.'</a></li>';
    } 
    
    $contenido.='</ul>
                        </li>
                        <li><a style="cursor: pointer;"><b>Oeste</b></a>
                        <ul class="child-navigation">';
    $sql = "SELECT i.nombre,p.latitud,p.longitud
            FROM CDD_GIS_punto p,CDD_GIS_capa_item i,CDD_GIS_capa c
            WHERE p.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa and c.tipo=1 and c.idCapa=7 and padre=275
            order by i.nombre";
    foreach ($conexion->query($sql) as $fila) {
        $lat=$fila['latitud'];
        $lon=$fila['longitud'];
        $nombre=$fila['nombre'];
        $nombre_q="'".$fila['nombre']."'";
        $contenido.='
            <li><a style="cursor: pointer;" onclick="cambiarCentro('.$lat.','.$lon.')">'.$nombre.'</a></li>';
    } 
    
    $contenido.='</ul>
                </li>
                </ul>
                </nav>
            </header>
        </div>
    </div>';
    echo $contenido;*/