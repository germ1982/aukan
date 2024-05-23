<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_ans_alimentar_titulares".
 *
 * @property int $id
 * @property string|null $apellido
 * @property string|null $nombre
 * @property string|null $cuil
 * @property string|null $provincia
 * @property string|null $municipio
 * @property string|null $totalHijos
 * @property string|null $embarazo
 * @property string|null $estado
 * @property string|null $localidad
 * @property string|null $departamento
 * @property int|null $dni
 * @property int|null $estado_entrega
 * @property string|null $fecha_hora
 */
class mds_ans_alimentar_titulares extends \yii\db\ActiveRecord
{ 
    const PENDIENTE='0';
    const ENTREGADA= '1';
    const EMBARAZO_N='0';
    const EMBARAZO_S= '1';
   
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_ans_alimentar_titulares';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dni', 'estado_entrega'], 'integer'],
            [['apellido', 'nombre', 'provincia', 'municipio', 'totalHijos', 'embarazo', 'estado', 'localidad', 'departamento', 'fecha_hora'], 'string', 'max' => 150],
            [['cuil'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'apellido' => 'Apellido',
            'nombre' => 'Nombre',
            'cuil' => 'Cuil',
            'provincia' => 'Provincia',
            'municipio' => 'Municipio',
            'totalHijos' => 'Total Hijos',
            'embarazo' => 'Embarazo',
            'estado' => 'Estado',
            'localidad' => 'Localidad',
            'departamento' => 'Departamento',
            'dni' => 'Dni',
            'estado_entrega' => 'Estado Entrega',
            'fecha_hora' => 'Fecha y Hora',
        ];
    }
}
