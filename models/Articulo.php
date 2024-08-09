<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "articulo".
 *
 * @property int $idarticulo
 * @property string|null $descripcion
 * @property int $idtipo
 * @property int|null $idmarca
 * @property string|null $modelo
 * @property int $idrubro
 * @property int $id_unidad_medida
 * @property int $activo
 * @property string|null $imagen
 */
class Articulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtipo', 'idrubro', 'id_unidad_medida', 'activo'], 'required'],
            [['idtipo', 'idmarca', 'idrubro', 'id_unidad_medida', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 45],
            [['modelo'], 'string', 'max' => 30],
            [['imagen'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idarticulo' => 'Id',
            'descripcion' => 'Descripcion',
            'idtipo' => 'Tipo Articulo',
            'idmarca' => 'Marca',
            'modelo' => 'Modelo',
            'idrubro' => 'Rubro',
            'id_unidad_medida' => 'Unidad Medida',
            'activo' => 'Activo',
            'imagen' => 'Imagen',
        ];
    }
}
