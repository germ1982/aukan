<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_recepcion_item".
 *
 * @property int $idrecepcionitem
 * @property int $idrecepcion
 * @property int $idarticulo
 * @property string $descripcion
 *
 * @property SdsStkArticulo $idarticulo0
 * @property SdsStkRecepcion $idrecepcion0
 */
class Sds_stk_recepcion_item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $deposito;
    public $idordencompra;
    public static function tableName()
    {
        return 'sds_stk_recepcion_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrecepcion', 'idarticulo', 'descripcion','cantidad'], 'required'],
            [['idrecepcion', 'idarticulo','cantidad','deposito','idordencompraitem'], 'integer'],
            //['idarticulo', 'unique'],
            //[['idrecepcion', 'idarticulo'], 'unique'],
            [['idrecepcion', 'idarticulo'], 'unique', 'targetAttribute' => ['idrecepcion', 'idarticulo']],
            [['descripcion'], 'string', 'max' => 255],
            [['idarticulo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::className(), 'targetAttribute' => ['idarticulo' => 'idarticulo']],
            [['idrecepcion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_recepcion::className(), 'targetAttribute' => ['idrecepcion' => 'idrecepcion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idrecepcionitem' => 'Idrecepcionitem',
            'idrecepcion' => 'Idrecepcion',
            'idarticulo' => 'Idarticulo',
            'descripcion' => 'Descripcion',
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
     * Gets query for [[Idrecepcion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdrecepcion0()
    {
        return $this->hasOne(Sds_stk_recepcion::className(), ['idrecepcion' => 'idrecepcion']);
    }
}
