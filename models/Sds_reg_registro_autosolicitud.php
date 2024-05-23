<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_registro".
 *
 * @property int $idregistro
 * @property string $fecha_hora
 * @property int $idorganismo
 * @property int $usuario_solicitante
 * @property string $problema
 * @property int|null $usuario_derivacion
 * @property int $registro_abierto 0: Finalizado, 1: Pendiente
 * @property int|null $incidencia_relacionada
 * @property int $idtipo
 * @property string|null $fecha_ingreso
 * @property int|null $usuario_ingreso
 * @property string|null $fecha_solucion
 * @property string|null $equipo_detalle
 * @property string|null $ip
 * @property int $iddispositivo
 *
 * @property SdsRegMovimiento[] $sdsRegMovimientos
 * @property MdsOrgDispositivo $iddispositivo0
 * @property SdsRegTipo $idtipo0
 * @property MdsOrgOrganismo $idorganismo0
 * @property MdsOrgContacto $usuarioSolicitante
 */
class Sds_reg_registro_autosolicitud extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $estado;
    public $movimiento;

    public $entidad; //Variable para filtrar por entidad. (Informatica, mantenimiento)

    const API_TELEGRAM = "6261656799:AAGHyME5nhsDyIM0Vm3CE7YdRpIatjnJKiY"; //bot
    const CHAT_ID = '@sur_tickets'; //canal

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_registro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_hora', 'idorganismo', 'usuario_solicitante', 'problema'], 'required'],
            [['fecha_hora', 'fecha_ingreso', 'fecha_solucion','fdesde','fhasta','estado','movimiento'], 'safe'],
            [['idorganismo', 'usuario_solicitante', 'usuario_derivacion', 'registro_abierto', 'incidencia_relacionada', 'idtipo', 'usuario_ingreso', 'iddispositivo','estado'], 'integer'],
            [['problema', 'equipo_detalle'], 'string'],
            [['ip'], 'string', 'max' => 15],
            [['iddispositivo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_dispositivo::class, 'targetAttribute' => ['iddispositivo' => 'iddispositivo']],
            [['idtipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_reg_tipo::class, 'targetAttribute' => ['idtipo' => 'idtipo']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::class, 'targetAttribute' => ['idorganismo' => 'idorganismo']],
            [['usuario_solicitante'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['usuario_solicitante' => 'idcontacto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistro' => 'Idregistro',
            'fecha_hora' => 'Fecha Hora',
            'idorganismo' => 'Idorganismo',
            'usuario_solicitante' => 'Usuario Solicitante',
            'problema' => 'Problema',
            'usuario_derivacion' => 'Usuario Derivacion',
            'registro_abierto' => 'Registro Abierto',
            'incidencia_relacionada' => 'Incidencia Relacionada',
            'idtipo' => 'Idtipo',
            'fecha_ingreso' => 'Fecha Ingreso',
            'usuario_ingreso' => 'Usuario Ingreso',
            'fecha_solucion' => 'Fecha Solucion',
            'equipo_detalle' => 'Equipo Detalle',
            'ip' => 'Ip',
            'iddispositivo' => 'Iddispositivo',
        ];
    }

    /**
     * Gets query for [[SdsRegMovimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRegMovimientos()
    {
        return $this->hasMany(Sds_reg_movimiento::class, ['idregistro' => 'idregistro']);
    }

    /**
     * Gets query for [[Iddispositivo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddispositivo0()
    {
        return $this->hasOne(MdsOrgDispositivo::class, ['iddispositivo' => 'iddispositivo']);
    }

    /**
     * Gets query for [[Idtipo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtipo0()
    {
        return $this->hasOne(SdsRegTipo::class, ['idtipo' => 'idtipo']);
    }

    /**
     * Gets query for [[Idorganismo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismo0()
    {
        return $this->hasOne(MdsOrgOrganismo::class, ['idorganismo' => 'idorganismo']);
    }

    /**
     * Gets query for [[UsuarioSolicitante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioSolicitante()
    {
        return $this->hasOne(MdsOrgContacto::class, ['idcontacto' => 'usuario_solicitante']);
    }
}