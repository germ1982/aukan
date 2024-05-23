<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "Mds_cor_intervencion_problema".
 *
 * @property int $idintervencionproblema
 * @property int $idintervencion
 * @property int $idproblema
 * @property int $idusuario_carga Usuario que carga
 * @property int $idusuario_borra Usuario que borra * 
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 *
 */
class Mds_cor_intervencion_problema extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_cor_intervencion_problema';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idintervencion', 'idproblema', 'idusuario_carga', 'created_at'], 'required'],
            [['idintervencionproblema', 'idintervencion', 'idproblema', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['created_at', 'deleted_at', 'updated_at'], 'safe'],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idintervencionproblema' => '#',
            'idintervencion' => '# Intervención',
            'idproblema' => 'Articulación interinstitucional',
            'idusuario_carga' => 'Usuario de carga',
            'idusuario_borra' => 'Usuario borra',
            'created_at' => 'Fecha de creación',
            'updated_at' => 'Fecha de modificación',
            'deleted_at' => 'Activo',
        ];
    }

    /**
     * Gets query for [[idusuario_carga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
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

    /**
     * Gets query for [[idintervencion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIntervencion()
    {
        return $this->hasOne(Mds_cor_intervencion::class, ['idintervencion' => 'idintervencion']);
    }

    /**
     * Gets query for [[idproblema]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProblema()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idproblema']);
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
        return $fecha . ' a las ' . $hora . ' hs';
    }

    public static function getListProblemas()
    {
        $problemas = Sds_com_configuracion::find()->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::COR_INTERVENCION_PROBLEMA, 'activo' => 1])->orderBy(['descripcion' => SORT_ASC])->asArray()->all();
        return ArrayHelper::map($problemas, 'idconfiguracion', 'descripcion');
    }

    public static function getProblemasCargadosByIdIntervencion($idIntervencion)
    {
        return Mds_cor_intervencion::find()
            ->select("problema.idintervencionproblema, configuracion.idconfiguracion, configuracion.descripcion")
            ->from("mds_cor_intervencion as intervencion")
            ->innerJoin('mds_cor_intervencion_problema as problema', "intervencion.idintervencion = problema.idintervencion")
            ->innerJoin('sds_com_configuracion as configuracion', "problema.idproblema = configuracion.idconfiguracion")
            ->where(['intervencion.idintervencion' => $idIntervencion, 'intervencion.deleted_at' => null, 'problema.deleted_at' => null])
            ->asArray()
            ->all();
    }
}
