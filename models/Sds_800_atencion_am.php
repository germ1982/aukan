<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_800_atencion_am".
 *
 * @property int $idllamada
 * @property int $idpersona
 * @property int $idusuario
 * @property string|null $fecha_hora
 * @property int $telefono_referente
 * @property string $demanda
 * @property int $atencion_previa
 * @property string|null $institucion
 * @property string|null $profesionales
 * @property int $basura
 * @property int $cable
 * @property int $internet
 * @property int $familiares
 * @property int $sociales
 * @property string|null $sociales_detalle
 * @property int $emergente
 * @property string|null $emergente_detalle
 * @property int $psicologico
 * @property int $psiquiatrico
 * @property int $administra_dinero
 * @property string|null $detalle_dinero
 * @property int $plan
 * @property string|null $detalle_plan
 * @property int $recreacion
 * @property int $centro
 * @property int $orientado
 * @property int $dependiente
 * @property int $intoxicado
 * @property int $delirios
 * @property int $violentado
 * @property int $expresion
 * @property string|null $observaciones
 * @property string|null $archivo_seguridad
 * @property string|null $archivo_salud
 *
 * @property Sds800AmRecreacion[] $sds800AmRecreacions
 * @property MdsSegUsuario $idusuario0
 * @property Sds800Llamada $idllamada0
 * @property Sds800Persona $idpersona0
 */
class Sds_800_atencion_am extends \yii\db\ActiveRecord
{
    public $dni;
    public $nombre;
    public $apellido;
    public $telefono;
    public $localidad;
    
    public $temp_archivo_salud;    
    public $temp_archivo_seguridad;
    public $borrar_adjunto_salud;
    public $borrar_adjunto_seguridad;

    const RESPUESTA_SIN_DATOS = 0;
    const RESPUESTA_SI = 1;
    const RESPUESTA_NO = 2;

    const NO_TIENE = 0;
    const FAMILIAR_SIN_VINCULO = 1;
    const FAMILIAR_CON_VINCULO = 2;


    const SOCIAL_NO_TIENE = 0;
    const SOCIAL_VECINOS = 1;
    const SOCIAL_ALQUILER = 2;
    const SOCIAL_OSCPM = 3;
    const SOCIAL_IGLESIA = 4;
    const SOCIAL_OBRA_SOCIAL = 5;
    const SOCIAL_BARRIAL = 6;
    const SOCIAL_OTRA = 7;

    const RED_FAMILIAR = 0;
    const RED_SOCIAL = 1;
    const RED_OTRO = 2;

    const TOTALMENTE_DEPENDIENTE =0;
    const SEMI_DEPENDIENTE =1;
    const INDEPENDIENTE =2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_800_atencion_am';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idllamada', 'idpersona', 'idusuario', 'nombre', 'apellido', 'demanda', 'basura', 'cable', 'internet', 'familiares', 'sociales', 'emergente', 'psicologico', 'psiquiatrico', 'administra_dinero', 'plan', 'recreacion', 'centro', 'orientado', 'dependiente', 'intoxicado', 'delirios', 'violentado', 'expresion', 'localidad','atencion_previa'], 'required'],
            [['idllamada', 'idpersona', 'idusuario', 'atencion_previa', 'basura', 'cable', 'internet', 'familiares', 'sociales', 'emergente', 'psicologico', 'psiquiatrico', 'administra_dinero', 'plan', 'recreacion', 'centro', 'orientado', 'dependiente', 'intoxicado', 'delirios', 'violentado', 'expresion'], 'integer'],
            [['fecha_hora', 'dni', 'nombre', 'apellido', 'telefono', 'localidad','borrar_adjunto_salud','borrar_adjunto_seguridad'], 'safe'],
            [['demanda', 'observaciones', 'archivo_seguridad', 'archivo_salud', 'telefono_referente'], 'string'],
            [['institucion', 'profesionales'], 'string', 'max' => 100],
            [['sociales_detalle', 'emergente_detalle', 'detalle_dinero', 'detalle_plan'], 'string', 'max' => 150],
            [['idllamada'], 'unique'],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idllamada'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_llamada::class, 'targetAttribute' => ['idllamada' => 'idllamada']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
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
            'idusuario' => 'Idusuario',
            'fecha_hora' => 'Fecha Hora',
            'telefono_referente' => 'Telefono Referente',
            'demanda' => 'Demanda',
            'atencion_previa' => 'Atencion Previa',
            'institucion' => 'Institucion',
            'profesionales' => 'Profesionales',
            'basura' => 'Basura',
            'cable' => 'Cable',
            'internet' => 'Internet',
            'familiares' => 'Familiares',
            'sociales' => 'Sociales',
            'sociales_detalle' => 'Sociales Detalle',
            'emergente' => 'Emergente',
            'emergente_detalle' => 'Emergente Detalle',
            'psicologico' => 'Psicologico',
            'psiquiatrico' => 'Psiquiatrico',
            'administra_dinero' => 'Administra Dinero',
            'detalle_dinero' => 'Detalle Dinero',
            'plan' => 'Plan',
            'detalle_plan' => 'Detalle Plan',
            'recreacion' => 'Recreacion',
            'centro' => 'Centro',
            'orientado' => 'Orientado',
            'dependiente' => 'Dependiente',
            'intoxicado' => 'Intoxicado',
            'delirios' => 'Delirios',
            'violentado' => 'Violentado',
            'expresion' => 'Expresion',
            'observaciones' => 'Observaciones',
            'archivo_seguridad' => 'Archivo Seguridad',
            'archivo_salud' => 'Archivo Salud',
            'dni' => 'DNI'
        ];
    }

    /**
     * Gets query for [[Sds800AmRecreacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSds800AmRecreacions()
    {
        return $this->hasMany(Sds_800_am_recreacion::class, ['idatencionam' => 'idllamada']);
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
}
