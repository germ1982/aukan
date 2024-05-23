<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_feriado".
 *
 * @property int $idferiado
 * @property string $fecha
 * @property string $descripcion
 */
class Mds_hor_feriado extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_hor_feriado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'descripcion'], 'required'],
            [['fecha','fdesde', 'fhasta'], 'safe'],
            [['descripcion'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idferiado' => 'Idferiado',
            'fecha' => 'Fecha',
            'descripcion' => 'Descripcion',
        ];
    }
}
