<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_licencia".
 *
 * @property int $idlicencia
 * @property string $desde
 * @property string $hasta
 * @property string $detalle
 * @property int $idcontacto
 * @property int $idmotivoinasistencia
 * @property int|null $cantidad_dias
 * @property int|null $idusuario
 *
 * @property MdsOrgContacto $idcontacto0
 * @property MdsHorMotivoInasistencia $idmotivoinasistencia0
 * @property MdsSegUsuario $idusuario0
 */
class Mds_hor_licencia extends \yii\db\ActiveRecord
{
    /* public $idpersona; */
    public $legajo;
    //public $idpersona;
    public $fdesde_desde;
    public $fdesde_hasta;
    public $fhasta_desde;
    public $fhasta_hasta;
    /**
     * {@inheritdoc}
     */
    
    public static function tableName()
    {
        return 'mds_hor_licencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['desde', 'hasta', 'detalle', 'idcontacto', 'idmotivoinasistencia'], 'required'],
            [['desde', 'hasta','legajo', 'fdesde_desde', 'fdesde_hasta', 'fhasta_desde', 'fhasta_hasta' ], 'safe'],
            [['detalle'], 'string'],
            [['idcontacto', 'idmotivoinasistencia', 'cantidad_dias', 'idusuario','legajo'], 'integer'],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['idcontacto' => 'idcontacto']],
            [['idmotivoinasistencia'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_hor_motivo_inasistencia::className(), 'targetAttribute' => ['idmotivoinasistencia' => 'idmotivoinasistencia']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
            //[['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::className(), 'targetAttribute' => ['idpersona' => 'idpersona']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idlicencia' => 'Idlicencia',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'detalle' => 'Detalle',
            'idcontacto' => 'Idcontacto',
            'idmotivoinasistencia' => 'Idmotivoinasistencia',
            'cantidad_dias' => 'Cantidad Dias',
            'idusuario' => 'Idusuario',
        ];
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
     * Gets query for [[Idmotivoinasistencia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdmotivoinasistencia0()
    {
        return $this->hasOne(Mds_hor_motivo_inasistencia::className(), ['idmotivoinasistencia' => 'idmotivoinasistencia']);
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
