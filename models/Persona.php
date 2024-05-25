<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "personas".
 *
 * @property int $idpersona
 * @property int $documento
 * @property int $documento_tipo
 * @property int $nacionalidad
 * @property int $genero
 * @property string $fecha_nacimiento
 * @property string $nombre
 * @property string $apellido
 * @property int|null $padre
 * @property int $conviviente
 * @property string|null $domicilio
 * @property string|null $domicilio_calle
 * @property string|null $domicilio_numero
 * @property int|null $idlocalidad
 */
class Persona extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documento', 'documento_tipo', 'nacionalidad', 'genero', 'nombre', 'apellido'], 'required'],
            [['documento', 'documento_tipo', 'nacionalidad', 'genero', 'padre', 'conviviente', 'idlocalidad'], 'integer'],
            [['fecha_nacimiento'], 'safe'],
            [['nombre', 'apellido', 'domicilio'], 'string', 'max' => 100],
            [['domicilio_calle'], 'string', 'max' => 255],
            [['domicilio_numero'], 'string', 'max' => 45],
            [['documento'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersona' => 'Idpersona',
            'documento' => 'Documento',
            'documento_tipo' => 'Documento Tipo',
            'nacionalidad' => 'Nacionalidad',
            'genero' => 'Genero',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'padre' => 'Padre',
            'conviviente' => 'Conviviente',
            'domicilio' => 'Domicilio',
            'domicilio_calle' => 'Domicilio Calle',
            'domicilio_numero' => 'Domicilio Numero',
            'idlocalidad' => 'Idlocalidad',
        ];
    }
}
