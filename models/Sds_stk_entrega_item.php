<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_entrega_item".
 *
 * @property int $identregaitem
 * @property int $recepcion_item
 * @property int $cantidad
 * @property int $identrega
 *
 * @property SdsStkEntrega $identrega0
 * @property SdsStkRecepcionItem $recepcionItem
 * @property SdsStkMovimiento[] $sdsStkMovimientos
 */
class Sds_stk_entrega_item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $articulo;
    public $deposito;
    public $expediente;
    public $disponible;
    public $unidad_medida;

    public static function tableName()
    {
        return 'sds_stk_entrega_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['recepcion_item', 'identrega', 'idarticulo'], 'required'],                        
            [['articulo', 'deposito', 'expediente'], 'safe'],
            [['recepcion_item', 'cantidad', 'identrega','articulo','deposito','disponible','expediente','entrega_rendicion', 'idarticulo'], 'integer'],
            //['disponible','compare','compareAttribute'=>'cantidad','operator'=>'>','message'=>'Start Date must be less than End Date'],
            [['identrega'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_entrega::className(), 'targetAttribute' => ['identrega' => 'identrega']],
            [['recepcion_item'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_recepcion_item::className(), 'targetAttribute' => ['recepcion_item' => 'idrecepcionitem']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'identregaitem' => 'Identregaitem',
            'recepcion_item' => 'Recepcion Item',
            'cantidad' => 'Cantidad',
            'identrega' => 'Identrega',
        ];
    }

    /**
     * Gets query for [[Identrega0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdentrega0()
    {
        return $this->hasOne(Sds_stk_entrega::className(), ['identrega' => 'identrega']);
    }

    /**
     * Gets query for [[RecepcionItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRecepcionItem()
    {
        return $this->hasOne(Sds_stk_recepcion_item::className(), ['idrecepcionitem' => 'recepcion_item']);
    }

    /**
     * Gets query for [[SdsStkMovimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsStkMovimientos()
    {
        return $this->hasMany(Sds_stk_movimiento::className(), ['item_entrega' => 'identregaitem']);
    }
}
