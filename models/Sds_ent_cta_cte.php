<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_sds_ent_cta_cte".
 *
 * @property string|null $codigo
 * @property int $identrega
 * @property string $fecha_hora
 * @property int $debe
 * @property int $haber
 * @property int|null $responsable
 * @property int $idtipo
 */
class Sds_ent_cta_cte extends \yii\db\ActiveRecord
{
    public $saldo_acumulado;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_sds_ent_cta_cte';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identrega', 'debe', 'haber', 'responsable', 'idtipo','codigo','saldo_acumulado'], 'integer'],
            [['fecha_hora'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo',
            'identrega' => 'Identrega',
            'fecha_hora' => 'Fecha Hora',
            'debe' => 'Debe',
            'haber' => 'Haber',
            'responsable' => 'Responsable',
            'saldo_acumulado' => 'Saldo',
            'idtipo' => 'Idtipo',
        ];
    }

    public static function primaryKey()
    {
        return ['codigo'];
    }

}
