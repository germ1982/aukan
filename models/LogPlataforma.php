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
        1 => [
            'nombre' => 'Registro Técnico Informática',
            'modelo' => ''
        ],
        2 => [
            'nombre' => 'Registro de IPS',
            'modelo' => ''
        ],
        3 => [
            'nombre' => 'Inventario Informática',
            'modelo' => ''
        ],
        4 => [
            'nombre' => 'Personas',
            'modelo' => ''
        ],
        5 => [
            'nombre' => 'Empleados',
            'modelo' => ''
        ],
        6 => [
            'nombre' => 'Organismos',
            'modelo' => ''
        ],
        7 => [
            'nombre' => 'Usuarios',
            'modelo' => ''
        ],
        8 => [
            'nombre' => 'Menú',
            'modelo' => ''
        ],
        9 => [
            'nombre' => 'Dispositivos',
            'modelo' => ''
        ],
        10 => [
            'nombre' => 'Runneu Indicadores',
            'modelo' => ''
        ],
        11 => [
            'nombre' => 'Artículos',
            'modelo' => \app\models\Articulo::class
        ],
        12 => [
            'nombre' => 'Datos',
            'modelo' => ''
        ],
        13 => [
            'nombre' => 'Tipo de Datos',
            'modelo' => ''
        ],
        14 => [
            'nombre' => 'Permisos de Perfil de Usuario',
            'modelo' => ''
        ],
        15 => [
            'nombre' => 'Perfiles de Usuario',
            'modelo' => ''
        ],
        16 => [
            'nombre' => 'Asignación de Perfil a Usuario',
            'modelo' => ''
        ],
        17 => [
            'nombre' => 'Edificios',
            'modelo' => ''
        ],
        18 => [
            'nombre' => 'Oficinas',
            'modelo' => ''
        ],
        19 => [
            'nombre' => 'Informática Web Empleados',
            'modelo' => ''
        ],
        20 => [
            'nombre' => 'Informática Web Eventos',
            'modelo' => ''
        ],
        21 => [
            'nombre' => 'Informática Web Sectores',
            'modelo' => ''
        ],
        22 => [
            'nombre' => 'Log DATAFAM',
            'modelo' => ''
        ],
        23 => [
            'nombre' => 'Legajos de Registro de Familia',
            'modelo' => ''
        ],
        24 => [
            'nombre' => 'Stock Informática Egreso',
            'modelo' => ''
        ],
        25 => [
            'nombre' => 'Stock Informática Ingreso',
            'modelo' => ''
        ],
        26 => [
            'nombre' => 'Stock Depósito Egreso',
            'modelo' => ''
        ],
        27 => [
            'nombre' => 'Stock Depósito Ingreso',
            'modelo' => ''
        ],
        28 => [
            'nombre' => 'Vehículos Oficiales',
            'modelo' => ''
        ],
        29 => [
            'nombre' => 'Movimientos de Vehículos Oficiales',
            'modelo' => ''
        ],
        30 => [
            'nombre' => 'Vehículos',
            'modelo' => ''
        ],
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
        return self::MODULOS[$id]['nombre'] ?? 'Desconocido';
    }

    public static function getModuloModelo($id)
{
    return self::MODULOS[$id]['modelo'] ?? null;
}

    public static function getAccionNombre($id)
    {
        return self::ACCIONES[$id] ?? 'Desconocida';
    }

    public static function getModulosLista()
    {
        $lista = [];
        foreach (self::MODULOS as $id => $info) {
            $lista[$id] = $info['nombre'];
        }
    
        asort($lista); // ordena por el nombre del módulo
        return $lista;
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
