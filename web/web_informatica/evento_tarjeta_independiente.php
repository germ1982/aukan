<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tarjeta con Carrusel</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 0 10px #ccc;
        }

        .carousel-inner img {
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>

<body>


<?php
$imageNames = explode(',', $fotos);
$carouselId = "carousel_" . $contador;
?>

<div class="card mb-4">
    <div class="row" style="width: 92%; margin: 0 auto;">
        <div class="col-md-4">
            <div id="<?= $carouselId ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($imageNames as $index => $img): ?>
                        <button type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide-to="<?= $index ?>" class="<?= $index == 0 ? 'active' : '' ?>"></button>
                    <?php endforeach; ?>
                </div>

                <div class="carousel-inner">
                    <?php foreach ($imageNames as $index => $imageName): ?>
                        <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                            <img src="../img/evento-fotos/<?= trim($imageName) ?>" class="d-block w-100" alt="Foto <?= $index + 1 ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><?= $titulo ?></h5>
                <p class="card-text"><?= $descripcion ?></p>
                <a href="#" class="btn btn-primary">Ver más</a>
            </div>
        </div>
    </div>
</div>


</body>

</html>