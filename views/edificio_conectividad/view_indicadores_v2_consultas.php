<?php
// C:\xampp_datafam\htdocs\datafam\views\edificio_conectividad\view_indicadores_v2_consultas.php

/**
 * A. Función para el TOTAL de conexiones
 */
function get_cantidad_connections($text_match, $tarjetas) {
    if ($text_match === 'RESTO') {
        // Juntamos las oficiales en un string tipo: 'CDI|HOGAR|DELEGACION|CCC|CFF'
        $excluir = implode('|', $tarjetas);
        
        $where = "WHERE e.descripcion_fija NOT REGEXP '{$excluir}' 
                    AND e.descripcion_gestion NOT REGEXP '{$excluir}'";
    } else {
        $where = "WHERE e.descripcion_fija LIKE :text 
                     OR e.descripcion_gestion LIKE :text";
    }

    $sql = "SELECT COUNT(*) 
            FROM familia.edificio_conectividad c
            JOIN familia.edificio e ON c.idedificio = e.idedificio
            {$where}";

    $command = Yii::$app->db->createCommand($sql);
    
    // El bindValue solo hace falta si NO es RESTO
    if ($text_match !== 'RESTO') {
        $command->bindValue(':text', "%{$text_match}%");
    }

    return (int)$command->queryScalar();
}

/**
 * B. Función para agrupar INFRAESTRUCTURA, SERVICIO o ENLACE
 */
function get_datos_agrupados($text_match, $columna_agrupar, $tarjetas) {
    // Definimos el WHERE según corresponda, sin tanto bardo
    if ($text_match === 'RESTO') {
        $excluir = implode('|', $tarjetas);
        $where = "WHERE e.descripcion_fija NOT REGEXP '{$excluir}' 
                    AND e.descripcion_gestion NOT REGEXP '{$excluir}'";
    } else {
        $where = "WHERE e.descripcion_fija LIKE :text 
                     OR e.descripcion_gestion LIKE :text";
    }

    $sql = "SELECT config.descripcion AS nombre, COUNT(*) AS cantidad
            FROM familia.edificio_conectividad c
            JOIN familia.edificio e ON c.idedificio = e.idedificio
            JOIN familia.configuracion config ON c.{$columna_agrupar} = config.id_configuracion
            {$where}
            GROUP BY c.{$columna_agrupar}, config.descripcion"; // Agregamos config.descripcion al GROUP BY por buena práctica SQL

    $command = Yii::$app->db->createCommand($sql);
    
    // El bindValue solo se necesita si estás buscando una tarjeta específica
    if ($text_match !== 'RESTO') {
        $command->bindValue(':text', "%{$text_match}%");
    }
    
    $resultado = $command->queryAll();
    
    // Mapeamos el resultado para el array clave-valor que espera la tarjeta
    $datos = [];
    foreach ($resultado as $fila) {
        $datos[$fila['nombre']] = (int)$fila['cantidad'];
    }
    return $datos;
}

/**
 * C. Función para el conteo de ESTADOS (Bueno, Malo, Regular, Caído)
 */
function get_estados_conexiones($text_match, $tarjetas) {
    $resultado = get_datos_agrupados($text_match, 'estado', $tarjetas);
    $estados = ['bueno' => 0, 'malo' => 0, 'regular' => 0, 'caido' => 0, 'desconocido' => 0];

    // Mapa de traducción: qué palabra de la base de datos va a qué clave de la tarjeta
    $mapa = [
        'bueno'       => 'bueno',
        'excelente'   => 'bueno',
        'malo'        => 'malo',
        'regular'     => 'regular',
        'caido'       => 'caido',
        'no funciona' => 'caido'
    ];

    foreach ($resultado as $nombre => $cantidad) {
        $nombreLimpio = strtolower($nombre);
        // Si el nombre existe en el mapa, lo suma ahí; si no, va a desconocido
        $claveDestino = $mapa[$nombreLimpio] ?? 'desconocido';
        $estados[$claveDestino] += $cantidad;
    }

    return $estados;
}