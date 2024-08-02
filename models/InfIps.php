<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inf_ips".
 *
 * @property int $idip
 * @property string|null $ip
 * @property string|null $idempleado
 */
class InfIps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inf_ips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'idempleado'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idip' => 'ID',
            'ip' => 'Direccion Ip',
            'idempleado' => 'Empleado',
        ];
    }
}
