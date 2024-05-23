<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_fs_persona".
 *
 * @property int $idfspersona
 * @property string $nombre
 * @property string $apellido
 * @property int $dni
 * @property string $fecha_nacimiento
 * @property string $lugar_nacimiento
 * @property int $nacionalidad
 * @property int $genero
 * @property int $estado_civil
 * @property string $domicilio
 * @property int $idlocalidad
 * @property int $idprovincia
 * @property string $tiempo_provincia
 * @property int $nivel_escolaridad
 * @property string $profesion
 * @property string $telefono
 * @property string|null $telefono_alternativo
 * @property string $mail
 * @property string|null $grupo_familiar
 * @property string|null $inscripto_rua
 * @property string $motivo_fs
 * @property string $acuerdo_familia
 * @property string $conocimiento_programa
 * @property string $disponibilidad_horaria
 * @property string $franja_etaria
 * @property string|null $consulta
 * @property string $fecha_hora
 * @property int $inscripto_rua_check
 *
 * @property SdsComConfiguracion $nacionalidad0
 * @property SdsComConfiguracion $ultimoAnio
 * @property SdsComConfiguracion $genero0
 * @property SdsComConfiguracion $estadoCivil
 * @property SdsComProvincia $idprovincia0
 * @property SdsComLocalidad $idlocalidad0
 */
class Mds_fs_persona extends \yii\db\ActiveRecord
{
    public $la_provincia;
    public $provincia;
    public $temp_informe_adjunto_path;
    public $borrar_adjunto;
    public $fecha_desde;
    public $fecha_hasta;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_fs_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'apellido', 'dni', 'fecha_nacimiento', 'lugar_nacimiento', 'nacionalidad', 'genero', 'estado_civil', 'domicilio', 'idlocalidad', 'idprovincia', 'tiempo_provincia', 'nivel_escolaridad', 'profesion', 'telefono', 'mail', 'motivo_fs', 'acuerdo_familia', 'conocimiento_programa', 'disponibilidad_horaria', 'franja_etaria', 'fecha_hora', 'inscripto_rua_check'], 'required'],
            [['dni', 'nacionalidad', 'genero', 'nivel_escolaridad', 'estado_civil', 'idlocalidad', 'idprovincia', 'inscripto_rua_check', 'estado'], 'integer'],
            [['fecha_nacimiento', 'fecha_hora', 'borrar_adjunto','fecha_desde', 'fecha_hasta'], 'safe'],
            [['grupo_familiar', 'inscripto_rua', 'motivo_fs', 'acuerdo_familia', 'conocimiento_programa', 'disponibilidad_horaria', 'franja_etaria', 'consulta'], 'string'],
            [['nombre', 'apellido', 'tiempo_provincia', 'profesion', 'mail', 'informe_adjunto_path'], 'string', 'max' => 100],
            [['lugar_nacimiento', 'domicilio'], 'string', 'max' => 150],
            [['telefono', 'telefono_alternativo'], 'string', 'max' => 20],
            [['dni'], 'unique'],
            [['nacionalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['nacionalidad' => 'idconfiguracion']],
            [['nivel_escolaridad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['nivel_escolaridad' => 'idconfiguracion']],
            [['genero'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['genero' => 'idconfiguracion']],
            [['estado_civil'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['estado_civil' => 'idconfiguracion']],
            [['idprovincia'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_provincia::className(), 'targetAttribute' => ['idprovincia' => 'idprovincia']],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::className(), 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idfspersona' => 'Idfspersona',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'dni' => 'Dni',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'lugar_nacimiento' => 'Lugar de Nacimiento',
            'nacionalidad' => 'Nacionalidad',
            'genero' => 'Genero',
            'estado_civil' => 'Estado Civil',
            'domicilio' => 'Domicilio',
            'idlocalidad' => 'Localidad',
            'idprovincia' => 'Provincia',
            'tiempo_provincia' => 'Tiempo de Residencia en la Provincia',
            'nivel_escolaridad' => 'Nivel de escolaridad Alcanzado',
            'profesion' => 'Profesion',
            'telefono' => 'Telefono',
            'telefono_alternativo' => 'Telefono Alternativo',
            'mail' => 'Mail',
            'grupo_familiar' => 'Grupo Familiar',
            'inscripto_rua' => '¿Alguna vez tuvo intenciones de hacerlo?',
            'motivo_fs' => 'Describa brevemente el motivo por el cual desea ser una familia solidaria',
            'acuerdo_familia' => '¿Hay acuerdo entre todos/as los/as miembros de su grupo conveniente para
            postularse como FS? ¿Quién lo propuso?',
            'conocimiento_programa' => '¿Cómo tomo conocimiento de la existencia del programa?',
            'disponibilidad_horaria' => '¿Qué disponibilidad horaria tiene para entrevistas?',
            'franja_etaria' => 'Franja de edades preferentes',
            'consulta' => 'Consulta',
            'fecha_hora' => 'Consulta o duda',
            'inscripto_rua_check' => '¿Se encuentra inscripto/a en el Registro Único de Adopción (RUA)?',
            'estado' => 'Estado',
            'informe_adjunto_path' => 'Informe Adjunto',
            'temp_informe_adjunto_path' => 'Seleccione un Archivo'
        ];
    }

    /**
     * Gets query for [[Nacionalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNacionalidad0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'nacionalidad']);
    }


    /**
     * Gets query for [[UltimoAnio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUltimoAnio()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'nivel_escolaridad']);
    }

    /**
     * Gets query for [[Genero0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenero0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'genero']);
    }

    /**
     * Gets query for [[EstadoCivil]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstadoCivil()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'estado_civil']);
    }

    /**
     * Gets query for [[Idprovincia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdprovincia0()
    {
        return $this->hasOne(Sds_com_provincia::className(), ['idprovincia' => 'idprovincia']);
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

    static public function getFecha($fecha) {
        $date = date_create($fecha);
        return date_format($date,"d/m/Y");
    }

    static public function getEstados() {
        return [ 0 => 'Inscripto', 1 => 'En proceso de evaluación', 2 => 'Reúne condiciones', 3 => 'No reúne condiciones', 4 => 'No continúa proceso de evaluación', 5 => 'No corresponde localidad'];
    }

    static public function getEstado($estado) {
        $estado_label = null;
        switch ($estado) {
            case 0:
                $estado_label = 'Inscripto';
                break;
            case 1:
                $estado_label = 'En proceso de evaluación';
                break;
            case 2:
                $estado_label = 'Reúne condiciones';
                break;
            case 3:
                $estado_label = 'No reúne condiciones';
                break;
            case 4:
                $estado_label = 'No continúa proceso de evaluación';
                break;
            case 5:
                $estado_label = 'No corresponde localidad';
                break;
        }
        return $estado_label;
    }
}
