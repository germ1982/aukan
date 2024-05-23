<?php
namespace app\models;
use Yii;
/**
 * This is the model class for table "sds_800_atencion".
 *
 * @property int $idllamada
 * @property int|null $idpersona
 * @property int $beneficio '0: No, 1: Si, 2: Sin Datos'
 * @property string|null $causa_situacion
 * @property int|null $edad
 * @property int $sabe_leer '0: No, 1: Si, 2: Sin Datos'
 * @property int $nivel_estudio '0: Sin Datos, 1: Primario Incompleto , 2: Primario Completo , 3: Secundario Incompleto , 4: Secundario Completo , 5: Terciario/Otro Incompleto , 6: Terciario/Otro Completo'
 * @property int $trabajo '0: No, 1: Formal, 2: Informal'
 * @property string|null $trabajo_detalle
 * @property int $antiguedad '0: Sin Datos, 1: menos de 1 años, 2: entre 1 y 5 años, 3: mas de 5 años'
 * @property int $ubicacion_anterior '0: Sin Datos, 1: en la casa de un familiar, 2:  alquilaba por cuenta propia , 3: le alquilaba algún efector del estado, 4:  Otro'
 * @property string|null $ubicacion_anterior_detalle
 * @property int $atencion_anterior '0: No, 1: Si, 2: Sin Datos'
 * @property string|null $atencion_anterior_institucion
 * @property string|null $atencion_anterior_profesional
 * @property int $asistencia_estado '0: No, 1: Si, 2: Sin Datos'
 * @property string|null $asistencia_estado_detalle
 * @property int $familia '0: Sin Datos, 1: Si tiene y con vínculos adecuados, 2:  Si tiene y sin vínculos adecuados, 3:  No tiene'
 * @property int $sentimiento '0: Sin Datos, 1: Bien, 2: Mal, 3: Es una eleccion de vida'
 * @property int $orientado '0: No, 1: Si, 2: Sin Datos'
 * @property int $evaluacion_funcional '0: Sin Datos, 1: TOTALMENTE DEPENDIENTE, 2:  DEPENDIENTE EN ALGUNAS O VARIAS ACTIVIDADES, 3:  INDEPENDIENTE , 4: OTRO'
 * @property string|null $evaluacion_funcional_detalle
 * @property int $intoxicado '0: No, 1: Si, 2: Sin Datos'
 * @property int $alucinaciones '0: No, 1: Si, 2: Sin Datos'
 * @property int $violentado '0: No, 1: Si, 2: Sin Datos'
 * @property int $expresar '0: No, 1: Si, 2: Sin Datos'
 * @property int $tratamiento '0: No, 1: Si, 2: Sin Datos'
 * @property string|null $tratamiento_institucion
 * @property string|null $tratamiento_profesional
 * @property string|null $observaciones
 * @property string|null $persona_datos
 * @property int $motivo_abandono  
 * @property string $fecha_hora
 * @property int $idusuario
 * @property int $tipo_ayuda
 * @property int $expectativa_corto_plazo
 * @property int $consumo_problematico
 * @property int $capacidad_limitada
 * @property int $r_situacion_laboral
 * @property int $aportes_economicos
 * @property int $oficio
 * @property int $situacion_salud
 * @property Sds_800_llamada $idllamada0
 * @property Sds_800_persona $idpersona0
 * @property Mds_seg_usuario $idusuario0
 * @property Sds_com_configuracion $tipo_ayuda0
 * @property Sds_com_configuracion $expectativa_corto_plazo0
 * @property Sds_com_configuracion $motivo_abandono0
 * @property Sds_com_configuracion $consumo_problematico0
 * @property Sds_com_configuracion $capacidad_limitada0
 * @property Sds_com_configuracion $r_situacion_laboral0
 * @property Sds_com_configuracion $aportes_economicos0
 * @property Sds_com_configuracion $situacion_salud0
 * 
 * 
 * 
 * 
 * 
 */
class Sds_800_atencion extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $dni;
    public $nombre;
    public $apellido;
    public $fecha_nacimiento;
    public $nacionalidad;
    public $sexo;
    public $localidad;
    public $telefono;
    public $sin_dni;
    public $temp_archivo_salud;
    public $borrar_adjunto;
    public $generoautopercibido;
    public $idlocalidadoriundo;
    public $provinciaoriundo;
    public $provincia;

    const RESPUESTA_SIN_DATOS = 0;
    const RESPUESTA_SI = 1;
    const RESPUESTA_NO = 2;

    const TRABAJO_NO = 0;
    const TRABAJO_FORMAL = 1;
    const TRABAJO_INFORMAL = 2;

    const ESTUDIO_SIN_DATOS = 0; //'Sin Datos'
    const ESTUDIO_PRIMARIO_INCOMPLETO = 1; //'Primario Incompleto'
    const ESTUDIO_PRIMARIO_COMPLETO = 2; //'Primario Completo'
    const ESTUDIO_SECUNDARIO_INCOMPLETO = 3; //'Secundario Incompleto'
    const ESTUDIO_SECUNDARIO_COMPLETO = 4; //'Secundario Completo'
    const ESTUDIO_TERCIARIO_OTRO_INCOMPLETO = 5; //'Terciario/Otro Incompleto'
    const ESTUDIO_TERCIARIO_OTRO_COMPLETO = 6; //'Terciario/Otro Completo'

    const ANTIGUEDAD_SIN_DATOS = 0; // 'Sin Datos'
    const ANTIGUEDAD_MENOS_1 = 1; // 'menos de 1 años'
    const ANTIGUEDAD_1_5 = 2; // 'entre 1 y 5 años'
    const ANTIGUEDAD_MAS_5 = 3; // 'mas de 5 años'

    const UBICACION_SIN_DATOS =0; //'Sin Datos'
    const UBICACION_FAMILIAR = 1; //'En la casa de un familiar'
    const UBICACION_CUENTA_PROPIA = 2; //'Alquilaba por cuenta propia'
    const UBICACION_ESTADO = 3; //'Le alquilaba algún efector del estado '
    const UBICACION_OTRO = 4; //'Otro'

    const FAMILIA_SIN_DATOS = 0; // 'Sin Datos'
    const FAMILIA_TIENE_VINCULO = 1; // 'Si tiene y con vínculos adecuados'
    const FAMILIA_TIENE_SIN_VINCULO = 2; // 'Si tiene y sin vínculos adecuados'
    const FAMILIA_NO_TIENE = 3; // 'No tiene'

    CONST SENTIMIENTO_SIN_DATOS = 0; //'Sin Datos'
    CONST SENTIMIENTO_BIEN = 1; //'Bien'
    CONST SENTIMIENTO_MAL = 2; //'Mal'
    CONST SENTIMIENTO_ELECCION = 3; //'Es una eleccion de vida'

    const FUNCIONAL_SIN_DATOS = 0; // 'Sin Datos'
    const FUNCIONAL_DEPENDIENTE = 1; // 'Totalmente Dependiente'
    const FUNCIONAL_CASI_DEPENDIENTE = 2; // 'Dependiente en Algunas o Varias Actividades'
    const FUNCIONAL_INDEPENDIENTE = 3; // 'Independiente'
    const FUNCIONAL_OTRO = 4; // 'Otro'

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_800_atencion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idlocalidadoriundo','idpersona','nombre','apellido','fecha_nacimiento','nacionalidad','sexo', 'localidad'], 'required'],
            [['motivo_abandono','expectativa_corto_plazo','idllamada', 'beneficio', 'sabe_leer', 'nivel_estudio', 'trabajo', 'antiguedad', 'ubicacion_anterior', 'atencion_anterior', 'asistencia_estado', 'familia', 'sentimiento', 'orientado', 'evaluacion_funcional', 'intoxicado', 'alucinaciones', 'violentado', 'expresar', 'tratamiento', 'fecha_hora', 'idusuario'], 'required'],
            [['situacion_salud','aportes_economicos','r_situacion_laboral','capacidad_limitada','consumo_problematico','tipo_ayuda','provincia','provinciaoriundo','idlocalidadoriundo','generoautopercibido','idllamada', 'idpersona', 'beneficio', 'edad', 'sabe_leer', 'nivel_estudio', 'trabajo', 'antiguedad', 'ubicacion_anterior', 'atencion_anterior', 'asistencia_estado', 'familia', 'sentimiento', 'orientado', 'evaluacion_funcional', 'intoxicado', 'alucinaciones', 'violentado', 'expresar', 'tratamiento', 'idusuario','sin_dni'], 'integer'],
            [['oficio','causa_situacion', 'trabajo_detalle', 'ubicacion_anterior_detalle', 'asistencia_estado_detalle', 'evaluacion_funcional_detalle', 'observaciones', 'persona_datos'], 'string'],
            [['generoautopercibido','idlocalidadoriundo','fecha_hora', 'fdesde', 'fhasta', 'dni','nombre','apellido','fecha_nacimiento','nacionalidad','sexo', 'localidad','sin_dni','telefono','borrar_adjunto'], 'safe'],
            [['atencion_anterior_institucion', 'atencion_anterior_profesional', 'tratamiento_institucion', 'tratamiento_profesional','telefono'], 'string', 'max' => 100],
            [['idllamada'], 'unique'],
            [['temp_archivo_salud'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],
            [['idllamada'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_llamada::class, 'targetAttribute' => ['idllamada' => 'idllamada']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['tipo_ayuda'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo_ayuda' => 'idconfiguracion']],
            [['consumo_problematico'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['consumo_problematico' => 'idconfiguracion']],
            [['capacidad_limitada'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['capacidad_limitada' => 'idconfiguracion']],
            [['r_situacion_laboral'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['r_situacion_laboral' => 'idconfiguracion']],
            [['situacion_salud'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['situacion_salud' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idllamada' => 'Id Llamada',
            'idpersona' => 'Persona Afectada',
            'beneficio' => '¿Posee Beneficio y/u Obra Social?',
            'causa_situacion' => '¿Por qué se encuentra en situación de calle?',
            'edad' => 'Edad que dice tener',
            'sabe_leer' => '¿Sabe Leer?',
            'nivel_estudio' => 'Nivel de Estudio',
            'trabajo' => '¿Tiene Trabajo?',
            'trabajo_detalle' => 'Detalle',
            'antiguedad' => 'Años en Situación de Calle',
            'ubicacion_anterior' => 'Último lugar de pernocte',
            'ubicacion_anterior_detalle' => 'Detalle',
            'atencion_anterior' => 'Atención Anterior',
            'atencion_anterior_institucion' => 'Institucion',
            'atencion_anterior_profesional' => 'Profesional',
            'asistencia_estado' => 'Recorrido Institucional',
            'asistencia_estado_detalle' => 'Detalle',
            'familia' => 'Red social y familiar',
            'sentimiento' => '¿Cómo se siente?',
            'orientado' => '¿Se encuentra orientado?',
            'evaluacion_funcional' => 'Evaluación Funcional',
            'evaluacion_funcional_detalle' => 'Detalle',
            'intoxicado' => 'Intoxicado',
            'alucinaciones' => 'Alucinaciones',
            'violentado' => 'Violentado',
            'expresar' => 'Se Puede Expresar',
            'tratamiento' => 'Se encuentra en Tratamiento',
            'tratamiento_institucion' => 'Institucion',
            'tratamiento_profesional' => 'Profesional',
            'observaciones' => 'Observaciones',
            'persona_datos' => 'Persona Datos',
            'fecha_hora' => 'Fecha Hora',
            'idusuario' => 'Atendió',
            'dni' => 'DNI',
            'nombre_completo' => 'Nombre',
            'persona_afectada' => 'Persona Afectada',
            'nombre'=>'Nombre',
            'apellido'=>'Apellido',
            'fecha_nacimiento'=>'Fecha Nacimiento',
            'nacionalidad'=>'Nacionalidad',
            'sexo'=>'Sexo',
            'sin_dni'=>'Sin DNI',
            'telefono'=> 'Teléfono',
            'temp_archivo_salud'=>'Seleccionar un Archivo (imagen o PDF)',
            'generoautopercibido' => 'Género Autopercibido',
            'idlocalidadoriundo' => 'Localidad Oriundo',
            'provinciaoriundo' => 'Provincia Oriundo',
            'provincia' => 'Provincia',
            'tipo_ayuda'=> 'Tipo de ayuda que solicita',
            'expectativa_corto_plazo'=> 'Expectativa a corto plazo',
            'motivo_abandono'=> 'Motivo de abandono de alojamiento previo',
            'consumo_problematico'=> 'Consumo Problemático',
            'capacidad_limitada'=> 'Capacidad Limitada',
            'r_situacion_laboral'=> 'Situación Laboral',
            'aportes_economicos'=> 'Aportes Económicos',
            'oficio'=> 'Oficio',
            'situacion_salud'=> 'Situación Salud',
            
        ];
    }

    /**
     * Gets query for [[Idllamada0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdllamada0()
    {
        return $this->hasOne(Sds_800_llamada::class, ['idllamada' => 'idllamada']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona0()
    {
        return $this->hasOne(Sds_800_persona::class, ['idpersona' => 'idpersona']);
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
}
