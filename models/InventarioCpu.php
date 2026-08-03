<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventario_cpu".
 *
 * @property int $idcpu
 * @property int $idinventario
 * @property int|null $idmicro

 * @property int|null $idso

 * @property Inventario $idinventario0
 * @property Articulo $idmicro0
 * @property Articulo $idram0
 * @property Configuracion $idso0
 */
class InventarioCpu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventario_cpu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idinventario'], 'required'],
            [['idinventario', 'idmicro', 'idso','total_ram_gb','total_disco_gb'], 'integer'],
            [['idinventario'], 'unique'],
            [['idinventario'], 'exist', 'skipOnError' => true, 'targetClass' => Inventario::className(), 'targetAttribute' => ['idinventario' => 'idInventario']],
            [['idmicro'], 'exist', 'skipOnError' => true, 'targetClass' => Articulo::className(), 'targetAttribute' => ['idmicro' => 'idarticulo']],
            [['idso'], 'exist', 'skipOnError' => true, 'targetClass' => Configuracion::className(), 'targetAttribute' => ['idso' => 'id_configuracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcpu' => 'ID CPU',
            'idinventario' => 'Inventario',
            'idmicro' => 'Procesador / Micro',
            'idso' => 'Sistema Operativo',
        ];
    }

    /**

     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[Idinventario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdinventario0()
    {
        return $this->hasOne(Inventario::className(), ['idInventario' => 'idinventario']);
    }

    /**
     * Gets query for [[Idmicro0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdmicro0()
    {
        return $this->hasOne(Articulo::className(), ['idarticulo' => 'idmicro']);
    }

    /**
     * Gets query for [[Idram0]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[IdramDos0]].
     *
     * @return \yii\db\ActiveQuery
     */


    /**
     * Gets query for [[Idso0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdso0()
    {
        return $this->hasOne(Configuracion::className(), ['id_configuracion' => 'idso']);
    }
}
