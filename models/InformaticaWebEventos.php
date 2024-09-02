<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informatica_web_eventos".
 *
 * @property int $idevento
 * @property string|null $fecha
 * @property string|null $titulo
 * @property string|null $descripcion
 * @property string|null $fotos
 * @property int|null $iddispositivo
 * @property int|null $activo
 */
class InformaticaWebEventos extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $imageFile;

    public static function tableName()
    {
        return 'informatica_web_eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'fdesde', 'fhasta'], 'safe'],
            [['descripcion'], 'string'],
            [['iddispositivo', 'activo'], 'integer'],
            [['titulo', 'fotos'], 'string', 'max' => 100],
            [['imageFile'], 'file', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000, 'maxFiles' => 10],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idevento' => 'ID',
            'fecha' => 'Fecha',
            'titulo' => 'Titulo',
            'descripcion' => 'Descripcion',
            'fotos' => 'Fotos',
            'iddispositivo' => 'Sector',
            'activo' => 'Activo',
        ];
    }
}
