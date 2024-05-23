<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_bdc_movimiento_equipo".
 *
 * @property int $idmovimientoequipo
 * @property int $idmovimiento
 * @property int $idequipo
 *
 * @property Sds_bdc_equipo $idequipo0
 * @property Sds_bdc_movimiento $idmovimiento0
 */
class Sds_bdc_movimiento_equipo extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $fecha_hora;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_bdc_movimiento_equipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idmovimiento', 'idequipo'], 'required'],
            [['idmovimiento', 'idequipo'], 'integer'],
            [['fdesde', 'fhasta', 'fecha_hora'], 'safe'],
            [['idequipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_bdc_equipo::class, 'targetAttribute' => ['idequipo' => 'idequipo']],
            [['idmovimiento'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_bdc_movimiento::class, 'targetAttribute' => ['idmovimiento' => 'idmovimiento']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimientoequipo' => 'Idmovimientoequipo',
            'idmovimiento' => 'Idmovimiento',
            'idequipo' => 'Idequipo',
        ];
    }

    /**
     * Gets query for [[Idequipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdequipo0()
    {
        return $this->hasOne(Sds_bdc_equipo::class, ['idequipo' => 'idequipo']);
    }

    /**
     * Gets query for [[Idmovimiento0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdmovimiento0()
    {
        return $this->hasOne(Sds_bdc_movimiento::class, ['idmovimiento' => 'idmovimiento']);
    }
}
