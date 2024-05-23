<?php
$dirBase = "";
require_once $dirBase . "comun/conectarse.php";
//require_once $dirBase . "comun/clases/clase_encuesta.php";
$paso = ($_REQUEST['id_seccion'] ) ? $_REQUEST['id_seccion'] : 1;
$paso_siguiente = $paso + 1;

$id_user = $_REQUEST['id_user'];
$name_user = $_REQUEST['name_user'];
$id_tipo_encuesta = $_REQUEST['id_tipo_encuesta'];
$and_encuesta = "";
$ver = ($_REQUEST['ver'])?1:0;

if (isset($id_user)) {
    $bd = new baseDatos();
    $bd->Conectarse();
    $bdR = new baseDatos();
    $bdR->Conectarse();
    $bd1 = new baseDatos();
    $bd1->Conectarse();
    $base = new baseDatos();
    $base->Conectarse();

    $base->select("SELECT * FROM mds_encuesta_tipo WHERE id_tipo = $id_tipo_encuesta");
    $tipo_enc = $base->registro();

    if ($_REQUEST['id_encuesta'] != 0){ //va a editar una
        $bd->select("SELECT * FROM mds_encuesta WHERE id_encuesta = ".$_REQUEST['id_encuesta']); //SELECT * FROM mds_encuesta WHERE id_user = $id_user AND (completa = 0 OR completa IS NULL)
        if ($bd->numero_filas() > 0) {
            $encuesta = $bd->registro();
            $id_encuesta = $encuesta['id_encuesta'];
            $nueva = 0;
        } 
    }else { //tengo que crear una nueva encuesta
        $nueva = 1;
        $fecha = date('Y-m-d H:i:s');
        $base->select("INSERT INTO mds_encuesta (id_user,fecha_creacion,id_tipo_encuesta) VALUES ($id_user,'$fecha',$id_tipo_encuesta)");
        error_log("INSERT INTO mds_encuesta (id_user,fecha_creacion,id_tipo_encuesta) VALUES ($id_user,'$fecha',$id_tipo_encuesta)");
        $base->select("SELECT LAST_INSERT_ID()");
        $id = $base->registro();
        $id_encuesta = $id['LAST_INSERT_ID()'];
        syslog(LOG_NOTICE,"ID-ENCUESTA ".$id_encuesta);
        $base->select("SELECT * FROM mds_encuesta WHERE id_encuesta = $id_encuesta");
        $encuesta = $base->registro();
    }

   // echo $id_encuesta;
    ?>

                <!-- Content Wrapper. Contains page content -->


                    <!-- Main content -->
                    <!-- Main content -->
                    <!--<div class="modal fade" id="encuesta_modal" tabindex='-1' role="dialog" aria-labelledby="global-modal-title">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <br>
                                    <h4 class="modal-title" id="global-modal-title"></h4>
                                </div>
                                <div class="modal-body"></div>
                            </div>
                        </div>
                    </div> -->
                        <!-- TABLE: LATEST ORDERS -->
                        <div class="box box-info"><h4 class="box-title">Nuevo Registro</h4>
                            </div>
                            

                          <!--  <div class="box-header with-border">
                                <h3 class="box-title"><?php //echo $tipo_enc['nombre']; ?>&nbsp;<small><?php //echo $tipo_enc['descripcion']; ?></small></h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div> -->
                            <?php
                            if ($paso == 0) {
                                ?>
                                <div class="box-footer text-justify text-primary">
                                    Gracias por responder esta encuesta. 
                                </div>
                                <?php
                            } else {
                                if ($paso == 1 && $nueva == 1) { //muestro el texto inicial 
                                    echo '<div class="box-footer text-justify text-primary">'.$tipo_enc['texto_inicial'].'</div>';
                                }
                                ?>
                                <!-- /.box-header -->
                                <?php
                                //busco todas las secciones del tipo de encuesta

                                $bd->select("SELECT * FROM mds_encuesta_seccion WHERE baja_fecha IS NULL AND id_tipo_encuesta = $id_tipo_encuesta ORDER BY orden ASC");
                                if ($bd->numero_filas() > 0) {
                                    while ($seccion = $bd->registro()) {
                                        $secciones[] = $seccion;
                                    }
                                }
                                ?>
                                <!-- paginacion -->
                                <div class="box-body" id="content">
                                    <ul class="pagination pagination-md no-margin pull-left">
                                        <?php
                                        for ($i = 0; $i <= sizeof($secciones) - 1; $i++) {
                                            echo "<li><a href='#'>";
                                            if ($paso == $secciones[$i]['orden'])
                                                echo "<strong class='badge bg-light-blue'>" . $secciones[$i]['orden'] . "</strong>";
                                            else
                                                echo $secciones[$i]['orden'];
                                            echo "</a></li>";
                                        }
                                        ?>
                                    </ul>
                                    <div class="text-right"><strong class="text-danger text-right">* Obligatorio</strong></div>
                                </div>
                                <!-- fin paginacion -->
                                <div class="box-footer text-justify">
                                    <?php
                                    if ($paso_siguiente > sizeof($secciones))
                                        $paso_siguiente = -1;
                                    ?>
                                    <!-- dentro del arreglo tengo el nombre de la seccion y la explicacion -->
                                    <input type="hidden" name="paso_siguiente" value="<?php echo $paso_siguiente; ?>" />
                                    <h4 class="text-primary"><?php echo $secciones[$paso - 1]['seccion']; ?> <small><?php echo $secciones[$paso - 1]['explicacion']; ?><small></small></h4>
                                    <form id="form_<?php echo $secciones[$paso - 1]['id_seccion']; ?>" onsubmit="return guardarInfo('form_<?php echo $secciones[$paso - 1]['id_seccion']; ?>',<?php echo $paso_siguiente; ?>,<?php echo $id_user; ?>,<?php echo $id_encuesta; ?>);">
                                        <?php if ($ver){ ?><fieldset disabled="disabled"><?php } ?>
                                            <input type="hidden" name="id_user" value="<?php echo $id_user; ?>" />
                                            <input type="hidden" name="name_user" value="<?php echo $name_user; ?>" />
                                            <input type="hidden" name="id_tipo_encuesta" value="<?php echo $id_tipo_encuesta; ?>" />
                                            <input type="hidden" name="id_encuesta" value="<?php echo $id_encuesta; ?>" />
                                            <input type="hidden" name="id_seccion" value="<?php echo $secciones[$paso - 1]['id_seccion']; ?>" />
                                            
                                            <?php
                                            $base->select("SELECT * FROM mds_encuesta_pregunta WHERE id_seccion = " . $secciones[$paso - 1]['id_seccion'] . " AND id_tipo_encuesta = $id_tipo_encuesta AND baja_fecha IS NULL ORDER BY orden ASC");
                                            while ($pregunta = $base->registro()) {
                                                ?>
                                                <input type="hidden" id="id_pregunta_<?php echo $pregunta['id_pregunta']; ?>" value="<?php echo $pregunta['id_pregunta']; ?>" />
                                                <label class="label-lg"><?php echo $pregunta['pregunta']; ?> <?php
                                                    if ($pregunta['requerida'] == 1) {
                                                        echo '<strong class="text-danger">*</strong>';
                                                    };
                                                    ?></label>
                                                <div class="form-group">
                                                    <?php
                                                    $bd1->select("SELECT * FROM mds_encuesta_respuesta WHERE id_pregunta = " . $pregunta['id_pregunta'] . " AND baja_fecha IS NULL");
                                                    while ($respuesta = $bd1->registro()) {
                                                        $bdR->select("SELECT * FROM mds_encuesta_resultado WHERE id_pregunta = " . $pregunta['id_pregunta'] . " AND id_encuesta = $id_encuesta");                                                      
                                                        $resultado = [];
                                                        $resultado_v = [];
                                                        while ($result = $bdR->registro()) {
                                                            $resultado[] = $result;
                                                            $resultado_v[] = $result['valor'];
                                                        }
                                                        //print_r($resultado_v);
                                                        switch ($respuesta['tipo']) {
                                                            case 'checkbox':
                                                                ?> 
                                                                <div class="radio input-lg">
                                                                    <label>
                                                                        <input name="check_<?php echo $pregunta['id_pregunta']; ?>-<?php echo $pregunta['id_pregunta']; ?>-<?php echo $respuesta['id_respuesta']; ?>" id="<?php echo $respuesta['name'] . "_" . $respuesta['id_respuesta']; ?>" value="<?php echo $respuesta['respuesta']; ?>" type="checkbox" <?php if (in_array($respuesta['respuesta'], $resultado_v)) echo "checked"; ?> ><?php echo $respuesta['respuesta']; ?>
                                                                    </label>
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'radio':
                                                                ?> 
                                                                <div class="radio input-lg">
                                                                    <label>
                                                                        <input name="<?php echo $pregunta['id_pregunta']; ?>" id="<?php echo $respuesta['name'] . "_" . $respuesta['id_respuesta']; ?>" value="<?php echo $respuesta['respuesta']; ?>"  <?php if (in_array($respuesta['respuesta'], $resultado_v)) echo "checked"; ?>  type="radio" <?php if ($pregunta['requerida'] == 1) echo "required='required'"; ?>><?php echo $respuesta['respuesta'];
                                        echo ($resultado['valor'] != '') ? " - " . $resultado['valor'] : '';
                                                                ?>
                                                                    </label>
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'radio_otro': //radio con input
                                                                ?>
                                                                <div class="radio input-lg">
                                                                    <label>
                                                                        <input name="<?php echo $pregunta['id_pregunta']; ?>" id="<?php echo $respuesta['name'] . '_' . $respuesta['id_respuesta']; ?>" value="<?php echo $respuesta['respuesta']; ?>" type="radio"  <?php if ($pregunta['requerida'] == 1) echo "required='required'";  if ($resultado_v[0] != '') echo "checked"; ?> ><?php echo $respuesta['respuesta']; ?>
                                                                        <input type="text" name="otro_<?php echo $pregunta['id_pregunta']; ?>" value="<?php echo $resultado_v[0]; ?>" />
                                                                    </label>
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'radio_varios': //escala lineal
                                                                ?>
                                                                <div class="radio input-lg"><?php echo $respuesta['texto_previo_desde']; ?>&nbsp;&nbsp;
                                                                    <?php
                                                                    //print_r($resultado_v);
                                                                    for ($i = $respuesta['radio_desde']; $i <= $respuesta['radio_hasta']; $i++) {
                                                                        ?>
                                                                        <label>
                                                                            <input  <?php if ($pregunta['requerida'] == 1) echo "required='required'"; ?>  <?php if (sizeof($resultado_v) > 0 && $resultado_v[0] == $i) echo "checked"; ?>  name="<?php echo $pregunta['id_pregunta']; ?>" id="<?php echo $pregunta['id_pregunta'] . '_' . $i; ?>" value="<?php echo $i; ?>" type="radio"><?php echo $i; ?>
                                                                        </label>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    &nbsp;&nbsp;<?php echo $respuesta['texto_posterior_hasta']; ?>
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'radio_varios_imagen': //escala con una imagen 
                                                                ?>
                                                                <img src="images/<?php echo $respuesta['imagen']; ?>" />
                                                                <div class="radio input-lg"><?php echo $respuesta['texto_previo_desde']; ?>&nbsp;&nbsp;
                                                                    <?php
                                                                    for ($i = $respuesta['radio_desde']; $i <= $respuesta['radio_hasta']; $i++) {
                                                                        ?>
                                                                        <label>
                                                                            <input  <?php if ($resultado['valor'] == $respuesta['respuesta']) echo "checked"; ?>  <?php if ($pregunta['requerida'] == 1) echo "required='required'"; ?> name="<?php echo $pregunta['id_pregunta']; ?>" id="<?php echo $pregunta['id_pregunta'] . '_' . $i; ?>" value="<?php echo $i; ?>" type="radio"><?php echo $i; ?>
                                                                        </label>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    &nbsp;&nbsp;<?php echo $respuesta['texto_posterior_hasta']; ?>
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'input': //texto corto
                                                                ?>
                                                                <div class="form-group">
                                                                    <!--<label for="<?php //echo $pregunta['id_pregunta'];   ?>"><?php echo $respuesta['respuesta']; ?></label> -->
                                                                    <input  <?php if ($pregunta['requerida'] == 1) echo "required='required'"; ?> class="form-control" id="<?php echo $respuesta['id_pregunta']; ?>" name="<?php echo $pregunta['id_pregunta']; ?>"  type="text" value="<?php echo $resultado_v[0]; ?>">
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'textarea': //parrafo8
                                                                ?>
                                                                <div class="form-group">
                                                                    <!--<label><?php //echo $respuesta['respuesta'];   ?></label> -->
                                                                    <textarea  <?php if ($pregunta['requerida'] == 1) echo "required='required'"; ?> class="form-control" rows="3" id="<?php echo $pregunta['id_pregunta']; ?>" name="<?php echo $pregunta['id_pregunta']; ?>" ><?php echo $resultado_v[0]; ?></textarea>
                                                                </div>
                                                                <?php
                                                                break;
                                                        }
                                                    }
                                                }
                                                ?>
                                            </div>                                                
                                            <?php if ($ver){ ?></fieldset><?php } ?>
                                            <?php
                                            if (!$ver){
                                                if ($paso == sizeof($secciones)) {
                                                    ?>
                                                    <button class="btn btn-lg btn-info btn-flat pull-left" type="submit">Finalizar</button>
                                                    <input type="hidden" name="ultima_seccion" value="1" />
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button class="btn btn-lg btn-info btn-flat pull-left" type="submit" >Siguiente</button>

                                                    <?php
                                                }
                                            }else{
                                                 if ($paso == sizeof($secciones)) {
                                                    ?>
                                                    <button class="btn btn-lg btn-info btn-flat pull-left" id="finalizarVer">Finalizar</button>
                                                    <input type="hidden" name="ultima_seccion" value="1" />
                                                    <?php
                                                } else {
                                                    ?>
                                                    <button class="btn btn-lg btn-info btn-flat pull-left" id="siguienteVer" >Siguiente</button>

                                                    <?php
                                                }
                                            }
                                            ?>
                                    </form>
                                </div>
                                <!-- /.box-body -->
                                <!--<div class="box-footer clearfix">
                                    <button class="btn btn-lg btn-info btn-flat pull-left" id="encuesta">Comenzar Encuesta</button>
                                </div> -->
                                <!-- /.box-footer -->
                                <?php
                            }
                            ?>
                        </div>
                        <!-- /.box -->
                </div>
                <!-- /.row -->
        <!-- /.content-wrapper -->



        <div class="control-sidebar-bg"></div>

    </div>
    <!-- jQuery 2.2.3 -->
    <script src="comun/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="comun/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--<script src="js/dashboard2.js"></script> -->
    <!-- AdminLTE for demo purposes -->
    <script src="js/demo.js"></script>
    <!-- ./wrapper -->
    <script type="text/javascript">
            $('#encuesta_modal').on('hidden.bs.modal', function (e) {
                $(this).data('bs.modal', null);
            });
            $('#encuesta_modal').on('show.bs.modal', function (e) {
                //get data-id attribute of the clicked element
                //get data-id attribute of the clicked element
                var id = $(e.relatedTarget).data('id');

                //populate the textbox
                $(e.currentTarget).find('input[name="id"]').val(id);
                var title = $(e.relatedTarget).data('title');
                $(e.currentTarget).find('h4[id="global-modal-title"]').html(title);
            });
            $("#siguienteVer").click(function(){                
                $.ajax({
                    url: 'encuesta.php?id_user=<?php echo $id_user; ?>&name_user=' + '<?php echo $name_user; ?>' + '&id_encuesta=' + <?php echo $id_encuesta; ?> + '&id_tipo_encuesta=' + '<?php echo $id_tipo_encuesta; ?>' + '&ver=1&id_seccion=<?php echo $paso_siguiente; ?>',
                    type: 'GET',
                    success: function (data) {
                        $('#content').html(data);
                    }
                });
            });
            function guardarInfo(form, paso, id_user, id_encuesta) {
                //alert($('#'+form).serialize());
                $.ajax({
                    method: "POST",
                    url: 'guardarResultadoEncuesta.php',
                    dataType: 'json',
                    data: $('#' + form).serialize(),
                })
                        .done(function (event) {
                            if (event.result == 'ok') {
                                    if (paso != -1){
                                        $.ajax({
                                            url: 'encuesta.php?id_user=<?php echo $id_user; ?>&name_user=' + '<?php echo $name_user; ?>' + '&id_encuesta=' + id_encuesta + '&id_tipo_encuesta=' + '<?php echo $id_tipo_encuesta; ?>' + '&id_seccion=' + paso,
                                            type: 'GET',
                                            success: function (data) {
                                                $('#content').html(data);
                                            }
                                        });
                                    }
                                else {
                                    <?php if (!$ver){ ?>
                                    alert('Muchas Gracias por contestar la encuesta');
                                    <?php } ?>
                                    window.open('mis_encuestas_busqueda.php?id_user=<?php echo $id_user; ?>&name_user=' + '<?php echo $name_user; ?>&id_tipo_encuesta=<?php echo $id_tipo_encuesta; ?>', '_self');
                                }
                            } else {
                                alert('No se ha podido almacenar la información')
                            }
                        });
                return false;
            }
    </script>
    <?php
}
?>
