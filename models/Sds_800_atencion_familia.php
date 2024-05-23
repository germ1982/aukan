<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_800_atencion_familia".
 *
 * @property int $idllamada
 * @property int $idpersona
 * @property int $lugar_intervencion '0:Comisaria, 1: Escuela, 2: Hospital, 3:Otros'
 * @property string|null $lugar_especificacion
 * @property string|null $defensora
 * @property int|null $edad
 * @property int $idpersona_referente
 * @property int $parentezco
 * @property string|null $alojado
 * @property string|null $hogar
 * @property string|null $dia_hora
 * @property string|null $operador
 * @property string|null $equipo_tecnico
 * @property int $sabe_leer '0:No, 1: Si, 2: Sin Datos'
 * @property int $nivel_estudio '0: Sin Datos, 1: Primario Incompleto , 2: Primario Completo , 3: Secundario Incompleto , 4: Secundario Completo'
 * @property string|null $establecimiento
 * @property int $trabaja '0:No, 1: Si, 2: Sin Datos'
 * @property string|null $tipo_trabajo
 * @property int $atendido '0:No, 1: Si, 2: Sin Datos'
 * @property string|null $institucion
 * @property string|null $nombre_profesionales
 * @property int $beneficio_social '0:No, 1: Si, 2: Sin Datos'
 * @property string|null $area_beneficio
 * @property int $centro_salud '0:No, 1: Si, 2: Sin Datos'
 * @property string|null $nombre_centro_salud
 * @property int $obra_social '0:No, 1: Si, 2: Sin Datos'
 * @property string|null $nombre_obra_social
 * @property int $tratamiento_medico '0:No, 1: Si, 2: Sin Datos'
 * @property string|null $tratamiento_institucion
 * @property int $orientado '0:No, 1: Si, 2: Sin Datos'
 * @property int $intoxicado '0:No, 1: Si, 2: Sin Datos'
 * @property int $violentado '0:No, 1: Si, 2: Sin Datos'
 * @property string|null $plan_accion
 * @property string|null $fecha_intervencion
 * @property int $idusuario
 *
 * @property Sds800Llamada $idllamada0
 * @property Sds800Persona $idpersona0
 * @property Sds800Persona $idpersonaReferente
 * @property MdsSegUsuario $idusuario0
 * @property SdsComConfiguracion $parentezco0
 */
class Sds_800_atencion_familia extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    //datos del NNA
    public $dni;
    public $nombre;
    public $apellido;
    public $localidad;
    public $provincia;
    public $telefono;

    //datos del referente afectivo
    public $dni1;
    public $nombre1;
    public $apellido1;
    public $fecha_nacimiento1;
    public $nacionalidad1;
    public $sexo1;
    public $provincia1;
    public $localidad1;
    public $telefono1;
    public $domicilio1;

    public $temp_archivo_adjunto;
    public $hora;
    public $borrar_adjunto;

    const RESPUESTA_SIN_DATOS = 0;
    const RESPUESTA_SI = 1;
    const RESPUESTA_NO = 2;

    const ESTUDIO_SIN_DATOS = 0; //'Sin Datos'
    const ESTUDIO_PRIMARIO_INCOMPLETO = 1; //'Primario Incompleto'
    const ESTUDIO_PRIMARIO_COMPLETO = 2; //'Primario Completo'
    const ESTUDIO_SECUNDARIO_INCOMPLETO = 3; //'Secundario Incompleto'
    const ESTUDIO_SECUNDARIO_COMPLETO = 4; //'Secundario Completo'

    const LUGAR_COMISARIA = 0;
    const LUGAR_ESCUELA = 1;
    const LUGAR_HOSPITAL = 2;
    const LUGAR_ADMISION = 3;
    const LUGAR_FAMILIA = 4;
    const LUGAR_OTROS = 5;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_800_atencion_familia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona', 'localidad'], 'required'],
            [['idpersona_referente', 'nombre1', 'apellido1', 'fecha_nacimiento1', 'nacionalidad1', 'sexo1', 'localidad1'], 'required'],
            [['idllamada', 'lugar_intervencion', 'idpersona_referente', 'parentezco', 'sabe_leer', 'nivel_estudio', 'trabaja', 'atendido', 'beneficio_social', 'centro_salud', 'obra_social', 'tratamiento_medico', 'orientado', 'intoxicado', 'violentado', 'idusuario'], 'required'],
            [['idllamada', 'idpersona', 'lugar_intervencion', 'edad', 'idpersona_referente', 'parentezco', 'sabe_leer', 'nivel_estudio', 'trabaja', 'atendido', 'beneficio_social', 'centro_salud', 'obra_social', 'tratamiento_medico', 'orientado', 'intoxicado', 'violentado', 'idusuario'], 'integer'],
            [['dia_hora', 'fecha_intervencion', 'fdesde', 'fhasta', 'dni', 'nombre', 'apellido', 'dni1', 'nombre1', 'apellido1', 'fecha_nacimiento1', 'nacionalidad1', 'sexo1', 'provincia1', 'localidad1', 'telefono1', 'domicilio1', 'hora', 'telefono', 'borrar_adjunto'], 'safe'],
            [['plan_accion'], 'string'],
            [['lugar_especificacion', 'nombre_centro_salud', 'nombre_obra_social'], 'string', 'max' => 50],
            [['defensora', 'operador', 'establecimiento', 'tipo_trabajo', 'institucion', 'tratamiento_institucion'], 'string', 'max' => 100],
            [['alojado', 'hogar', 'equipo_tecnico', 'nombre_profesionales', 'area_beneficio'], 'string', 'max' => 150],
            [['idllamada'], 'unique'],
            [['temp_archivo_adjunto'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],

            [['idllamada'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_llamada::class, 'targetAttribute' => ['idllamada' => 'idllamada']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idpersona_referente'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_persona::class, 'targetAttribute' => ['idpersona_referente' => 'idpersona']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['parentezco'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['parentezco' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idllamada' => 'Idllamada',
            'idpersona' => 'Idpersona',
            'lugar_intervencion' => 'Lugar Intervención',
            'lugar_especificacion' => 'Lugar Especificación',
            'defensora' => 'Defensora',
            'edad' => 'Edad',
            'idpersona_referente' => 'Idpersona Referente',
            'parentezco' => 'Parentesco',
            'alojado' => 'Alojado',
            'hogar' => 'Hogar',
            'dia_hora' => 'Dia Hora',
            'operador' => 'Operador',
            'equipo_tecnico' => 'Equipo Técnico',
            'sabe_leer' => '¿Sabe Leer?',
            'nivel_estudio' => 'Nivel Estudio',
            'establecimiento' => 'Establecimiento',
            'trabaja' => 'Trabaja',
            'tipo_trabajo' => 'Tipo Trabajo',
            'atendido' => 'Atendido',
            'institucion' => 'Institución',
            'nombre_profesionales' => 'Nombre Profesionales',
            'beneficio_social' => 'Beneficio Social',
            'area_beneficio' => 'Área Beneficio',
            'centro_salud' => 'Centro Salud',
            'nombre_centro_salud' => 'Nombre Centro Salud',
            'obra_social' => 'Obra Social',
            'nombre_obra_social' => 'Nombre Obra Social',
            'tratamiento_medico' => 'Tratamiento Médico',
            'tratamiento_institucion' => 'Tratamiento Institución',
            'orientado' => 'Orientado',
            'intoxicado' => 'Intoxicado',
            'violentado' => 'Violentado',
            'plan_accion' => 'Plan de Acción',
            'fecha_intervencion' => 'Fecha Intervención',
            'idusuario' => 'Idusuario',
            'temp_archivo_adjunto' => 'Seleccionar un Archivo (imagen o PDF)',
            'dni1' => 'DNI Referente',
            'nombre1' => 'Nombre',
            'apellido1' => 'Apellido',
            'fecha_nacimiento1' => 'Fecha de Nacimiento',
            'sexo1' => 'Género',
            'localidad1' => 'Localidad',
            'telefono1' => 'Teléfono',
            'domicilio1' => 'Domicilio',
            'nacionalidad1' => 'Nacionalidad',
            'sexo' => 'Género'
        ];
    }

    /**
     * Gets query for [[Idllamada0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdllamada()
    {
        return $this->hasOne(Sds_800_llamada::class, ['idllamada' => 'idllamada']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona()
    {
        return $this->hasOne(Sds_800_persona::class, ['idpersona' => 'idpersona']);
    }
    public function getIdpersona0()
    {
        return $this->hasOne(Sds_800_persona::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[IdpersonaReferente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersonaReferente()
    {
        return $this->hasOne(Sds_800_persona::class, ['idpersona' => 'idpersona_referente']);
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
     * Gets query for [[Parentezco0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentezco()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'parentezco']);
    }
}
