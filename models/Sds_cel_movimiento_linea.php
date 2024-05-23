<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_cel_movimiento_linea".
 *
 * @property int $idmovimientolinea
 * @property string $fecha_hora
 * @property int $idusuario
 * @property int $solicitante
 * @property int $tipo idconfiguraciontipo=111
 * @property int|null $responsable_anterior
 * @property int|null $responsable_nuevo
 * @property int|null $equipo_anterior
 * @property int|null $equipo_nuevo
 * @property int|null $organismo_anterior
 * @property int|null $organismo_nuevo
 * @property string|null $observaciones
 * @property int $idlinea
 * @property string|null $adjunto path del adjunto
 *
 * @property Sds_bdc_equipo $equipoAnterior
 * @property Sds_bdc_equipo $equipoNuevo
 * @property Sds_cel_linea $idlinea0
 * @property Mds_org_organismo $organismoAnterior
 * @property Mds_org_organismo $organismoNuevo
 * @property Mds_org_contacto $responsableAnterior
 * @property Mds_org_contacto $responsableNuevo
 * @property Mds_org_contacto $solicitante0
 * @property Sds_com_configuracion $tipo0
 * @property Mds_seg_usuario $idusuario0
 */
class Sds_cel_movimiento_linea extends \yii\db\ActiveRecord
{

    public $fdesde;
    public $fhasta;
    public $usuario_carga;

    const MOV_ALTA=2802;
    const MOV_BAJA=2803;
    const MOV_SUSP_ROBO=2804;
    const MOV_CAMBIO_EQUIPO=2805;
    const MOV_CAMBIO_RESP=2806;
    const MOV_CAMBIO_PLAN=2807;
    const MOV_CAMBIO_CHIP=2808;
    const MOV_SUSP_DESCONOCIDO=2809;
    const MOV_DESASIGNAR_EQUIPO=4433;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_cel_movimiento_linea';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'idusuario', 'solicitante', 'tipo', 'idlinea'], 'required'],
            [['fecha_hora', 'plan_anterior', 'plan_nuevo', 'usuario_carga', 'organismo_cuenta_nuevo', 'fdesde','fhasta'], 'safe'],
            [['idusuario', 'solicitante', 'tipo', 'responsable_anterior', 'responsable_nuevo', 'equipo_anterior', 'equipo_nuevo', 'organismo_anterior', 'organismo_nuevo', 'organismo_cuenta_anterior', 'organismo_cuenta_nuevo', 'idlinea'], 'integer'],
            [['observaciones', 'adjunto'], 'string'],
            [['equipo_anterior'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_bdc_equipo::class, 'targetAttribute' => ['equipo_anterior' => 'idequipo']],
            [['equipo_nuevo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_bdc_equipo::class, 'targetAttribute' => ['equipo_nuevo' => 'idequipo']],
            [['idlinea'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_cel_linea::class, 'targetAttribute' => ['idlinea' => 'idlinea']],
            [['organismo_anterior'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['organismo_anterior' => 'idorganismo']],
            [['organismo_nuevo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['organismo_nuevo' => 'idorganismo']],
            [['responsable_anterior'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['responsable_anterior' => 'idcontacto']],
            [['responsable_nuevo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['responsable_nuevo' => 'idcontacto']],
            [['solicitante'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['solicitante' => 'idcontacto']],
            [['tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idmovimientolinea' => 'Idmovimientolinea',
            'fecha_hora' => 'Fecha Hora',
            'idusuario' => 'Usuario',
            'solicitante' => 'Solicitante',
            'tipo' => 'Tipo Movimiento',
            'responsable_anterior' => 'Responsable Anterior',
            'responsable_nuevo' => 'Responsable Nuevo',
            'equipo_anterior' => 'Equipo Anterior',
            'equipo_nuevo' => 'Equipo Nuevo',
            'organismo_anterior' => 'Organismo Anterior',
            'organismo_nuevo' => 'Organismo Nuevo',
            'observaciones' => 'Observaciones',
            'idlinea' => 'Linea',
            'adjunto' => 'Adjunto',
            'usuario_carga' => 'Usuario Carga',
            'organismo_cuenta_nuevo' => 'Organismo Cuenta Nuevo'
        ];
    }

    /**
     * Gets query for [[EquipoAnterior]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipoAnterior()
    {
        return $this->hasOne(Sds_bdc_equipo::class, ['idequipo' => 'equipo_anterior']);
    }

    /**
     * Gets query for [[EquipoNuevo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipoNuevo()
    {
        return $this->hasOne(Sds_bdc_equipo::class, ['idequipo' => 'equipo_nuevo']);
    }

    /**
     * Gets query for [[Idlinea0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdlinea0()
    {
        return $this->hasOne(Sds_cel_linea::class, ['idlinea' => 'idlinea']);
    }

    /**
     * Gets query for [[OrganismoAnterior]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismoAnterior()
    {
        return $this->hasOne(Mds_org_organismo::class, ['idorganismo' => 'organismo_anterior']);
    }

    /**
     * Gets query for [[OrganismoNuevo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismoNuevo()
    {
        return $this->hasOne(Mds_org_organismo::class, ['idorganismo' => 'organismo_nuevo']);
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
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }
}
