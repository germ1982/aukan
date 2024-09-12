<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "provincias".
 *
 * @property int $id
 * @property string $provincia
 */
class Provincias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provincias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['provincia'], 'required'],
            [['provincia'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provincia' => 'Provincia',
        ];
    }
}
