<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empleado".
 *
 * @property int $idempleado
 * @property int $idpersona
 * @property int $iddispositivo
 * @property int $legajo
 * @property string|null $email
 * @property string|null $telefono
 * @property string|null $foto
 * @property int $activo
 * @property int|null $categoria
 * @property int|null $antiguedad_legal
 * @property int|null $antiguedad_total
 * @property string|null $ingreso_real
 * @property string|null $ingreso_administrativo
 * @property int|null $contratacion
 * @property int|null $cuil
 * @property int|null $funcion
 * @property int|null $fichado
 * @property int|null $afiliacion
 */
class Empleado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empleado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona', 'iddispositivo', 'legajo', 'activo'], 'required'],
            [['idpersona', 'iddispositivo', 'legajo', 'activo', 'categoria', 'antiguedad_legal', 'antiguedad_total', 'contratacion', 'cuil', 'funcion', 'fichado', 'afiliacion'], 'integer'],
            [['ingreso_real', 'ingreso_administrativo'], 'safe'],
            [['email', 'foto'], 'string', 'max' => 100],
            [['telefono'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idempleado' => 'Idempleado',
            'idpersona' => 'Idpersona',
            'iddispositivo' => 'Iddispositivo',
            'legajo' => 'Legajo',
            'email' => 'Email',
            'telefono' => 'Telefono',
            'foto' => 'Foto',
            'activo' => 'Activo',
            'categoria' => 'Categoria',
            'antiguedad_legal' => 'Antiguedad Legal',
            'antiguedad_total' => 'Antiguedad Total',
            'ingreso_real' => 'Ingreso Real',
            'ingreso_administrativo' => 'Ingreso Administrativo',
            'contratacion' => 'Contratacion',
            'cuil' => 'Cuil',
            'funcion' => 'Funcion',
            'fichado' => 'Fichado',
            'afiliacion' => 'Afiliacion',
        ];
    }
}
