<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_certificacion".
 *
 * @property int $idcertificacion
 * @property int $certificado
 * @property int $certificante
 * @property int $periodo_mes
 * @property int $periodo_anio
 * @property string $desde
 * @property string $hasta
 * @property string $detalle
 * @property int $estado 0: pendiente, 1: generado
 *
 * @property MdsOrgContacto $certificado0
 * @property MdsOrgContacto $certificante0
 */
class Mds_hor_certificacion extends \yii\db\ActiveRecord
{
    public $reset_form; //Lo uso para saber cuando el usuario quiere limpiar el formulario en la carga de registros.
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_hor_certificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['certificado', 'certificante', 'desde', 'hasta', 'detalle'], 'required'],
            [['certificado', 'certificante', 'estado', 'reset_form'], 'integer'],
            [['periodo_mes', 'periodo_anio'], 'string'],
            [['desde', 'hasta'], 'safe'],
            [['detalle'], 'string'],
            [['certificado'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['certificado' => 'idcontacto']],
            [['certificante'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['certificante' => 'idcontacto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcertificacion' => 'Idcertificacion',
            'certificado' => 'Certificado',
            'certificante' => 'Certificante',
            'periodo_mes' => 'Periodo Mes',
            'periodo_anio' => 'Periodo Anio',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'detalle' => 'Detalle',
            'estado' => 'Estado',
            'reset_form' => 'Limpiar Formulario',
        ];
    }

    /**
     * Gets query for [[Certificado0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificado0()
    {
        return $this->hasOne(Mds_Org_Contacto::className(), ['idcontacto' => 'certificado']);
    }

    /**
     * Gets query for [[Certificante0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCertificante0()
    {
        return $this->hasOne(Mds_Org_Contacto::className(), ['idcontacto' => 'certificante']);
    }
}
