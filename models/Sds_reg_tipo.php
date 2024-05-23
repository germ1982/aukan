<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_tipo".
 *
 * @property int $idtipo
 * @property string $descripcion
 * @property int $activo
 *
 * @property SdsRegRegistro[] $sdsRegRegistros
 */
class Sds_reg_tipo extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idtipo' => 'Idtipo',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[SdsRegRegistros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRegRegistros()
    {
        return $this->hasMany(Sds_reg_registro::class, ['idtipo' => 'idtipo']);
    }
}
