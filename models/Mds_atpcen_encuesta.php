<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_atpcen_encuesta".
 *
 * @property int $id_atpcen
 * @property int|null $id_persona_carga
 * @property string|null $fecha_alta
 * @property string|null $hora_alta
 * @property int|null $id_entrevistador
 * @property string|null $fecha_hora_entrevista
 * @property int|null $id_localidad_entrevista
 * @property string|null $dni_beneficiario
 * @property int|null $id_risneu
 * @property int|null $tip_control
 * @property string|null $telefono_contacto1
 * @property string|null $telefono_contacto2
 * @property string|null $email
 * @property string|null $tipo_documento_tutor
 * @property string|null $documento_tutor
 * @property string|null $cuil_tutor
 * @property string|null $apellido_tutor
 * @property string|null $fecha_nac_tutor
 * @property string|null $parentezco_tutor
 * @property string|null $frente_dni
 * @property string|null $dorso_dni
 * @property int|null $id_fuente_ingreso foranea aSds_com_configuracion
 * @property int|null $sexo_tutor
 * @property string|null $nombre_tutor
 * @property int|null $tiene_obra_social
 * @property string|null $obra_social
 * @property int|null $tiene_biopsia
 * @property string|null $fecha_diagnostico
 * @property string|null $estudio_biopsia
 * @property int|null $concurre_a_control
 * @property int|null $frecuencia
 * @property int|null $integrante_celiaco 0: falso 1:verdadero
 * @property int|null $establecimiento_salud
 * @property int|null $id_establ_salud foranea a Sds_com_configuracion
 * @property int|null $organismo_asiste
 * @property int|null $cantidad_asistencia
 * @property int|null $periocidad_asistencia
 * @property string|null $capacitacion_solicitada
 * @property string|null $observacion
 */
class Mds_atpcen_encuesta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $dni;
    public $nombre;
    public $apellido;
    public $localidad;
    public $fecha_nacimiento;
    public $mensaje;
    public $nacionalidad;
    public $sexo;
    public $la_provincia;
    public $persona;
    public $archivo_foto_dni;
    public $archivo_foto_dnidorso;
    public $archivo_biopsia;
    public $idlocalidad;
    public $cod_postal;
    public $loc_prov;
    public $loc_prov_e;
    public $cad_vulnerabilidad;
    
    public static function tableName()
    {
        return 'mds_atpcen_encuesta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vulnerabilidad_social','modulo_alimento','interes_capacitacion','tarjeta_atpcen','id_persona_carga',  'id_localidad_entrevista', 'id_risneu', 'tip_control', 'id_fuente_ingreso',  'tiene_obra_social', 'tiene_biopsia', 'concurre_a_control', 'frecuencia', 'integrante_celiaco',  'id_establ_salud', 'periocidad_asistencia'], 'integer'],
            [['cad_vulnerabilidad','loc_prov_e','loc_prov','cod_postal','idlocalidad','persona', 'hora_entrevista','nacionalidad','sexo','nombre','apellido','localidad','fecha_nacimiento','mensaje','dni','hora_alta','fecha_alta', 'fecha_hora_entrevista', 'fecha_nac_tutor', 'fecha_diagnostico'], 'safe'],
            [['sexo_tutor','frente_dni', 'dorso_dni', 'estudio_biopsia', 'capacitacion_solicitada', 'observacion'], 'string'],
            [['condiciones', 'dni_beneficiario', 'telefono_contacto1', 'telefono_contacto2', 'tipo_documento_tutor', 'documento_tutor'], 'string', 'max' => 20],
            [['email', 'parentezco_tutor'], 'string', 'max' => 60],
            [['cantidad_asistencia','organismo_asiste','establecimiento_salud'], 'string', 'max' => 100],
            
            [['cuil_tutor'], 'string', 'max' => 30],
            [['apellido_tutor'], 'string', 'max' => 70],
            [['entrevistador','nombre_tutor', 'obra_social'], 'string', 'max' => 100],

            [['archivo_foto_dni'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],            
            [['archivo_foto_dnidorso'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],                        
            [['archivo_biopsia'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_atpcen' => 'Id Atpcen',
            'id_persona_carga' => 'Id Persona Carga',
            'fecha_alta' => 'Fecha Alta',
            'hora_alta' => 'Hora Alta',
            'entrevistador' => 'Entrevistador',
            'fecha_hora_entrevista' => 'Fecha de la Entrevista',
            'hora_entrevista' => 'Hora Entrevista',
            'dni_beneficiario' =>'Suficiente',
            'id_localidad_entrevista' => 'Id Localidad Entrevista',
            'dni_beneficiario' => 'Dni Beneficiario',
            'id_risneu' => 'Id Risneu',
            'tip_control' => 'Tip Control',
            'telefono_contacto1' => 'Telefono Contacto1',
            'telefono_contacto2' => 'Telefono Contacto2',
            'email' => 'Email',
            'tipo_documento_tutor' => 'Tipo Documento Tutor',
            'documento_tutor' => 'Documento Tutor',
            'cuil_tutor' => 'Cuil Tutor',
            'apellido_tutor' => 'Apellido Tutor',
            'fecha_nac_tutor' => 'Fecha Nac Tutor',
            'parentezco_tutor' => 'Parentezco Tutor',
            'frente_dni' => 'Frente Dni',
            'dorso_dni' => 'Dorso Dni',
            'id_fuente_ingreso' => 'Id Fuente Ingreso',
            'sexo_tutor' => 'Sexo Tutor',
            'nombre_tutor' => 'Nombre Tutor',
            'tiene_obra_social' => 'Tiene Obra Social',
            'obra_social' => 'Obra Social',
            'tiene_biopsia' => 'Tiene Biopsia',
            'fecha_diagnostico' => 'Fecha Diagnostico',
            'estudio_biopsia' => 'Estudio Biopsia',
            'concurre_a_control' => 'Concurre A Control',
            'frecuencia' => 'Frecuencia',
            'integrante_celiaco' => 'Integrante Celiaco',
            'establecimiento_salud' => 'Establecimiento Salud',
            'id_establ_salud' => 'Id Establ Salud',
            'organismo_asiste' => 'Organismo Asiste',
            'cantidad_asistencia' => 'Cantidad Asistencia',
            'periocidad_asistencia' => 'Periocidad Asistencia',
            'capacitacion_solicitada' => 'Capacitacion Solicitada',
            'observacion' => 'Observacion',
            'tarjeta_atpcen' => 'Tarjeta para celiacos',
            'interes_capacitacion' => 'Interes capacitacion',
            'modulo_alimento' => 'Modulo de alimento',
            
                        
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
