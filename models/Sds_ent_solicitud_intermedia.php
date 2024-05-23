<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ent_solicitud_intermedia".
 *
 * @property int $idsolicitudintermedia
 * @property int $emisor
 * @property int $receptor
 * @property string $fecha_hora
 * @property int $irregular irregular es cuando el receptor tiene rendiciones pendientes
 * @property int $usuario_carga
 * @property int $usuario_aprobacion
 * @property int $estado 0: pendiente, 1: aprobada, 2: rechazada
 * @property int $idtipo
 * @property int $cantidad
 * @property string|null $rendiciones_pendientes detalla: Fecha - Saldo - Tipo de cada rendicion pendiente
 * @property string|null $observaciones
 *
 * @property SdsEntEntrega $emisor0
 * @property SdsComConfiguracion $receptor0
 * @property MdsSegUsuario $usuarioCarga
 * @property MdsSegUsuario $usuarioAprobacion
 * @property SdsEntTipo $idtipo0
 */
class Sds_ent_solicitud_intermedia extends \yii\db\ActiveRecord
{
    const ESTADO_PENDIENTE = 0;
    const ESTADO_APROBADA = 1;
    const ESTADO_RECHAZADA = 2;
    const ESTADO_ENTREGADA = 3;

    public $fdesde;
    public $fhasta;
    public $hora;
    public $saldo;
    public $motivo_rechazo;
    public $nombre_receptor;
    public $datos_emisor; 

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ent_solicitud_intermedia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emisor', 'receptor', 'fecha_hora', 'usuario_carga', 'idtipo', 'cantidad'], 'required'],
            [['emisor', 'receptor', 'irregular', 'usuario_carga', 'usuario_aprobacion', 'estado', 'idtipo', 'saldo', 'cantidad'], 'integer'],
            [['fecha_hora', 'hora', 'fecha_aprobacion', 'fdesde', 'fhasta', 'motivo_rechazo'], 'safe'],
            [['rendiciones_pendientes', 'observaciones', 'motivo_rechazo'], 'string'],
            [['emisor'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ent_entrega::className(), 'targetAttribute' => ['emisor' => 'identrega']],
            [['receptor'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['receptor' => 'idconfiguracion']],
            [['usuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['usuario_carga' => 'idusuario']],
            [['usuario_aprobacion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['usuario_aprobacion' => 'idusuario']],
            [['idtipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ent_tipo::className(), 'targetAttribute' => ['idtipo' => 'idtipo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idsolicitudintermedia' => 'Idsolicitudintermedia',
            'emisor' => 'Emisor',
            'receptor' => 'Receptor',
            'fecha_hora' => 'Fecha Hora',
            'irregular' => 'Irregular',
            'usuario_carga' => 'Usuario Carga',
            'usuario_aprobacion' => 'Usuario Aprobacion',
            'estado' => 'Estado',
            'idtipo' => 'Tipo',
            'cantidad' => 'Cantidad',
            'rendiciones_pendientes' => 'Rendiciones Pendientes',
            'observaciones' => 'Observaciones',
        ];
    }

    /**
     * Gets query for [[Emisor0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmisor0()
    {
        return $this->hasOne(Sds_ent_entrega::className(), ['identrega' => 'emisor']);
    }

    /**
     * Gets query for [[Receptor0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceptor0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'receptor']);
    }

    /**
     * Gets query for [[UsuarioCarga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'usuario_carga']);
    }

    /**
     * Gets query for [[UsuarioAprobacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioAprobacion()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'usuario_aprobacion']);
    }

    /**
     * Gets query for [[Idtipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtipo0()
    {
        return $this->hasOne(Sds_ent_tipo::className(), ['idtipo' => 'idtipo']);
    }
}
