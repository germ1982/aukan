<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_oferta_laboral".
 *
 * @property int $id
 * @property string $titulo
 * @property string $fechaalta
 * @property string $horaalta
 * @property string $fechamodificacion
 * @property string $horamodificacion
 * @property string $fecha_publicacion
 * @property string $hora_publicacion
 * @property float|null $salario
 * @property int|null $id_nivel_ocupacion
 * @property int|null $id_experiencia
 * @property int $genero
 * @property int $id_categoria
 * @property int $id_cualificacion
 * @property string $descripcion
 * @property string $competencia
 * @property int|null $id_tipo_trabajo
 * @property int $activo
 * @property int $num_visto
 * @property string $email1
 * @property string $email2
 * @property string $telefono1
 * @property string $telefono2
 * @property string $imagen
 * @property int $id_dur_trabajo
 * @property string $ubicacion
 * @property int $id_localidad
 * @property int $id_empleador
 * @property int $fin_dias
 * @property int $fin_horas
 * @property int $fin_min
 * @property int $fin_seg
 * 
 * @property int ver_info_empresa
 * @property int info_empresa

 */

class Mds_rum_oferta_laboral extends \yii\db\ActiveRecord
{
    public $postulados;
    public $la_provincia;
    public $idempleador;
    public $idactualusuario;
    public $mensaje;
    public $el_genero;
    public $titulo_of;
    public $num_visto2;
    public $id_categoria2;
    public $genero2;
    public $id_dur_trabajo2;    
    public $fdesde;
    public $fhasta;
    public $postulaciones;

    public $fecha_publicacion2;    
    public $hora_publicacion2;
    public $fecha_publicacionfin2;
    public $hora_publicacionfin2;
    public $finalizo;
    public $empresa;

    
    /**
     * {@inheritdoc}
     */
    public $archivo_imagen;

    public static function tableName()
    {
        return 'mds_rum_oferta_laboral';
    }
 
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_publicacion', 'hora_publicacion','hora_publicacionfin','fecha_publicacionfin','ubicacion','titulo', 'id_categoria', 'id_cualificacion', 'descripcion', 'competencia', 'email1', 'telefono1', 'id_dur_trabajo',  'id_localidad', 'id_empleador', 'id_nivel_ocupacion', 'id_experiencia', 'id_categoria', 'id_cualificacion', 'id_tipo_trabajo','genero'], 'required'],           
            [['empresa','postulaciones','fdesde', 'fhasta','id_dur_trabajo2','genero2','id_categoria2','titulo_of','fechaalta', 'horaalta', 'fechamodificacion', 'horamodificacion', 'fecha_publicacion', 'hora_publicacion'], 'safe'],            
            [['salario'], 'number'],
            [['ver_info_empresa','id_nivel_ocupacion', 'id_experiencia', 'id_categoria', 'id_cualificacion', 'id_tipo_trabajo', 'activo', 'num_visto', 'id_dur_trabajo', 'id_localidad', 'id_empleador', 'fin_dias', 'fin_horas', 'fin_min', 'fin_seg','idempleador'], 'integer'],
            [['descripcion', 'competencia'], 'string'],
            [['info_empresa','titulo', 'email1', 'email2', 'imagen'], 'string', 'max' => 255],
            [['genero'], 'string', 'max' => 2],            
            [['finalizo','telefono1'], 'string', 'max' => 100],
            [['telefono2'], 'string', 'max' => 200],
            [['ubicacion'], 'string', 'max' => 250],  
            [['archivo_imagen'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000], 
            //[['archivo_imagen'], 'file', 'skipOnEmpty' => false, 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    { 
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'fechaalta' => 'Fecha Alta',
            'horaalta' => 'Hora Alta',
            'fechamodificacion' => 'Fecha Modificacion',
            'horamodificacion' => 'Hora Modificacion',
            'fecha_publicacion' => 'Fecha Inicio Publicación',
            'fecha_publicacion2' => 'Fecha Inicio Publicación',
            'hora_publicacion' => 'Hora Inicio Publicación',
            'fecha_publicacionfin' => 'Fecha Fin Publicación',
            'fecha_publicacionfin2' => 'Fecha Fin Publicación',
            'hora_publicacionfin' => 'Hora Fin Publicación',
            'salario' => 'Salario',
            'id_nivel_ocupacion' => 'Nivel Ocupacion',
            'id_experiencia' => 'Experiencia',
            'genero' => 'Genero',
            'id_categoria' => 'Categoría',
            'id_cualificacion' => 'Cualificacion',
            'descripcion' => 'Descripcion',
            'competencia' => 'Competencia',
            'id_tipo_trabajo' => 'Tipo Trabajo',
            'activo' => 'Activo',
            'num_visto' => 'Visto por',
            'email1' => 'Email 1',
            'email2' => 'Email 2',
            'telefono1' => 'Telefono 1',
            'telefono2' => 'Telefono 2',
            'imagen' => 'Imagen',
            'id_dur_trabajo' => 'Dur. Trabajo',
            'ubicacion' => 'Ubicacion',
            'id_localidad' => 'Localidad', 
            'id_empleador' => 'Empleador',
            'fin_dias' => 'Fin Dias',
            'fin_horas' => 'Fin Horas',
            'fin_min' => 'Fin Min',
            'fin_seg' => 'Fin Seg',   
            'ver_info_empresa' =>'Generar publicaciones anónimas' ,
            'info_empresa' =>'Informacion alternativa sobre la empresa que se publica en la web: ' ,          
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
