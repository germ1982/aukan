<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_movimiento_articulo".
 *
 * @property int $idmovimientoarticulo
 * @property int $idmovimiento
 * @property int $idarticulo
 * @property int $factor
 *
 * @property SdsStkArticulo $idarticulo0
 * @property SdsStkMovimiento $idmovimiento0
 */
class Sds_stk_movimiento_articulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_stk_movimiento_articulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idmovimiento', 'idarticulo', 'factor'], 'required'],
            [['idmovimiento', 'idarticulo', 'factor'], 'integer', 'min'=>1],
            [['idarticulo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::class, 'targetAttribute' => ['idarticulo' => 'idarticulo']],
            [['idmovimiento'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_movimiento::class, 'targetAttribute' => ['idmovimiento' => 'idmovimiento']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimientoarticulo' => 'Idmovimientoarticulo',
            'idmovimiento' => 'Idmovimiento',
            'idarticulo' => 'Articulo',
            'factor' => 'Factor',
        ];
    }

    /**
     * Gets query for [[Idarticulo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdarticulo0()
    {
        return $this->hasOne(SdsStkArticulo::class, ['idarticulo' => 'idarticulo']);
    }

    /**
     * Gets query for [[Idmovimiento0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdmovimiento0()
    {
        return $this->hasOne(SdsStkMovimiento::class, ['idmovimiento' => 'idmovimiento']);
    }
}
