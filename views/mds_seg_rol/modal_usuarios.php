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
        <div class="col-md-6">
            <h4>Cantidad total: <?= count($model); ?></h4>
        </div>
        <div class="col-md-6">
            <button type="button" class='btn btn-primary pull-right'>
                <?php $url =  Url::to(['/mds_seg_rol/reporte_usuarios', 'idrol' => $idrol]); ?>
                <a href="<?= $url ?>" target="_blank" title="Exportar PDF" class="exportBTN">Exportar PDF</a>
            </button>
        </div>

    </div>
    <TABLE BORDER style="width:100% ;text-align: center">
        <TR>
            <TD><b>#</b></TD>
            <TD><b>Apellido y Nombre</b></TD>
            <TD><b>Usuario SUR</b></TD>
            <TD><b>Organismo</b></TD>
            <TD><b>Dispositivo</b></TD>
        </TR>
        <?php foreach ($model as $usuario) {  ?>
            <TR>
                <TD><?= $usuario['idusuario']; ?></TD>
                <TD><?= strtoupper($usuario['apellido']) ?>, <?= strtoupper($usuario['nombre']) ?></TD>
                <TD><?= $usuario['user']; ?></TD>
                <TD><?= $usuario['organismo']; ?></TD>
                <TD><?= $usuario['dispositivo']; ?></TD>
            <?php } ?>
            </TR>
    </TABLE>
<?php } else { ?>
    No existen usuarios con el rol seleccionado.
<?php } ?>