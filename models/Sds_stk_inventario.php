<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_inventario".
 *
 * @property int $idinventario
 * @property string $fecha_hora
 * @property int $idusuario
 * @property int $iddeposito
 * @property int $idorganismo
 *
 * @property SdsStkDeposito $iddeposito0
 * @property MdsOrgOrganismo $idorganismo0
 * @property MdsSegUsuario $idusuario0
 * @property SdsStkInventarioItem[] $sdsStkInventarioItems
 */
class Sds_stk_inventario extends \yii\db\ActiveRecord
{
    public $hora;
    public $usuario_descripcion;
    public $fdesde;
    public $fhasta;
    public $detalle_items;
    public $deposito_descripcion;
    public $idrubro;
    public $rubro_descripcion;

    
    public static function tableName()
    {
        return 'sds_stk_inventario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'idusuario', 'iddeposito', 'idorganismo'], 'required'],
            [['fecha_hora','hora', 'fdesde', 'fhasta', ], 'safe'],
            [['idusuario', 'iddeposito', 'idorganismo','idrubro'], 'integer'],
            [['usuario_descripcion','detalle_items','deposito_descripcion','rubro_descripcion'], 'string'],
            [['iddeposito'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_stk_deposito::className(), 'targetAttribute' => ['iddeposito' => 'iddeposito']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idinventario' => 'Idinventario',
            'fecha_hora' => 'Fecha Hora',
            'idusuario' => 'Idusuario',
            'iddeposito' => 'Iddeposito',
            'idorganismo' => 'Idorganismo',
        ];
    }

    /**
     * Gets query for [[Iddeposito0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddeposito0()
    {
        return $this->hasOne(Sds_stk_deposito::className(), ['iddeposito' => 'iddeposito']);
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(Mds_org_organismo::className(), ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[SdsStkInventarioItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsStkInventarioItems()
    {
        return $this->hasMany(Sds_stk_inventario_item::className(), ['idinventario' => 'idinventario']);
    }
}
