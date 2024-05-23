<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_recepcion".
 *
 * @property int $idrecepcion
 * @property string $fecha
 * @property int $proveedor
 * @property string $pedido
 * @property string $expediente
 *
 * @property SdsComConfiguracion $proveedor0
 * @property SdsStkRecepcionItem[] $sdsStkRecepcionItems
 */
class Sds_stk_recepcion extends \yii\db\ActiveRecord
{
    const RECEPTOR = 242;
    public $fdesde;
    public $fhasta;
    public $disponible;
    public $detalle_items;
    public $mostrar;
    public $orden_compra;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_stk_recepcion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'proveedor', 'expediente'], 'required'],
            [['fecha', 'fdesde', 'fhasta','orden_compra'], 'safe'],
            [['proveedor','organismo','idordencompra','disponible','mostrar'], 'integer'],
            [['pedido', 'detalle_items','expediente'], 'string', 'max' => 45],
            [['proveedor'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['proveedor' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idrecepcion' => 'Idrecepcion',
            'fecha' => 'Fecha',
            'proveedor' => 'Proveedor',
            'pedido' => 'Pedido',
        ];
    }

    /**
     * Gets query for [[Proveedor0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProveedor0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'proveedor']);
    }

    /**
     * Gets query for [[SdsStkRecepcionItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsStkRecepcionItems()
    {
        return $this->hasMany(Sds_stk_recepcion_item::className(), ['idrecepcion' => 'idrecepcion']);
    }
}
