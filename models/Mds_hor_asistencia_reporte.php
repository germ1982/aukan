<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "view_contacto_registro".
 *
 * @property string $periodo
 * @property string $fecha
 * @property int|null $idfranco
 * @property int|null $idregistrohorario
 * @property int|null $idlicencia
 * @property int|null $codContacto
 */
class Mds_hor_asistencia_reporte extends \yii\db\ActiveRecord
{
    public $dia;
    public $estado;
    public $detalle;
    public $foto;
    public $latitud;
    public $longitud;
    public $idorganismo;
    public $turno_rotativo;
    public $desde;
    public $hasta;
    public $pr_categoria;
    public $detalle_fichada;
    public $empleado;
    public $legajo;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'view_contacto_registro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['desde', 'hasta', 'fecha'], 'required'],
            [['fecha', 'foto', 'latitud', 'longitud', 'idorganismo', 'pr_categoria','detalle_fichada','empleado','legajo'], 'safe'],
            [['idfranco', 'idcertificacion', 'idregistrohorario', 'idlicencia', 'codContacto', 'idorganismo', 'turno_rotativo'], 'integer'],
            [['desde', 'hasta', 'periodo'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'periodo' => 'Período',
            'fecha' => 'Fecha',
            'idfranco' => 'Franco',
            'idcertificacion' => 'Certificación',
            'idregistrohorario' => 'Idregistrohorario',
            'idlicencia' => 'Idlicencia',
            'codContacto' => 'Empleado',
            'desde' => 'Desde',
            'hasta' => 'Hasta',
            'dia' => 'Día'
        ];
    }

    public static function primaryKey()
    {
        return ['fecha', 'codContacto'];
    }
}
