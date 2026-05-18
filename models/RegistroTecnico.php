<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_tecnico".
 *
 * @property int $idregistro
 * @property string $fecha_solicitud
 * @property int $idsolicitante
 * @property int|null $iddispositivo
 * @property int|null $idtipo_registro
 * @property string|null $problema
 * @property string|null $solucion
 * @property string|null $fecha_solucion
 */
class RegistroTecnico extends \yii\db\ActiveRecord
{
    const ESTADO_PENDIENTE = 0;
    const ESTADO_ASISTENCIA = 1;
    const ESTADO_FINALIZADO = 2;
    public $fdesde;
    public $fhasta;
    public $asistentes_informaticos;
    public $solicitante;
    public static function tableName()
    {
        return 'registro_tecnico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_solicitud', 'idsolicitante'], 'required'],
            [['fecha_solicitud','hora_solicitud', 'fecha_solucion', 'hora_solucion', 'asistentes_informaticos', 'solicitante'], 'safe'],
            [['idsolicitante', 'iddispositivo', 'idtipo_registro', 'estado'], 'integer'],
            [['problema', 'solucion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistro' => 'Idregistro',
            'fecha_solicitud' => 'Fecha Solicitud',
            'idsolicitante' => 'Solicitante',
            'iddispositivo' => 'Sector',
            'idtipo_registro' => 'Tipo de Registro',
            'problema' => 'Problema',
            'solucion' => 'Solucion',
            'fecha_solucion' => 'Fecha Solucion',
            'asistentes_informaticos' => 'Asistencia',
            'hora_solicitud' => 'Hora Solicitud',
            'hora_solucion' => 'Hora Solución',
        ];
    }


    /**
     * Devuelve el listado completo de estados
     * Ideal para usar en Dropdowns y Filtros del Grid
     */
    public static function getEstadosLista()
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_ASISTENCIA   => 'En Asistencia',
            self::ESTADO_FINALIZADO => 'Finalizado',
        ];
    }
    /**
     * Versión ESTÁTICA: La podés llamar desde cualquier lado sin tener un modelo.
     * Ejemplo: RegistroTecnico::getNombreEstado(1);
     */
    public static function getNombreEstado($idEstado)
    {
        $estados = self::getEstadosLista();
        return $estados[$idEstado] ?? 'Desconocido';
    }

    /**
     * Versión de INSTANCIA: La usás cuando tenés el objeto (ej. en el Grid o View).
     * Ejemplo: $model->estadoEtiqueta;
     */
    public function getEstadoEtiqueta()
    {
        return self::getNombreEstado($this->estado);
    }
}
