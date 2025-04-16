<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_plataforma".
 *
 * @property int $idlog
 * @property int|null $idusuario
 * @property string|null $fecha
 * @property string|null $hora
 * @property int|null $modulo
 * @property int|null $accion
 * @property int|null $idregistro
 */
class LogPlataforma extends \yii\db\ActiveRecord
{
    //se agregan estas dos variables para que funcione el filtro por fechas
    public $fdesde;
    public $fhasta;

    public static function tableName()
    {
        return 'log_plataforma';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlog'], 'required'],
            [['idlog', 'idusuario', 'modulo', 'accion', 'idregistro'], 'integer'],
            [['fecha', 'hora', 'fdesde', 'fhasta'], 'safe'], //se agregan estas dos variables para que funcione el filtro por fechas
            [['idlog'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlog' => 'ID',
            'idusuario' => 'Usuario',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'modulo' => 'Modulo',
            'accion' => 'Accion',
            'idregistro' => 'ID Registro',
        ];
    }

    const MODULOS = [
        1  => 'Registro Técnico Informática',
        2  => 'Registro de IPS',
        3  => 'Inventario Informática',
        4  => 'Personas',
        5  => 'Empleados',
        6  => 'Organismos',
        7  => 'Usuarios',
        8  => 'Menú',
        9  => 'Dispositivos',
        10 => 'Runneu Indicadores',
        11 => 'Artículos',
        12 => 'Datos',
        13 => 'Tipo de Datos',
        14 => 'Permisos de Perfil de Usuario',
        15 => 'Perfiles de Usuario',
        16 => 'Asignación de Perfil a Usuario',
        17 => 'Edificios',
        18 => 'Oficinas',
        19 => 'Informática Web Empleados',
        20 => 'Informática Web Eventos',
        21 => 'Informática Web Sectores',
        22 => 'Log DATAFAM',
        23 => 'Legajos de Registro de Familia',
        24 => 'Stock Informática Egreso',
        25 => 'Stock Informática Ingreso',
        26 => 'Stock Depósito Egreso',
        27 => 'Stock Depósito Ingreso',
        28 => 'Vehículos Oficiales',
        29 => 'Movimientos de Vehículos Oficiales',
        30 => 'Vehículos',
    ];


    const ACCIONES = [
        1 => 'Creación',
        2 => 'Modificación',
        3 => 'Eliminación',
        4 => 'Visualización',
        5 => 'Exportación',
        // más acciones si querés
    ];

    public static function getModuloNombre($id)
    {
        return self::MODULOS[$id] ?? 'Desconocido';
    }

    public static function getAccionNombre($id)
    {
        return self::ACCIONES[$id] ?? 'Desconocida';
    }

    public static function getModulosLista()
    {
        $modulos = self::MODULOS;
        asort($modulos); // ordena por valor (nombre del módulo)
        return $modulos;
    }

    public static function getAccionesLista()
    {
        return self::ACCIONES;
    }

    public static function registrar($modulo_id, $accion, $registro_id)
    {
        $usuario_id = Yii::$app->user->identity->id;
        $log = new self();
        $log->idusuario = $usuario_id;
        $log->modulo = $modulo_id;
        $log->accion = $accion;
        $log->idregistro = $registro_id;
        $log->fecha = date('Y-m-d H:i:s');
        $log->hora = date('H:i:s');
        $log->save(false); // false si no querés validar (más rápido)
    }

}
