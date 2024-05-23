<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_cel_plan".
 *
 * @property int $idplan
 * @property string $descripcion
 * @property int $activo
 *
 * @property SdsCelLinea[] $sdsCelLineas
 */
class Sds_cel_plan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_cel_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idplan' => 'Idplan',
            'descripcion' => 'Descripción',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[SdsCelLineas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsCelLineas()
    {
        return $this->hasMany(Sds_cel_linea::className(), ['idplan' => 'idplan']);
    }
}
