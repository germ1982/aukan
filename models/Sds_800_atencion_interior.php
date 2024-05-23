<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_800_atencion_interior".
 *
 * @property int $idllamada
 * @property int $idpersona
 * @property int $lugar_intervencion
 * @property string|null $lugar_especificacion
 * @property string|null $defensora
 * @property int $idpersona_referente
 * @property int $parentezco
 * @property string $plan_accion
 * @property string $fecha_intervencion
 * @property int $idusuario
 * @property string|null $archivo_adjunto
 *
 * @property Sds800Llamada $idllamada0
 * @property Sds800Persona $idpersona0
 * @property Sds800Persona $idpersonaReferente
 * @property SdsComConfiguracion $parentezco0
 * @property MdsSegUsuario $idusuario0
 */
class Sds_800_atencion_interior extends \yii\db\ActiveRecord
{

        //datos del encuestado
        public $dni;
        public $nombre;
        public $apellido;
        public $provincia;
        public $localidad;
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
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_800_atencion_interior';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idllamada', 'idpersona', 'lugar_intervencion', 'idpersona_referente', 'parentezco', 'plan_accion', 'fecha_intervencion', 'idusuario','localidad'], 'required'],
            [['idpersona_referente', 'nombre1', 'apellido1', 'fecha_nacimiento1', 'nacionalidad1', 'sexo1', 'localidad1'], 'required'],
            [['idllamada', 'idpersona', 'lugar_intervencion', 'idpersona_referente', 'parentezco', 'idusuario'], 'integer'],
            [['plan_accion', 'archivo_adjunto'], 'string'],
            [['fecha_intervencion', 'safe','dni', 'nombre', 'apellido', 'dni1', 'nombre1', 'apellido1', 'fecha_nacimiento1', 'nacionalidad1', 'sexo1', 'provincia1', 'localidad1', 'telefono1', 'domicilio1', 'telefono'], 'safe'],
            [['lugar_especificacion'], 'string', 'max' => 50],
            [['defensora'], 'string', 'max' => 100],
            [['idllamada'], 'unique'],
            [['temp_archivo_adjunto'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],

            [['idllamada'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_llamada::class, 'targetAttribute' => ['idllamada' => 'idllamada']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idpersona_referente'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_800_persona::class, 'targetAttribute' => ['idpersona_referente' => 'idpersona']],
            [['parentezco'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['parentezco' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
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
            'lugar_intervencion' => 'Lugar Intervencion',
            'lugar_especificacion' => 'Lugar Especificacion',
            'defensora' => 'Defensora',
            'idpersona_referente' => 'Idpersona Referente',
            'parentezco' => 'Parentesco',
            'plan_accion' => 'Plan de Acción',
            'fecha_intervencion' => 'Fecha Intervención',
            'idusuario' => 'Idusuario',
            'archivo_adjunto' => 'Archivo Adjunto',
            'temp_archivo_adjunto' => 'Seleccionar un Archivo (imagen o PDF)',
            'dni1' => 'DNI Referente',
            'nombre1' => 'Nombre',
            'apellido1' => 'Apellido',
            'fecha_nacimiento1' => 'Fecha de Nacimiento',
            'sexo1' => 'Género',
            'localidad1' => 'Localidad',
            'telefono1' => 'Teléfono',
            'domicilio1' => 'Domicilio',
            'nacionalidad1'=>'Nacionalidad',
            'sexo' => 'Género'
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
     * Gets query for [[IdpersonaReferente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersonaReferente()
    {
        return $this->hasOne(Sds_800_persona::class, ['idpersona' => 'idpersona_referente']);
    }

    /**
     * Gets query for [[Parentezco0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentezco0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'parentezco']);
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
