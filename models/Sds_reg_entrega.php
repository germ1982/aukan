<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_entrega".
 *
 * @property int $idregistroentrega
 * @property int $idregistro
 * @property int $idarticulo
 * @property int $cantidad
 *
 * @property SdsStkArticulo $idarticulo0
 * @property SdsRegRegistro $idregistro0
 */
class Sds_reg_entrega extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_entrega';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idregistro', 'idarticulo', 'cantidad'], 'required'],
            [['idregistro', 'idarticulo', 'cantidad'], 'integer'],
            [['idarticulo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::className(), 'targetAttribute' => ['idarticulo' => 'idarticulo']],
            [['idregistro'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_reg_registro::className(), 'targetAttribute' => ['idregistro' => 'idregistro']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistroentrega' => 'Idregistroentrega',
            'idregistro' => 'Idregistro',
            'idarticulo' => 'Idarticulo',
            'cantidad' => 'Cantidad',
        ];
    }

    /**
     * Gets query for [[Idarticulo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdarticulo0()
    {
        return $this->hasOne(Sds_stk_articulo::className(), ['idarticulo' => 'idarticulo']);
    }

    /**
     * Gets query for [[Idregistro0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdregistro0()
    {
        return $this->hasOne(Sds_reg_registro::className(), ['idregistro' => 'idregistro']);
    }
}
