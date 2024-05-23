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
                <?php if ($index == 0 || ($index > 0 && $oficio->caratula != $oficios[$index - 1]['caratula'])) : 
                    $oficiosConPersonasVinculadas = "";
                    $oficiosSinPersonasVinculadas = "";
                    ?>
                    <tr style="background-color: #dddddd;">
                        <th class="titulo" valign="top" colspan="4">
                            <h5>Caratula: "<?= $oficio->caratula ?>" </h5>
                        </th>
                    </tr>
                <?php endif; ?>
                <?php 
                if ($oficio->personasVinculadas && count($oficio->personasVinculadas) > 0) {
                    $oficiosConPersonasVinculadas .= "{$oficio->idlegalesoficio}, ";
                } else {
                    $oficiosSinPersonasVinculadas .= "{$oficio->idlegalesoficio}, ";
                }

                if ($index == count($oficios)-1 || ($index < count($oficios)-1 && $oficio->caratula != $oficios[$index + 1]['caratula'])) :
                ?>
                <tr>
                    <td valign="top" colspan="2"><b>Con personas vinculadas:</b><span><?= $oficiosConPersonasVinculadas ?></span></td>
                    <td valign="top" colspan="2"><b>Sin personas vinculadas:</b><span><?= $oficiosSinPersonasVinculadas ?></span></td>
                </tr>
                <?php endif; ?>
            </table>
            <?php if ($index == count($oficios)-1 || ($index < count($oficios)-1 && $oficio->caratula != $oficios[$index + 1]['caratula'])) :
            ?>
            <hr style="margin: 10px 0 10px 0">
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</body>

</html>