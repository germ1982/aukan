<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_por_desempleo".
 *
 * @property int $iddesempleo
 * @property string|null $tipo
 * @property string|null $fecha
 * @property int|null $dni
 * @property string|null $nombre
 * @property int|null $cheque
 * @property int|null $monto
 * @property int|null $prov
 * @property string|null $lug
 */
class mds_por_desempleo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_por_desempleo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha'], 'safe'],
            [['dni', 'cheque', 'monto', 'prov'], 'integer'],
            [['tipo', 'lug'], 'string', 'max' => 50],
            [['nombre'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddesempleo' => 'Iddesempleo',
            'tipo' => 'Tipo',
            'fecha' => 'Fecha',
            'dni' => 'Dni',
            'nombre' => 'Nombre',
            'cheque' => 'Cheque',
            'monto' => 'Monto',
            'prov' => 'Prov',
            'lug' => 'Lug',
        ];
    }
}
