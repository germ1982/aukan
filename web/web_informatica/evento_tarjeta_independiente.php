<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Web Informatica</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <style>
        .card {
            /* border-radius: 15px; */
            box-shadow: 0 0 10px #ccc;
            height: 300px;
            margin: 5px;


        }

        .card:hover {
            transform: scale(1.02);
        }

        .carousel-inner img {
            height: 175px;
            object-fit: cover;
            border-radius: 1px;
        }

        .marco-foto {

            /* border-radius: 20px; */
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);

        }

        .marco-foto:hover {

            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
        }

        .neon-border:hover {

            display: block;
            box-shadow: 0 0 5px #87B867, 0 0 5px #87B867, 0 0 20px #87B867, 0 0 10px #87B867, 0 0 10px #87B867;
        }

        .tipo_evento {
            color: #5f913d;
            text-decoration: underline;
        }

        .titulo_evento {
            text-decoration: none !important;  /* Quita el subrayado */
        color: grey !important;         /* Hereda el color del texto o usa un color específico */
        border: none !important;       /* Hereda el color del texto, o puedes definir un color específico si prefieres */
        }

        .titulo_evento:hover {
            color: #5f913d;
        }
    </style>

</head>

<body>


    <?php
    $imageNames = explode(',', $fotos);
    $carouselId = "carousel_" . $contador;
    ?>
    <div class="col-md-3 mb-3">
        <div class="card neon-border mb-3">
        
            <div class="row">


                <div id="<?= $carouselId ?>" class="carousel slide" data-bs-ride="carousel">

                    <div class="carousel-indicators">
                        <?php foreach ($imageNames as $index => $img): ?>
                            <button type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide-to="<?= $index ?>" class="<?= $index == 0 ? 'active' : '' ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <a href="javascript:void(0)" onclick='abrirModalEvento(
                                                    <?= json_encode($titulo) ?>, 
                                                    <?= json_encode($descripcion) ?>, 
                                                    <?= json_encode($fotos) ?>,
                                                    <?= json_encode($tipo_evento) ?>,
                                                    <?= json_encode($fecha) ?>
                                                )' >
                        <div class="carousel-inner">
                            <?php foreach ($imageNames as $index => $imageName): ?>
                                <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                                    <img src="../img/evento-fotos/<?= trim($imageName) ?>" class="d-block w-100" alt="Foto <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </a>
                    <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>

            </div>
            
            <div class="row" style="width: 100%; margin: 0 auto;">
                <div class="col-md-12">
                    <div class="card-body">


                        <a href="javascript:void(0)"
                            onclick='abrirModalEvento(
                                                    <?= json_encode($titulo) ?>, 
                                                    <?= json_encode($descripcion) ?>, 
                                                    <?= json_encode($fotos) ?>,
                                                    <?= json_encode($tipo_evento) ?>,
                                                    <?= json_encode($fecha) ?>
                                                )'>
                            <h5 class="tipo_evento"
                                style="cursor: pointer;">
                                <?= "$fecha $tipo_evento" ?>
                            </h5>
                        </a>
                            <br>
                        <a href="javascript:void(0)" onclick='abrirModalEvento(
                                                    <?= json_encode($titulo) ?>, 
                                                    <?= json_encode($descripcion) ?>, 
                                                    <?= json_encode($fotos) ?>,
                                                    <?= json_encode($tipo_evento) ?>,
                                                    <?= json_encode($fecha) ?>
                                                )' class="titulo_evento">
                            <h4 
                                style="cursor: pointer;">
                                <?= $titulo ?>
                            </h4>
                        </a>



                    </div>
                </div>
            </div>
        </div>
    </div>






</body>

</html>