<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_tes_adjunto".
 *
 * @property int $idadjunto
 * @property string $carga
 * @property int $periodo_mes
 * @property int $periodo_anio
 * @property int $tipo 1: Desempleo, 2: Familia, 3: SST
 * @property int $pago 1: Acreditacion, 2: Cheque
 */
class Sds_tes_adjunto extends \yii\db\ActiveRecord
{
    public $fdesde;
    public $fhasta;
    public $temp_archivo_adjunto;
    public $borrar_adjunto;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_tes_adjunto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['carga', 'periodo_mes', 'periodo_anio', 'tipo', 'pago', 'path'], 'required'],
            [['carga','fdesde', 'fhasta','borrar_adjunto'], 'safe'],
            [['periodo_mes', 'periodo_anio', 'tipo', 'pago'], 'integer'],
            [['path'], 'string'],
            [['temp_archivo_adjunto'], 'file', 'extensions' => 'xls, xlsx, csv', 'maxSize' => 1000000],
            [['path'], 'unique', 'targetAttribute' => ['path'],'message'=>'Ya eiste un archivo con caracteristicas similares..'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idadjunto' => 'Idadjunto',
            'carga' => 'Carga',
            'periodo_mes' => 'Periodo Mes',
            'periodo_anio' => 'Periodo Año',
            'tipo' => 'Tipo',
            'pago' => 'Pago',
            'path' => 'Path',
        ];
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
}
