<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_ts_persona".
 *
 * @property int $idtspersona
 * @property string|null $nombre
 * @property string|null $apellido
 * @property string|null $fecha_nacimiento
 * @property string|null $domicilio
 * @property string|null $telefono
 * @property string|null $mail
 * @property int $idlocalidad
 * @property string $foto_dni_frente
 * @property string $foto_dni_dorso
 * @property string|null $recibo_sueldo
 * @property string $factura_luz
 * @property int $dni
 * @property int $la_provincia
 * @property int $la_provincia
 * @property Mds_ts_checklist[] $mdsTsChecklists
 * @property Sds_com_localidad $idlocalidad0
 * @property Sds_com_configuracion $tipo_institucion0
 * @property  int estado
 * 
 */
class Mds_ts_persona extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $la_provincia;
    public $archivo_imagen;
    public $archivo_imagen2;
    public $temp_archivo_adjunto;
    public $temp_archivo_adjunto2;
    public $borrar_adjunto1;
    public $borrar_adjunto2;
    public $borrar_adjunto_personeria;
    public $num_opciones_asistencia;
    public $cad_check;
    public $provincia;
    public $temp_personeria_juridica;

    
    const SOLICITUD = 1;
    const ACEPTADA = 2;
    const RECHAZADA = 3;
    
    public static function tableName()
    {
        return 'mds_ts_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cad_check','fecha_nacimiento','borrar_adjunto1','borrar_adjunto2','borrar_adjunto_personeria', 'tipo_beneficiario'], 'safe'],
            /* descomentar la siguiente linea para hacer obligatoria las imagenes: */
            //[['estado','idlocalidad','nombre', 'apellido', 'mail','domicilio','telefono','foto_dni_frente', 'foto_dni_dorso', 'factura_luz','fecha_nacimiento'], 'required'],
            [['estado','idlocalidad','nombre', 'apellido', 'mail','domicilio','telefono','fecha_nacimiento','campania','tipo_beneficiario'], 'required'],
            [['idlocalidad','estado', 'tipo_institucion'], 'integer'],
            [['dni'], 'integer'],
            [['nombre', 'apellido', 'mail'], 'string', 'max' => 100],
            [['domicilio'], 'string', 'max' => 150],
            [['telefono','nro_persona'], 'string', 'max' => 20],
            [['foto_dni_frente', 'foto_dni_dorso', 'recibo_sueldo', 'factura_luz', 'temp_personeria_juridica', 'nombre_institucion', 'domicilio_institucion', 'relacion_institucion'], 'string', 'max' => 255],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::className(), 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
            [['tipo_institucion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['tipo_institucion' => 'idconfiguracion']],
            [['archivo_imagen','archivo_imagen2'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000], 
            [['temp_archivo_adjunto'], 'file', 'extensions' => 'jpg, jpeg, gif, svg, png, pdf, odt, ods, doc, docx, xls, xlsx', 'maxSize' => 1000000000],
            [['temp_archivo_adjunto2'], 'file', 'extensions' => 'jpg, jpeg, gif, svg, png, pdf, odt, ods, doc, docx, xls, xlsx', 'maxSize' => 1000000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idtspersona' => 'Idtspersona',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'domicilio' => 'Domicilio',
            'telefono' => 'Telefono',
            'mail' => 'Mail',
            'dni' => 'Dni',
            'idlocalidad' => 'Idlocalidad',
            'foto_dni_frente' => 'Foto Dni Frente',
            'foto_dni_dorso' => 'Foto Dni Dorso',
            'recibo_sueldo' => 'Recibo Sueldo',
            'factura_luz' => 'Factura Luz',
            'Estado' => 'Estado',
            'temp_archivo_adjunto' => 'Seleccionar un Archivo',
            'nombre_institucion' => 'Nombre Institución',
            'domicilio_institucion' => 'Domicilio Institución',
            'tipo_institucion' => 'Tipo Institución',
            'relacion_institucion' => 'Relación con la Institución'
        ];
    }

    /**
     * Gets query for [[MdsTsChecklists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsTsChecklists()
    {
        return $this->hasMany(Mds_ts_checklist::className(), ['idtspersona' => 'idtspersona']);
    }

    /**
     * Gets query for [[Idlocalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdlocalidad0()
    {
        return $this->hasOne(Sds_com_localidad::className(), ['idlocalidad' => 'idlocalidad']);
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
