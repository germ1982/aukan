<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ris_persona".
 *
 * @property int $idpersonarisneu
 * @property int $idpersona
 * @property int $idrisneu
 * @property int $parentezco
 * @property int $situacion_conyugal
 * @property int $escolaridad
 * @property int $ultimo_ano_aprobado
 * @property int $tipo_establecimiento_educativo
 * @property int $vinculo_contractual
 * @property int $trabajo
 * @property int $trabajo_tipo
 * @property int $trabajo_horas
 * @property int $trabajo_dias
 * @property int $discapacidad
 * @property int $cobertura_salud
 * @property int $cud
 * @property int $trabajo_porque
 * @property int $ingreso
 * @property int $condicion_hacinamiento
 * @property int $pueblo_originario_pertenece
 * @property int $pueblo_originario_reconoce
 * @property int $pueblo_originario
 * @property int $activo
 *
 * @property SdsComConfiguracion $coberturaSalud
 * @property SdsComConfiguracion $discapacidad0
 * @property SdsComConfiguracion $escolaridad0
 * @property SdsComConfiguracion $parentezco0
 * @property SdsComPersona $idpersona0
 * @property SdsRisRisneu $idrisneu0
 * @property SdsComConfiguracion $situacionConyugal
 * @property SdsComConfiguracion $tipoEstablecimientoEducativo
 * @property SdsComConfiguracion $trabajo0
 * @property SdsComConfiguracion $trabajoPorque
 * @property SdsComConfiguracion $trabajoTipo
 * @property SdsComConfiguracion $ultimoAnoAprobado
 * @property SdsComConfiguracion $vinculoContractual
 * @property SdsRisPersonaEnfermedad[] $sdsRisPersonaEnfermedads
 */
class Sds_ris_persona extends \yii\db\ActiveRecord
{
    public $apellido; //Para grilla
    public $nombre; //Para grilla
    public $documento; //para predeterminar dni que viene desde risneu
    public $doc_tipo_num; //Concatenación de tipo y número para grilla de familia
    public $enfermedades; //Arreglo con ids de configuraciones correspondientes a enfermedades. A persistir en tabla intermedia de sds_ris_persona_enfermedad
    public $discapacidades; //Arreglo con ids de configuraciones correspondientes a discapacidades. A persistir en tabla intermedia de sds_ris_persona_discapacidad
    public $sustancias; //Arreglo con ids de configuraciones correspondientes a sustancias. A persistir en tabla intermedia de sds_ris_persona_sustancia
    const ID_PARENTESCO_JEFE = 60;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ris_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'idpersona', 'idrisneu', 'parentezco', 'situacion_conyugal', 'escolaridad', 'ultimo_ano_aprobado',
                'tipo_establecimiento_educativo', 'vinculo_contractual', 'trabajo', 'trabajo_tipo', 'trabajo_horas',
                'trabajo_dias', 'cobertura_salud', 'cud', 'trabajo_porque', 'ingreso'
            ], 'required'],
            [[
                'idpersona', 'idrisneu', 'parentezco', 'situacion_conyugal', 'escolaridad', 'ultimo_ano_aprobado',
                'tipo_establecimiento_educativo', 'vinculo_contractual', 'trabajo', 'trabajo_tipo', 'trabajo_horas',
                'trabajo_dias', 'discapacidad', 'cobertura_salud', 'cud', 'trabajo_porque', 'ingreso', 'condicion_hacinamiento',
                'pueblo_originario_pertenece', 'pueblo_originario_reconoce', 'pueblo_originario', 'consume_sustancia', 'activo',
                'idusuario_carga', 'idusuario_actualiza', 'idusuario_borra'
            ], 'integer'],
            [['apellido', 'nombre', 'documento', 'enfermedades', 'discapacidades', 'sustancias', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['cobertura_salud'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['cobertura_salud' => 'idconfiguracion']],
            [['discapacidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['discapacidad' => 'idconfiguracion']],
            [['escolaridad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['escolaridad' => 'idconfiguracion']],
            [['parentezco'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['parentezco' => 'idconfiguracion']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idrisneu'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ris_risneu::class, 'targetAttribute' => ['idrisneu' => 'idrisneu']],
            [['situacion_conyugal'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['situacion_conyugal' => 'idconfiguracion']],
            [['tipo_establecimiento_educativo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo_establecimiento_educativo' => 'idconfiguracion']],
            [['trabajo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['trabajo' => 'idconfiguracion']],
            [['trabajo_porque'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['trabajo_porque' => 'idconfiguracion']],
            [['trabajo_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['trabajo_tipo' => 'idconfiguracion']],
            [['ultimo_ano_aprobado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['ultimo_ano_aprobado' => 'idconfiguracion']],
            [['vinculo_contractual'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vinculo_contractual' => 'idconfiguracion']],
            [['condicion_hacinamiento'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['condicion_hacinamiento' => 'idconfiguracion']],
            [['pueblo_originario'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['pueblo_originario' => 'idconfiguracion']],
            [['observaciones'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersonarisneu' => 'Idpersonarisneu',
            'idpersona' => 'Idpersona',
            'idrisneu' => 'Idrisneu',
            'parentezco' => 'Parentesco',
            'situacion_conyugal' => 'Situación Conyugal',
            'escolaridad' => 'Escolaridad',
            'ultimo_ano_aprobado' => 'Ultimo Año Aprobado',
            'tipo_establecimiento_educativo' => 'Tipo Establecimiento Educativo',
            'vinculo_contractual' => 'Vinculo Contractual',
            'trabajo' => 'Trabajo',
            'trabajo_tipo' => 'Trabajo Tipo',
            'trabajo_horas' => 'Trabajo Horas',
            'trabajo_dias' => 'Trabajo Dias',
            'discapacidad' => 'Discapacidad',
            'cobertura_salud' => 'Cobertura Salud',
            'cud' => 'CUD',
            'trabajo_porque' => 'Trabajo Porque',
            'ingreso' => 'Ingreso Mensual',
            'Condición de hacinamiento' => 'condicion_hacinamiento',
            'Pertenencia o Descendencia de pueblos indígenas u originarios' => 'pueblo_originario_pertenece',
            '¿Se reconoce?' => 'pueblo_originario_reconoce',
            'Pueblo indígena u originario' => 'pueblo_originario',
            'Indique consumos problemáticos' => 'consume_sustancia',
            'activo' => 'Activo',
            'observaciones' => 'Observaciones'
        ];
    }

    /**
     * Gets query for [[CoberturaSalud]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCoberturaSalud()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'cobertura_salud']);
    }

    /**
     * Gets query for [[Discapacidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiscapacidad0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'discapacidad']);
    }

    /**
     * Gets query for [[Escolaridad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEscolaridad0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'escolaridad']);
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
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Idrisneu0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdrisneu0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idrisneu' => 'idrisneu']);
    }

    /**
     * Gets query for [[SituacionConyugal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSituacionConyugal()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'situacion_conyugal']);
    }

    /**
     * Gets query for [[TipoEstablecimientoEducativo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoEstablecimientoEducativo()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo_establecimiento_educativo']);
    }

    /**
     * Gets query for [[Trabajo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajo0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'trabajo']);
    }

    /**
     * Gets query for [[TrabajoPorque]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajoPorque()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'trabajo_porque']);
    }

    /**
     * Gets query for [[TrabajoTipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajoTipo()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'trabajo_tipo']);
    }

    /**
     * Gets query for [[UltimoAnoAprobado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUltimoAnoAprobado()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'ultimo_ano_aprobado']);
    }

    /**
     * Gets query for [[VinculoContractual]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVinculoContractual()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vinculo_contractual']);
    }

    /**
     * Gets query for [[CondicionHacinamiento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCondicionHacinamiento()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'condicion_hacinamiento']);
    }

    /**
     * Gets query for [[PuebloOriginario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPuebloOriginario()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'pueblo_originario']);
    }

    /**
     * Gets query for [[SdsRisPersonaEnfermedads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRisPersonaEnfermedads()
    {
        return $this->hasMany(Sds_com_configuracion::class, ['idpersonarisneu' => 'idpersonarisneu']);
    }

    public function getFirstPersonaByIdRisneu($idRisneu)
    {
        return $this->find()->where("idrisneu = $idRisneu AND activo = 1")->orderBy(['idpersonarisneu' => SORT_ASC])->one();
    }

    public function getJefeByIdRisneu($idRisneu)
    {
        $idParentescoJefe = self::ID_PARENTESCO_JEFE;
        return $this->find()->where("idrisneu = $idRisneu AND activo = 1 AND parentezco = $idParentescoJefe")->one();
    }
}
