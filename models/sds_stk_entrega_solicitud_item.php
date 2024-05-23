<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_entrega_solicitud_item".
 *
 * @property int $identregasolicituditem
 * @property int $idarticulo
 * @property int $cantidad
 * @property int $identregasolicitud
 *
 * @property SdsStkArticulo $idarticulo0
 * @property SdsStkEntregaSolicitud $identregasolicitud0
 */
class Sds_stk_entrega_solicitud_item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_stk_entrega_solicitud_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idarticulo', 'cantidad', 'identregasolicitud'], 'required'],
            [['idarticulo', 'cantidad', 'identregasolicitud'], 'integer'],
            [['idarticulo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::class, 'targetAttribute' => ['idarticulo' => 'idarticulo']],
            [['identregasolicitud'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_entrega_solicitud::class, 'targetAttribute' => ['identregasolicitud' => 'identregasolicitud']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'identregasolicituditem' => 'Identregasolicituditem',
            'idarticulo' => 'Idarticulo',
            'cantidad' => 'Cantidad',
            'identregasolicitud' => 'Identregasolicitud',
        ];
    }

    /**
     * Gets query for [[Idarticulo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdarticulo0()
    {
        return $this->hasOne(Sds_stk_articulo::class, ['idarticulo' => 'idarticulo']);
    }

    /**
     * Gets query for [[Identregasolicitud0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdentregasolicitud0()
    {
        return $this->hasOne(Sds_stk_entrega_solicitud::class, ['identregasolicitud' => 'identregasolicitud']);
    }
}
