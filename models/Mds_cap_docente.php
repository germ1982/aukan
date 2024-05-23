<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Mds_cap_docente".
 *
 * @property int $idpersona
 * @property string|null $datos_docente
 * @property string|null $firma
 * @property int|null $firma_digital
 * @property int|null $profesion_corta
 * @property string|null $cargo_certificado
 * 
 * @property MdsCapInstancia $localidad0
 * @property SdsComPersona $idpersona0
 * @property SdsComConfiguracion $idconfiguracion0
 *
 */
class Mds_cap_docente extends \yii\db\ActiveRecord
{
    public $dni;
    public $nombre; 
    public $apellido;
    public $fecha_nacimiento;
    public $nacionalidad;
    public $sexo;
    public $temp_imagen;
    public $borrar_firma;
    const FIRMA_DIGITAL_SI = 1;
    const FIRMA_DIGITAL_NO = 0;
    
        
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cap_docente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona','localidad', 'dni','firma_digital', 'profesion_corta'], 'integer'],
            [['localidad', 'dni', 'nombre', 'apellido', 'fecha_nacimiento', 'nacionalidad', 'sexo', 'idpersona', 'email', 'telefono'], 'required'],            
            [['datos_docente', 'firma'], 'string'],
            [['borrar_firma','firma'], 'safe'],
            [['cargo_certificado'], 'string', 'max' => 100],
            [['idpersona'], 'unique'],
            [['localidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::className(), 'targetAttribute' => ['localidad' => 'idlocalidad']],            
            [['temp_imagen'], 'file', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::className(), 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['profesion_corta'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['profesion_corta' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersona' => 'Nrodocente',           
            'telefono' => 'Teléfono',
            'email' => 'eMail',
            'localidad' => 'Localidad',            
            'dni' => 'DNI',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',                     
            'datos_docente' => 'CV Resumido',
            'temp_imagen'=>'Seleccionar un Archivo (firma)',            
            'firma_digital' => 'Firma Digital',
            'profesion_corta' => 'Profesión Corta',
            'cargo_certificado' => 'Cargo para Certificado',
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

    public function random_filename($length, $directory , $extension )
    {
           // default to this files directory if empty...
           $dir = !empty($directory) && is_dir($directory) ? $directory : dirname(__FILE__);
    
           do {
               $key = '';
               $keys = array_merge(range(0, 9), range('a', 'z'));
    
               for ($i = 0; $i < $length; $i++) {
                   $key .= $keys[array_rand($keys)];
               }
           } while (file_exists($dir . '/' . $key . (!empty($extension) ? '.' . $extension : '')));
    
           return $key . (!empty($extension) ? '.' . $extension : '');
    }
    

    public static function getExtension($file) {
        $array = explode(".", $file);
        $extension = end($array);
        $extImagenes = array('jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp'); 
        if (in_array($extension, $extImagenes)) { 
            return 'image';
        } else {
            return $extension;
        }
    }

}
