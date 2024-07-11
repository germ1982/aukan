        <style>
            .text_titulo {
                font-size: 18px;
                color: #87b867;
            }

            .text_descripcion {
                color: #87b867;
            }

            .panel_contenido {
                background-color: aliceblue;



                text-align: center;

            }

            .contenedor {
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: 5px;
            padding-top: 5px;
            width: 100%; /* Ajusta el ancho del contenedor */
            max-width: 600px; /* Tamaño máximo del contenedor */

        }
            .contenedor iframe {
                width: 100%;
                /* Hace que el GIF se ajuste al ancho del contenedor */
                height: auto;
                /* Mantiene la proporción del GIF */
                display: block;
                /* Elimina el espacio inferior del GIF */
            }

            .contenedor img {
                width: 100%;
                /* Hace que el GIF se ajuste al ancho del contenedor */
                height: auto;
                /* Mantiene la proporción del GIF */
                display: block;
                /* Elimina el espacio inferior del GIF */
                border-radius: 5px;
            }
        </style>
        <section class="panel panel-featured-left panel-featured-primary">
            <header class="panel-heading ">
                <div class="panel-actions">
                    <!-- onclick="javascript:editarPerfil();" -->
                </div>
                <h2 class="panel-title text-center neon" style="color: #f4dfb9;"><?= $titulo ?></h2>
            </header>
            <div class="panel_contenido">
                <div class="row justify-content-between">
                    <div class="contenedor">
                        <?php include "$archivo_contenido_tarjeta" ?>
                    </div>
                    
                </div>
            </div>
        </section>