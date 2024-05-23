<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img src="https://desasur.neuquen.gov.ar/familia/web/img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
        <div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
            <h4 style="margin: 0; font-weight: bold;">REPORTE VACANTE</h4>
            <p><span> Concurso Vacante </span></p>
            <hr style="margin: 0 0 10px 0">
        </div>

        <table>
            <tr style="background-color: #dddddd;">
                <th class="titulo" colspan="4">
                    <h5>DETALLE DE LA VACANTE #<?= $vacante->idvacante ?>:</h5>
                </th>
            </tr>
            <tr>
                <td valign="top"><b>Concurso: </b><span><?= $vacante->concurso->descripcion ?></span></td>
            </tr>
            <tr>
                <td valign="top"><b>Categoría: </b><span><?= $vacante->categoria0->descripcion ?></span></td>
            </tr>
            <tr>
                <td valign="top"><b>Cantidad: </b><span><?= $vacante->cantidad ?></span></td>
            </tr>
            <tr>
                <td valign="top"><b>¿Requiere título?: </b><span><?= $vacante->requiere_titulo ? 'Si' : 'No' ?></span></td>
            </tr>
        </table>
    </div>
</body>

</html>