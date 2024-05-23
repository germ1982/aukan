<?php 
    $ruta=$_SERVER['DOCUMENT_ROOT'];
    if (file_exists($ruta.'/gis')){
        $ruta=$ruta.'/gis';
    }
    $contenido='<div class="col-md-9 col-sm-10">
                    <section id="my-properties">
                        <header><h1>Ubicaciones      
                        <a href="ubicacion_alta.php" class="btn btn-default"><i class="fa fa-plus"></i><span class="text"></span></a>
                    </header></h1>  
                        <div class="my-properties">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Ubicación</th>
                                        <th>Latitud</th>
                                        <th>Longitud</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>';
    include $ruta.'/includes/bd.php';
    $sql="SELECT c.Icono, c.idCapa, i.idCapaItem, i.Descripcion Descripcion, i.Nombre,p.Latitud,p.Longitud, c.Descripcion Capa, i.Padre
          FROM CDD_GIS_Punto p,CDD_GIS_Capa_Item i,CDD_GIS_Capa c
          WHERE p.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa and c.idCapa between 13 and 27
          order by i.idCapaItem;";
        /*union all
          SELECT c.Icono, c.idCapa, i.idCapaItem, i2.Nombre Descripcion, i.Nombre,p.Latitud,p.Longitud, c.Descripcion Capa, i.Padre
          FROM CDD_GIS_Punto p,CDD_GIS_Capa_Item i,CDD_GIS_Capa c, CDD_GIS_Capa_Item i2
          WHERE p.idCapaItem=i.idCapaItem and i.idCapa=c.idCapa and c.idCapa=8 and i.Padre=i2.idCapaItem;";*/
    foreach ($conexion->query($sql) as $fila) {
        $idCapaItem=$fila['idCapaItem'];
        $idCapa=$fila['idCapa'];
        $descripcion=$fila['Descripcion'];
        $lat=$fila['Latitud'];
        $lon=$fila['Longitud'];
        $nombre=$fila['Nombre'];
        $icono=$fila['Icono'];
        $capa=$fila['Capa'];
        $padre=$fila['Padre'];
        $contenido.='
            <tr>
                <td><div class="inner">
                    <a href="property-detail.html"><h2>'.$idCapaItem.'- '.$nombre.'</h2></a>
                    <figure>'.$descripcion.'</figure>
                    <div class="tag price">'.$capa.'</div>
                </div>
                </td>
                <td>'.$lat.'</td>
                <td>'.$lon.'</td>
                <td class="actions">
                    <a href="ubicacion_editar.php?idCapaItem='.$idCapaItem.
                                      '&idCapa='.$idCapa.
                                      '&capa='.$capa.
                                      '&nombre='.$nombre.
                                      '&padre='.$padre.
                                      '&descripcion='.$descripcion.
                                      '&lat='.$lat.
                                      '&lon='.$lon.
                                      '&icono='.$icono.'" class="edit"><i class="fa fa-pencil"></i>Modificar</a>
                    <a href="#"><i class="delete fa fa-trash-o"></i></a>
                </td>
            </tr>';
    }
    $contenido.='
                        </tbody>
                    </table>
                </div><!-- /.table-responsive -->
                <!-- Pagination
                <div class="center">
                    <ul class="pagination">
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                    </ul>
                </div> /.center-->
            </div><!-- /.my-properties -->
        </section><!-- /#my-properties -->
    </div>';
    echo $contenido;
?>