<?php

namespace app\models;

use Yii;

/**
 * 
 * This is the model class for table "mds_org_documento".
 *
 * @property int $iddocumento
 * @property int $idusuario
 * @property int $tipo
 * @property string $nombre
 * @property string $fecha
 * @property string $path
 * @property string $detalle
 * @property int $idcontacto
 *
 * @property MdsOrgContacto $idcontacto0
 * @property SdsComConfiguracion $tipo0
 * @property MdsSegUsuario $idusuario0
 */
class Mds_org_documento extends \yii\db\ActiveRecord
{
    //Tipos Documentos Medicina Laboral:
    const DOC_CERTIFICADO_MEDICO = 2389;
    const DOC_LEG_MEDICO = 2411;

    //Estados Doc. Medicina Laboral:
    const DOC_AUDITAR_MED = 2391;
    const DOC_AUDITADO = 2392;
    const DOC_CARGADO_RH = 2393;
    const DOC_RECHAZADO = 2394;
    const DOC_NOTIFICADO = 2395;
    const DOC_PROCESADO = 2396;
    const DOC_AUDITAR_PSI = 2397;
    
    public $fdesde;
    public $fhasta;
    public $idpersona;
    public $nomAp;
    public $temp_archivo_adjunto;
    public $borrar_adjunto;
    public $medicina;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_documento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idusuario', 'tipo', 'nombre', 'fecha', 'path', 'detalle', 'idcontacto', 'estado'], 'required'],
            [['idusuario', 'tipo', 'idcontacto', 'estado'], 'integer'],
            [['fecha', 'idpersona', 'borrar_adjunto', 'nomAp', 'medicina'], 'safe'],
            [['path', 'detalle', 'path'], 'string'],
            [['nombre', 'nomAp'], 'string', 'max' => 255],
            [['temp_archivo_adjunto'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['idcontacto' => 'idcontacto']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['estado' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddocumento' => 'Iddocumento',
            'idusuario' => 'Idusuario',
            'tipo' => 'Tipo',
            'nombre' => 'Nombre',
            'fecha' => 'Fecha',
            'path' => 'Path',
            'detalle' => 'Detalle',
            'idcontacto' => 'Idcontacto',
            'path' => 'Archivo Adjunto',
            'estado' => 'Estado',
            'temp_archivo_adjunto' => 'Seleccionar un Archivo (imagen o PDF)'
        ];
    }

    /**
     * Gets query for [[Idcontacto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcontacto0()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'idcontacto']);
    }

    /**
     * Gets query for [[Tipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
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
}
