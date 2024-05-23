<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_atp_sucursal".
 *
 * @property int $idsucursal
 * @property int $codigo
 * @property string $direccion
 */
class Mds_atp_sucursal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_atp_sucursal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'direccion'], 'required'],
            [['codigo'], 'integer'],
            [['direccion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idsucursal' => 'Sucursal',
            'codigo' => 'Codigo',
            'direccion' => 'Direccion',
        ];
    }
}
