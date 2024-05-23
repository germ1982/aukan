<?php


$idagresor = $_POST['idagresor'];
$dni = $_POST['dni'];

require_once '../config/db.php';

$dbh = new BaseDatos();
$dbh->Iniciar();
$encontrado = false;
if ($idagresor) {
    $sql = "SELECT * FROM sds_vio_agresor WHERE idagresor= $idagresor";
} else {
    $sql = "SELECT * FROM sds_vio_agresor WHERE dni= $dni";
}

$result = $dbh->Select($sql);
if (!$result) {
    echo "<p>Error en la consulta.</p>";
} else {
    while ($result = $dbh->Registro()) {
        $encontrado = true;
        $nombre = $result['nombre'];
        $apellido = $result['apellido'];
        $genero = $result['genero'];
        $dni = $result['dni'];
        $agresor_dato_denuncia = $result['agresor_dato_denuncia'];
        $agresor_dav = $result['agresor_dav'];
        $agresor_dav_datos = $result['agresor_dav_datos'];

        $agresor_escolaridad = $result['escolaridad'];
        $agresor_funcionario = $result['funcionario'];
        $agresor_desc_actividad = $result['desc_actividad'];
        $agresor_desc_jubilacion = $result['desc_jubilacion'];
        $agresor_acceso_armas = $result['acceso_armas'];
        $agresor_antecedente_penales = $result['antecedente_penales'];
        $agresor_antecedente_violencia = $result['antecedente_violencia'];
        $agresor_antecedente_restricciones = $result['antecedente_restricciones'];
        $agresor_vinculo_ilicito = $result['vinculo_ilicito'];
        $agresor_vinculo_personal_seguridad = $result['vinculo_personal_seguridad'];
        $agresor_consumo_problematico = $result['consumo_problematico'];
    }
}

$arrayConsumos  = array();

if ($idagresor) {
    $consultaConsumo = "SELECT * FROM sds_vio_agresor_consumo WHERE idagresor= $idagresor AND deleted_at IS NULL";
} else {
    $consultaConsumo =
        "SELECT consumo.* 
        FROM sds_vio_agresor_consumo AS consumo
        INNER JOIN sds_vio_agresor AS agresor ON consumo.idagresor = agresor.idagresor
        WHERE agresor.dni= $dni AND consumo.deleted_at IS NULL
    ";
}
if ($dbh->Ejecutar($consultaConsumo) && $dbh->Cantidad() > 0) {
    while ($fila = $dbh->Registro()) {
        $idconsumos = $fila['idconsumo'];
        $arrayConsumos[$idconsumos] = $idconsumos;
    }
}

$dbh->Cerrar();
$dbh = NULL;
$resultado = array();
if ($encontrado) {
    $resultado = array(
        "nombre" => $nombre,
        "apellido" => $apellido,
        "genero" => $genero,
        "dni" => $dni,

        "agresor_dato_denuncia" => $agresor_dato_denuncia,
        "agresor_dav" => $agresor_dav,
        "agresor_dav_datos" => $agresor_dav_datos,

        "escolaridad" => $agresor_escolaridad,
        "funcionario" => $agresor_funcionario,
        "desc_actividad" => $agresor_desc_actividad,
        "desc_jubilacion" => $agresor_desc_jubilacion,
        "acceso_armas" => $agresor_acceso_armas,
        "antecedente_penales" => $agresor_antecedente_penales,
        "antecedente_violencia" => $agresor_antecedente_violencia,
        "antecedente_restricciones" => $agresor_antecedente_restricciones,
        "vinculo_ilicito" => $agresor_vinculo_ilicito,
        "vinculo_personal_seguridad" => $agresor_vinculo_personal_seguridad,
        "consumo_problematico" => $agresor_consumo_problematico,

        "arrayConsumos" => $arrayConsumos,

    );
}


echo json_encode($resultado);//lo que haya sucedido aca lo devuelve en json 
