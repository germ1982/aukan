<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_movimiento".
 *
 * @property int $idmovimiento
 * @property int $tipo 0: stock Inicial; 1: Ingreso; 2: Reubicación; 3: Egreso
 * @property int $cantidad
 * @property int|null $deposito_ingreso
 * @property int $idarticulo
 * @property int|null $deposito_egreso
 * @property string $fecha_hora
 * @property int|null $item_recepcion
 * @property int|null $item_entrega
 *
 * @property Sds_stk_articulo $idarticulo0
 * @property SdsStkDeposito $depositoIngreso
 * @property SdsStkDeposito $depositoEgreso
 * @property SdsStkEntregaItem $itemEntrega
 * @property SdsStkRecepcionItem $itemRecepcion
 */
class Sds_stk_movimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    
    const TIPO_INICIAL=0;
    const TIPO_INGRESO=1;
    const TIPO_REUBICACION=2;
    const TIPO_EGRESO=3;
    const TIPO_CONVERSION=4;

    public $fdesde;
    public $fhasta;
    public $origen;
    public $destino;
    public $disponible;
    public $expediente;
    public $deposito;
    public $organismo;

    public static function tableName()
    {
        return 'sds_stk_movimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo', 'cantidad', 'idarticulo', 'fecha_hora'], 'required'],
            [['tipo', 'cantidad', 'deposito_ingreso', 'idarticulo', 'deposito_egreso', 'item_recepcion', 'item_entrega','disponible','expediente','organismo','generado'], 'integer'],
            [['fecha_hora', 'fdesde', 'fhasta','origen','destino','disponible','expediente'], 'safe'],
            [['cantidad'], 'integer', 'min'=>1],
            ['cantidad','compare','compareAttribute'=>'disponible','operator'=>'<=','message'=>'Cantidad no debe superar el disponible', 'on'=>['conversion', 'create']],
            [['idarticulo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_articulo::class, 'targetAttribute' => ['idarticulo' => 'idarticulo']],
            [['deposito_ingreso'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_deposito::class, 'targetAttribute' => ['deposito_ingreso' => 'iddeposito']],
            [['deposito_egreso'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_deposito::class, 'targetAttribute' => ['deposito_egreso' => 'iddeposito']],
            [['item_entrega'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_entrega_item::class, 'targetAttribute' => ['item_entrega' => 'identregaitem']],
            [['item_recepcion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_recepcion_item::class, 'targetAttribute' => ['item_recepcion' => 'idrecepcionitem']],
            [['item_recepcion', 'disponible', 'deposito_egreso'], 'required', 'on'=>'conversion'],
            [['item_recepcion', 'disponible', 'deposito_egreso', 'deposito_ingreso'], 'required', 'on'=>'create'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimiento' => 'Idmovimiento',
            'tipo' => 'Tipo',
            'cantidad' => 'Cantidad',
            'deposito_ingreso' => 'Deposito Destino',
            'idarticulo' => 'Articulo',
            'deposito_egreso' => 'Deposito Origen',
            'fecha_hora' => 'Fecha Hora',
            'item_recepcion' => 'Expediente',
            'item_entrega' => 'Item Entrega',
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
     * Gets query for [[DepositoIngreso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepositoIngreso()
    {
        return $this->hasOne(Sds_stk_deposito::class, ['iddeposito' => 'deposito_ingreso']);
    }

    /**
     * Gets query for [[DepositoEgreso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepositoEgreso()
    {
        return $this->hasOne(Sds_stk_deposito::class, ['iddeposito' => 'deposito_egreso']);
    }

    /**
     * Gets query for [[ItemEntrega]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemEntrega()
    {
        return $this->hasOne(Sds_stk_entrega_item::class, ['identregaitem' => 'item_entrega']);
    }

    /**
     * Gets query for [[ItemRecepcion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemRecepcion()
    {
        return $this->hasOne(Sds_stk_recepcion_item::class, ['idrecepcionitem' => 'item_recepcion']);
    }
}
