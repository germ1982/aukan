<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_800_llamada".
 *
 * @property int $idllamada
 * @property int $idpersona
 * @property string|null $institucion
 * @property string|null $vinculo
 * @property string $detalle
 * @property int $tipo
 * @property int|null $idderivacion
 * @property int|null $afectado_dni
 * @property string|null $afectado_nombre
 * @property string|null $afectado_apodo
 * @property string $fecha_hora
 * @property int $idusuario
 * @property int $idrisneu
 * @property int $iddispositivo
 * @property int $estado 0: pendiente de evaluacion, 1: NC, 2: Derivada, 3: Atendida, 4: Cerrada
 * @property string|null $derivacion_referente
 * @property string|null $derivacion_detalle
 * @property string|null $cierre_detalle
 * @property float $latitud
 * @property float $longitud
 * @property string|null $deleted_at
 * @property Sds800Atencion $sds800Atencion
 * @property Sds800Derivacion $idderivacion0
 * @property Sds800Persona $idpersona0
 * @property SdsComConfiguracion $tipo0
 * @property Sds800Derivacion $idderivacion0
 * @property MdsSegUsuario $idusuario0
 * @property MdsSegUsuario $usuarioAlta
 * @property MdsOrgDispositivo $iddispositivo0
 */
class Sds_800_llamada extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $dni;
    public $telefono;
    public $nombre_completo;
    public $persona_afectada;
    public $nombre;
    public $apellido;
    public $usuario;
    public $usuarioDeriva;
    public $fecha_nacimiento;
    public $nacionalidad;
    public $sexo;
    public $genero;
    public $domicilio;
    public $localidad;
    public $coordenadas;
    public $edad;
    public $provincia;

    public $causa_situacion;
    public $familia;
    public $tipo_ayuda;
    public $expectativa_corto_plazo;
    public $evaluacion_funcional;
    public $evaluacion_funcional_detalle;

    //estado --> 0: pendiente de evaluacion, 1: NC, 2: Derivada, 3: Atendida, 4: Cerrada
    const ESTADO_PENDIENTE = 0;
    const ESTADO_NC = 1;
    const ESTADO_DERIVADA = 2;
    const ESTADO_ATENDIDA = 3;
    const ESTADO_CERRADA = 4;
    const ESTADO_DESPEJADA = 5;

    //afectado_tratamiento --> 0: Paciente de adicciones, 1: paciente salud mental, 2: Pacientes duales
    const PACIENTE_ADICCIONES = 0;
    const PACIENTE_MENTAL = 1;
    const PACIENTE_DUALES = 2;

    const AREAINTERVINIENTE_ADMISION = 0;
    const AREAINTERVINIENTE_GUARDIA = 1;
    const AREAINTERVINIENTE_POSTGUARDIA = 2;
    const AREAINTERVINIENTE_CALLE = 3;

    const DESCRIPCION_AREAINTERVINIENTE_ADMISION = "Admisión";
    const DESCRIPCION_AREAINTERVINIENTE_GUARDIA = "Guardia";
    const DESCRIPCION_AREAINTERVINIENTE_POSTGUARDIA = "Post Guardia";
    const DESCRIPCION_AREAINTERVINIENTE_CALLE = "Situación de Calle";

    const ARRAY_AREAINTERVINIENTE =
    [
        Sds_800_llamada::AREAINTERVINIENTE_ADMISION =>
        Sds_800_llamada::DESCRIPCION_AREAINTERVINIENTE_ADMISION,
        Sds_800_llamada::AREAINTERVINIENTE_GUARDIA =>
        Sds_800_llamada::DESCRIPCION_AREAINTERVINIENTE_GUARDIA,
        Sds_800_llamada::AREAINTERVINIENTE_POSTGUARDIA =>
        Sds_800_llamada::DESCRIPCION_AREAINTERVINIENTE_POSTGUARDIA,
        Sds_800_llamada::AREAINTERVINIENTE_CALLE =>
        Sds_800_llamada::DESCRIPCION_AREAINTERVINIENTE_CALLE,
    ];

    const ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL = 181;
    const ID_ROL_DASHBOARD = 193;
    const ROL_OPERADOR0800_FAMILIA = 48;
    const ROL_OPERADOR0800_ADULTOSMAYORES = 49;
    const ROL_OPERADOR0800_INTERIOR = 67;
    const ROL_OPERADOR0800 = 23;

    const AREA_SITUACIONDECALLE = 0;
    const AREA_FAMILIA = 1;
    const AREA_ADULTOSMAYORES = 2;
    const AREA_INTERIOR = 3;
    const AREA_VIOLENCIA = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_800_llamada';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona', 'area', 'localidad', 'detalle', 'fecha_hora', 'idusuario', 'dni', 'nombre', 'apellido', 'fecha_nacimiento', 'nacionalidad', 'sexo', 'latitud', 'longitud', 'provincia'], 'required'],
            [['evaluacion_funcional', 'tipo_ayuda', 'familia', 'idpersona', 'tipo', 'genero', 'area',  'idderivacion', 'afectado_dni', 'idusuario', 'idusuario_deriva', 'dni', 'localidad', 'estado', 'afectado_tratamiento', 'idorigen', 'area_interviniente', 'solicitante', 'idusuario_borra', 'edad', 'idrisneu', 'iddispositivo', 'profesional_interviniente'], 'integer'],
            [['evaluacion_funcional_detalle', 'causa_situacion', 'detalle', 'derivacion_referente', 'derivacion_detalle', 'cierre_detalle', 'direccion'], 'string'],
            [[
                'fecha_hora', 'fdesde', 'fhasta', 'telefono', 'persona_afectada', 'nombre_completo', 'estado', 'derivacion_fecha', 'cierre_fecha', 'localidad', 'direccion', 'solicitante',
                'edad', 'provincia', 'deleted_at', 'idrisneu', 'iddispositivo'
            ], 'safe'],
            [['institucion', 'vinculo', 'afectado_nombre', 'nombre', 'apellido', 'telefono', 'domicilio'], 'string', 'max' => 100],
            [['afectado_apodo'], 'string', 'max' => 45],
            [['derivacion_referente'], 'string', 'max' => 512],
            [['latitud', 'longitud'], 'number'],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
            [['genero'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['genero' => 'idconfiguracion']],
            [['idderivacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_derivacion::class, 'targetAttribute' => ['idderivacion' => 'idderivacion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idusuario_deriva'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_deriva' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
            [['profesional_interviniente'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['profesional_interviniente' => 'idusuario']],
            [['idorigen'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_llamada::class, 'targetAttribute' => ['idorigen' => 'idllamada']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idllamada' => 'Nro. Llamada',
            'idpersona' => 'Idpersona',
            'idrisneu' => 'Nro. RISNeu',
            'institucion' => 'Institución',
            'vinculo' => 'Vínculo',
            'detalle' => 'Detalle de la Situación',
            'tipo' => 'Tipo',
            'area' => 'Área',
            'idderivacion' => 'Derivación',
            'afectado_dni' => 'Afectado Dni',
            'afectado_nombre' => 'Afectado Nombre',
            'afectado_apodo' => 'Afectado Apodo',
            'fecha_hora' => 'Fecha y Hora',
            'idusuario' => 'Usuario',
            'estado' => 'No Corresponde',
            'derivacion_referente' => 'Referente',
            'derivacion_detalle' => 'Detalle',
            'cierre_detalle' => 'Detalle',
            'dni' => 'DNI',
            'telefono' => 'Teléfono',
            'nombre_completo' => 'Nombre',
            'persona_afectada' => 'Persona Afectada',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'nacionalidad' => 'Nacionalidad',
            'sexo' => 'Sexo',
            'genero' => 'Genero',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
            'afectado_tratamiento' => 'Afectado en Tratamiento',
            'idorigen' => 'Llamada de Origen',
            'area_interviniente' => 'Área Interviniente',
            'direccion' => 'Dirección',
            'solicitante' => 'Solicitante de Situación',
            'idusuario_deriva' => 'Usuario deriva',
            'deleted_at' => 'Deleted At',
            'causa_situacion' => '¿Por qué se encuentra en situación de calle?',
            'familia' => 'Red social y familiar',
            'tipo_ayuda' => 'Tipo de ayuda que solicita',
            'expectativa_corto_plazo' => 'Expectativa a corto plazo',
            'evaluacion_funcional' => 'Evaluacion Funcional',
            'evaluacion_funcional_detalle' => 'Detalle',
            'profesional_interviniente' => 'Profesional Interviniente',
        ];
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdperson0()
    {
        return $this->hasOne(Sds_800_Persona::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Profesional_interviniente0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfesional_interviniente0()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'profesional_interviniente']);
    }

    /**
     * Gets query for [[Tipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo']);
    }
    public function getGenero0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'genero']);
    }

    public function getArea()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'area']);
    }

    /**
     * Gets query for [[Idderivacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdderivacion0()
    {
        return $this->hasOne(Sds_800_derivacion::class, ['idderivacion' => 'idderivacion']);
    }

    /**
     * Gets query for [[getLocalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad0()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'localidad']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[usuarioAlta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioAlta()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[IdusuarioDeriva]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioDeriva()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario_deriva' => 'idusuario']);
    }

    public function getIdorigen()
    {
        return $this->hasOne(Sds_800_llamada::class, ['idllamada' => 'idorigen']);
    }

    /**
     * Gets query for [[Iddispositivo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddispositivo0()
    {
        return $this->hasOne(MdsOrgDispositivo::class, ['iddispositivo' => 'iddispositivo']);
    }

    /*
    public static function calculaDireccionGM($longitud, $latitud) {
        $geolocation = trim($latitud) . ',' . trim($longitud);
        $request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $geolocation . '&sensor=false&key=AIzaSyCK3p73QxCZU_nghp1P_3eTOyZoHjhFyzI';
        $file_contents = file_get_contents($request);
        $json_decode = json_decode($file_contents);
        if (isset($json_decode->results[0])) {
            return $json_decode->results[0]->formatted_address;
        } else {
            return ' Error al consultar google maps api ';
        }
    }
    */

    public function getEstado0()
    {
        //0: pendiente de evaluacion, 1: NC, 2: Derivada, 3: Atendida, 4: Cerrada
        $estado = "";
        switch ($this->estado) {
            case Sds_800_llamada::ESTADO_PENDIENTE:
                $estado = "Pendiente de evaluación";
                break;
            case Sds_800_llamada::ESTADO_NC:
                $estado = "No Corresponde";
                break;
            case Sds_800_llamada::ESTADO_DERIVADA:
                $estado = "Derivada";
                break;
            case Sds_800_llamada::ESTADO_ATENDIDA:
                $estado = "Atendida";
                break;
            case Sds_800_llamada::ESTADO_CERRADA:
                $estado = "Cerrada";
                break;
            case Sds_800_llamada::ESTADO_DESPEJADA:
                $estado = "Situación Despejada";
                break;
        }
        return $estado;
    }

    public function getArea_interviniente0()
    {
        $interviniente =  "";
        if (!is_null($this->area_interviniente)) {
            switch ($this->area_interviniente) {
                case Sds_800_llamada::AREAINTERVINIENTE_ADMISION:
                    $interviniente =  Sds_800_llamada::DESCRIPCION_AREAINTERVINIENTE_ADMISION;
                    break;
                case Sds_800_llamada::AREAINTERVINIENTE_GUARDIA:
                    $interviniente = Sds_800_llamada::DESCRIPCION_AREAINTERVINIENTE_GUARDIA;
                    break;
                case Sds_800_llamada::AREAINTERVINIENTE_POSTGUARDIA:
                    $interviniente = Sds_800_llamada::DESCRIPCION_AREAINTERVINIENTE_POSTGUARDIA;
                    break;
                case Sds_800_llamada::AREAINTERVINIENTE_CALLE:
                    $interviniente =  Sds_800_llamada::DESCRIPCION_AREAINTERVINIENTE_CALLE;
                    break;
                default:
                    $interviniente =  "";
                    break;
            }
        }
        return $interviniente;
    }

    public static function estaPendiente($idllamada)
    {
        $estaAtendida = false;
        $model_llamada = Sds_800_llamada::findOne($idllamada);
        if ($model_llamada) {
            $estaAtendida = $model_llamada->estado == Sds_800_llamada::ESTADO_PENDIENTE;
        }
        return $estaAtendida;
    }


    public static function estaAtendida($idllamada)
    {
        $estaAtendida = false;
        $model_llamada = Sds_800_llamada::findOne($idllamada);
        if ($model_llamada) {
            $estaAtendida =  $model_llamada->estado == Sds_800_llamada::ESTADO_ATENDIDA;
        }
        return $estaAtendida;
    }


    public static function intervencionActiva($idllamada)
    {
        $model_llamada = Sds_800_llamada::findOne($idllamada);
        $idintervencion = null;
        if ($model_llamada) {
            $model_intervencion = Sds_vio_intervencion::findOne(['idllamada' => $idllamada, 'deleted_at' => null]);
            $idintervencion =   $model_intervencion ? $model_intervencion->idintervencion  : null;
        }
        return $idintervencion;
    }
}
