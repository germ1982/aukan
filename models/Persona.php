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
    public $nombre_apellido;
    public $fdesde;
    public $fhasta;
    public $idprovincia;
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
            [['fecha_nacimiento', 'nombre_apellido', 'fdesde', 'fhasta', 'idprovincia'], 'safe'],
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
            'idpersona' => 'ID',
            'documento' => 'Documento',
            'documento_tipo' => 'Documento Tipo',
            'nacionalidad' => 'Nacionalidad',
            'genero' => 'Genero',
            'fecha_nacimiento' => 'Nacimiento',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'padre' => 'Padre',
            'conviviente' => 'Conviviente',
            'domicilio' => 'Domicilio',
            'domicilio_calle' => 'Calle',
            'domicilio_numero' => 'Numero',
            'idlocalidad' => 'Localidad
            ',
        ];
    }

    public static function get_direccion($id)
    {
        $sql = "SELECT 
                    CONCAT_WS(
                        ' ', -- Aquí defines el separador (en este caso un espacio)
                        COALESCE(p.domicilio_calle, ''), 
                        COALESCE(p.domicilio_numero, ''), 
                        COALESCE(p.domicilio, ''),
                        COALESCE(l.localidad, ''), 
                        CASE 
                            WHEN v.provincia IS NOT NULL THEN CONCAT('(', v.provincia, ')')
                            ELSE '' -- Si la provincia no existe, no muestra nada
                        END
                        
                    ) as domicilio 
                FROM personas p
                LEFT JOIN localidades l ON p.idlocalidad = l.id
                LEFT JOIN provincias v ON l.id_provincia = v.id
                WHERE p.idpersona = $id";

        $dato = Persona::findBySql($sql)->one()->domicilio;
        return $dato;
    }

    public static function get_persona_ayn($id)
    {
        $dato = Persona::findOne($id);
        return "$dato->apellido $dato->nombre";
    }
}
