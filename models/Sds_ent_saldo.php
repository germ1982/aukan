<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_sds_ent_saldo".
 *
 * @property string|null $codigo
 * @property int|null $responsable
 * @property int $idtipo
 * @property float|null $ingresos
 * @property float|null $egresos
 * @property float|null $saldo
 */
class Sds_ent_saldo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_sds_ent_saldo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['responsable', 'idtipo', 'codigo'], 'integer'],
            [['ingresos', 'egresos', 'saldo'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codigo' => 'Codigo',
            'responsable' => 'Responsable',
            'idtipo' => 'Tipo',
            'ingresos' => 'Ingresos',
            'egresos' => 'Egresos',
            'saldo' => 'Saldo',
        ];
    }

    public static function primaryKey()
    {
        return ['codigo'];
    }

    public static function getSaldo($idtipo, $identrega)
    {
        //Si el movimiento es egreso, tengo que buscar las entregas donde el identrega pasado como parametro figura como emisor
        $saldo = Sds_ent_saldo::findBySql("select saldo from sds_ent_entrega ent
                                    join (select if(haber>0,(select emisor
                                    from sds_ent_entrega
                                    entemisor where entemisor.identrega=ctacte.identrega),ctacte.identrega) codEnt,
                                    sum(debe-haber) saldo
                                    from view_sds_ent_cta_cte ctacte
                                    group by codEnt
                                    having saldo>0) temp on temp.codEnt=ent.identrega
                                                            where idtipo=$idtipo and ent.identrega=$identrega")->one();
        if ($saldo != null) {
            return $saldo->saldo;
        }

        return 0;
    }
}
