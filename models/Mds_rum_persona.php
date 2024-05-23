<?php

namespace app\models;

use Yii;
 
/**
 * This is the model class for table "mds_rum_persona".
 *
 * @property int $id
 * @property string $nombres
 * @property string $apellido
 * @property string $fechanacimiento
 * @property int $hijos
 * @property int $tienecuil
 * @property int $precuil
 * @property int $postcuil
 * @property string $email
 * @property string $telfijo
 * @property string $telcel
 * @property string $idestado
 * @property int $iddomicilio
 * @property int $dni
 * @property string $sexo
 * @property int $idnacionalidad
 * @property int $idestadocivil
 * @property int $iddocadicional
 * @property string $fechaalta
 * @property string $horaalta
 * @property string $last_date
 * @property string $last_time
 * @property string $fechamodificacion
 * @property string $horamodificacion
 * @property string $foto
 * @property int $idvista
 * @property int $no
 * @property string $V1
 * @property string $V2
 * @property string $E
 * @property string $B
 * @property string $Labels
 * @property string $Localidad
 * @property string $Trabajos
 * @property string $EstSup
 * @property string $Tel
 * @property string $ec
 * @property string $s
 * @property int $admin
 * @property string $ingreso
 * @property string $estado
 * @property string $fechaactuser
 * @property string $horaactuser
 * @property int $ultimaversion
 * @property string $tengoformacion
 * @property string $tengoexperiencia
 * @property string $tengocapacitacion
 * @property string $tengoconocimiento
 * @property int $id_com_persona
 */
class Mds_rum_persona extends \yii\db\ActiveRecord
{
    public $su_genero;
    public $tiene_cuil;
    public $estado_civil;
    public $la_nacionalidad;
    public $loc_domicilio;
    public $una_localidad;
    public $dni;
    public $persona;
    public $documento;
    public $edad;
    public $fdesde;
    public $fhasta;
    public $prov_dom;
    public $prov_exp;
    public $prov_cap;
    public $el_nivel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id',  'hijos', 'precuil', 'postcuil', 'email', 'telfijo', 'telcel', 'idestado', 'iddomicilio',  'idestadocivil', 'iddocadicional', 'fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion', 'Labels', 'Localidad', 'Trabajos', 'EstSup', 'Tel', 'ec', 's', 'admin', 'fechaactuser', 'horaactuser', 'ultimaversion', 'id_com_persona'], 'required'],
            [['id', 'hijos', 'tienecuil', 'precuil', 'postcuil', 'iddomicilio', 'dni',  'idestadocivil', 'iddocadicional',  'id_com_persona'], 'integer'],
            [[ 'fdesde', 'fhasta','persona','last_date', 'last_time','fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion','documento'], 'safe'],
            [['Labels', 'Trabajos'], 'string'],
            [['nombres'], 'string', 'max' => 80],
            [['apellido', 'foto'], 'string', 'max' => 254],
            [['email'], 'string', 'max' => 250],
            [['telfijo', 'telcel'], 'string', 'max' => 100],
            [['idestado', 'ingreso'], 'string', 'max' => 150],           
            
            [['Tel'], 'string', 'max' => 200],                       
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombres' => 'Nombres',
            'apellido' => 'Apellido',
            
            'hijos' => 'Hijos',
            'tienecuil' => 'Tienecuil',
            'precuil' => 'Precuil',
            'postcuil' => 'Postcuil',
            'email' => 'Email',
            'telfijo' => 'Telfijo',
            'telcel' => 'Telcel',
            'idestado' => 'Idestado',
            'iddomicilio' => 'Iddomicilio',            
            'idestadocivil' => 'Idestadocivil',
            'iddocadicional' => 'Iddocadicional',
            'fechaalta' => 'Fechaalta',
            'horaalta' => 'Horaalta',
            'fechamodificacion' => 'Fechamodificacion',
            'horamodificacion' => 'Horamodificacion',
            'foto' => 'Foto',            
            'Labels' => 'Labels',
            'Localidad' => 'Localidad',
            'Trabajos' => 'Trabajos',
            'EstSup' => 'Est Sup',
            'Tel' => 'Tel',
            
            'ingreso' => 'Ingreso',
            'estado' => 'Estado',            
            'id_com_persona' => 'Id Com Persona',
            'last_date'=> 'Fecha último Ingreso',
            'last_time'=> 'Hora último ingreso',
        ];
    }
}
