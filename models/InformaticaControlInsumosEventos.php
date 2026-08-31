<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informatica_control_insumos_eventos".
 *
 * @property int $identrega
 * @property string|null $fecha
 * @property string|null $hora
 * @property int|null $idsolicitante
 * @property int|null $idsector_solicitante
 * @property string|null $destino_prestamo
 * @property string|null $descripcion
 * @property int|null $idresponsable
 * @property int|null $estado
 * @property string|null $observacion
 */
class InformaticaControlInsumosEventos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    // Constantes de Estado
    const ESTADO_SOLICITADO              = 1;
    const ESTADO_EN_PRESTAMO             = 2;
    const ESTADO_DEVUELTO                = 3;
    const ESTADO_DEVUELTO_OBSERVACIONES  = 4;
    const ESTADO_CANCELADO               = 5;

    public static function tableName()
    {
        return 'informatica_control_insumos_eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'idsolicitante', 'idsector_solicitante'], 'required'],
            [['identrega', 'idsolicitante', 'idsector_solicitante', 'idresponsable', 'estado'], 'integer'],
            [['fecha', 'hora'], 'safe'],
            [['descripcion', 'observacion'], 'string'],
            [['destino_prestamo'], 'string', 'max' => 200],
            [['identrega'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'identrega' => 'Identrega',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'idsolicitante' => 'Solicitante',
            'idsector_solicitante' => 'Sector Solicitante',
            'destino_prestamo' => 'Lugar de Destino del Prestamo',
            'descripcion' => 'Detalle de Insumos Prestados',
            'idresponsable' => 'Responsable de Retiro y Entrega',
            'estado' => 'Estado',
            'observacion' => 'Observacion',
        ];
    }

    public static function getEstadosMap()
    {
        return [
            self::ESTADO_SOLICITADO             => 'Solicitado',
            self::ESTADO_EN_PRESTAMO            => 'En Prestamo',
            self::ESTADO_DEVUELTO               => 'Devuelto',
            self::ESTADO_DEVUELTO_OBSERVACIONES => 'Devuelto con Observación',
            self::ESTADO_CANCELADO              => 'Cancelado',
        ];
    }

    /**
     * Retorna el array key-value con id y descripcion para combos/selects.
     * @return array
     */
    public static function get_estados()
    {
        $data = [];
        foreach (self::getEstadosMap() as $id => $descripcion) {
            $data[] = [
                'id' => $id,
                'descripcion' => $descripcion,
            ];
        }
        return $data;
    }

    /**
     * Devuelve la descripción de un estado en particular.
     * @param int|null $estado
     * @return string
     */
    public static function getEstadoTexto($estado)
    {
        $map = self::getEstadosMap();
        return $map[$estado] ?? '';
    }
}
