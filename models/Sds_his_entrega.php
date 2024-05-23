<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_his_entrega".
 *
 * @property int $numero_documento
 * @property string $fecha
 * @property string $servicio
 * @property float $cantidad
 * @property string $destino
 */
class Sds_his_entrega extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_his_entrega';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_documento', 'fecha', 'servicio', 'cantidad', 'destino'], 'required'],
            [['numero_documento'], 'integer'],
            [['fecha'], 'safe'],
            [['cantidad'], 'number'],
            [['servicio', 'destino'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'numero_documento' => 'Numero Documento',
            'fecha' => 'Fecha',
            'servicio' => 'Servicio',
            'cantidad' => 'Cantidad',
            'destino' => 'Destino',
        ];
    }

    public static function primaryKey()
    {
        return [
            'numero_documento',
            'fecha',
            'servicio',
            'destino'
    ];
    }
}
