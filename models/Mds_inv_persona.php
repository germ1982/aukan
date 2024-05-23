<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_inv_persona".
 *
 * @property int $idpersona
 * @property int $grupo_familiar
 * @property string|null $telefono
 * @property string|null $email
 * @property string|null $domicilio
 * @property bool|null $seguimiento
 * @property int $cant_nnya
 * @property int $recibe_plantines
 * @property int $cosecha_plantines
 * 
 *
 * @property MdsInvAsistencia[] $mdsInvAsistencias
 * @property SdsComPersona $idpersona0
 */
class Mds_inv_persona extends \yii\db\ActiveRecord
{
    public $dni_search;
    public $nombre;
    public $apellido;
    public $fecha_nac;
    public $id_nacionalidad;
    public $id_genero;
    public $id_plantin;
    public $num_opciones_asistencia;
    public $obs1;
    public $obs2;
    public $persona;
    public $dni;
    public $cantplantines;
    
   
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_inv_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [        
            [['persona','dni',], 'safe'],                        
            [['cant_nnya','recibe_plantines','cosecha_plantines','grupo_familiar'], 'integer'],
            [['telefono'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 100],
            [['domicilio'], 'string', 'max' => 200],
            [['seguimiento'], 'boolean'],
            [['whatsapp'], 'integer'],
            
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::className(), 'targetAttribute' => ['idpersona' => 'idpersona']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersona' => 'Persona',
            'grupo_familiar' => 'Grupo Familiar',            
            'telefono' => 'Telefono',
            'email' => 'Email',
            'domicilio' => 'Domicilio',
            'seguimiento' => 'Quiere seguimiento',
            'id_genero' => 'Genero',
            'id_nacionalidad' => 'Nacionalidad',
            'cant_nnya'=> 'Cantidad NNyA',            
            'recibe_plantines'=> 'Recibe plantines',
            'cosecha_plantines'=> 'Cosechó Plantines',
        ];
    }

    /**
     * Gets query for [[MdsInvAsistencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsInvAsistencias()
    {
        return $this->hasMany(Mds_inv_asistencia::className(), ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Idcompersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcompersona0()
    {
        return $this->hasOne(Sds_com_persona::className(), ['idpersona' => 'idcompersona']);
    }
}
