<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_ingreso".
 *
 * @property int $idingreso
 * @property int $idcontacto
 * @property string $fecha_hora
 * @property float|null $temperatura
 * @property string|null $observaciones
 *
 * @property MdsOrgContacto $idcontacto0
 */
class Mds_hor_ingreso extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $fecha;
    public $hora;

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_hor_ingreso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcontacto', 'fecha_hora'], 'required'],
            [['idcontacto'], 'integer'],
            [['fdesde', 'fhasta','fecha_hora'], 'safe'],
            [['temperatura'], 'number'],
            [['observaciones'], 'string'],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::className(), 'targetAttribute' => ['idcontacto' => 'idcontacto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idingreso' => 'Idingreso',
            'idcontacto' => 'Empleado',
            'fecha_hora' => 'Fecha Hora',
            'temperatura' => 'Temperatura',
            'observaciones' => 'Observaciones',
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
}
