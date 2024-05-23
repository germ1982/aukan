<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_conc_historial".
 *
 * @property int $idhistorial
 * @property int $idusuario
 * @property int $idusuario_borra Usuario que borra
 * @property int $idpostulacion
 * @property string|null $observacion
 * @property string|null $observacion_publica
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $estado_nuevo
 * @property int|null $estado_anterior
 * @property SdsComConfiguracion int|null $estado_nuevo
 * @property SdsComConfiguracion int|null $estado_anterior
 * @property Mds_conc_postulacion int|null $idpostulacion
 * @property MdsSegUsuario $idusuario
 * @property MdsSegUsuario $idusuario_borra
 */
class Mds_conc_historial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $estado_aux_anterior;
    public $estado_aux;
    public $cad_estado;
    public static function tableName()
    {
        return 'mds_conc_historial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpostulacion', 'estado_nuevo', 'estado_anterior', 'idusuario', 'idusuario_borra'], 'integer'],
            [['observacion', 'observacion_publica'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['estado_nuevo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['estado_nuevo' => 'idconfiguracion']],
            [['estado_anterior'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['estado_anterior' => 'idconfiguracion']],
            [['idpostulacion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_conc_postulacion::class, 'targetAttribute' => ['idpostulacion' => 'idpostulacion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idhistorial' => '#',
            'idpostulacion' => '# Postulación',
            'observacion' => 'Observación',
            'observacion_publica' => 'Observación pública',
            'created_at' => 'Fecha',
            'deleted_at' => 'Activo',
            'idusuario' => 'Usuario carga',
            'estado_nuevo' => 'Estado nuevo',
            'estado_anterior' => 'Estado anterior',
            'estado_aux' => 'Estado Actual de la postulacion',
        ];
    }

    /**
     * Gets query for [[estado_nuevo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNuevoEstado()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'estado_nuevo']);
    }

    /**
     * Gets query for [[estado_anterior]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAnteriorEstado()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'estado_anterior']);
    }

    /**
     * Gets query for [[idpostulacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostulacion0()
    {
        return $this->hasOne(Mds_conc_postulacion::class, ['idpostulacion' => 'idpostulacion']);
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

    public static function getEstadoFiltro($stringAtributo, $idpostulacion)
    {
        $where = "historial.deleted_at IS NULL 
        AND configuracion.activo = 1";

        if ($idpostulacion) {
            $where .= " AND historial.idpostulacion = $idpostulacion";
        }

        return Mds_conc_historial::find()
            ->select("configuracion.idconfiguracion, 
                configuracion.descripcion")
            ->from("mds_conc_historial as historial")
            ->innerJoin('sds_com_configuracion as configuracion', "configuracion.idconfiguracion = historial.$stringAtributo")
            ->where($where)
            ->orderBy(['descripcion' => SORT_ASC])
            ->asArray()
            ->all();
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

    public static function getMotivosImpugnacionByIdHistorial($idHistorial)
    {
        return Mds_conc_historial::find()
            ->select("impungacionMotvio.idconcimpugnacionmotivo, configuracion.idconfiguracion, configuracion.descripcion")
            ->from("mds_conc_historial as historial")
            ->innerJoin('mds_conc_impugnacion_motivo as impungacionMotvio', "historial.idhistorial = impungacionMotvio.idhistorial")
            ->innerJoin('sds_com_configuracion as configuracion', "impungacionMotvio.idmotivo = configuracion.idconfiguracion")
            ->where(['historial.idhistorial' => $idHistorial, 'historial.deleted_at' => null, 'impungacionMotvio.deleted_at' => null])
            ->asArray()
            ->all();
    }
}
