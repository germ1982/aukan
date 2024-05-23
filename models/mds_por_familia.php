<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_por_familia".
 *
 * @property int $idfamilia
 * @property string|null $localidad
 * @property string|null $nombre
 * @property float|null $dni
 * @property float|null $cuil
 * @property string|null $responsable_cobro
 * @property float|null $dni_responsable
 * @property float|null $importe
 * @property string|null $programa
 * @property string|null $subprograma
 * @property string|null $area
 * @property string|null $responsable_certificacion
 * @property string|null $expediente
 * @property string|null $desde
 * @property string|null $hasta
 * @property string|null $F12
 * @property string|null $F15
 * @property string|null $F16
 * @property string|null $F17
 * @property string|null $F18
 * @property string|null $F19
 * @property int $mes
 * @property int $anio
 */
class mds_por_familia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_por_familia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dni', 'cuil', 'dni_responsable', 'importe'], 'number'],
            [['mes', 'anio'], 'required'],
            [['mes', 'anio'], 'integer'],
            [['localidad', 'nombre', 'responsable_cobro', 'programa', 'subprograma', 'area', 'responsable_certificacion', 'expediente', 'desde', 'hasta', 'F12', 'F15', 'F16', 'F17', 'F18', 'F19'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idfamilia' => 'Idfamilia',
            'localidad' => 'Localidad',
            'nombre' => 'Nombre',
            'dni' => 'Dni',
            'cuil' => 'Cuil',
            'responsable_cobro' => 'Responsable de Cobro',
            'dni_responsable' => 'Dni Responsable de Cobro',
            'importe' => 'Importe',
            'programa' => 'Programa',
            'subprograma' => 'Subprograma',
            'area' => 'Area',
            'responsable_certificacion' => 'Responsable Certificacion',
            'expediente' => 'Expediente',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'F12' => 'F12',
            'F15' => 'F15',
            'F16' => 'F16',
            'F17' => 'F17',
            'F18' => 'F18',
            'F19' => 'F19',
            'mes' => 'Mes',
            'anio' => 'Año',
        ];
    }
}
