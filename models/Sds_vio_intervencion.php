<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_vio_intervencion".
 *
 * @property int $idintervencion
 * @property int $idpersona
 * @property string $fecha
 * @property int $idusuario
 * @property int $ingreso
 * @property int $tipo
 * @property int|null $derivacion
 * @property int|null $denuncia
 * @property int|null $idrisneu
 * @property int|null $iddispositivo
 * @property string|null $juzgado
 * @property string|null $localidad_hecho
 * @property string $detalle
 * @property string|null $archivo_adjunto1
 * @property string|null $archivo_adjunto2
 *
 * @property SdsComConfiguracion $derivacion0
 * @property SdsVioPersona $idpersona0
 * @property SdsComPersona $persona0
 * @property SdsComConfiguracion $tipo0
 * @property MdsSegUsuario $idusuario0
 * @property SdsComConfiguracion $tipoviolencia0
 * @property MdsOrgDispositivo $iddispositivo0
 */
class Sds_vio_intervencion extends \yii\db\ActiveRecord
{
    public $nueva_persona;
    public $fdesde;
    public $fhasta;
    public $dni;
    public $nombrecompuesto;
    public $nombre;
    public $apellido;
    public $sexo;
    public $genero_autopercibido;
    public $telefono;
    public $domicilio; //no esta en safe
    public $provincia;
    public $localidad; //no esta en safe
    public $provincia_oriunda;
    public $localidad_oriunda;
    public $nacionalidad_origen;
    public $agresores;
    //Para filtrar las que son solo del sector del usuario logueado
    public $entidad;
    public $temp_archivo_adjunto1;
    public $temp_archivo_adjunto2;
    public $borrar_archivo_adjunto1;
    public $borrar_archivo_adjunto2;

    public $provincia_hecho;

    //0:codigoA, 1:codigoB, 2: Asesoramiento
    const TIPO_SITUACION_CODIGO_A = 0;
    const TIPO_SITUACION_CODIGO_B = 1;
    const TIPO_SITUACION_ASESORAMIENTO = 2;
    const TIPO_LISTA_ESPERA = 779;
    const ID_PROVINCIA_NEUQUEN = 58;

    const ID_ROL_VIO_JERARQUICO = 17;
    const ID_ROL_VIO_ADMINISTRACION = 18;
    const ID_ROL_VIO_PROFESIONAL = 19;

    // Parseo de SUR o OPTIC

    const ID_SUR_MODALIDAD_FAMILIAR = 4373;
    const ID_SUR_MODALIDAD_INSTITUCIONAL = 4374;
    const ID_SUR_MODALIDAD_LABORAL = 4375;
    const ID_SUR_MODALIDAD_LIBERTAD_REPRODUCTIVA = 4376;
    const ID_SUR_MODALIDAD_OBSTETRICA = 4377;
    const ID_SUR_MODALIDAD_MEDIATICA = 4378;
    const ID_SUR_MODALIDAD_SIN_ASIGNAR = 1;
    const ID_OPTIC_MODALIDAD_FAMILIAR = 1;
    const ID_OPTIC_MODALIDAD_INSTITUCIONAL = 2;
    const ID_OPTIC_MODALIDAD_LABORAL = 3;
    const ID_OPTIC_MODALIDAD_LIBERTAD_REPRODUCTIVA = 4;
    const ID_OPTIC_MODALIDAD_OBSTETRICA = 5;
    const ID_OPTIC_MODALIDAD_MEDIATICA = 6;
    const ID_OPTIC_MODALIDAD_SIN_ASIGNAR = 1;
    const ID_OPTIC_TIPO_VIOLENCIA_FISICA = 25;
    const ID_OPTIC_TIPO_VIOLENCIA_PSICOLOGICA = 26;
    const ID_OPTIC_TIPO_VIOLENCIA_SEXUAL = 27;
    const ID_OPTIC_TIPO_VIOLENCIA_PATRIMONIAL = 28;
    const ID_OPTIC_TIPO_VIOLENCIA_SIMBOLICA = 29;
    const ID_OPTIC_TIPO_VIOLENCIA_NEGLIGENCIA_ABANDONO = 30;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_vio_intervencion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'fecha', 'idusuario', 'detalle', 'genero_autopercibido', 'tipo_modalidad',
                'dni', 'domicilio', 'provincia', 'localidad', 'telefono', 'tipo_situacion', 'ingreso', 'tipo', 'localidad_hecho', 'provincia_hecho', 'detalle_plataforma'
            ], 'required'],
            [[
                'idpersona', 'idusuario', 'ingreso', 'tipo', 'derivacion', 'denuncia', 'dni', 'tipo_situacion', 'localidad_hecho',
                'idllamada', 'idrisneu', 'iddispositivo', 'tipo_violencia', 'tipo_violencia_fisica', 'tipo_violencia_psicologica', 'tipo_violencia_sexual', 'tipo_violencia_economica_patrimonial', 'tipo_violencia_negligencia_abandono',
                'tipo_violencia_simbolica', 'tipo_violencia_ambiental', 'consumo_problematico', 'localidad_oriunda', 'nacionalidad_origen', 'idusuario_borra'
            ], 'integer'],
            [[
                'fdesde', 'fhasta', 'dni', 'nombrecompuesto', 'agresor_dni', 'agresor_nombre', 'agresor_apellido', 'referente_nombre', 'referente_telefono',
                'referente_vinculo', 'sexo', 'genero_autopercibido', 'nombre', 'apellido', 'telefono', 'entidad', 'abordaje_complementario', 'idllamada', 'idrisneu', 'iddispositivo',
                'borrar_archivo_adjunto1', 'tipo_modalidad', 'borrar_archivo_adjunto2', 'agresores', 'domicilio', 'localidad', 'provincia', 'provincia_oriunda', 'provincia_hecho', 'deleted_at', 'created_at'
            ], 'safe'],
            [['detalle', 'detalle_plataforma', 'abordaje_complementario', 'archivo_adjunto1', 'archivo_adjunto2', 'profesionales_intervinientes'], 'string'],
            [['juzgado'], 'string', 'max' => 100],
            [['derivacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['derivacion' => 'idconfiguracion']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_vio_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['localidad_hecho'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::class, 'targetAttribute' => ['localidad_hecho' => 'idlocalidad']],
            [['localidad_oriunda'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::class, 'targetAttribute' => ['localidad_oriunda' => 'idlocalidad']],
            [['temp_archivo_adjunto1'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],
            [['temp_archivo_adjunto2'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],
            [['idpersona'], 'required', 'message' => 'Debe ingresar un DNI y presionar sobre la lupa.'],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idintervencion' => 'Idintervencion',
            'idpersona' => 'Idpersona',
            'idrisneu' => '# RISNeu',
            'idllamada' => '# Llamada',
            'fecha' => 'Fecha',
            'idusuario' => 'Usuario',
            'ingreso' => 'Ingreso',
            'tipo' => 'Tipo de Intervención',
            'tipo_situacion' => 'Tipo de Situación',
            'derivacion' => 'Oficina Interviniente',
            'denuncia' => 'Denuncia',
            'juzgado' => 'Juzgado',
            'detalle' => 'Detalle',
            'detalle_plataforma' => 'Detalle para plataforma vulnerabilidad',
            'dni' => 'DNI',
            'nombrecompuesto' => 'Nombre',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'telefono' => 'Número de teléfono',
            'referente_nombre' => 'Nombre y Apellido',
            'referente_vinculo' => 'Vínculo',
            'referente_telefono' => 'Teléfono',
            'agresor_dni' => 'DNI',
            'agresor_nombre' => 'Nombre',
            'agresor_apellido' => 'Apellido',
            'ingreso' => 'Nuevo Ingreso',
            'abordaje_complementario' => 'Abordajes Complementarios',
            'localidad_hecho' => 'Localidad del Hecho',
            'sexo' => 'Sexo',
            'genero_autopercibido' => 'Género Autopercibido',
            'agresor_dato_denuncia' => '¿Denuncias policiales? - Datos',
            'agresor_dav' => '¿Asiste al DAV?',
            'agresor_dav_datos' => 'Fechas, datos, estado de asistencia al DAV',
            'agresor_consumo' => 'Información',
            'agresor_problematico' => '¿Posee consumo problemático?',
            'archivo_adjunto1' => 'Archivo Adjunto 1',
            'archivo_adjunto2' => 'Archivo Adjunto 2',
            'localidad_oriunda' => 'Localidad Oriunda',
            'temp_archivo_adjunto1' => 'Seleccionar un Archivo (imagen o PDF)',
            'temp_archivo_adjunto2' => 'Seleccionar un Archivo (imagen o PDF)',
            'provincia_hecho' => 'Provincia del hecho',
            'deleted_at' => 'Eliminado',
            'created_at' => 'Creado',
            'tipo_modalidad' => 'Tipo de modalidad',
        ];
    }

    /**
     * Gets query for [[Derivacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDerivacion0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'derivacion']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */


    public function getIdpersona0()
    {
        return $this->hasOne(Sds_vio_persona::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Persona0]].
     *
     * @return \yii\db\ActiveQuery
     */


    public function getPersona0()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
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

    /**
     * Gets query for [[Tipoviolencia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoviolencia0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo_violencia']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[Localidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad0()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'localidad']);
    }

    /**
     * Gets query for [[LocalidadHecho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidadHecho()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'localidad_hecho']);
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

    /**
     * Gets query for [[Modalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModalidad0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo_modalidad']);
    }

    /**
     * Gets query for [[Modalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLlamada0()
    {
        return $this->hasOne(Sds_800_llamada::class, ['idllamada' => 'idllamada']);
    }



    public static function getExtension($file)
    {
        $array = explode(".", $file);
        $extension = end($array);
        $extImagenes = array('jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp');
        if (in_array($extension, $extImagenes)) {
            return 'image';
        } else {
            return $extension;
        }
    }

    public static function estaAtendida($idintervencion)
    {
        $estaAtendida = false;
        $model_intervencion = Sds_vio_intervencion::findOne($idintervencion);
        if($model_intervencion){
            $estado = $model_intervencion->idllamada ? $model_intervencion->llamada0->estado : null;
            $estaAtendida = $estado ? $estado == Sds_800_llamada::ESTADO_ATENDIDA : true;
        }
        return $estaAtendida;
    }

    public static function hasRolViolencia()
    {
        $hasRolViolencia = Mds_seg_usuario_rol::hasRol(Sds_vio_intervencion::ID_ROL_VIO_JERARQUICO)
            || Mds_seg_usuario_rol::hasRol(Sds_vio_intervencion::ID_ROL_VIO_ADMINISTRACION)
            || Mds_seg_usuario_rol::hasRol(Sds_vio_intervencion::ID_ROL_VIO_PROFESIONAL)
            || Mds_seg_usuario_rol::hasRol(Sds_800_llamada::ID_ROL_INTERVENCIONES_ADMINISTRADOR_GENERAL);

        return $hasRolViolencia;
    }

    /**
     * Mapeadores con Plataforma Vulnerabilidad Optic
     */

    public static function opticMapCodigoSituacion($id){
        $response = "";
        switch ($id) {
            case Sds_vio_intervencion::TIPO_SITUACION_CODIGO_A:
                $response = 'Código A';
                break;
            case Sds_vio_intervencion::TIPO_SITUACION_CODIGO_B:
                $response = 'Código B';
                break;
            case Sds_vio_intervencion::TIPO_SITUACION_ASESORAMIENTO:
                $response = 'Asesoramiento';
                break;
        }
        return $response;
    }

    public static function opticMapSexoPersona($id){
        switch ($id) {
            case 81:
                $response = "F";
                break;
            case 82:
                $response = "M";
                break;
            default:
                $response = null;
        }
        return $response;
    }

    public static function opticMapModalidad($id){
        $response = null;
        switch ($id) {
            case Sds_vio_intervencion::ID_SUR_MODALIDAD_FAMILIAR:
                $response = Sds_vio_intervencion::ID_OPTIC_MODALIDAD_FAMILIAR;
                break;
            case Sds_vio_intervencion::ID_SUR_MODALIDAD_INSTITUCIONAL:
                $response = Sds_vio_intervencion::ID_OPTIC_MODALIDAD_INSTITUCIONAL;
                break;
            case Sds_vio_intervencion::ID_SUR_MODALIDAD_LABORAL:
                $response = Sds_vio_intervencion::ID_OPTIC_MODALIDAD_LABORAL;
                break;
            case Sds_vio_intervencion::ID_SUR_MODALIDAD_LIBERTAD_REPRODUCTIVA:
                $response = Sds_vio_intervencion::ID_OPTIC_MODALIDAD_LIBERTAD_REPRODUCTIVA;
                break;
            case Sds_vio_intervencion::ID_SUR_MODALIDAD_OBSTETRICA:
                $response = Sds_vio_intervencion::ID_OPTIC_MODALIDAD_OBSTETRICA;
                break;
            case Sds_vio_intervencion::ID_SUR_MODALIDAD_MEDIATICA:
                $response = Sds_vio_intervencion::ID_OPTIC_MODALIDAD_MEDIATICA;
                break;
            default:
                $response = Sds_vio_intervencion::ID_OPTIC_MODALIDAD_FAMILIAR;
                break;
        }
        return $response;
    }
     
}
