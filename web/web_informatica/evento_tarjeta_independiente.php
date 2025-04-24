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

        .titulo_evento:hover {
            color: #5f913d;
        }
    </style>
    <style>
        /* Fondo oscuro */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9) !important;
            /* <- más oscuro */
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            /* Inicialmente transparente */
            transition: opacity 5s ease;
            /* Transición suave */
        }

        /* Contenido del modal */
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            width: 90%;
            max-height: 90%;
            overflow-y: auto;
            /* box-shadow: 0 0 30px rgba(0, 0, 0, 0.5); */
        }

        .modal-backdrop.show {
            display: flex !important;
            opacity: 1;  /* Cuando se muestra, es completamente opaco */
        }

        .close-btn {
            float: right;
            font-size: 1.5em;
            cursor: pointer;
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
            <div class="row" style="width: 100%; margin: 0 auto;">
                <div class="col-md-12">
                    <div class="card-body">
                        <h5 class="tipo_evento"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEvento<?= $contador ?>"
                            style="cursor: pointer;">
                            <?= "$fecha $tipo_evento" ?>
                        </h5>

                        <br>

                        <h4 class="titulo_evento"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEvento<?= $contador ?>"
                            style="cursor: pointer;">
                            <?= $titulo ?>
                        </h4>

                        <button class="openModalBtn" data-modal="#customModal<?= $contador ?>">Abrir Modal</button>

                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Modal -->

<div id="customModal<?= $contador ?>" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="modalTitle">

    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <span class="close-btn float-end" id="closeModalBtn<?= $contador ?>">&times;</span>
        <h2 id="modalTitle">Título del Modal</h2>

        <div id="<?= $carouselId ?>_modal" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($imageNames as $index => $img): ?>
                    <button type="button" data-bs-target="#<?= $carouselId ?>_modal" data-bs-slide-to="<?= $index ?>" class="<?= $index == 0 ? 'active' : '' ?>"></button>
                <?php endforeach; ?>
            </div>

            <div class="carousel-inner">
                <?php foreach ($imageNames as $index => $imageName): ?>
                    <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                        <img src="../img/evento-fotos/<?= trim($imageName) ?>" class="d-block w-100" alt="Foto <?= $index + 1 ?>">
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>_modal" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>_modal" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <p>Este es un modal sin Bootstrap.</p>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const openBtns = document.querySelectorAll('.openModalBtn');

        openBtns.forEach(btn => {
            const modalId = btn.dataset.modal;
            const modal = document.querySelector(modalId);
            const closeBtn = modal.querySelector('.close-btn');

            btn.addEventListener('click', () => {
                modal.classList.add('show');
            });

            closeBtn.addEventListener('click', () => {
                modal.classList.remove('show');
            });

            modal.addEventListener('click', e => {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });
        });

        // Manejar Escape de forma global
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-backdrop.show').forEach(modal => {
                    modal.classList.remove('show');
                });
            }
        });
    });
</script>




</body>

</html>