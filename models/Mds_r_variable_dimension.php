<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_r_variable_dimension".
 *
 * @property int $idvardimension
 * @property int $idplanilla
 * @property int $idvariable
 * @property int $origen
 * @property int $iddimension
 * @property string $fecha_carga
 * @property string $fecha_actualizacion
 * @property int $mapear
 * @property int $tipomapa
 * @property string $detalle
 * @property string $observacion
 * @property int $activo 
 *
 * @property Mds_r_diagnostico[] $Mds_r_diagnosticos
 * @property Sds_com_configuracion_tipo $iddimension0
 * @property Mds_r_planilla $idplanilla0
 * @property Sds_com_configuracion $idvariable0
 * @property Sds_com_configuracion $origen0
 * @property Sds_com_configuracion $tipomapa0
 */
class Mds_r_variable_dimension extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const ORIGEN_LOCALIDADES = 4092;
    const ORIGEN_DISPOSITIVO = 4093;
    public $id_plantilla;
    public $id_variable_dim;
    public $id_iddimension_var;
    public static function tableName()
    {
        return 'mds_r_variable_dimension';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idplanilla', 'idvariable', 'origen', 'iddimension', 'mapear', 'tipomapa','activo'], 'integer'],
            [['fecha_carga', 'fecha_actualizacion','iddimension'], 'required'],
            [['detalle', 'observacion'], 'string'],
            [['detalle', 'observacion','fecha_carga', 'fecha_actualizacion','origen','id_plantilla','id_variable_dim','id_iddimension_var','idplanilla','activo'], 'safe'],
            [['iddimension'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion_tipo::className(), 'targetAttribute' => ['iddimension' => 'idconfiguraciontipo']],
            [['idplanilla'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_r_planilla::className(), 'targetAttribute' => ['idplanilla' => 'idplanilla']],
            [['idvariable'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idvariable' => 'idconfiguracion']],
            [['origen'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['origen' => 'idconfiguracion']],
            [['tipomapa'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['tipomapa' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idvardimension' => 'Idvardimension',
            'idplanilla' => 'Idplanilla',
            'idvariable' => 'Idvariable',
            'origen' => 'Origen',
            'iddimension' => 'Iddimension',
            'fecha_carga' => 'Fecha Carga',
            'fecha_actualizacion' => 'Fecha Actualizacion',
            'mapear' => 'Mapear',
            'tipomapa' => 'Tipomapa',
            'detalle'=> 'Detalle',
            'observacion'=> 'Observacion',
        ];
    }

    /**
     * Gets query for [[Mds_r_diagnosticos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMds_r_diagnosticos()
    {
        return $this->hasMany(Mds_r_diagnostico::className(), ['idvardimension' => 'idvardimension']);
    }

    /**
     * Gets query for [[Iddimension0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddimension0()
    {
        return $this->hasOne(Sds_com_configuracion_tipo::className(), ['idconfiguraciontipo' => 'iddimension']);
    }

    /**
     * Gets query for [[Idplanilla0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdplanilla0()
    {
        return $this->hasOne(Mds_r_planilla::className(), ['idplanilla' => 'idplanilla']);
    }

    /**
     * Gets query for [[Idvariable0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdvariable0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'idvariable']);
    }

    /**
     * Gets query for [[Origen0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrigen0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'origen']);
    }

    /**
     * Gets query for [[Tipomapa0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipomapa0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'tipomapa']);
    }
}
