<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_com_periodo".
 *
 * @property string $fecha
 * @property string $periodo
 */
class Sds_com_periodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_com_periodo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'periodo'], 'required'],
            [['fecha'], 'safe'],
            [['periodo'], 'string', 'max' => 100],
            [['fecha'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fecha' => 'Fecha',
            'periodo' => 'Periodo',
        ];
    }
}
