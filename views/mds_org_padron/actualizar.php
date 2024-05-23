<div class="row">
    <div class='col-md-12'>
        <div id="resultado" style="height:300px;overflow: auto;">
            <i class="fas fa-info-circle"></i> Presione "Actualizar Datos" para iniciar el proceso.
        </div>
    </div>
</div>
<?php

$script = <<<  JS

    $(document).ready(function() {
        $("#btn_actualizar").click(function() {
            $("#btn_actualizar").prop('disabled',true);
            var parametros = {
                //"r": "mds_org_contacto/get_id_contacto_por_legajo",
                "procesar": 1
            };
            $("#resultado").html("Guardando datos... <i class=\"fas fa-spinner fa-pulse\"></i>");
            $.ajax({
                data: parametros, //datos que se envian a traves de ajax
                url: "index.php?r=mds_org_padron/actualizar", //php que recibe la peticion
                type: 'get',
                success: function(response) {
                    $("#btn_actualizar").prop('disabled',false);
                    $("#resultado").html($("#resultado").html().replace("<i class=\"fas fa-spinner fa-pulse\"></i>",""));
                    if (response.guardados > 0) {
                        $("#resultado").html($("#resultado").html() +"<div class='text-success'>" + (response.guardados) + " contactos actualizados correctamente.</div>");
                        if (response.pendientes.length>0){
                            $("#resultado").html($("#resultado").html() + "<div class='text-warning'>" + (response.pendientes.length) + " registros sin contacto creado:</div>");
                            response.pendientes.forEach(function(pend) {
                                $("#resultado").html($("#resultado").html() + "<div class='text-warning'>" + ("DNI: "+pend.dni+" | "+pend.apellido_nombre) + "</div>");
                            });
                        }
                    }
                    if (response.errores.length > 0) {
                        $("#resultado").html($("#resultado").html() +"<br>" + response.errores.length 
                        + " registros fallaron. Imprimiendo errores...");
                        response.errores.forEach(function(error) {
                            if (Array.isArray(error)) {
                                error.forEach(function(suberror) {
                                    if (Array.isArray(suberror)) {
                                        suberror.forEach(function(subsuberror) {
                                            $("#resultado").html($("#resultado").html() + "<div class=\"text-danger\">3- " + subsuberror + "</div>");
                                        });
                                    } else {
                                        $("#resultado").html($("#resultado").html() + "<div class=\"text-danger\">2- " + suberror + "</div>");
                                    }
                                });
                            } else {
                                if (error.legajo) {
                                    $("#resultado").html($("#resultado").html() + "<div class=\"text-danger\">1- " + JSON.stringify(error.legajo) + "</div>");
                                } else {
                                    $("#resultado").html($("#resultado").html() + "<div class=\"text-danger\">1- " + JSON.stringify(error) + "</div>");
                                }
                            }
                        });
                    }
                    window.scrollTo(0,300);
                    generarContactos();
                }
            });
        });
    });

    function generarContactos(){
        $("#resultado").html($("#resultado").html() +"<div>Generando contactos faltantes... <i class=\"fas fa-spinner fa-pulse\"></i></div>");
        $("#btn_actualizar").prop('disabled',true);
        $.ajax({
            data: [], //datos que se envian a traves de ajax
            url: "index.php?r=mds_org_padron/generar_contactos", //php que recibe la peticion
            type: 'get',
            success: function(response) {
                $("#btn_actualizar").prop('disabled',false);
                $("#resultado").html($("#resultado").html().replace("<i class=\"fas fa-spinner fa-pulse\"></i>",""));
                if (response.guardados > 0) {
                    $("#resultado").html($("#resultado").html() +"<div class='text-success'>" + (response.generados.length) + " contactos generados correctamente.</div>");
                    if (response.pendientes.length>0){
                        $("#resultado").html($("#resultado").html() + "<div class='text-warning'>" + (response.sin_generar.length) + " registros sin generar:</div>");
                        response.pendientes.forEach(function(pend) {
                            $("#resultado").html($("#resultado").html() + "<div class='text-warning'>" + ("DNI: "+pend.dni+" | "+pend.apellido_nombre) + "</div>");
                        });
                    }
                }
                else {
                    $("#resultado").html($("#resultado").html() +"<div class='text-success'>No hay contactos que necesiten ser generados.</div>");
                }
                if (response.errores.length > 0) {
                    $("#resultado").html($("#resultado").html() +"<br>" + response.errores.length 
                    + " intentos de guardado fallaron. Imprimiendo errores...");
                    response.errores.forEach(function(error) {
                        if (Array.isArray(error)) {
                            error.forEach(function(suberror) {
                                if (Array.isArray(suberror)) {
                                    suberror.forEach(function(subsuberror) {
                                        $("#resultado").html($("#resultado").html() + "<div class=\"text-danger\">3- " + subsuberror + "</div>");
                                    });
                                } else {
                                    $("#resultado").html($("#resultado").html() + "<div class=\"text-danger\">2- " + suberror + "</div>");
                                }
                            });
                        } else {
                            $("#resultado").html($("#resultado").html() + "<div class=\"text-danger\">1- " + JSON.stringify(error) + "</div>");                            
                        }
                    });
                }
                $("#resultado").html($("#resultado").html() +"<br><div class=\"text-success\">Proceso Finalizado!</div>");
                window.scrollTo(0,300);
            }
        });
    }
JS;

$this->registerJs($script);
