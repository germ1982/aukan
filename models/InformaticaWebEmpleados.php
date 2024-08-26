<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informatica_web_empleados".
 *
 * @property int $idwebempleado
 * @property int|null $idempleado
 * @property string|null $descripcion
 * @property int|null $activo
 * @property int|null $orden
 */
class InformaticaWebEmpleados extends \yii\db\ActiveRecord
{
    public $funcion_empleado;
    public static function tableName()
    {
        return 'informatica_web_empleados';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idempleado', 'activo', 'orden'], 'integer'],
            [['descripcion'], 'string'],
            [['funcion_empleado'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idwebempleado' => 'Id',
            'idempleado' => 'Empleado',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
            'orden' => 'Orden',
            'funcion_empleado' => 'Funcion',
        ];
    }

        // Relación con Empleado
        public function getEmpleado()
        {
            return $this->hasOne(Empleado::className(), ['idempleado' => 'idempleado']);
        }
}
