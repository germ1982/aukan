<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_r_planilla".
 *
 * @property int $idplanilla
 * @property int $idorganismo
 * @property int $idusuario
 * @property int $mes
 * @property int $anio
 * @property int $idplantilla
 * @property int $periodo
 * @property string $fechacarga
 * @property int $activo
 * @property int $ver_diagnostico
 *  
 *
 * @property Sds_com_configuracion $idorganismo0
 * @property Sds_com_configuracion $idplantilla0
 * @property Mds_r_variable_dimension[] $Mds_r_variable_dimensions
 */
class Mds_r_planilla extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $idvariable;
    public $origen;
    public $iddimension;
    public $mapear;
    public $tipomapa;
    public $id_giscapa;
    public $fecha_desde;
    public $fecha_hasta;
    const ORIGEN_DISPOSITIVO = 4093;
    const ORIGEN_LOCALIDADES = 4092;
    public static function tableName()
    {
        return 'mds_r_planilla';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idorganismo', 'idusuario', 'mes', 'anio', 'idplantilla', 'periodo','activo'], 'integer'],
            [['fecha_desde', 'fecha_hasta','fechacarga','idvariable','id_giscapa','origen','iddimension','mapear','tipomapa','activo','ver_diagnostico'], 'safe'],
            
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idorganismo' => 'idconfiguracion']],
            [['idplantilla'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['idplantilla' => 'idconfiguracion']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idplanilla' => 'Idplanilla',
            'idorganismo' => 'Idorganismo',
            'idusuario' => 'Idusuario',
            'mes' => 'Mes',
            'anio' => 'Año',
            'idplantilla' => 'Idplantilla',
            'periodo' => 'Periodo',
            'ver_diagnostico' => 'No ver planilla en diagnóstico',            
        ];
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'idorganismo']);
    }

    /**
     * Gets query for [[Idplantilla0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdplantilla0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'idplantilla']);
    }

    /**
     * Gets query for [[Mds_r_variable_dimensions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMds_r_variable_dimensions()
    {
        return $this->hasMany(Mds_r_variable_dimension::className(), ['idplanilla' => 'idplanilla']);
    }
}
