<html>

<body>
    <div class="pdf-index" style="font-family: Arial, Helvetica, sans-serif;">
        <img src="https://desasur.neuquen.gov.ar/familia/web/img/membrete_nuevo_pri.png" width="100%" alt="Ministerio de Desarrollo Social y Trabajo">
        <div class="row" style="margin-top: 10px; padding: 2%; text-align: center">
            <h4 style="margin: 0; font-weight: bold;">Rol <?= $rol->descripcion ?></h4>
            <p><span>Listado usuarios activos</span></p>
            <hr style="margin: 0 0 20px 0">
        </div>
        <?php if (count($model) != 0) : ?>
            <table cellpadding="10" cellspacing="0">
                <tr style="background-color: #dddddd; text-align: justify">
                    <thead style="display:table-header-group">
                        <th style="width: 10%">#</th>
                        <th style="width: 15%">Apellido y Nombre</th>
                        <th style="width: 15%">Usuario</th>
                        <th style="width: 30%">Organismo</th>
                        <th style="width: 30%">Dispositivo</th>
                    </thead>
                </tr>
                <?php foreach ($model as $usuario) {
                    $usuario_nombre = "{$usuario['apellido']} {$usuario['nombre']}";
                ?>
                    <tr>
                        <td valign="top"><?= $usuario['idusuario'] ?></td>
                        <td valign="top"><?= $usuario_nombre ?></td>
                        <td valign="top"><?= $usuario['user'] ?></td>
                        <td valign="top"><?= $usuario['organismo'] ?></td>
                        <td valign="top"><?= $usuario['dispositivo'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php else : ?>
            <p>No hay usuarios.</p>
        <?php endif; ?>
    </div>
</body>

</html>