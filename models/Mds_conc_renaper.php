<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_legales_respuesta".
 *
 * @property string|null $dni
 * @property string|null $nombres
 * @property string|null $apellido
 * @property string|null $fecha_nacimiento
 * @property string|null $cuil
 * @property string|null $calle
 * @property string|null $numero
 * @property string|null $piso
 * @property string|null $departamento
 * @property string|null $codigo_postal
 * @property string|null $barrio
 * @property string|null $monoblock
 * @property string|null $ciudad
 * @property string|null $municipio
 * @property string|null $provincia
 * @property string|null $pais
 * @property string|null $nacionalidad
 * @property int $codigo_fallecido
 * @property string|null $mensaje_fallecido
 * @property string|null $fecha_fallecimiento
 * @property string|null $genero
 */
class Mds_conc_renaper extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_conc_renaper';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dni'], 'required'],
            [['idconcrenaper','dni', 'nombres', 'apellido', 'fecha_nacimiento', 'cuil', 'calle', 'numero', 'piso', 'departamento', 'codigo_postal', 'barrio', 'monoblock', 'ciudad', 'municipio', 'provincia', 'pais', 'nacionalidad', 'mensaje_fallecido', 'fecha_fallecimiento', 'genero'], 'string'],
            [['idconcrenaper', 'codigo_fallecido'], 'integer'],
            [['dni', 'apellido', 'fecha_nacimiento', 'cuil', 'calle', 'numero', 'piso', 'departamento', 'codigo_postal', 'barrio', 'monoblock', 'ciudad', 'municipio', 'provincia', 'pais', 'nacionalidad', 'mensaje_fallecido', 'fecha_fallecimiento'], 'string', 'max' => 100],
            [['nombres'], 'string', 'max' => 255],
            [['genero'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return


            [
                'dni' => 'DNI',
                'nombres' => 'Nombres',
                'apellido' => 'Apellido',
                'fecha_nacimiento' => 'Fecha de nacimiento',
                'cuil' => 'CUIL',
                'calle' => 'Calle',
                'numero' => 'Número',
                'piso' => 'Piso',
                'departamento' => 'Departamento',
                'codigo_postal' => 'Código postal',
                'barrio' => 'Barrio',
                'monoblock' => 'Monoblock',
                'ciudad' => 'Ciudad',
                'municipio' => 'Municipio',
                'provincia' => 'Provincia',
                'pais' => 'País',
                'nacionalidad' => 'Nacionalidad',
                'codigo_fallecido' => 'Código fallecido',
                'mensaje_fallecido' => 'Mensaje fallecido',
                'fecha_fallecimiento' => 'Fecha de fallecimiento',
                'genero' => 'Género'
            ];
    }
}
