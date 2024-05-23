<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ris_risneu_alimentacion".
 *
 * @property int $idrisneualimentacion
 * @property int $idrisneu
 * @property int $alimentacion
 *
 * @property SdsRisRisneu $idrisneu0
 * @property SdsComConfiguracion $alimentacion0
 */
class Sds_ris_risneu_alimentacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ris_risneu_alimentacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrisneu', 'alimentacion'], 'required'],
            [['idrisneu', 'alimentacion', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['idrisneu'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ris_risneu::class, 'targetAttribute' => ['idrisneu' => 'idrisneu']],
            [['alimentacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['alimentacion' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idrisneualimentacion' => 'Idrisneualimentacion',
            'idrisneu' => 'Idrisneu',
            'alimentacion' => 'Alimentacion',
        ];
    }
    public function getAlimentacion0(){
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'alimentacion']);
    }

}
