<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_his_registro_familia".
 *
 * @property int $idregistrofamilia
 * @property int $dni
 * @property int $legajo
 */
class Sds_his_registro_familia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_his_registro_familia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dni', 'legajo'], 'required'],
            [['dni', 'legajo'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistrofamilia' => 'Idregistrofamilia',
            'dni' => 'Dni',
            'legajo' => 'Legajo',
        ];
    }
}
