<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_contadores_fichadas".
 *
 * @property int|null $activos_ciclo
 * @property int|null $hoy_ciclo
 * @property int|null $hoy_reloj
 * @property int|null $hoy_manual
 * @property int|null $hoy_guardia
 */
class View_contadores_fichadas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_contadores_fichadas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activos_ciclo', 'hoy_ciclo', 'hoy_reloj', 'hoy_manual', 'hoy_guardia'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'activos_ciclo' => 'Activos Ciclo',
            'hoy_ciclo' => 'Hoy Ciclo',
            'hoy_reloj' => 'Hoy Reloj',
            'hoy_manual' => 'Hoy Manual',
            'hoy_guardia' => 'Hoy Guardia',
        ];
    }
}
