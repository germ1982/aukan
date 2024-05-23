<?php

namespace app\models;
use Yii;


/**
 * This is the model class for table "mds_atp_solicitud".
 *
 * @property int $id
 * @property string $documento
 * @property string $nombre
 * @property string $apellido
 * @property string $fecha_nacimiento
 * @property string $foto_dni
 * @property string $foto_certificado
 * @property int $carga_grupo_familiar 1="1", 2="2", 3="3", 4="4", 5="5", 6="6 o más"
 * @property int $ingreso_grupo_familiar 1="menos de $10000", 2="entre $10000 y $20000", 3="entre $20000 y $30000", 4="entre $30000 y $40000", 5="entre $40000 y $50000", 6="más de $50000"
 * @property string $telefono
 * @property string $telefono_alternativo
 * @property string $email
 * @property string|null $tutor_documento
 * @property string|null $tutor_nombre
 * @property string|null $tutor_apellido
 * @property string|null $tutor_parentesco
 * @property string|null $tutor_fecha_nacimiento
 * @property string|null $tutor_foto_dni
 * @property int $estado
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Mds_atp_solicitud extends \yii\db\ActiveRecord
{    
    public $archivo_foto_dni;
    public $archivo_foto_dnidorso;
    public $archivo_foto_certificado;
    public $archivo_tutor_foto_dni;
    public $archivo_tutor_foto_dnidorso;
    public $desc_historial;
    public $estado_anterior;
    public $cad_estado;

    const INSCRIPTO = 1;
    const RECHAZADO = 2;
    const PENDIENTE_ALTA = 3;
    const APROBADO = 4;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_atp_solicitud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documento', 'nombre', 'apellido', 'fecha_nacimiento', 'foto_dni','foto_dnidorso', 'foto_certificado',   'telefono', 'telefono_alternativo', 'email', 'estado', 'retirada'], 'required'],
            [['estado_anterior','desc_historial','cuil','fecha_nacimiento', 'tutor_fecha_nacimiento','tutor_foto_dni','tutor_foto_dnidorso'], 'safe'],
            [['digito_verificador','numero_cuenta','entidad','sucursal','carga_grupo_familiar', 'ingreso_grupo_familiar', 'estado', 'created_at', 'updated_at', 'idlocalidad', 'retirada'], 'integer'],
            [['tutor_sexo','documento', 'tutor_documento'], 'string', 'max' => 20],
            [['direccion','localidad','nombre', 'apellido', 'email'], 'string', 'max' => 255],
            [['tutor_cuil','cuil','tipo_documento','tutor_tipo_documento','telefono', 'telefono_alternativo', 'tutor_nombre', 'tutor_apellido', 'tutor_parentesco'], 'string', 'max' => 100],
            [['sexo','tutor_sexo' ], 'string', 'max' => 100],      
            [['cad_estado'], 'string'],   
            [['foto_dni','foto_dnidorso','foto_certificado', 'tutor_foto_dni','tutor_foto_dnidorso'], 'string'],
            [['archivo_foto_dni'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],            
            [['archivo_foto_dnidorso'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],                        
            [['archivo_foto_certificado'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            [['archivo_tutor_foto_dni'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            [['archivo_tutor_foto_dnidorso'], 'image', 'extensions' => 'jpg, jpeg, gif, png', 'maxSize' => 1000000],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::className(), 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'documento' => 'Documento',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'foto_dni' => 'Foto Dni Frente',
            'foto_dnidorso' => 'Foto Dni Dorso',
            'foto_certificado' => 'Foto Certificado',
            'carga_grupo_familiar' => 'Carga Grupo Familiar',
            'ingreso_grupo_familiar' => 'Ingreso Grupo Familiar',
            'telefono' => 'Telefono',
            'telefono_alternativo' => 'Telefono Alternativo',
            'email' => 'Email',
            'tutor_documento' => 'Tutor Documento',
            'tutor_nombre' => 'Tutor Nombre',
            'tutor_apellido' => 'Tutor Apellido',
            'tutor_parentesco' => 'Tutor Parentesco',
            'tutor_fecha_nacimiento' => 'Tutor Fecha Nacimiento',
            'tutor_foto_dni' => 'Tutor Foto Dni',
            'tutor_foto_dnidorso' => 'Tutor Foto Dni Dorso',            
            'estado' => 'Estado',
            'created_at' => 'Fecha de Inscripcion',
            'updated_at' => 'Updated At',
            'cuil'=> 'Cuil',
            'tipo_documento'=> 'Tipo de Documento',
            'sexo'=> 'Sexo',
            'direccion' =>'Direccion',
            'localidad' => 'Localidad',
            'tutor_cuil'=> 'Tutor Cuil',
            'tutor_sexo' => 'Tutor Sexo',
            'tutor_tipo_documento'=>'Tutor Tipo Documento',
            'idlocalidad' => 'Localidad',
        ];
    }

}
