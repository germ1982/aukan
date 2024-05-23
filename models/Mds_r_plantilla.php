<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_r_plantilla".
 *
 * @property int $idplantilla
 * @property int $variable_diagnostico
 * @property int $idtipoplantilla
 * @property int $dimension
 * @property int $origen
 * @property string $fechahoracreate
 * @property int $id_gis_capa
 * @property int $activo
 *
 * @property Mds_r_planilla[] $Mds_r_planillas
 * @property Sds_com_configuracion $dimension0
 * @property Sds_com_configuracion $idtipoplantilla0
 * @property Sds_com_configuracion $origen0
 * @property Sds_com_configuracion $variableDiagnostico
 * @property Sds_gis_capa $idcapa0
 */
class Mds_r_plantilla extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $nombre_plantilla;
    public $nombre_variable;
    public $nombre_origen;
    public $dimensiones;

    const CONST_DISP = 4093;
    const CONST_LOCALIDAD = 4092;

    public static function tableName()
    {
        return 'mds_r_plantilla';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['variable_diagnostico', 'idtipoplantilla', 'dimension', 'origen','activo'], 'integer'],
            [['fechahoracreate'], 'required'],
            [['nombre_plantilla','nombre_origen','nombre_variable'], 'string', 'max' => 255],
            [['fechahoracreate','dimensiones','nombre_plantilla','nombre_variable','nombre_origen', 'id_gis_capa','activo'], 'safe'],
            [['dimension'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['dimension' => 'idconfiguracion']],
            [['idtipoplantilla'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idtipoplantilla' => 'idconfiguracion']],
            [['origen'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['origen' => 'idconfiguracion']],
            [['variable_diagnostico'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['variable_diagnostico' => 'idconfiguracion']],
            [['id_gis_capa'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa::className(), 'targetAttribute' => ['id_gis_capa' => 'idcapa']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idplantilla' => 'Plantilla',
            'variable_diagnostico' => 'Variable Diagnostico',
            'idtipoplantilla' => 'Tipo plantilla',
            'dimension' => 'Dimension',
            'origen' => 'Origen',
            'nombre_origen' => 'Origen',
            'fechahoracreate' => 'Fechahoracreate',
            'nombre_plantilla'=> 'Nombre Plantilla',
            'dimensiones' => 'Dimensiones',
        ];
    }

    /**
     * Gets query for [[Mds_r_planillas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMds_r_planillas()
    {
        return $this->hasMany(Mds_r_planilla::className(), ['idplantilla' => 'idplantilla']);
    }

    /**
     * Gets query for [[Dimension0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDimension0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'dimension']);
    }

    /**
     * Gets query for [[Idtipoplantilla0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtipoplantilla0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'idtipoplantilla']);
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
     * Gets query for [[Idcapa0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcapa0()
    {
        return $this->hasOne(Sds_gis_capa::className(), ['idcapa' => 'id_gis_capa']);
    }

    /**
     * Gets query for [[VariableDiagnostico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVariableDiagnostico()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'variable_diagnostico']);
    }
}
