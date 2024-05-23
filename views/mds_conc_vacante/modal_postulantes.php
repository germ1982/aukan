<?php

use yii\helpers\Url;
use yii\helpers\Html;
?>

<style>
    .exportBTN:link {
        color: white;
        background-color: transparent;
        text-decoration: none;
    }

    .exportBTN:visited {
        color: white;
        background-color: transparent;
        text-decoration: none;
    }
</style>

<?php if ($model) { ?>
    <div class="row">
        <div class="col-md-12">
            <h4>Cantidad total: <b> <?= count($model); ?></b></h4>
        </div>
    </div>
    <table BORDER style="width:100% ;text-align: center">
        <tr>
            <td><b>#</b></td>
            <td><b>Apellido</b></td>
            <td><b>Nombre</b></td>
            <td><b>DNI</b></td>
            <td><b>Fecha</b></td>
        </tr>
        <?php foreach ($model as $postulacion) {  ?>

            <?php
            $date = date_create($postulacion['created_at']);
            $date = date_format($date, 'd/m/Y H:i');
            ?>

            <tr>
                <td><?= $postulacion['idpostulacion']; ?></td>
                <td><?= isset($postulacion['apellido']) ? strtoupper($postulacion['apellido']) : "" ?></td>
                <td><?= isset($postulacion['nombre']) ? strtoupper($postulacion['nombre']) : "" ?></td>
                <td><?= isset($postulacion['documento']) ? $postulacion['documento'] : "" ?></td>
                <td><?= $date; ?></td>
            <?php } ?>
            </tr>
    </table>
<?php } else { ?>
    No existen postulaciones en esta vacante.
<?php } ?>