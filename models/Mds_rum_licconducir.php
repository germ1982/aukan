<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_licconducir".
 *
 * @property int $id
 * @property string $categoria
 * @property string $clase
 * @property string $subclase
 * @property string $descripcion
 * @property int $tienesubclases
 * @property int $orden
 */
class Mds_rum_licconducir extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_licconducir';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'categoria', 'clase', 'subclase', 'descripcion', 'orden'], 'required'],
            [['id', 'tienesubclases', 'orden'], 'integer'],
            [['descripcion'], 'string'],
            [['categoria', 'clase', 'subclase'], 'string', 'max' => 10],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categoria' => 'Categoria',
            'clase' => 'Clase',
            'subclase' => 'Subclase',
            'descripcion' => 'Descripcion',
            'tienesubclases' => 'Tienesubclases',
            'orden' => 'Orden',
        ];
    }
}
