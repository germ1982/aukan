<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_bdc_movimiento".
 *
 * @property int $idmovimiento
 * @property string $fecha_hora
 * @property int $idusuario usuario carga
 * @property int $solicitante
 * @property int $tipo idconfiguraciontipo=103
 * @property int|null $responsable_anterior
 * @property int|null $responsable_nuevo
 * @property int|null $usuario_anterior
 * @property int|null $usuario_nuevo
 * @property int|null $ip_anterior
 * @property int|null $ip_nueva
 * @property string|null $observaciones
 *
 * @property Mds_org_contacto $responsableAnterior
 * @property Mds_org_contacto $responsableNuevo
 * @property Mds_org_contacto $solicitante0
 * @property Sds_com_configuracion $tipo0
 * @property Mds_org_contacto $usuarioAnterior
 * @property Mds_seg_usuario $idusuario0
 * @property Mds_org_contacto $usuarioNuevo
 * @property Sds_bdc_movimiento_equipo[] $sdsBdcMovimientoEquipos
 */
class Sds_bdc_movimiento extends \yii\db\ActiveRecord
{
    const MOV_BAJA=2434;
    const MOV_ALTA=2435;
    const MOV_CAM_RESPONSABLE=2436;
    const MOV_REPARACION=2437;
    const MOV_HOME_OFFICE=2438;
    const MOV_ENT_REPARACION=2439;
    const MOV_CAM_IP=2440;

    public $usuario_carga;
    public $equipos;
    public $fdesde;
    public $fhasta;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_bdc_movimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'idusuario', 'solicitante', 'tipo'], 'required'],
            [['fecha_hora', 'usuario_carga', 'equipos', 'fdesde', 'fhasta', 'preventivo'], 'safe'],
            [['idusuario', 'solicitante', 'tipo', 'responsable_anterior', 'responsable_nuevo', 'usuario_anterior', 'usuario_nuevo', 'organismo_anterior', 'organismo_nuevo'], 'integer'],
            [['ip_anterior', 'ip_nueva', 'observaciones'], 'string'],
            [['responsable_anterior'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['responsable_anterior' => 'idcontacto']],
            [['responsable_nuevo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['responsable_nuevo' => 'idcontacto']],
            [['solicitante'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['solicitante' => 'idcontacto']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
            [['usuario_anterior'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['usuario_anterior' => 'idcontacto']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['usuario_nuevo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['usuario_nuevo' => 'idcontacto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimiento' => 'Movimiento',
            'fecha_hora' => 'Fecha y Hora',
            'idusuario' => 'idusuario',
            'solicitante' => 'Solicitante',
            'tipo' => 'Tipo',
            'responsable_anterior' => 'Responsable Anterior',
            'responsable_nuevo' => 'Responsable Nuevo',
            'usuario_anterior' => 'Usuario Anterior',
            'usuario_nuevo' => 'Usuario Nuevo',
            'ip_anterior' => 'IP Anterior',
            'ip_nueva' => 'IP Nueva',
            'observaciones' => 'Observaciones',
            'usuario_carga' => 'Carga',
            'equipos' => 'Equipos',
            'preventivo' => 'Mantenimiento Preventivo'
        ];
    }

    /**
     * Gets query for [[ResponsableAnterior]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsableAnterior()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'responsable_anterior']);
    }

    /**
     * Gets query for [[ResponsableNuevo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponsableNuevo()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'responsable_nuevo']);
    }

    /**
     * Gets query for [[Solicitante0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitante0()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'solicitante']);
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
     * Gets query for [[UsuarioAnterior]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioAnterior()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'usuario_anterior']);
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

    /**
     * Gets query for [[UsuarioNuevo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioNuevo()
    {
        return $this->hasOne(Mds_org_contacto::class, ['idcontacto' => 'usuario_nuevo']);
    }

    /**
     * Gets query for [[SdsBdcMovimientoEquipos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsBdcMovimientoEquipos()
    {
        return $this->hasMany(Sds_bdc_movimiento_equipo::class, ['idmovimiento' => 'idmovimiento']);
    }
}
