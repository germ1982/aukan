<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_cat_nov".
 *
 * @property int $id
 * @property string $descripcion
 * @property int $activo
 */
class Mds_rum_cat_nov extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_cat_nov';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }
}
