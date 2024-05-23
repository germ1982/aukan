<?php

use Da\QrCode\QrCode;

$query = new yii\db\Query;

/* select persona.nombre,persona.apellido,persona.documento,contacto.legajo,
(select descripcion from mds_org_organismo organismo
where organismo.idorganismo=dispositivo.idorganismo) organismo,
dispositivo.descripcion dependencia
from mds_org_contacto contacto
join sds_com_persona persona on persona.idpersona = contacto.idpersona
left join mds_org_dispositivo dispositivo on dispositivo.iddispositivo=contacto.iddispositivo
where idcontacto=2 */
if (is_numeric($idcontacto)) {
    $query->select([
        "persona.nombre", "persona.apellido", "persona.documento", "contacto.legajo",
        "(select abreviatura from mds_org_organismo organismo
        where organismo.idorganismo=dispositivo.idorganismo) organismo",
        "dispositivo.descripcion dependencia",
        "(SELECT path FROM mds_org_documento foto_dni where tipo=1472 and foto_dni.idcontacto=contacto.idcontacto limit 1) foto_dni",
        "esencial"
    ])
        ->from(["mds_org_contacto contacto"])
        ->leftJoin("sds_com_persona as persona", "persona.idpersona = contacto.idpersona")
        ->leftJoin("mds_org_dispositivo as dispositivo", "dispositivo.iddispositivo=contacto.iddispositivo")
        ->where("contacto.idcontacto=" . $idcontacto);
    $command = $query->createCommand();
    $datos = $command->queryOne();

    $nombre = strtolower(explode(' ', $datos['nombre'], 2)[0]);
    $ultima_letra = strtolower(substr($nombre, strlen($nombre) - 1, strlen($nombre)));
    $sexo = $ultima_letra == 'a' || ($ultima_letra == 'y' && $nombre != "hardy")
        || $ultima_letra == 'i' || $ultima_letra == 'h' || $nombre == "jael"
        || $ultima_letra == 't';

    $qrCode = (new QrCode("{idcontacto=$idcontacto}"))
        ->setSize(50)
        ->setMargin(5)
        ->useForegroundColor(2, 2, 2);
}
?>

<html>
<div style="position: fixed; top: 45px; left: 48px;background:#64bde0;height:154px;width:154px;">

</div>
<div style="<?= isset($datos) && $datos['foto_dni'] == null ?
                "position: fixed; top: 46px; left: 51px;"
                : "position: fixed; top: 46px; left: 63px;" ?>">
    <img id="foto" src="<?= !isset($datos) || $datos['foto_dni'] == null ?
                            ("img/sinfoto" . (!isset($sexo) || $sexo ? "f" : "m") . ".jpg")
                            : $datos['foto_dni'] ?>" height="
                            <?= !isset($datos) || $datos['foto_dni'] == null ? "150px" : "153px" ?>" alt="Sistema Único de Registro">
</div>
<div style="position:fixed;top: 45px; left: 236px;font-size: 16pt;">
    <?= '<b>' . (isset($datos) ? $datos['apellido'] : "") . '<br></b> '
        . (isset($datos) ? $datos['nombre'] : "") ?>
</div>
<div style="position:fixed;top: 125px; left: 250px;font-size: 14pt;">
    <?= "D.N.I.: " . (isset($datos) ?  $datos['documento'] : "") ?>
</div>
<div style="position:fixed;top: 152px; left: 250px;font-size: 14pt;">
    <?= "Nº Legajo: " . (isset($datos) ?  $datos['legajo'] : "") ?>
</div>
<div style="position:fixed;top: 180px; left: 250px;width:280px;font-size: 14pt;">
    <?= (isset($datos) ?  $datos['organismo'] : "") ?>
</div>
<div style="position:fixed;top: 234px; left: 250px;width:280px;font-size: 14pt;">
    <?= (isset($datos) ?  $datos['dependencia'] : "") ?>
</div>
<div style="position:fixed;top: 299px; left: 7px;">
    <img src="<?= isset($qrCode) ? $qrCode->writeDataUri() : "" ?>">
</div>
<div class="pdf-index" style="color: #292929;text-align:left;
         background-image: url('<?= '../web/img/credencial.jpg'; ?>');
         background-size: contain; resize: both;background-repeat: no-repeat;
         background-color: #FFFFFF;height:80%;width:80%;font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">
</div>

</html>