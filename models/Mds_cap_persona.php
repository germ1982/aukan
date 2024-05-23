<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_cap_persona".
 *
 * 
 * @property int $idpersonacap
 * @property int $idpersona
 * @property string|null $telefono
 * @property string|null $mail
 * @property int $localidad
 *
 * @property MdsCapInstancia $localidad0
 * @property SdsComPersona $idpersona0
 */
class Mds_cap_persona extends \yii\db\ActiveRecord
{
    public $dni;
    public $nombre;
    public $apellido;
    public $nombrecompuesto;
    public $fecha_nacimiento;
    public $nacionalidad;
    public $sexo;

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cap_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['localidad', 'dni', 'idpersona', 'ultimo_año'], 'integer'],
            [['localidad', 'dni', 'nombre', 'apellido', 'fecha_nacimiento', 'nacionalidad', 'sexo', 'ultimo_año', 'idpersona', 'mail', 'telefono'], 'required'],
            [['dni', 'nombre', 'apellido', 'nombrecompuesto', 'ultimo_año'],  'safe'],
            [['idpersona'], 'unique'],
            [['telefono', 'mail', 'nombre', 'apellido'], 'string', 'max' => 100],
            [['localidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::className(), 'targetAttribute' => ['localidad' => 'idlocalidad']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::className(), 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['ultimo_año'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['ultimo_año' => 'idconfiguracion']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersona' => 'Idpersona',
            'telefono' => 'Teléfono',
            'mail' => 'Mail',
            'localidad' => 'Localidad',
            'ultimo_año' => 'Último año cursado',
            'dni' => 'DNI',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'nombrecompuesto' => 'Nombre y Apellido'
        ];
    }

    /**
     * Gets query for [[localidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getlocalidad()
    {
        return $this->hasOne(Sds_com_localidad::className(), ['idlocalidad' => 'localidad']);
    }

    public function getultimo_año()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'ultimo_año']);
    }


    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona()
    {
        return $this->hasOne(Sds_com_persona::className(), ['idpersona' => 'idpersona']);
    }

    public static function getPersona($idper)
    {
        return Sds_com_persona::find()
            ->where(['idpersona' => $idper])
            ->orderBy(['apellido' => SORT_ASC, 'nombre' => SORT_ASC])->all();
    }
}
