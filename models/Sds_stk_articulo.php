<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_stk_articulo".
 *
 * @property int $idarticulo
 * @property string $descripcion
 * @property int $activo
 * @property int $unidad_medida
 *
 * @property SdsRegEntrega[] $sdsRegEntregas
 * @property SdsStkRecepcionItem[] $sdsStkRecepcionItems
 */
class Sds_stk_articulo extends \yii\db\ActiveRecord
{
    CONST PERIODO_PRUEBA = '2023-11-14';
    
    public $disponible;
    public $entregado;
    public $ingresado;
    public $deposito;
    public $temp_imagen;
    public $organizacion_social;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_stk_articulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //['descripcion', 'exist'],
            [
                [
                    'descripcion',
                    'activo',
                    'organismo',
                    'unidad_medida',
                    'rubro',
                    'orden',
                ],
                'required',
            ],
            [
                [
                    'activo',
                    'disponible',
                    'entregado',
                    'ingresado',
                    'unidad_medida',
                    'organismo',
                    'deposito',
                    'rubro',
                    'stock_minimo',
                    'orden',
                    'ocultar',
                    'idtipo',
                    'organizacion_social',
                    'devolucion',
                ],
                'integer',
            ],
            [['observaciones','imagen'], 'string'],
            [['descripcion'], 'string', 'max' => 100],
            [['abreviatura'], 'string', 'max' => 45],
            [['disponible', 'entregado', 'ingresado'], 'safe'],
            [
                ['organismo'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Mds_org_organismo::className(),
                'targetAttribute' => ['organismo' => 'idorganismo'],
            ],
            [
                ['unidad_medida'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sds_com_configuracion::className(),
                'targetAttribute' => ['unidad_medida' => 'idconfiguracion'],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idarticulo' => 'Idarticulo',
            'descripcion' => 'Descripcion',
            'activo' => 'Activo',
            'unidad_medida' => 'Unidad Medida',
            'orden' => 'Orden',
            'ocultar' => 'Ocultar',
            'abreviatura' => 'Abreviatura',
            'devolucion' => 'Devolucion',
        ];
    }

    /**
     * Gets query for [[SdsRegEntregas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRegEntregas()
    {
        return $this->hasMany(Sds_reg_entrega::className(), [
            'idarticulo' => 'idarticulo',
        ]);
    }
    public static function getExtension($file) {
        $array = explode(".", $file);
        $extension = end($array);
        $extImagenes = array('jpg', 'jpeg', 'gif', 'svg', 'png', 'bmp'); 
        if (in_array($extension, $extImagenes)) { 
            return 'image';
        } else {
            return $extension;
        }
    }
    /**
     * Gets query for [[SdsStkRecepcionItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsStkRecepcionItems()
    {
        return $this->hasMany(Sds_stk_recepcion_item::className(), [
            'idarticulo' => 'idarticulo',
        ]);
    }
}
