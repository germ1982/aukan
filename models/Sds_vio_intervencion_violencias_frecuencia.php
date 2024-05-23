<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_vio_intervencion_violencias_frecuencia".
 *
 * @property int $idviolenciafrecuencia
 * @property int|null $idintervencion
 * @property int|null $idtipoviolencia
 * @property int|null $idfrecuencia
 * @property int|null $idocurrencia
 * @property int|null $vigencia_actualidad
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property SdsComConfiguracion $idfrecuencia0
 * @property SdsVioIntervencion $idintervencion0
 * @property SdsComConfiguracionTipo $idtipoviolencia0
 * @property SdsComConfiguracion $idocurrencia0
 */
class Sds_vio_intervencion_violencias_frecuencia extends \yii\db\ActiveRecord
{
    public $tipoFisica = [];
    public $tipoPsicologica = [];
    public $tipoSexual = [];
    public $tipoEconomicaPatrimonial = [];
    public $tipoSimbolica = [];
    public $tipoAmbiental = [];
    public $tipoNegligenciaAbandono = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_vio_intervencion_violencias_frecuencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idintervencion', 'idtipoviolencia', 'idusuario_carga', 'created_at'], 'required'],
            [['idintervencion', 'idtipoviolencia', 'idfrecuencia', 'idocurrencia', 'vigencia_actualidad', 'idusuario_carga'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['idfrecuencia'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idfrecuencia' => 'idconfiguracion']],
            [['idintervencion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_vio_intervencion::class, 'targetAttribute' => ['idintervencion' => 'idintervencion']],
            [['idtipoviolencia'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion_tipo::class, 'targetAttribute' => ['idtipoviolencia' => 'idconfiguraciontipo']],
            [['idocurrencia'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idocurrencia' => 'idconfiguracion']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idviolenciafrecuencia' => 'Idviolenciafrecuencia',
            'idintervencion' => 'Idintervencion',
            'idtipoviolencia' => 'Tipo de violencia',
            'idfrecuencia' => 'Frecuencia',
            'idocurrencia' => 'Ocurrencia',
            'vigencia_actualidad' => 'Vigencia Actualidad',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Idfrecuencia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdfrecuencia0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idfrecuencia']);
    }

    /**
     * Gets query for [[Idintervencion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdintervencion0()
    {
        return $this->hasOne(Sds_vio_intervencion::class, ['idintervencion' => 'idintervencion']);
    }

    /**
     * Gets query for [[Idtipoviolencia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtipoviolencia0()
    {
        return $this->hasOne(Sds_com_configuracion_tipo::class, ['idconfiguraciontipo' => 'idtipoviolencia']);
    }

    /**
     * Gets query for [[Idocurrencia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdocurrencia0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idocurrencia']);
    }

    /**
     * Gets query for [[usuarioCarga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }
}
