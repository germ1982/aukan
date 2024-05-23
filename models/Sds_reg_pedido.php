<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_reg_pedido".
 *
 * @property int $idpedido
 * @property int $numero
 * @property string $expediente
 * @property int $estado
 *
 * @property SdsComConfiguracion $estado0
 */
class Sds_reg_pedido extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_reg_pedido';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero', 'expediente', 'estado'], 'required'],
            [['numero', 'estado'], 'integer'],
            [['descripcion'], 'string'],
            [['expediente'], 'string', 'max' => 45],
            [['estado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['estado' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpedido' => 'Idpedido',
            'numero' => 'Numero',
            'expediente' => 'Expediente',
            'estado' => 'Estado',
        ];
    }

}
