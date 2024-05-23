<style>
    .titulo-dashboard {
        padding-left: 15px;
        margin: 0 0 20px 0;
        text-decoration: underline;
    }

    .subtitulo-dashboard {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 15px 0 15px 0;
    }

    .a-dashboard {
        font-size: 40px;
        font-weight: bold;
    }

    @media only screen and (max-width: 991px) {
        .subtitulo-dashboard {
            font-size: 1.5rem;
            margin: 10px 0 10px 0;
        }

        .a-dashboard {
            font-size: 30px;
        }
    }
</style>

<div class="row">
    <?php if (!empty($notificaciones)) : ?>
        <div class="col-md-12">
            <p class="subtitulo-dashboard">Total</p>
            <p class="a-dashboard"><?= $total ?></p>
        </div>
        <?php foreach ($notificaciones as $key => $notificacion) :
            if (!empty($notificacion['notificaciones'])) :
                $url = "index.php?r=mds_legales_oficio%2Findex&notificacion=$key";
                if ($key === "oficiosRespuestasAprobadasNoEnviadas") {
                    $url = "index.php?r=mds_legales_oficio%2Fvinculacionenviar";
                }
        ?>
                <div class="col-md-6 col-sm-12">
                    <p class="subtitulo-dashboard"><?= $notificacion['titulo'] ?></p>
                    <a href="<?= $url ?>" target="_blank" class="a-dashboard"><?= count($notificacion['notificaciones']); ?></a>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="subtitulo-dashboard">No posee notificaciones.</p>
    <?php endif; ?>
</div>