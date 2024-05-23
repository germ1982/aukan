<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_por_sst".
 *
 * @property int $id
 * @property string|null $asiento
 * @property string|null $tipo
 * @property string|null $cheque
 * @property string|null $cantidad
 * @property string|null $fecha
 * @property string|null $dni
 * @property string|null $nombre
 * @property string|null $monto
 * @property string|null $PROV
 * @property string|null $CTA
 * @property string|null $LUG
 * @property string|null $destino
 * @property string|null $localidad
 * @property string|null $id_localidad
 * @property string $grupo
 * @property string $referente
 * @property string|null $pago
 * @property string $autorizo
 * @property string|null $observacion
 * @property string|null $situacion
 * @property string|null $retira_cheque
 * @property int $mes
 * @property int $anio
 * @property string $sexo
 * @property string $apellido
 * @property string $liquidacion_anterior
 */
class mds_por_sst extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_por_sst';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['grupo', 'referente', 'autorizo', 'mes', 'anio', 'sexo', 'apellido', 'liquidacion_anterior'], 'required'],
            [['mes', 'anio'], 'integer'],
            [['asiento', 'tipo', 'cheque', 'cantidad', 'fecha', 'dni', 'nombre', 'monto', 'PROV', 'CTA', 'LUG', 'destino', 'localidad', 'id_localidad', 'grupo', 'referente', 'pago', 'autorizo', 'observacion', 'situacion', 'retira_cheque', 'sexo', 'apellido', 'liquidacion_anterior'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'asiento' => 'Asiento',
            'tipo' => 'Tipo',
            'cheque' => 'Cheque',
            'cantidad' => 'Cantidad',
            'fecha' => 'Fecha ',
            'dni' => 'Documento',
            'nombre' => 'Nombre y Apellido',
            'monto' => 'Monto',
            'PROV' => 'Prov',
            'CTA' => 'Cta',
            'LUG' => 'Lug',
            'destino' => 'Destino',
            'localidad' => 'Localidad',
            'id_localidad' => 'Id Localidad',
            'grupo' => 'Grupo',
            'referente' => 'Referente',
            'pago' => 'Pago',
            'autorizo' => 'Autorizo',
            'observacion' => 'Observación',
            'situacion' => 'Situación',
            'retira_cheque' => 'Retira Cheque',
            'mes' => 'Mes',
            'anio' => 'Año',
            'sexo' => 'Sexo',
            'apellido' => 'Apellido',
            'liquidacion_anterior' => 'Liquidación Anterior',
        ];
    }
}
