<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_detalle".
 *
 * @property string $fecha_hora
 * @property int $idarticulo
 * @property int|null $deposito
 * @property int $tipo
 * @property int $cantidad
 * @property int $organismo
 * @property int|null $item_recepcion
 * @property int|null $item_entrega
 */
class Sds_view_stock_detalle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_stock_detalle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora'], 'required'],
            [['fecha_hora'], 'safe'],
            [['idarticulo', 'deposito', 'tipo', 'cantidad', 'organismo', 'item_recepcion', 'item_entrega'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fecha_hora' => 'Fecha Hora',
            'idarticulo' => 'Idarticulo',
            'deposito' => 'Deposito',
            'tipo' => 'Tipo',
            'cantidad' => 'Cantidad',
            'organismo' => 'Organismo',
            'item_recepcion' => 'Item Recepcion',
            'item_entrega' => 'Item Entrega',
        ];
    }
}