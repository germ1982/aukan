<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_familia_legajo".
 *
 * @property int|null $num_legajo
 * @property string|null $dni
 * @property string $archivo_adjunto
 * @property int $id
 * @property string|null $nombre
 * @property string|null $apellido
 * @property int|null $tipo_legajo
 */
class RegistroFamiliaLegajo extends \yii\db\ActiveRecord
{
    public $archivo_adjunto_file;  // Para manejar la carga del archivo

    public static function tableName()
    {
        return 'registro_familia_legajo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['num_legajo', 'tipo_legajo','idpersona'], 'integer'],
            [['archivo_adjunto'], 'required'],
            [['dni'], 'string', 'max' => 20],
            [['archivo_adjunto', 'nombre'], 'string', 'max' => 255],
            [['apellido'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'num_legajo' => 'Legajo',
            'dni' => 'Dni',
            'archivo_adjunto' => 'Archivo Adjunto',
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'tipo_legajo' => 'Tipo Legajo',
        ];
    }

    /* public function getRutaUploads()
    {
        return Yii::$app->params['rutaUploads'] . 'registro_familia_legajos/';
    } */
    public static function getRutaUploads()
    {
        return Yii::$app->params['rutaUploads'] . 'registro_familia_legajos/';
    }
    /* public static function getUrlUploads() {
        return Yii::getAlias('@web') . '/uploads_datafam/registro_familia_legajos/';
    } */
}
