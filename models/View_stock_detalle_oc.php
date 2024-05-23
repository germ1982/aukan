<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_detalle_oc".
 *
 * @property string $fecha_hora
 * @property int $idarticulo
 * @property int|null $deposito
 * @property int $tipo
 * @property int $cantidad
 * @property int $organismo
 * @property int|null $item_recepcion
 * @property int|null $idordencompra
 * @property int|null $anio
 * @property int|null $mes
 */
class View_stock_detalle_oc extends \yii\db\ActiveRecord
{
    public $organizacion_social;
    public static function tableName()
    {
        return 'view_stock_detalle_oc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora'], 'required'],
            [['fecha_hora'], 'safe'],
            [['idarticulo', 'deposito', 'tipo', 'cantidad', 'organismo', 'item_recepcion', 'idordencompra', 'anio', 'mes','organizacion_social'], 'integer'],
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
            'idordencompra' => 'Idordencompra',
            'anio' => 'Anio',
            'mes' => 'Mes',
        ];
    }

    public static function getPrimerAnio()
    {
        //Aca para no complicar el asunto, le mando el año al campo numero para hacer la consulta mas facil con yii (que trucazo no?)
        $anio_stock = View_stock_detalle_oc::findBySql(
            "SELECT anio
            FROM view_stock_detalle_oc
            order by fecha_hora limit 1"
        )->one();
        return $anio_stock != null ? $anio_stock->anio : date('Y');
    }

    public static function primaryKey()
    {
        return ['fecha_hora', 'idarticulo', 'deposito', 'tipo', 'organismo'];
    }
}
