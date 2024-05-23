<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_articulo_conversion".
 *
 * @property int $idarticuloconversion
 * @property int $articulo_base
 * @property int $articulo_convertido
 *
 * @property Sds_stk_articulo $articuloBase
 * @property Sds_stk_articulo $articuloConvertido
 */
class Sds_stk_articulo_conversion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $descripcion_base;
    public $descripcion_convertido;
    
    public static function tableName()
    {
        return 'sds_stk_articulo_conversion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['articulo_base', 'articulo_convertido'], 'required'],
            [['articulo_base', 'articulo_convertido'], 'integer'],
            [['descripcion_base', 'descripcion_convertido'], 'string'],
            [['articulo_base'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::className(), 'targetAttribute' => ['articulo_base' => 'idarticulo']],
            [['articulo_convertido'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::className(), 'targetAttribute' => ['articulo_convertido' => 'idarticulo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idarticuloconversion' => 'Idarticuloconversion',
            'articulo_base' => 'Articulo Base',
            'articulo_convertido' => 'Articulo Convertido',
        ];
    }

    /**
     * Gets query for [[ArticuloBase]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticuloBase()
    {
        return $this->hasOne(Sds_stk_articulo::className(), ['idarticulo' => 'articulo_base']);
    }

    /**
     * Gets query for [[ArticuloConvertido]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticuloConvertido()
    {
        return $this->hasOne(Sds_stk_articulo::className(), ['idarticulo' => 'articulo_convertido']);
    }
}
