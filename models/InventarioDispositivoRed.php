<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventario_dispositivo_red".
 *
 * @property int $iddispositivo_red
 * @property int $idinventario
 * @property int|null $tipo_senal
 * @property int|null $tipo_tecnologia
 *
 * @property InformaticaIp[] $informaticaIps
 * @property Inventario $idinventario0
 * @property Configuracion $tipoSenal
 * @property Configuracion $tipoTecnologia
 */
class InventarioDispositivoRed extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventario_dispositivo_red';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idinventario'], 'required'],
            [['idinventario', 'tipo_senal', 'tipo_tecnologia'], 'integer'],
            [['idinventario'], 'unique'],
            [['idinventario'], 'exist', 'skipOnError' => true, 'targetClass' => Inventario::className(), 'targetAttribute' => ['idinventario' => 'idInventario']],
            [['tipo_senal'], 'exist', 'skipOnError' => true, 'targetClass' => Configuracion::className(), 'targetAttribute' => ['tipo_senal' => 'id_configuracion']],
            [['tipo_tecnologia'], 'exist', 'skipOnError' => true, 'targetClass' => Configuracion::className(), 'targetAttribute' => ['tipo_tecnologia' => 'id_configuracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddispositivo_red' => 'Iddispositivo Red',
            'idinventario' => 'Idinventario',
            'tipo_senal' => 'Senal',
            'tipo_tecnologia' => 'Tecnologia',
        ];
    }

    /**
     * Gets query for [[InformaticaIps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInformaticaIps()
    {
        return $this->hasMany(InformaticaIp::className(), ['iddispositivo_red' => 'iddispositivo_red']);
    }

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
     * Gets query for [[TipoSenal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoSenal()
    {
        return $this->hasOne(Configuracion::className(), ['id_configuracion' => 'tipo_senal']);
    }

    /**
     * Gets query for [[TipoTecnologia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoTecnologia()
    {
        return $this->hasOne(Configuracion::className(), ['id_configuracion' => 'tipo_tecnologia']);
    }
}
