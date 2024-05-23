<div class="row">
    <div class="col-md-12">
        <section class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">Detalle:</h2>
            </header>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if ($model->id != null) :
                        ?>
                            <div class="col-md-1">
                                <b>ID del Registro</b>
                            </div>
                            <div class="col-md-1">
                                <?= $model->id ?>
                            </div>
                        <?php
                        endif;
                        ?>
                        <?php
                        $datos = json_decode($model->datos);
                        if (!empty($datos)) :
                        ?>
                            <div class="col-md-1">
                                <b>Datos</b>
                            </div>
                            <div class="col-md-9">
                                <?php
                                $datos = is_array($datos) ? $datos : get_object_vars($datos);
                                $parametros = array_keys($datos);
                                foreach ($parametros as $param) {
                                    $datos_param = $datos[$param];
                                    if (is_array($datos_param)) {
                                        $datos_param = implode(', ', $datos_param);
                                    }
                                    echo "<div class=\"col-md-4\"><b>" . $param . ": </b>" . $datos_param . "</div>";
                                }
                                ?>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>