<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_conc_solicitud".
 *
 * @property int $idsolicitud
 * @property int $idusuario Usuario que carga
 * @property int $idusuario_borra Usuario que borra
 * 
 * @property string $documento
 * @property string $nombre
 * @property string $apellido
 * @property string $legajo
 * @property string $mail
 * @property string $telefono
 * @property string $domicilio_fiscal
 * @property string $deudores_morosos
 * @property string $registro_violencia
 * @property string $antecedente_nacional
 * @property string $titulo
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 */
class Mds_conc_solicitud extends \yii\db\ActiveRecord
{
    const PATH = "uploads/concurso/";
    const CARPETA_CONCURSO = '@web/uploads/concurso/';
    const CARPETA_CONCURSO_IMPUGNACION = '@web/uploads/concurso/impugnacion/';

    const ESTADO_ADMITIDO = 6370;
    const ESTADO_IMPUGNADO = 6402;
    const ESTADO_INSCRIPTO = 6368;
    const ESTADO_NO_ADMITIDO = 6401;
    const ESTADO_RECHAZADO = 6369;
    const ESTADO_SELECCIONADO = 6403;
    const ESTADO_REASIGNADO = 6549;
    const ESTADO_ASIGNACION_PROVISORIA = 6563;
    const ESTADO_NO_ASIGNADO = 6564;
    const ID_ROLES = [];
    public $desc_historial;
    public $estado_anterior;
    public $titulo_isRequired;
    public $categoria_actual;
    public $antiguedad;
    public $eventual;

    const ID_ROL_ADMIN_GENERAL = 209;
    const ID_ROL_ADMINISTRADOR = 210;
    const ID_ROL_ADMINISTRATIVO = 211;
    const ID_ROL_DASHBOARD = 216;
    const ID_ROLES_CONCURSOS = [209, 210, 211];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_conc_solicitud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documento', 'nombre', 'apellido', 'legajo', 'mail', 'telefono', 'domicilio_fiscal', 'idusuario', 'deudores_morosos', 'registro_violencia', 'antecedente_nacional', 'created_at', 'idconcurso'], 'required'],
            [['idsolicitud', 'idusuario', 'idusuario_borra', 'idconcurso'], 'integer'],
            [['created_at', 'desc_historial'], 'safe'],
            [['documento', 'nombre', 'apellido', 'legajo', 'mail', 'telefono', 'domicilio_fiscal', 'created_at', 'updated_at', 'deleted_at', 'desc_historial'], 'string'],
            [['domicilio_fiscal'], 'string', 'max' => 255],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
            [['deudores_morosos'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 52428800],
            [['registro_violencia'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 52428800],
            [['antecedente_nacional'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 52428800],
            [['titulo'], 'file', 'skipOnEmpty' => !$this->titulo_isRequired, 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 52428800],
            [['titulo_isRequired'], 'boolean'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idsolicitud' => '# Solicitud',
            'idconcurso' => 'Concurso',
            'documento' => 'Documento',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'legajo' => 'Legajo',
            'mail' => 'Correo electrónico',
            'telefono' => 'Teléfono',
            'domicilio_fiscal' => 'Domicilio Fiscal',
            'deudores_morosos' => 'Deudor Moroso',
            'registro_violencia' => 'Registro de Violencia',
            'antecedente_nacional' => 'Antecedente Nacional',
            'titulo' => 'Título',
            'idusuario' => 'Usuario de carga',
            'idusuario_borra' => 'Usuario borra',
            'created_at' => 'Fecha de carga',
            'updated_at' => 'Fecha de actualización',
            'deleted_at' => 'Activo',
            'categoria_actual' => 'Categoría Actual',
            'antiguedad' => 'Antigüedad',
            'eventual' => 'Eventual'
        ];
    }

    /**
     * Gets query for [[idusuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[idusuario_borra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
    }

    public function getPostulaciones()
    {
        return Mds_conc_postulacion::find()->where(['idsolicitud' => $this->idsolicitud, "deleted_at" => null])->all();
    }

    public function getConcRenaper()
    {
        return Mds_conc_renaper::find()->where(['dni' => $this->documento])->orderBy(['idconcrenaper' => SORT_DESC])->one();
    }

    public function getConcProneu()
    {
        return Mds_conc_proneu::find()->where(['nro_doc' => $this->documento])->orderBy(['idconcproneu' => SORT_DESC])->one();
    }

    public function getConcRhSur()
    {
        return Mds_org_padron::find()->where(['dni' => $this->documento])->orderBy(['idpadron' => SORT_DESC])->one();
    }

    public function getConcurso()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idconcurso']);
    }

    /**
     * Gets query for [[created_at]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaCarga()
    {
        $date = date_create($this->created_at);
        $fecha = date_format($date, 'd/m/Y');
        $hora = date_format($date, 'H:i');
        return "$fecha a las $hora" . "hs";
    }

    public static function getExtension($file)
    {
        $array = explode(".", $file);
        $extension = end($array);
        $extImagenes = array('jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp');
        if (in_array($extension, $extImagenes)) {
            return 'image';
        } else {
            return $extension;
        }
    }

    public static function getConcursosFiltro()
    {
        return Mds_conc_solicitud::find()
            ->select("configuracion.idconfiguracion, 
                configuracion.descripcion as concurso")
            ->from("mds_conc_solicitud as solicitud")
            ->innerJoin('sds_com_configuracion as configuracion', 'configuracion.idconfiguracion = solicitud.idconcurso')
            ->where("solicitud.deleted_at IS NULL 
                AND configuracion.activo = 1")
            ->orderBy(['concurso' => SORT_ASC])
            ->asArray()
            ->all();
    }
}
