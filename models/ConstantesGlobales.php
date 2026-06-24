<?php

namespace app\models; // O el namespace de la carpeta donde esté guardado

class ConstantesGlobales
{
    // ==========================================
    // CONSTANTES DE MÓDULOS (Para Autocompletado)
    // ==========================================
    const REGISTRO_TECNICO_INFORMATICA      = 1;
    const REGISTRO_DE_IPS                   = 2; // Internet Protocol Suite (IPS)
    const INVENTARIO_INFORMATICA            = 3;
    const PERSONAS                          = 4;
    const EMPLEADOS                         = 5;
    const ORGANISMOS                        = 6;
    const USUARIOS                          = 7;
    const MENU                              = 8;
    const DISPOSITIVOS                      = 9;
    const RUNNEU_INDICADORES                = 10;
    const ARTICULOS                         = 11;
    const DATOS                             = 12;
    const TIPO_DE_DATOS                     = 13;
    const PERMISOS_DE_PERFIL_DE_USUARIO     = 14;
    const PERFILES_DE_USUARIO               = 15;
    const ASIGNACION_DE_PERFIL_A_USUARIO    = 16;
    const EDIFICIOS                         = 17;
    const OFICINAS                          = 18;
    const INFORMATICA_WEB_EMPLEADOS         = 19;
    const INFORMATICA_WEB_EVENTOS           = 20;
    const INFORMATICA_WEB_SECTORES          = 21;
    const LOG_DATAFAM                       = 22;
    const LEGAJOS_DE_REGISTRO_DE_FAMILIA    = 23;
    const STOCK_INFORMATICA_EGRESO          = 24;
    const STOCK_INFORMATICA_INGRESO         = 25;
    const STOCK_DEPOSITO_EGRESO             = 26;
    const STOCK_DEPOSITO_INGRESO            = 27;
    const VEHICULOS_OFICIALES               = 28;
    const MOVIMIENTOS_DE_VEHICULOS_OFICIALES = 29;
    const VEHICULOS                         = 30;
    const RECEPCION                         = 31;
    const PERSONAS_NO_HOMOLOGADAS           = 32;
    const DICCIONARIO                       = 33;
    const EDIFICIO_ACCESO                   = 34;
    const EDIFICIO_CONECTIVIDAD             = 35;
    const LOCALIDADES                       = 36;
    const DECRETO_ORGANISMO                 = 37;
    const LEGAJOS_RUNNEU                    = 38;

    // ==========================================
    // MAPA MULTIDIMENSIONAL DE MÓDULOS
    // ==========================================
    const MODULOS = [
        'REGISTRO_TECNICO_INFORMATICA' => [
            'id' => self::REGISTRO_TECNICO_INFORMATICA,
            'nombre' => 'Registro Técnico Informática',
            'modelo' => \app\models\RegistroTecnico::class
        ],
        'REGISTRO_DE_IPS' => [
            'id' => self::REGISTRO_DE_IPS,
            'nombre' => 'Registro de IPS',
            'modelo' => \app\models\InfIps::class
        ],
        'INVENTARIO_INFORMATICA' => [
            'id' => self::INVENTARIO_INFORMATICA,
            'nombre' => 'Inventario Informática',
            'modelo' => \app\models\Inventario::class
        ],
        'PERSONAS' => [
            'id' => self::PERSONAS,
            'nombre' => 'Personas',
            'modelo' => \app\models\Persona::class
        ],
        'EMPLEADOS' => [
            'id' => self::EMPLEADOS,
            'nombre' => 'Empleados',
            'modelo' => \app\models\Empleado::class
        ],
        'ORGANISMOS' => [
            'id' => self::ORGANISMOS,
            'nombre' => 'Organismos',
            'modelo' => \app\models\Organismo::class
        ],
        'USUARIOS' => [
            'id' => self::USUARIOS,
            'nombre' => 'Usuarios',
            'modelo' => \app\models\Usuarios::class
        ],
        'MENU' => [
            'id' => self::MENU,
            'nombre' => 'Menú',
            'modelo' => \app\models\Menu::class
        ],
        'DISPOSITIVOS' => [
            'id' => self::DISPOSITIVOS,
            'nombre' => 'Dispositivos',
            'modelo' => \app\models\OrganismoDispositivo::class
        ],
        'RUNNEU_INDICADORES' => [
            'id' => self::RUNNEU_INDICADORES,
            'nombre' => 'Runneu Indicadores',
            'modelo' => '\app\models\RunneuIndicadores::class'
        ],
        'ARTICULOS' => [
            'id' => self::ARTICULOS,
            'nombre' => 'Artículos',
            'modelo' => \app\models\Articulo::class
        ],
        'DATOS' => [
            'id' => self::DATOS,
            'nombre' => 'Datos',
            'modelo' => \app\models\Configuracion::class
        ],
        'TIPO_DE_DATOS' => [
            'id' => self::TIPO_DE_DATOS,
            'nombre' => 'Tipo de Datos',
            'modelo' => \app\models\ConfiguracionTipo::class
        ],
        'PERMISOS_DE_PERFIL_DE_USUARIO' => [
            'id' => self::PERMISOS_DE_PERFIL_DE_USUARIO,
            'nombre' => 'Permisos de Perfil de Usuario',
            'modelo' => \app\models\UsuarioPerfilPermiso::class
        ],
        'PERFILES_DE_USUARIO' => [
            'id' => self::PERFILES_DE_USUARIO,
            'nombre' => 'Perfiles de Usuario',
            'modelo' => \app\models\UsuarioPerfil::class
        ],
        'ASIGNACION_DE_PERFIL_A_USUARIO' => [
            'id' => self::ASIGNACION_DE_PERFIL_A_USUARIO,
            'nombre' => 'Asignación de Perfil a Usuario',
            'modelo' => \app\models\UsuarioAsignacionPerfil::class
        ],
        'EDIFICIOS' => [
            'id' => self::EDIFICIOS,
            'nombre' => 'Edificios',
            'modelo' => \app\models\Edificio::class
        ],
        'OFICINAS' => [
            'id' => self::OFICINAS,
            'nombre' => 'Oficinas',
            'modelo' => \app\models\EdificioOficina::class
        ],
        'INFORMATICA_WEB_EMPLEADOS' => [
            'id' => self::INFORMATICA_WEB_EMPLEADOS,
            'nombre' => 'Informática Web Empleados',
            'modelo' => \app\models\InformaticaWebEmpleados::class
        ],
        'INFORMATICA_WEB_EVENTOS' => [
            'id' => self::INFORMATICA_WEB_EVENTOS,
            'nombre' => 'Informática Web Eventos',
            'modelo' => \app\models\InformaticaWebEventos::class
        ],
        'INFORMATICA_WEB_SECTORES' => [
            'id' => self::INFORMATICA_WEB_SECTORES,
            'nombre' => 'Informática Web Sectores',
            'modelo' => \app\models\InformaticaWebSectores::class
        ],
        'LOG_DATAFAM' => [
            'id' => self::LOG_DATAFAM,
            'nombre' => 'Log DATAFAM',
            'modelo' => \app\models\LogPlataforma::class
        ],
        'LEGAJOS_DE_REGISTRO_DE_FAMILIA' => [
            'id' => self::LEGAJOS_DE_REGISTRO_DE_FAMILIA,
            'nombre' => 'Legajos de Registro de Familia',
            'modelo' => \app\models\RegistroFamiliaLegajo::class
        ],
        'STOCK_INFORMATICA_EGRESO' => [
            'id' => self::STOCK_INFORMATICA_EGRESO,
            'nombre' => 'Stock Informática Egreso',
            'modelo' => \app\models\StockInformaticaEgreso::class
        ],
        'STOCK_INFORMATICA_INGRESO' => [
            'id' => self::STOCK_INFORMATICA_INGRESO,
            'nombre' => 'Stock Informática Ingreso',
            'modelo' => \app\models\StockInformaticaIngreso::class
        ],
        'STOCK_DEPOSITO_EGRESO' => [
            'id' => self::STOCK_DEPOSITO_EGRESO,
            'nombre' => 'Stock Depósito Egreso',
            'modelo' => \app\models\StockDepositoEgreso::class
        ],
        'STOCK_DEPOSITO_INGRESO' => [
            'id' => self::STOCK_DEPOSITO_INGRESO,
            'nombre' => 'Stock Depósito Ingreso',
            'modelo' => \app\models\StockDepositoIngreso::class
        ],
        'VEHICULOS_OFICIALES' => [
            'id' => self::VEHICULOS_OFICIALES,
            'nombre' => 'Vehículos Oficiales',
            'modelo' => \app\models\VehiculoOficial::class
        ],
        'MOVIMIENTOS_DE_VEHICULOS_OFICIALES' => [
            'id' => self::MOVIMIENTOS_DE_VEHICULOS_OFICIALES,
            'nombre' => 'Movimientos de Vehículos Oficiales',
            'modelo' => \app\models\VehiculoOficialMovimiento::class
        ],
        'VEHICULOS' => [
            'id' => self::VEHICULOS,
            'nombre' => 'Vehículos',
            'modelo' => \app\models\Vehiculos::class
        ],
        'RECEPCION' => [
            'id' => self::RECEPCION,
            'nombre' => 'Recepcion',
            'modelo' => \app\models\RegistroRecepcion::class
        ],
        'PERSONAS_NO_HOMOLOGADAS' => [
            'id' => self::PERSONAS_NO_HOMOLOGADAS,
            'nombre' => 'Personas No Homologadas',
            'modelo' => \app\models\PersonasNoHomologadas::class
        ],
        'DICCIONARIO' => [
            'id' => self::DICCIONARIO,
            'nombre' => 'Diccionario',
            'modelo' => \app\models\ConfiguracionDiccionario::class
        ],
        'EDIFICIO_ACCESO' => [
            'id' => self::EDIFICIO_ACCESO,
            'nombre' => 'Accesos de Edificios',
            'modelo' => \app\models\EdificioAcceso::class
        ],
        'EDIFICIO_CONECTIVIDAD' => [
            'id' => self::EDIFICIO_CONECTIVIDAD,
            'nombre' => 'Conectividad de Edificios',
            'modelo' => \app\models\EdificioConectividad::class
        ],

        'LOCALIDADES' => [
            'id' => self::LOCALIDADES,
            'nombre' => 'Localidades',
            'modelo' => \app\models\Localidades::class
        ],

        'DECRETO_ORGANISMO' => [
            'id' => self::DECRETO_ORGANISMO,
            'nombre' => 'Decretos de Estructuras de Organismos',
            'modelo' => \app\models\OrganismoDecreto::class
        ],

        'LEGAJOS_RUNNEU' => [
            'id' => self::LEGAJOS_RUNNEU,
            'nombre' => 'Legajos de RUNNEU',
            'modelo' => \app\models\RunneuLegajo::class
        ],
    ];


    // ==========================================
    // CONSTANTES DE ACCIONES (Para Autocompletado)
    // ==========================================
    const CREACION        = 1;
    const MODIFICACION    = 2;
    const ELIMINACION     = 3;
    const VISUALIZACION   = 4;
    const EXPORTACION     = 5;
    const RESET_PASSWORD  = 6; // Password (PWD)
    const CAMBIO_PASSWORD = 7;
    const MIGRACION_EGRESA_DATOS = 8;
    const MIGRACION_INGRESA_DATOS = 9;
    const ACTIVAR = 10;
    const DESACTIVAR = 11;
    const DESCARGA = 12;
    // ==========================================
    // MAPA MULTIDIMENSIONAL DE ACCIONES
    // ==========================================
    const ACCIONES = [
        'CREACION' => [
            'id' => self::CREACION,
            'nombre' => 'Creación'
        ],
        'MODIFICACION' => [
            'id' => self::MODIFICACION,
            'nombre' => 'Modificación'
        ],
        'ELIMINACION' => [
            'id' => self::ELIMINACION,
            'nombre' => 'Eliminación'
        ],
        'VISUALIZACION' => [
            'id' => self::VISUALIZACION,
            'nombre' => 'Visualización'
        ],
        'EXPORTACION' => [
            'id' => self::EXPORTACION,
            'nombre' => 'Exportación'
        ],
        'RESET_PASSWORD' => [
            'id' => self::RESET_PASSWORD,
            'nombre' => 'Reseteo Password'
        ],
        'CAMBIO_PASSWORD' => [
            'id' => self::CAMBIO_PASSWORD,
            'nombre' => 'Cambio Password'
        ],

        'MIGRACION_EGRESA_DATOS' => [
            'id' => self::MIGRACION_EGRESA_DATOS,
            'nombre' => 'Migracion Egresando Datos'
        ],

        'MIGRACION_INGRESA_DATOS' => [
            'id' => self::MIGRACION_INGRESA_DATOS,
            'nombre' => 'Migracion Ingresando Datos'
        ],
        'ACTIVAR' => [
            'id' => self::ACTIVAR,
            'nombre' => 'Activacion de Datos'
        ],

        'DESACTIVAR' => [
            'id' => self::DESACTIVAR,
            'nombre' => 'Desactivacion de Datos'
        ],
        'DESCARGA' => [
            'id' => self::DESCARGA,
            'nombre' => 'Descarga de Archivo'
        ],
    ];
}
