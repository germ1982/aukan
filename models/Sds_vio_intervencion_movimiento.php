<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_vio_intervencion_movimiento".
 *
 * @property int $idintervencion
 * @property int $tipo_movimiento
 * @property int $idusuario
 * 
 * @property string $fecha
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * 
 * 
 * @property string|null $detalle
 * @property string|null $profesionales_intervinientes
 *
 * @property SdsVioIntervencion $idintervencion
 * @property SdsComConfiguracion $tipo_movimiento
 * @property MdsSegUsuario $idusuario
 */
class Sds_vio_intervencion_movimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_vio_intervencion_movimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idmovimiento'], 'unique'],
            [['idintervencion', 'tipo_movimiento', 'detalle', 'profesionales_intervinientes', 'fecha', 'idusuario'], 'required'],
            [['idmovimiento', 'idintervencion', 'tipo_movimiento', 'idusuario'], 'integer'],

            [['created_at', 'updated_at', 'deleted_at'], 'safe'],

            [['detalle', 'profesionales_intervinientes'], 'string'],
            [['profesionales_intervinientes'], 'string', 'max' => 150],

            [['idintervencion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_vio_intervencion::class, 'targetAttribute' => ['idintervencion' => 'idintervencion'], 'message' => 'Debe ingresar.'],
            [['tipo_movimiento'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo_movimiento' => 'idconfiguracion'], 'message' => 'Debe ingresar.'],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimiento' => '#',
            'idintervencion' => '#Intervención',
            'tipo_movimiento' => 'Tipo de Movimiento',
            'profesionales_intervinientes' => 'Profesionales que intervinieron',
            'detalle' => 'Detalle',
            'fecha' => 'Fecha',
            'fecha_alta' => 'Fecha de alta',
            'idusuario' => 'Usuario de alta',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Activo',
        ];
    }

    /**
     * Gets query for [[idintervencion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIntervencion()
    {
        return $this->hasOne(Sds_vio_intervencion::class, ['idintervencion' => 'idintervencion']);
    }

    /**
     * Gets query for [[tipo_movimiento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoMovimiento()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo_movimiento']);
    }

    /**
     * Gets query for [[idusuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    public static function getMovimientosByIntervencion($idintervencion)
    {
        return Sds_vio_intervencion_movimiento::find()
            ->select('
            sds_vio_intervencion_movimiento.idmovimiento, 
            sds_vio_intervencion_movimiento.profesionales_intervinientes, 
            sds_vio_intervencion_movimiento.detalle, 
            sds_com_configuracion.descripcion as tipo_movimiento, 
            ')
            ->addSelect(["DATE_FORMAT(sds_vio_intervencion_movimiento.fecha, '%d-%m-%Y') as fecha"])
            ->innerJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion = sds_vio_intervencion_movimiento.tipo_movimiento')
            ->where(['idintervencion' => $idintervencion])
            ->andWhere(['sds_vio_intervencion_movimiento.deleted_at' => null])
            ->asArray()
            ->all();
    }
}
