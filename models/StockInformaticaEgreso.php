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
    public $fdesde;
    public $fhasta;
    public $documento_solicitante;
    public $documento_receptor;
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
            [['fecha', 'idpersona_solicitante', 'idempleado_autorizacion', 'idempleado_despacha', 'idpersona_recibe', 'documento_solicitante', 'documento_receptor'], 'required'],
            [['fecha'], 'safe'],
            [['idpersona_solicitante', 'idempleado_autorizacion', 'idempleado_despacha', 'idpersona_recibe', 'idusuario_carga', 'idusuario_edicion', 'id_dispositivo_destino'], 'integer'],
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
            'idusuario_carga' => 'Carga',
            'idusuario_edicion' => 'Edicion',
            'id_dispositivo_destino' => 'Destino',
        ];
    }

    /* Esto indica que cada registro en StockInformaticaEgreso tiene muchos StockInformaticaEgresoDetalle. */
    public function getDetalles()
    {
        return $this->hasMany(StockInformaticaEgresoDetalle::class, ['idstock_informatica_Egreso' => 'id']);
    }

    public function getPersonaSolicitante()
    {
        return $this->hasOne(Persona::className(), ['idpersona' => 'idpersona_solicitante']);
    }
    public function getPersonaReceptor()
    {
        return $this->hasOne(Persona::className(), ['idpersona' => 'idpersona_recibe']);
    }

    public function getEmpleadoAutorizacion()
    {
        return $this->hasOne(Empleado::className(), ['idempleado' => 'idempleado_autorizacion']);
    }

    public function getEmpleadoDespacha()
    {
        return $this->hasOne(Empleado::className(), ['idempleado' => 'idempleado_despacha']);
    }
}
