<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_cap_instancia".
 *
 * @property int $idinstancia
 * @property int $idcapacitacion
 * @property string $descripcion
 * @property int $presencial
 * @property string $desde
 * @property string $hasta
 * @property string $lugar
 * @property string $detalle
 * @property int $idusuario
 * @property int $resolucion_aval
 * @property int $imagen_path
 * 
 *
 * @property MdsCapCapacitacion $idcapacitacion0
 * @property MdsSegUsuario $idusuario0
 */
class Mds_cap_instancia extends \yii\db\ActiveRecord
{
    public $fdesde_desde;
    public $fdesde_hasta;
    public $fhasta_desde;
    public $fhasta_hasta;
    public $temp_imagen;
    public $temp_logo1;
    public $temp_logo_princ;
    public $docentes;
    public $docentes_no_firmantes;
    public $inscriptos;
    public $lista_firmas;
    public $lista_firmas_aux;
    public $borrar_imagen;
    public $borrar_logo_princ;
    public $borrar_logo1;
    
    const ESTADO_ACTIVA = 1;
    const ESTADO_NO_ACTIVA = 0;
    const PRIVACIDAD_PUBLICA = 1;
    const PRIVACIDAD_PRIVADA = 0;
    const PRIVACIDAD_EXTERNA = 2;
    const MODALIDAD_PRESENCIAL = 0;
    const MODALIDAD_VIRTUAL = 1;
    const MODALIDAD_DUAL = 2;
    const LISTA_ESPERA_NO= 0;
    const LISTA_ESPERA_SI= 1;
    const CONST_NOT_ADMIN = 1;
    const TIPO_CUMBRE = 0;
    const TIPO_EXTERNO = 1;
    const TIPO_VISIBLE = 2;

    const ID_ROL_ACREDITACION = 194;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cap_instancia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcapacitacion', 'descripcion', 'desde', 'hasta', 'lugar', 'detalle', 'idusuario', 'estado', 'fecha_limite', 'privacidad', 'alias', 'capacidad', 'cant_horas', 'presencial'], 'required'],
            [['inscripcion_externa','notificar_admin','idcapacitacion', 'presencial', 'idusuario', 'estado', 'privacidad', 'capacidad', 'cant_horas', 'inscriptos', 'lista_espera','capacidad_espera','idcampania', 'tipo'], 'integer'],
            [['resolucion_aval','desde', 'hasta', 'fdesde_desde', 'fdesde_hasta', 'fhasta_desde', 'fhasta_hasta', 'fecha_limite', 'fecha_inscripcion','docentes', 'docentes_no_firmantes','borrar_imagen','borrar_logo_princ','borrar_logo1','fecha_publicacion_cert'], 'safe'],
            [['email_administrador','lugar', 'detalle', 'observacion', 'alias', 'titulo_dato_adicional', 'area_certificado', 'resolucion_aval','link_video'], 'string'],
            [['enlace_ext','descripcion','imagen_path'], 'string', 'max' => 255],            
            [['alias'], 'unique'],
            ['alias', 'match', 'pattern' => '/^[a-z0-9_-]+$/', 'message' => 'Ingresar sin espacios y en minusculas.'],
            ['capacidad', 'match', 'pattern' => '/^[0-9]+$/', 'message' => 'Ingresar solo números.'],
            ['cant_horas', 'match', 'pattern' => '/^[0-9]+$/', 'message' => 'Ingresar solo números.'],
            [['capacidad', 'cant_horas'], 'number', 'min' => 0],
            [['temp_imagen'], 'file', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            [['temp_logo1'], 'file', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            [['temp_logo_princ'], 'file', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            [['idcapacitacion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_cap_capacitacion::className(), 'targetAttribute' => ['idcapacitacion' => 'idcapacitacion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idinstancia' => 'Idinstancia',
            'idcapacitacion' => 'Idcapacitacion',
            'descripcion' => 'Nombre de Instancia',
            'presencial' => 'Modalidad',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'lugar' => 'Lugar',
            'detalle' => 'Detalle',
            'idusuario' => 'Idusuario',
            'estado' => 'Estado',
            'fecha_limite' => 'Fecha Límite Inscripción',
            'fecha_inscripcion' => 'Fecha Inicio Inscripción',
            'observacion' => 'Datos para poner en el mail (Link de zoom, contrato, etc) - OPCIONAL',
            'temp_imagen' => 'Seleccionar un Archivo (imagen)',
            'privacidad' => 'Privacidad',
            'alias' => 'Alias para el link de Cumbre',
            'titulo_dato_adicional' => 'Dato Adicional',
            'cant_horas' => 'Cantidad de Horas Total',
            'area_certificado' => 'Áreas intervinientes',
            'resolucion_aval' => 'Resolución/Ley/Aval',           
            'temp_logo1' => 'Seleccionar la imagen del logo adicional que debe salir en el certificado',
            'temp_logo_princ' => 'Seleccionar la imagen del logo principal que debe salir en el certificado',
            'tipo'=>'Tipo de Inscripción',
            
            'lista_espera' => '¿Inscripciones en Lista de Espera?',
            'capacidad_espera'=>'Capacidad de Lista de Espera',
            'notificar_admin'=>'Notificar inscripciones al administrador',
            'enlace_ext'=>'Enlace Externo para inscripcion',
            'inscripcion_externa'=>'Inscripción a link externo',
            'email_administrador'=>'Correo electrónico del administrador',
            'link_video'=> 'Link de video de youtube (Opcional)',
            'idcampania'=> 'Campaña asociada  (Opcional)',
            'fecha_publicacion_cert' => 'Fecha de Publicación'
        ];
    }

    /**
     * Gets query for [[Idcapacitacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcapacitacion0()
    {
        return $this->hasOne(Mds_cap_capacitacion::className(), ['idcapacitacion' => 'idcapacitacion']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
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
