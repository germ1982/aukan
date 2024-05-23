<?php

use yii\helpers\Url;
?>

<style>
    .titulos-container {
        display: flex;
        flex-direction: column;
        border-right: 1px solid #999;
    }

    .content-div {
        padding-left: 3rem;
    }

    .btn-notificaciones {
        margin: 5px 0 5px 0;
        font-size: 16px;
    }

    .titulo {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 15px 0 15px 0;
    }

    @media only screen and (max-width: 991px) {
        .titulos-container {
            border-right: 0;
        }

        .content-div {
            text-align: center;
        }
    }

    @media only screen and (min-width: 992px) {
        .notificaciones-container {
            display: flex;
            align-items: stretch;
        }
    }
</style>

<header class="page-header">
    <h2>Notificaciones</h2>

    <div class="right-wrapper pull-right">
        s
        <ol class="breadcrumbs">
            <li>
                <a href="index.php">
                    <i class="fa fa-home"></i>
                </a>
            </li>
            <li><span>Notificaciones</span></li>
        </ol>

        <div class="sidebar-right-toggle"></div>
    </div>
</header>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <section class="panel">
            <div class="panel-body">
                <div class="row notificaciones-container">
                    <div class="col-md-3 col-sm-12 titulos-container">
                        <?php foreach ($dataNotificaciones as $modulo => $data) : ?>
                            <button class="btn btn-primary btn-notificaciones" onClick="cambiarVista('<?= $modulo ?>')"><?= $data['titulo'] ?></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-md-9 col-sm-12 content-div" id="contentDiv">
                        <p class="titulo">Seleccione un módulo.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php $this->registerJs("
    $(document).ready(function() {
        const urlParams = new URLSearchParams(window.location.search);
        const moduloParam = urlParams.get('modulo');

        if (moduloParam) {
            cambiarVista(moduloParam);
        }
    });
    "); ?>

<script>
    function cambiarVista(moduloSeleccionado) {
        $('#loading').show();
        $.ajax({
            url: `<?= Url::to(['/mds_notificacion/rendervista']) ?>&modulo=${moduloSeleccionado}`, // Ruta al controlador y acción
            type: "GET",
            success: function(response) {
                $("#contentDiv").html(response); // Inserta el contenido en el div
            },
            error: function(xhr, status, error) {
                console.error(error);
            },
            complete: function() {
                $('#loading').hide();
            }
        });
    }
</script>