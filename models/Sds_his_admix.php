<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_his_admix".
 *
 * @property int $documento_numero
 * @property string $nombre
 * @property string $servicio
 * @property float $importe
 * @property string $fecha
 * @property string $periodo
 * @property string $extracto
 */
class Sds_his_admix extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_his_admix';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documento_numero', 'nombre', 'servicio', 'importe', 'fecha', 'periodo', 'extracto'], 'required'],
            [['documento_numero'], 'integer'],
            [['importe'], 'number'],
            [['fecha'], 'safe'],
            [['nombre', 'servicio', 'extracto'], 'string', 'max' => 255],
            [['periodo'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'documento_numero' => 'DNI',
            'nombre' => 'Beneficiario',
            'servicio' => 'Servicio',
            'importe' => 'Importe',
            'fecha' => 'Fecha',
            'periodo' => 'Periodo',
            'extracto' => 'Observaciones',
        ];
    }

    public static function primaryKey()
    {
        return [
            'documento_numero',
            'nombre',
            'servicio',
            'importe',
            'fecha',
            'periodo'
    ];
    }
}
