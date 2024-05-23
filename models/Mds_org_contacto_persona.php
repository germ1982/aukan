<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_contacto_datos_persona".
 *
 * @property int|null $legajo
 * @property int $dni
 * @property string $apellido
 * @property string $nombre
 * @property string|null $domicilio
 * @property string|null $localidad
 * @property string $in_prov
 */
class Mds_org_contacto_persona extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_contacto_datos_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['legajo', 'dni'], 'integer'],
            [['dni', 'apellido', 'nombre'], 'required'],
            [['apellido', 'nombre', 'localidad'], 'string', 'max' => 100],
            [['domicilio'], 'string', 'max' => 301],
            [['in_prov'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'legajo' => 'Legajo',
            'dni' => 'Dni',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'domicilio' => 'Domicilio',
            'localidad' => 'Localidad',
            'in_prov' => 'In Prov',
        ];
    }

    public static function primaryKey()
    {
        return ['legajo', 'dni'];
    }
}
