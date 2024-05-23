<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img src="img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
        <div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
            <h4 style="margin: 0; font-weight: bold;">Reporte Personas Vinculadas</h4>
            <p><span> </span></p>
            <hr style="margin: 0 0 10px 0">
        </div>

        <?php foreach ($oficios as $index => $oficio) : ?>
            <table>
                <?php if ($index == 0 || ($index > 0 && $oficio->caratula != $oficios[$index - 1]['caratula'])) : ?>
                    <tr style="background-color: #dddddd;">
                        <th class="titulo" valign="top" colspan="4">
                            <h5>Caratula: "<?= $oficio->caratula ?>" </h5>
                        </th>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td valign="top" colspan="2"><b>Requerimiento: </b><span><?= $oficio->idlegalesoficio ?></span></td>
                    <td valign="top" colspan="2"><b>idlegalescaratula: </b><span><?= $oficio->idlegalescaratula ?></span></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2"><b>Expediente: </b><span><?= $oficio->numero_expediente ?></span></td>
                    <td valign="top" colspan="2"><b>Caso: </b><span><?= $oficio->caso ?></span></td>
                </tr>
                <tr>
                    <td valign="top" colspan="2"><b>Año: </b><span><?= $oficio->anio_expediente ?></span></td>
                    <td valign="top" colspan="2"><b>Tiene personas vinculadas: </b><span><?= $oficio->personasVinculadas && count($oficio->personasVinculadas) > 0 ? 'Si' : 'No' ?></span></td>
                </tr>
            </table>
            <hr style="margin: 10px 0 10px 0">
        <?php endforeach; ?>
    </div>
</body>

</html>