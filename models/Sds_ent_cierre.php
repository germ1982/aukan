<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_ent_cierre".
 *
 * @property int $idcierre
 * @property int $identrega
 * @property int $cantidad
 * @property int $motivo
 * @property int|null $numero
 *
 * @property SdsEntEntrega $identrega0
 * @property SdsComConfiguracion $motivo0
 */
class Sds_ent_cierre extends \yii\db\ActiveRecord
{
    public $fecha;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ent_cierre';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identrega', 'cantidad', 'motivo'], 'required'],
            [['identrega', 'cantidad', 'motivo', 'numero'], 'integer'],            
            [['fecha'], 'safe'],            
            [['identrega'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_ent_entrega::className(), 'targetAttribute' => ['identrega' => 'identrega']],
            [['motivo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['motivo' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcierre' => 'Idcierre',
            'identrega' => 'Identrega',
            'cantidad' => 'Cantidad',
            'motivo' => 'Motivo',
            'numero' => 'Numero',
        ];
    }
}
