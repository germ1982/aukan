<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_bdc_tipo".
 *
 * @property int $idconfiguracion
 * @property int $procesador
 * @property int $memoria
 * @property int $disco
 * @property int $sistema_operativo
 * @property int $conectividad
 * @property int $ip
 *
 * @property SdsComConfiguracion $idconfiguracion0
 */
class Sds_bdc_tipo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_bdc_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['procesador', 'memoria', 'disco', 'sistema_operativo', 'conectividad', 'ip'], 'required'],
            [['procesador', 'memoria', 'disco', 'sistema_operativo', 'conectividad', 'ip'], 'integer'],
            [['idconfiguracion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idconfiguracion' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idconfiguracion' => 'Idconfiguracion',
            'procesador' => 'Procesador',
            'memoria' => 'Memoria',
            'disco' => 'Disco',
            'sistema_operativo' => 'Sistema Operativo',
            'conectividad' => 'Conectividad',
            'ip' => 'Ip',
        ];
    }

    /**
     * Gets query for [[Idconfiguracion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdconfiguracion0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idconfiguracion']);
    }
}
