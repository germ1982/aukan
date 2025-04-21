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
    $imageNames = explode(',', $fotos); // Convertir el string de fotos a un array
    $items = [];
    $cont_carrusel = 1;


    ?>

    <div class="container py-4">
        <div class="card">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
                </div>

                <div class="carousel-inner">


                    <?php
                    echo ""
                    foreach ($imageNames as $imageName) {
                        $items[] = [
                            'content' => '<img src="../img/evento-fotos/' . $imageName . '" class="d-block w-100">',
                            'route' => 'src="../img/evento-fotos/' . $imageName . '"',
                            'caption' => $imageName,
                            'index' => $cont_carrusel,
                        ];
                        $cont_carrusel++;
                    }
                    ?>
                    <div class="carousel-item active">
                        <img src="img/foto1.jpg" class="d-block w-100" alt="Foto 1">
                    </div>
                    <div class="carousel-item">
                        <img src="img/foto2.jpg" class="d-block w-100" alt="Foto 2">
                    </div>
                    <div class="carousel-item">
                        <img src="img/foto3.jpg" class="d-block w-100" alt="Foto 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>

            <div class="card-body">
                <h5 class="card-title">Título de la Tarjeta</h5>
                <p class="card-text">Este es un resumen del contenido o descripción que acompaña la tarjeta. Puede incluir info del modelo o cualquier dato.</p>
                <a href="#" class="btn btn-primary">Ver más</a>
            </div>
        </div>
    </div>

</body>

</html>