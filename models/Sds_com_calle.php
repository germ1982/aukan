<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_com_calle".
 *
 * @property int $idcalle
 * @property string $descripcion
 * @property int $activo
 *
 * @property SdsRisRisneu[] $calle
 * @property SdsRisRisneu[] $calleInterseccion
 */
class Sds_com_calle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_com_calle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'required'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcalle' => 'Idcalle',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[getCalle]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalle()
    {
        return $this->hasMany(Sds_ris_risneu::className(), ['calle' => 'idcalle']);
    }

    /**
     * Gets query for [[getCalleInterseccion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalleInterseccion()
    {
        return $this->hasMany(Sds_ris_risneu::className(), ['calle_interseccion' => 'idcalle']);
    }
}
