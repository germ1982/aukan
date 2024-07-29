<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informatica_web_sectores".
 *
 * @property int $idsector
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property string|null $fotos
 * @property int|null $activo
 * @property int|null $orden
 * @property int|null $alto_foto
 */
class InformaticaWebSectores extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'informatica_web_sectores';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion'], 'string'],
            [['activo', 'orden', 'alto_foto'], 'integer'],
            [['nombre'], 'string', 'max' => 50],
            [['fotos'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idsector' => 'Idsector',
            'nombre' => 'Nombre',
            'descripcion' => 'Descripcion',
            'fotos' => 'Fotos',
            'activo' => 'Activo',
            'orden' => 'Orden',
            'alto_foto' => 'Alto Foto',
        ];
    }

}
