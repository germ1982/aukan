<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "personas_no_homologadas".
 *
 * @property int $idpersona_no_homologada
 * @property string|null $documento
 * @property int|null $documento_tipo
 * @property int|null $nacionalidad
 * @property int|null $genero
 * @property string|null $fecha_nacimiento
 * @property string|null $nombre
 * @property string|null $apellido
 */
class PersonasNoHomologadas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personas_no_homologadas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documento_tipo', 'nacionalidad', 'genero'], 'integer'],
            [['fecha_nacimiento'], 'safe'],
            [['documento', 'apellido'], 'string', 'max' => 45],
            [['nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersona_no_homologada' => 'Idpersona No Homologada',
            'documento' => 'Documento',
            'documento_tipo' => 'Documento Tipo',
            'nacionalidad' => 'Nacionalidad',
            'genero' => 'Genero',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
        ];
    }

}
