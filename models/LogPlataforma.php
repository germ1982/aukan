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
            'modelo' => '\app\models\MODELO::class'
        ],
        2 => [
            'nombre' => 'Registro de IPS',
            'modelo' => \app\models\InfIps::class
        ],
        3 => [
            'nombre' => 'Inventario Informática',
            'modelo' => \app\models\Inventario::class
        ],
        4 => [
            'nombre' => 'Personas',
            'modelo' => \app\models\Persona::class
        ],
        5 => [
            'nombre' => 'Empleados',
            'modelo' => \app\models\Empleado::class
        ],
        6 => [
            'nombre' => 'Organismos',
            'modelo' => \app\models\Organismo::class
        ],
        7 => [
            'nombre' => 'Usuarios',
            'modelo' => \app\models\Usuarios::class
        ],
        8 => [
            'nombre' => 'Menú',
            'modelo' => \app\models\Menu::class
        ],
        9 => [
            'nombre' => 'Dispositivos',
            'modelo' => \app\models\OrganismoDispositivo::class
        ],
        10 => [
            'nombre' => 'Runneu Indicadores',
            'modelo' => '\app\models\RunneuIndicadores::class'
        ],
        11 => [
            'nombre' => 'Artículos',
            'modelo' => \app\models\Articulo::class
        ],
        12 => [
            'nombre' => 'Datos',
            'modelo' => \app\models\Configuracion::class
        ],
        13 => [
            'nombre' => 'Tipo de Datos',
            'modelo' => \app\models\ConfiguracionTipo::class
        ],
        14 => [
            'nombre' => 'Permisos de Perfil de Usuario',
            'modelo' => \app\models\UsuarioPerfilPermiso::class
        ],
        15 => [
            'nombre' => 'Perfiles de Usuario',
            'modelo' => \app\models\UsuarioPerfil::class
        ],
        16 => [
            'nombre' => 'Asignación de Perfil a Usuario',
            'modelo' => \app\models\UsuarioAsignacionPerfil::class
        ],
        17 => [
            'nombre' => 'Edificios',
            'modelo' => \app\models\Edificio::class
        ],
        18 => [
            'nombre' => 'Oficinas',
            'modelo' => \app\models\EdificioOficina::class
        ],
        19 => [
            'nombre' => 'Informática Web Empleados',
            'modelo' => \app\models\InformaticaWebEmpleados::class
        ],
        20 => [
            'nombre' => 'Informática Web Eventos',
            'modelo' => \app\models\InformaticaWebEventos::class
        ],
        21 => [
            'nombre' => 'Informática Web Sectores',
            'modelo' => \app\models\InformaticaWebSectores::class
        ],
        22 => [
            'nombre' => 'Log DATAFAM',
            'modelo' => \app\models\LogPlataforma::class
        ],
        23 => [
            'nombre' => 'Legajos de Registro de Familia',
            'modelo' => \app\models\RegistroFamiliaLegajo::class
        ],
        24 => [
            'nombre' => 'Stock Informática Egreso',
            'modelo' => \app\models\StockInformaticaEgreso::class
        ],
        25 => [
            'nombre' => 'Stock Informática Ingreso',
            'modelo' => \app\models\StockInformaticaIngreso::class
        ],
        26 => [
            'nombre' => 'Stock Depósito Egreso',
            'modelo' => \app\models\StockDepositoEgreso::class
        ],
        27 => [
            'nombre' => 'Stock Depósito Ingreso',
            'modelo' => \app\models\StockDepositoIngreso::class
        ],
        28 => [
            'nombre' => 'Vehículos Oficiales',
            'modelo' => \app\models\VehiculoOficial::class
        ],
        29 => [
            'nombre' => 'Movimientos de Vehículos Oficiales',
            'modelo' => \app\models\VehiculoOficialMovimiento::class
        ],
        30 => [
            'nombre' => 'Vehículos',
            'modelo' => \app\models\Vehiculos::class
        ],
        31 => [
            'nombre' => 'Recepcion',
            'modelo' => \app\models\RegistroRecepcion::class
        ],
        
    ];



    const ACCIONES = [
        1 => 'Creación',
        2 => 'Modificación',
        3 => 'Eliminación',
        4 => 'Visualización',
        5 => 'Exportación',
        6 => 'Reseteo Password',
        7 => 'Cambio Password',
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
