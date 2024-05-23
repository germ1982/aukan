<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_ingreso_externo".
 *
 * @property int $idingresoexterno
 * @property int $idpersona
 * @property string $fecha_hora
 * @property string $observaciones
 * @property int $idcontacto
 */
class Mds_hor_ingreso_externo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $fdesde;
    public $fhasta;
    public $hora;
    public $estado;
    public $contacto;
    public $persona;


    public static function tableName()
    {
        return 'mds_hor_ingreso_externo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona', 'fecha_hora'], 'required'],
            //, 'fecha_hora_ingreso'
            [['idpersona','idcontacto' ,'idorganismo'], 'integer'],
            //'idcontacto'
            [[ 'fecha_hora_ingreso','estado' ], 'safe'],
            //,'fdesde', 'fhasta'
            [[ 'observaciones','sector', 'contacto', 'persona'], 'string'],
            //'nombre','apellido',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idingresoexterno' => 'Idingresoexterno',
            'idpersona' => 'Idpersona',
            'fecha_hora' => 'Fecha Hora',
            'observaciones' => 'Observaciones',
            //'idcontacto' => 'Contacto',
            'idorganismo'=> 'Organismo',
            'sector' => 'Sector',
            'fecha_hora_ingreso' => 'Fecha Hora Ingreso',
            /*'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            */
            'estado'=> 'Estado',
            'motivo'=> 'Motivo'
        ];
    }
}
