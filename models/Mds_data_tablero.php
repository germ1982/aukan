<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_data_tablero".
 *
 * @property int $idtablero
 * @property string $nombre
 * @property string|null $descripcion
 * @property int $idcategoria
 * @property string $url
 * @property int|null $iditem
 * @property int $orden
 * @property int $estado
 *
 * @property Mds_data_categoria $idcategoria0
 * @property Mds_seg_item $iditem0
 */
class Mds_data_tablero extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_data_tablero';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'idcategoria', 'url', 'orden', 'estado'], 'required'],
            [['idcategoria', 'iditem', 'orden', 'estado'], 'integer'],
            [['nombre', 'url'], 'string', 'max' => 100],
            [['descripcion'], 'string', 'max' => 255],
            [['idcategoria'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_data_categoria::className(), 'targetAttribute' => ['idcategoria' => 'idcategoria']],
            [['iditem'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_item::className(), 'targetAttribute' => ['iditem' => 'iditem']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idtablero' => 'Id',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'idcategoria' => 'Categoria',
            'url' => 'Url',
            'iditem' => 'Permiso',
            'orden' => 'Orden',
            'estado' => 'Estado',
            'icono' => 'Icono',
        ];
    }

    /**
     * Gets query for [[Idcategoria0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcategoria0()
    {
        return $this->hasOne(Mds_data_categoria::className(), ['idcategoria' => 'idcategoria']);
    }

    /**
     * Gets query for [[Iditem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIditem0()
    {
        return $this->hasOne(Mds_seg_item::className(), ['iditem' => 'iditem']);
    }
}
