<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_org_situacion".
 *
 * @property int $idsituacion
 * @property int $idcontacto
 * @property int $idcapaitem
 * @property string $inicio
 * @property string|null $fin
 * @property string $descripcion
 * @property string $profesional_firma
 * @property string $dias_horarios
 * @property int $iddocumento
 *
 * @property SdsGisCapaItem $idcapaitem0
 * @property MdsOrgContacto $idcontacto0
 * @property MdsOrgDocumento $iddocumento0
 */
class Mds_org_situacion extends \yii\db\ActiveRecord
{

    public $temp_archivo_adjunto;
    //temporal para mapa
    public $direccion;
    public $path;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_org_situacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcontacto', 'idcapaitem', 'inicio', 'descripcion', 'profesional_firma', 'dias_horarios', 'iddocumento'], 'required'],
            [['idcontacto', 'idcapaitem', 'iddocumento'], 'integer'],
            [['inicio', 'fin'], 'safe'],
            [['path','funcion','detalles'], 'string'],
            [['temp_archivo_adjunto'], 'file', 'extensions' => 'jpg, jpeg, gif, png, pdf', 'maxSize' => 1000000],
            [['descripcion', 'profesional_firma', 'dias_horarios'], 'string', 'max' => 100],
            [['idcapaitem'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa_item::className(), 'targetAttribute' => ['idcapaitem' => 'idcapaitem']],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['idcontacto' => 'idcontacto']],
            [['iddocumento'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_documento::className(), 'targetAttribute' => ['iddocumento' => 'iddocumento']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idsituacion' => 'Id',
            'idcontacto' => 'Empleado',
            'idcapaitem' => 'Domicilio Laboral',
            'inicio' => 'Inicio',
            'fin' => 'Fin',
            'descripcion' => 'Situación',
            'profesional_firma' => 'Profesional que Firma',
            'dias_horarios' => 'Días Horarios',
            'iddocumento' => 'Documento',
            'temp_archivo_adjunto' => 'Archivo Adjunto',
            'funcion' => 'Función',
            'detalles' => 'Detalles'
        ];
    }

    /**
     * Gets query for [[Idcapaitem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcapaitem0()
    {
        return $this->hasOne(Sds_gis_capa_item::className(), ['idcapaitem' => 'idcapaitem']);
    }

    /**
     * Gets query for [[Idcontacto0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcontacto0()
    {
        return $this->hasOne(Mds_org_contacto::className(), ['idcontacto' => 'idcontacto']);
    }

    /**
     * Gets query for [[Iddocumento0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddocumento0()
    {
        return $this->hasOne(Mds_org_documento::className(), ['iddocumento' => 'iddocumento']);
    }
}
