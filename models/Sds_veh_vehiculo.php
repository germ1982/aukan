<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_veh_vehiculo".
 *
 * @property int $idvehiculo
 * @property string $dominio
 * @property int $estado
 * @property int $modelo
 * @property int $tipo
 * @property int $anio
 * @property int $alquilado
 * @property string|null $detalle
 * @property int $idorganismo
 *
 * @property SdsVehHabilitacion[] $sdsVehHabilitacions
 * @property Sds_veh_mantenimiento[] $sds_veh_mantenimientos
 * @property SdsComConfiguracion $estado0
 * @property Sds_veh_modelo $modelo0
 * @property MdsOrgOrganismo $idorganismo0
 * @property SdsComConfiguracion $tipo0
 */
class Sds_veh_vehiculo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $marca;
    public $estado_descripcion;
    public $tipo_descripcion;
    public $modelo_descripcion;
    
    public static function tableName()
    {
        return 'sds_veh_vehiculo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dominio', 'estado', 'modelo', 'tipo', 'anio', 'idorganismo','marca'], 'required'],
            [['estado', 'modelo', 'tipo', 'anio', 'alquilado', 'idorganismo'], 'integer'],
            [['detalle', 'marca','estado_descripcion','tipo_descripcion','modelo_descripcion'], 'string'],
            [['estado_descripcion','tipo_descripcion','modelo_descripcion'], 'safe'],
            [['dominio'], 'string', 'max' => 12],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['estado' => 'idconfiguracion']],
            [['modelo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_veh_modelo::className(), 'targetAttribute' => ['modelo' => 'idmodelo']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['tipo' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idvehiculo' => 'Idvehiculo',
            'dominio' => 'Dominio',
            'estado' => 'Estado',
            'modelo_descripcion' => 'Modelo',
            'tipo_descripcion' => 'Tipo',
            'estado_descripcion' => 'Estado',
            'anio' => 'Año',
            'alquilado' => 'Alquilado',
            'detalle' => 'Detalle',
            'idorganismo' => 'Idorganismo',
            'marca' => 'Marca'
        ];
    }

    /**
     * Gets query for [[SdsVehHabilitacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsVehHabilitacions()
    {
        return $this->hasMany(Sds_veh_habilitacion::className(), ['idvehiculo' => 'idvehiculo']);
    }

    /**
     * Gets query for [[Sds_veh_mantenimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsVehMantenimientos()
    {
        return $this->hasMany(Sds_veh_mantenimiento::className(), ['idvehiculo' => 'idvehiculo']);
    }

    /**
     * Gets query for [[Estado0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstado0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'estado']);
    }

    /**
     * Gets query for [[Modelo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModelo0()
    {
        return $this->hasOne(Sds_veh_modelo::className(), ['idmodelo' => 'modelo']);
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::className(), ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[Tipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'tipo']);
    }
}
