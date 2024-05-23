<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_padron".
 *
 * @property int $idpadron
 * @property int $mes
 * @property int $anio
 * @property int $legajo
 * @property int $idunidadoperativa
 * @property string $categoria
 * @property string $apellido_nombre
 * @property int $sexo 0:Femenino, 1: Masculino
 * @property string $dni
 * @property string $cuil
 * @property string $fecha_nacimiento
 * @property string $fecha_ingreso
 * @property float $antiguedad_administrativa
 * @property float $antiguedad_privada
 * @property float $antiguedad_total
 * @property int $eventual
 */
class Mds_org_padron extends \yii\db\ActiveRecord
{

    public $temp_excel_import;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_padron';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'mes', 'anio', 'legajo', 'idunidadoperativa', 'categoria', 'apellido_nombre',
                'dni', 'cuil', 'fecha_nacimiento', 'fecha_ingreso'
            ], 'required'],
            [['mes', 'anio', 'legajo', 'idunidadoperativa', 'sexo', 'eventual','pr'], 'integer'],
            [['fecha_nacimiento', 'fecha_ingreso'], 'safe'],
            [['antiguedad_administrativa', 'antiguedad_privada', 'antiguedad_total'], 'number'],
            [['categoria', 'dni', 'cuil'], 'string', 'max' => 45],
            [['apellido_nombre'], 'string', 'max' => 255],
            [['temp_excel_import'], 'file', 'extensions' => 'xlsx,xls', 'maxSize' => 100000000],
            [['mes', 'anio', 'legajo'], 'unique', 'targetAttribute' => ['mes', 'anio', 'legajo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpadron' => 'Idpadron',
            'mes' => 'Mes',
            'anio' => 'Año',
            'legajo' => 'Legajo',
            'idunidadoperativa' => 'Unidad Operativa',
            'categoria' => 'Categoria',
            'apellido_nombre' => 'Apellido Nombre',
            'sexo' => 'Sexo',
            'dni' => 'Dni',
            'cuil' => 'Cuil',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'fecha_ingreso' => 'Fecha Ingreso',
            'antiguedad_administrativa' => 'Antiguedad Administrativa',
            'antiguedad_privada' => 'Antiguedad Privada',
            'antiguedad_total' => 'Antiguedad Total',
            'eventual' => 'Eventual',
        ];
    }
}
