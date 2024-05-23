<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_stock_detalle_ent_resp".
 *
 * @property int $idcontacto
 * @property string|null $contacto
 * @property int $organismo
 * @property string $fecha_hora
 * @property int $idarticulo
 * @property string $articulo
 * @property float|null $cantidad
 */
class View_stock_detalle_ent_resp extends \yii\db\ActiveRecord
{
    public $desde;
    public $hasta;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_stock_detalle_ent_resp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [            
            [['idcontacto', 'organismo', 'idarticulo'], 'integer'],
            [['fecha_hora','desde','hasta'], 'safe'],
            [['cantidad'], 'number'],
            [['contacto'], 'string', 'max' => 202],
            [['articulo'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcontacto' => 'Idcontacto',
            'contacto' => 'Contacto',
            'organismo' => 'Organismo',
            'fecha_hora' => 'Fecha Hora',
            'idarticulo' => 'Idarticulo',
            'articulo' => 'Articulo',
            'cantidad' => 'Cantidad',
        ];
    }
}
