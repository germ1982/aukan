<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_not_nota".
 *
 * @property int $idnota
 * @property string $fecha
 * @property int $numero
 * @property int $idusuario
 * @property string $destinatario_nombre
 * @property string $destinatario_cargo
 * @property string $destinatario_area
 * @property string $referencia
 * @property string $detalle
 * @property int $enviada
 * @property string $fecha_carga
 * @property int|null $expediente_guarismo
 * @property int|null $expediente_numero
 * @property int|null $expediente_anio
 *
 * @property MdsSegUsuario $idusuario0
 */
class Mds_not_nota extends \yii\db\ActiveRecord
{

    public $fdesde;
    public $fhasta;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_not_nota';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'numero', 'idusuario', 'destinatario_nombre', 'destinatario_cargo', 'destinatario_area', 'referencia', 'detalle'], 'required'],
            [['fecha', 'fecha_carga','fdesde', 'fhasta'], 'safe'],
            [['numero', 'idusuario', 'enviada','anulada', 'expediente_guarismo', 'expediente_numero', 'expediente_anio'], 'integer'],
            [['detalle'], 'string'],
            [['destinatario_nombre', 'destinatario_cargo', 'destinatario_area', 'referencia'], 'string', 'max' => 100],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::className(), 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['idorganismo'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_organismo::className(), 'targetAttribute' => ['idorganismo' => 'idorganismo']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idnota' => 'Idnota',
            'fecha' => 'Fecha',
            'numero' => 'Numero',
            'idusuario' => 'Usuario',
            'destinatario_nombre' => 'Destinatario Nombre',
            'destinatario_cargo' => 'Destinatario Cargo',
            'destinatario_area' => 'Destinatario Área',
            'referencia' => 'Referencia',
            'detalle' => 'Detalle',
            'enviada' => 'Enviada',
            'anulada'=> 'Anulada',
            'fecha_carga' => 'Fecha Carga',
            'expediente_guarismo' => 'Expediente Guarismo',
            'expediente_numero' => 'Expediente Número',
            'expediente_anio' => 'Expediente Año',
            'idorganismo'=>'Área que envía',
        ];
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Mds_seg_usuario::className(), ['idusuario' => 'idusuario']);
    }

    public function getNumeroFecha(){
        $anio = date_format(date_create($this->fecha),'Y');
        return $this->numero .'/'.$anio;
    }
}
