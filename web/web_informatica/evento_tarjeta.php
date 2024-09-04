<?php

$imageNames = explode(',', $fotos); // Convertir el string de fotos a un array
$items = [];

foreach ($imageNames as $imageName) {
      $items[] = [
            'content' => '<img src="../img/evento-fotos/' . $imageName . '" class="d-block w-100">',
            //'caption' => '<h5>' . $imageName . '</h5>',
      ];
}
?>
<style>
      img {
            max-width: 100%;
            /* height: ; */
            display: block;
            margin: 0 auto;
            border-radius: 5%;
      }

      .titulo {
            background-color: #87B867;
            color: #2B3E4C;
            font-size: 25px;
            padding-left: 15px;
      }


      .descripcion {
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
            color: #333;
            text-align: justify;
            line-height: 1.6;
            margin: 20px;
      }

      .neon-border {

            display: block;
            margin: 0 auto;
            border-radius: 5%;

            position: relative;
            box-shadow: 0 0 5px #87B867, 0 0 5px #87B867, 0 0 20px #87B867, 0 0 10px #87B867, 0 0 10px #87B867;
      }

      .gray-border {

            width: 80%;
            margin: 0 auto;
            border-radius: 5%;
            border: 0.5px solid rgb(204, 204, 204);
            /* Gray border with 90% opacity */
      }
      .carousel-control-prev, .carousel-control-next {
    width: 5% !important;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 100% !important;
    height: 100% !important;
    filter: invert(1) !important; /* Si deseas invertir el color de los íconos */
}

.carousel-item img {
    width: 100% !important;
    height: auto !important;
    max-height: 500px !important; /* Ajusta esta altura según sea necesario */
    object-fit: cover !important; /* Ajusta cómo se escalan las imágenes */
}

.carousel-control-prev, .carousel-control-next {
    top: 50% !important;
    transform: translateY(-50%) !important;
}


</style>

<div class="row" style="width: 90%; margin: 0 auto;" ;>

      <div class="col-md-6">

            <div id="eventoCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
                  <div class="carousel-inner">
                        <?php foreach ($items as $index => $item): ?>
                              <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <?= $item['content'] ?>
                              </div>
                        <?php endforeach; ?>
                  </div>
                  <a class="carousel-control-prev" href="#eventoCarousel" role="button" data-slide="prev">
                        <span class="fas fa-angle-left" style="font-size:36px" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                  </a>
                  <a class="carousel-control-next" href="#eventoCarousel" role="button" data-slide="next">
                        <span class="fas fa-angle-right" style="font-size:36px" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                  </a>
            </div>


      </div>

      <div class="col-md-6">

            <div class="titulo"><?= $titulo ?> </div>

            <div class="descripcion"><?= $descripcion ?></div>

      </div>
</div><br>
<div class="gray-border"></div>
<br><br>