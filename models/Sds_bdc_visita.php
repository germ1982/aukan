<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_bdc_visita".
 *
 * @property int $idvisita
 * @property string $fecha
 * @property int $sector
 * @property string|null $observacion
 *
 * @property SdsComConfiguracion $sector0
 */
class Sds_bdc_visita extends \yii\db\ActiveRecord
{
    public $sector_descripcion;
    public $fdesde;
    public $fhasta;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_bdc_visita';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'sector'], 'required'],
            [['idvisita', 'sector'], 'integer'],
            [['fecha', 'fdesde', 'fhasta'], 'safe'],
            [['observacion', 'sector_descripcion'], 'string'],
            [['idvisita'], 'unique'],
            [['sector'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['sector' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idvisita' => 'Visita',
            'fecha' => 'Fecha',
            'sector' => 'Sector',
            'sector_descripcion' => 'Sector',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * Gets query for [[Sector0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSector0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'sector']);
    }
}
