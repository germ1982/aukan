<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_contacto".
 *
 * @property int $idcontacto

 * @property string $mail
 * @property string $telefono
 * @property int $iddispositivo
 * @property int $activo
 * @property int $rotativo
 *
 * @property MdsOrgDispositivo $iddispositivo0
 * @property MdsSegUsuario[] $mdsSegUsuarios
 * @property SdsRegRegistro[] $sdsRegRegistros
 */
class Mds_org_contacto extends \yii\db\ActiveRecord
{
    //'0: Planta Política; 1: Planta Permanente; 2: Eventuales; 3: Contrato'
    const TIPO_CONTRATACION_PLANTA_POLITICA = 0;
    const TIPO_CONTRATACION_PLANTA_PERMANENTE = 1;
    const TIPO_CONTRATACION_EVENTUALES = 2;
    const TIPO_CONTRATACION_CONTRATO = 3;
    const TIPO_CONTRATACION_PLANTA_POLITICA_PURA = 4;

    public $idorganismo;
    public $sexo;
    public $nacionalidad;
    public $fecha_nacimiento;
    public $documento;
    public $nombre;
    public $apellido;
    public $crear_usuario = false;
    public $organismo_search; //lo uso para filtro
    public $foto_dni; //foto DNI base64
    public $temp_excel_import;
    public $idlocalidad;
    public $calle;
    public $numero;
    public $codigo_postal;
    public $descripcion_actividad;//Creado para mostrar en organigrama

    //Agregado para reporte de fichadas:
    public $edificio;
    public $fichadas;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_contacto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'iddispositivo', 'documento', 'sexo', 'activo', 'legajo', 'idpersona', 'rotativo', 'acompaniante', 'interno',
                'perfil', 'idoficina', 'actividad', 'esencial', 'categoria', 'unidad_operativa',
                'nacionalidad', 'tipo_contratacion', 'planta_politica', 'eventual', 'nacionalidad', 'idorganismo',
                'turno_rotativo', 'ficha', 'retenido'
            ], 'integer'],
            [['fecha_nacimiento', 'iddispositivo', 'sexo', 'nacionalidad', 'documento', 'nombre', 'apellido'], 'required'],
            [[
                'mail', 'telefono', 'legajo', 'ubicacion_fisica', 'idorganismo', 'crear_usuario',
                'organismo_search', 'foto_dni', 'tipo_contratacion', 'eventual', 'planta_politica', 'unidad_operativa',
                'antiguedad_administrativa', 'antiguedad_privada', 'antiguedad_total', 'cuil', 'fecha_ingreso', 'actividad',
                'idoficina', 'categoria', 'titulo', 'fecha_ingreso_planta', 'idlocalidad', 'calle', 'numero', 'codigo_postal',
                'edificio', 'fichadas', 'descripcion_actividad', 'servicio', 'observaciones'
            ], 'safe'],
            [['antiguedad_administrativa', 'antiguedad_privada', 'antiguedad_total'], 'number'],
            [['mail','norma_legal'], 'string', 'max' => 100],
            [['titulo'], 'string', 'max' => 200],
            [['telefono'], 'string', 'max' => 50],
            [['ubicacion_fisica'], 'string', 'max' => 45],
            [['cuil'], 'string', 'max' => 11],
            [['codigo_postal'], 'string', 'max' => 8],
            [['idpersona'], 'unique'],
            [['actividad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['actividad' => 'idconfiguracion']],
            [['categoria'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['categoria' => 'idconfiguracion']],
            [['iddispositivo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_dispositivo::class, 'targetAttribute' => ['iddispositivo' => 'iddispositivo']],
            [['idoficina'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_oficina::class, 'targetAttribute' => ['idoficina' => 'idoficina']],
            [['perfil'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['perfil' => 'idconfiguracion']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['unidad_operativa'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['unidad_operativa' => 'idconfiguracion']],
            [['temp_excel_import'], 'file', 'extensions' => 'xlsx,xls', 'maxSize' => 100000000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcontacto' => 'Contacto',
            'mail' => 'Mail',
            'telefono' => 'Teléfono',
            'iddispositivo' => 'Dispositivo',
            'activo' => 'Activo',
            'turno_rotativo' => 'Turno Rotativo',
            'legajo' => 'Legajo',
            'ubicacion_fisica' => 'Ubicación Física',
            'idorganismo' => 'Organismo',
            'rotativo' => 'Sem. no Cal.',
            'eventual' => 'Eventual',
            'esencial' => 'Esencial',
            'acompaniante' => 'Categoria',
            'idoficina' => ' Oficina',
            'planta_politica' => 'Planta Política',
            'categoria' => 'Categoría',
            'tipo_contratacion' => 'Tipo Contratación',
            'titulo' => 'Título',
            'fecha_ingreso_planta' => 'Fecha Ingreso a Planta Permanente',
            'ficha' => 'Ficha',
            'retenido' => 'Retenido',
            'perfil' => 'Perfil',
            'actividad' => 'Actividad',
            'servicio' => 'Servicio',
            'observaciones' => 'Observaciones'
        ];
    }

    /**
     * Gets query for [[Iddispositivo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDispositivo()
    {
        return $this->hasOne(Mds_org_dispositivo::class, ['iddispositivo' => 'iddispositivo']);
    }


    public function getOrganismo()
    {
        return $this->hasOne(Mds_org_organismo::class, ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[MdsSegUsuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsSegUsuarios()
    {
        return $this->hasMany(Mds_seg_usuario::class, ['idcontacto' => 'idcontacto']);
    }

    /**
     * Gets query for [[SdsRegRegistros]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRegRegistros()
    {
        return $this->hasMany(Sds_reg_registro::class, ['usuario_solicitante' => 'idcontacto']);
    }

    public static function getTodoslosCont($iddispositivo)
    {
        return Mds_org_contacto::findBySql(
            "SELECT c.*, p.*, IFNULL(conf.descripcion,'No Seteado') AS descripcion_actividad
            FROM mds_org_contacto c
            JOIN sds_com_persona p ON p.idpersona=c.idpersona
            LEFT JOIN sds_com_configuracion conf ON conf.idconfiguracion=c.actividad
            WHERE c.activo=1 AND c.iddispositivo = " . $iddispositivo)->all();
    }

    public static function getContactos()
    {
        return Mds_org_contacto::findBySql(
            "SELECT c.idcontacto as idcontacto, concat(p.apellido,' ',p.nombre) as apellido
            FROM mds_org_contacto c
            JOIN sds_com_persona p on c.idpersona = p.idpersona
            order by p.apellido, p.nombre")->all();
    }

    public static function eliminar_tildes($cadena)
    {
        //Codificamos la cadena en formato utf8 en caso de que nos de errores
        //$cadena = utf8_encode($cadena);
        //Ahora reemplazamos las letras
        $cadena = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $cadena
        );
        $cadena = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $cadena
        );
        $cadena = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $cadena
        );
        $cadena = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $cadena
        );
        $cadena = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $cadena
        );
        $cadena = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C'),
            $cadena
        );
        return $cadena;
    }

    public static function getAyN($idcontacto){
        $contacto=Mds_org_contacto::findBySql(
            "SELECT * FROM mds_org_contacto c
            JOIN sds_com_persona p ON c.idpersona=p.idpersona 
            WHERE c.idcontacto=".$idcontacto)->one();
        if($contacto!=null){
            return $contacto->apellido.' '.$contacto->nombre;
        }
        return null;
    }

    public static function get_internos($idcontacto)
    {
        $iddispositivo = Mds_org_contacto::findOne($idcontacto)->iddispositivo;
        $internos = Sds_reg_interno::find()->where("iddispositivo = $iddispositivo")->all();
        $txt_internos = '';
        foreach($internos as $interno)
            {
                $txt_internos = "$txt_internos -$interno->idinterno-"; 
            }
        return $txt_internos;

    }
}