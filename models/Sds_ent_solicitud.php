<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ent_solicitud".
 *
 * @property int $idsolicitud
 * @property string $fecha_hora
 * @property int $cantidad
 * @property int $dni
 * @property int $idtipo
 * @property string|null $observaciones
 * @property int $idusuario
 * @property int $estado 0: pendiente, 1: Aprobado, 2: Desaprobado, 3: Entregado
 * @property string|null $fecha_aprobacion 'fecha en la que se aprueba o no una solicitud'
 * @property int|null $entrega
 *
 * @property SdsEntEntrega $entrega0
 * @property SdsEntTipo $idtipo0
 * @property MdsSegUsuario $idusuario0
 */
class Sds_ent_solicitud extends \yii\db\ActiveRecord
{

    const ESTADO_PENDIENTE = 0;
    const ESTADO_APROBADO = 1;
    const ESTADO_DESAPROBADO = 2;
    const ESTADO_ENTREGADO = 3;

    public $entidad;
    public $motivo_rechazo;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ent_solicitud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cantidad', 'dni', 'idtipo', 'idusuario'], 'required'],
            [['cantidad', 'dni', 'idtipo', 'idusuario', 'estado'], 'integer'],
            [['fecha_hora', 'fecha_aprobacion','motivo_rechazo'], 'safe'],
            [['observaciones','motivo_rechazo'], 'string'],
            [['idsolicitud'], 'unique'],            
            [['idtipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ent_tipo::className(), 'targetAttribute' => ['idtipo' => 'idtipo']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idsolicitud' => 'Idsolicitud',
            'fecha_hora' => 'Fecha Hora',
            'cantidad' => 'Cantidad',
            'dni' => 'Dni',
            'idtipo' => 'Tipo',
            'observaciones' => 'Detalle',
            'idusuario' => 'Usuario',
            'estado' => 'Estado',
            'fecha_aprobacion' => 'Fecha Aprobación',
            'entrega' => 'Entrega',
        ];
    }

    /**
     * Gets query for [[Entrega0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntrega0()
    {
        return $this->hasOne(Sds_ent_entrega::className(), ['identrega' => 'entrega']);
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

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }
}
