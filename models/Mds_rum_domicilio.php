<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_rum_domicilio".
 *
 * @property int $id
 * @property string $calle
 * @property string $numero
 * @property string $barrio  
 * @property string $descripcion
 * @property string $adicional
 * @property int $idlocalidad
 * @property string $manzana
 * @property string $duplex
 * @property string $monoblock
 * @property string $piso
 * @property string $dpto
 * @property string $lote
 */
class Mds_rum_domicilio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rum_domicilio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['calle', 'numero', 'barrio', 'descripcion', 'adicional', 'idlocalidad', 'manzana', 'duplex', 'monoblock', 'piso', 'dpto', 'lote'], 'safe'],
            [['id', 'idlocalidad'], 'integer'],
            [['descripcion'], 'string'],
            [['calle', 'numero', 'barrio', 'manzana', 'duplex', 'monoblock', 'piso', 'dpto', 'lote'], 'string', 'max' => 200],
            [['adicional'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'calle' => 'Calle',
            'numero' => 'Numero',
            'barrio' => 'Barrio',
            'descripcion' => 'Descripcion',
            'adicional' => 'Adicional',
            'idlocalidad' => 'Idlocalidad',
            'manzana' => 'Manzana',
            'duplex' => 'Duplex',
            'monoblock' => 'Monoblock',
            'piso' => 'Piso',
            'dpto' => 'Dpto',
            'lote' => 'Lote',
        ];
    }
}
