<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_800_derivacion".
 *
 * @property int $idderivacion
 * @property string $descripcion
 * @property string $direccion
 * @property string $telefonos
 * @property int $activo
 *
 * @property Sds800Llamada[] $sds800Llamadas
 */
class Sds_800_derivacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_800_derivacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'direccion', 'telefonos', 'activo'], 'required'],
            [['activo'], 'integer'],
            [['descripcion', 'telefonos'], 'string', 'max' => 100],
            [['direccion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idderivacion' => 'Idderivacion',
            'descripcion' => 'Descripcion',
            'direccion' => 'Direccion',
            'telefonos' => 'Telefonos',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[Sds800Llamadas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSds800Llamadas()
    {
        return $this->hasMany(Sds_800_llamada::class, ['idderivacion' => 'idderivacion']);
    }
}
