<?php

class Parametros {
    private static $data = [];

    // Método para setear un valor en el array
    public static function set($name, $value) {
        self::$data[$name] = $value;
    }

    // Método para obtener un valor del array
    public static function get($name) {
        return isset(self::$data[$name]) ? self::$data[$name] : null;
    }
}

// Inicializar algunos parámetros si es necesario
Parametros::set('nombre_inicial', 'Valor inicial');

?>
