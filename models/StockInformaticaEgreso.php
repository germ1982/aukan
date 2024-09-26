<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_informatica_egreso".
 *
 * @property int $idegreso
 * @property string $fecha
 * @property int $idpersona_solicitante
 * @property int $idempleado_autorizacion
 * @property int $idempleado_despacha
 * @property int $idpersona_recibe
 * @property string|null $observacion
 */
class StockInformaticaEgreso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_informatica_egreso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'idpersona_solicitante', 'idempleado_autorizacion', 'idempleado_despacha', 'idpersona_recibe'], 'required'],
            [['fecha'], 'safe'],
            [['idpersona_solicitante', 'idempleado_autorizacion', 'idempleado_despacha', 'idpersona_recibe'], 'integer'],
            [['observacion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idegreso' => 'Nro.',
            'fecha' => 'Fecha',
            'idpersona_solicitante' => 'Solicitante',
            'idempleado_autorizacion' => 'Autorizacion',
            'idempleado_despacha' => 'Despachante',
            'idpersona_recibe' => 'Receptor',
            'observacion' => 'Observacion',
        ];
    }
}
