<?php

namespace app\models;

use Yii;
use app\models\Mds_rum_domicilio;
/**
 * This is the model class for table "mds_rum_empleador".
 *
 * @property int $id
 * @property int $idpersona
 * @property string $nombre
 * @property int $slogan
 * @property string $imagen
 * @property int $iddomicilio
 * @property string $email
 * @property string $telefono1
 * @property string $telefono2
 * @property int $id_categoria
 * @property string $fechamodificacion
 * @property string $horamodificacion
 * @property string $fechaalta
 * @property string $horaalta
 * @property int $activo
 */
class Mds_rum_empleador extends \yii\db\ActiveRecord
{
    public $archivo_imagen;
    //para el domicilio
    public $calle;
    public $numero;
    public $barrio;
    public $descripcion;
    public $adicional;
    public $idlocalidad;
    public $idprovincia;
    public $manzana; 
    public $duplex;
    public $monoblock; 
    public $piso;
    public $dpto;
    public $lote;
    public $laprovincia; 
    public $mensaje;
    public $nombre_emp;
    public $iddomicilio2;
    public $email2;    
    public $id_categoria2;
    public $activo2;
    public $el_estado;
    public $estado2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_empleador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estado','idpersona', 'iddomicilio', 'activo','idlocalidad','idprovincia','id_categoria'], 'integer'],
            [['calle','id_categoria','nombre_contacto','cargo_contacto','idlocalidad','nombre','slogan','cuit', 'email', 'telefono1'], 'required'],
            [['estado2','activo2','id_categoria2','email2','fechamodificacion', 'horamodificacion', 'fechaalta', 'horaalta','nombre','nombre_emp','iddomicilio2'], 'safe'],
            [['nombre', 'imagen', 'slogan','adicional','descripcion'], 'string', 'max' => 255],
            [['nombre_contacto','cargo_contacto'], 'string', 'max' => 150],
            [['cuit'], 'string', 'max' => 20],
            [['email', 'telefono1', 'telefono2','calle','numero','barrio','manzana','duplex','monoblock','piso','dpto','lote'], 'string', 'max' => 200],
            [['archivo_imagen'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idpersona' => 'Idpersona',  
            'nombre' => 'Nombre',
            'slogan' => 'Slogan',
            'imagen' => 'Imagen',
            'iddomicilio' => 'Iddomicilio',
            'email' => 'Email', 
            'telefono1' => 'Telefono1',
            'telefono2' => 'Telefono2',
            
            'fechamodificacion' => 'Fechamodificacion',
            'horamodificacion' => 'Horamodificacion',
            'fechaalta' => 'Fechaalta',
            'horaalta' => 'Horaalta',
            'id_categoria' => 'Categoria',
            'activo' => 'Activo',

            'calle' => 'Calle',
            'numero' => 'numero',
            'barrio' => 'barrio',
            'descripcion' => 'descripcion',
            'adicional' => 'adicional',
            'idlocalidad' => 'Localidad',
            'manzana' => 'manzana',
            'duplex' => 'duplex',
            'monoblock' => 'monoblock',
            'piso' => 'piso',

            'dpto' => 'dpto',
            'lote' => 'lote',
            'cuit' => 'Cuit',
            'nombre_contacto' => 'Nombre del Contacto',
            'cargo_contacto' => 'Cargo del Contacto',
            

        ];
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

}
