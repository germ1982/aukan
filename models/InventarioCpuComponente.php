<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventario_cpu_componente".
 *
 * @property int $id
 * @property int $idcpu
 * @property int $idarticulo
 * @property int $id_tipo_componente
 *
 * @property Articulo $idarticulo0
 * @property InventarioCpu $idcpu0
 * @property Configuracion $tipoComponente
 */
class InventarioCpuComponente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventario_cpu_componente';
    }

    /** @var yii\widgets\ActiveForm $form */
    /** @var app\models\InventarioCpuComponente $model */

    public function rules()
    {
        return [
            [['idcpu', 'idarticulo'], 'required'],
            [['idcpu', 'idarticulo', 'id_tipo_componente'], 'integer'],
            [['idarticulo'], 'exist', 'skipOnError' => true, 'targetClass' => Articulo::className(), 'targetAttribute' => ['idarticulo' => 'idarticulo']],
            [['idcpu'], 'exist', 'skipOnError' => true, 'targetClass' => InventarioCpu::className(), 'targetAttribute' => ['idcpu' => 'idcpu']],
            [['id_tipo_componente'], 'exist', 'skipOnError' => true, 'targetClass' => Configuracion::className(), 'targetAttribute' => ['id_tipo_componente' => 'id_configuracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idcpu' => 'Idcpu',
            'idarticulo' => 'Idarticulo',
            'id_tipo_componente' => 'Id Tipo Componente',
        ];
    }

    /**
     * Gets query for [[Idarticulo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdarticulo0()
    {
        return $this->hasOne(Articulo::className(), ['idarticulo' => 'idarticulo']);
    }

    /**
     * Gets query for [[Idcpu0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcpu0()
    {
        return $this->hasOne(InventarioCpu::className(), ['idcpu' => 'idcpu']);
    }

    /**
     * Gets query for [[TipoComponente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoComponente()
    {
        return $this->hasOne(Configuracion::className(), ['id_configuracion' => 'id_tipo_componente']);
    }
}
