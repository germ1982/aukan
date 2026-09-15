<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Modelo para la tabla 'imagenes'.
 *
 * @property int $idimagen
 * @property int $idmodulo
 * @property int $idindice
 * @property string $archivo
 */
class Imagenes extends ActiveRecord
{
    /**
     * Define el nombre de la tabla en la base de datos.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'imagenes';
    }

    /**
     * Reglas de validación de atributos.
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['idmodulo', 'idindice', 'archivo'], 'required'],
            [['idmodulo', 'idindice'], 'integer'],
            [['archivo'], 'string', 'max' => 255],
        ];
    }

    /**
     * Etiquetas de los atributos del modelo.
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'idimagen' => 'ID Imagen',
            'idmodulo' => 'ID Módulo',
            'idindice' => 'ID Índice / ID Registro',
            'archivo'  => 'Nombre de Archivo',
        ];
    }

    /**
     * Devuelve la URL relativa web del archivo de imagen.
     *
     * @return string
     */
    public function getUrl()
    {
        return Yii::$app->request->baseUrl . '/img/registros_tecnicos/' . $this->archivo;
    }

    /**
     * Devuelve la ruta absoluta en disco del archivo.
     *
     * @return string
     */
    public function getPath()
    {
        return Yii::getAlias('@webroot') . '/img/registros_tecnicos/' . $this->archivo;
    }
}