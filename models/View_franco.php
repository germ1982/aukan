<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_franco".
 *
 * @property int $idfranco
 * @property string $fecha
 * @property int|null $anio
 * @property int|null $mes
 * @property int $idcontacto
 * @property int|null $legajo
 * @property int $documento
 * @property string $nombre
 * @property string $apellido
 * @property string $dispositivo
 * @property string $organismo
 * @property int $tipo
 * @property string $tipo_descripcion
 * @property string|null $descripcion
 */
class View_franco extends \yii\db\ActiveRecord
{
    public $desde;
    public $hasta;
    public static function tableName()
    {
        return 'view_franco';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idfranco', 'anio', 'mes', 'idcontacto', 'legajo', 'documento', 'tipo'], 'integer'],
            [['fecha', 'documento', 'nombre', 'apellido', 'dispositivo', 'organismo', 'tipo_descripcion'], 'required'],
            [['fecha','desde','hasta'], 'safe'],
            [['nombre', 'apellido', 'dispositivo', 'descripcion'], 'string', 'max' => 100],
            [['organismo'], 'string', 'max' => 200],
            [['tipo_descripcion'], 'string', 'max' => 255],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idfranco' => 'Idfranco',
            'fecha' => 'Fecha',
            'anio' => 'Anio',
            'mes' => 'Mes',
            'idcontacto' => 'Idcontacto',
            'legajo' => 'Legajo',
            'documento' => 'Documento',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'dispositivo' => 'Dispositivo',
            'organismo' => 'Organismo',
            'tipo' => 'Tipo',
            'tipo_descripcion' => 'Tipo Descripcion',
            'descripcion' => 'Descripcion',
        ];
    }

    public static function primaryKey()
    {
        return ['idfranco'];
    }
}
