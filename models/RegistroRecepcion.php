<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "registro_recepcion".
 *
 * @property int $id_registro_recepcion
 * @property string|null $fecha
 * @property string|null $hora
 * @property int|null $dni
 * @property string|null $motivo
 * @property int|null $acceso Lugar por donde se realiza la recepcion
 * @property int|null $id_dispositivo_derivacion
 * @property int|null $id_responsable_derivacion
 * @property int|null $id_tipo_recepcion telefonica o precencial
 * @property string|null $observacion
 */
class RegistroRecepcion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_recepcion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'hora'], 'safe'],
            [['dni', 'acceso', 'id_dispositivo_derivacion', 'id_responsable_derivacion', 'id_tipo_recepcion'], 'integer'],
            [['motivo', 'observacion'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_registro_recepcion' => 'Id Registro Recepcion',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'dni' => 'Dni',
            'motivo' => 'Motivo',
            'acceso' => 'Acceso',
            'id_dispositivo_derivacion' => 'Id Dispositivo Derivacion',
            'id_responsable_derivacion' => 'Id Responsable Derivacion',
            'id_tipo_recepcion' => 'Id Tipo Recepcion',
            'observacion' => 'Observacion',
        ];
    }
    public function getEdificioAcceso()
    {
        return $this->hasOne(EdificioAcceso::class, ['id_edificio_acceso' => 'acceso']);
    }
    public function getFechaFormateada()
    {
        if (empty($this->fecha) || $this->fecha === '0000-00-00') {
            return '-';
        }

        try {
            return Yii::$app->formatter->asDate($this->fecha, 'php:d/m/Y');
        } catch (\Exception $e) {
            return '-';
        }
    }


    public function getHoraFormateada()
    {
        return Yii::$app->formatter->asTime($this->hora, 'php:H:i');
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            // Convertir fecha de d/m/Y a Y-m-d si es necesario
            if (!empty($this->fecha) && strpos($this->fecha, '/') !== false) {
                $fecha = \DateTime::createFromFormat('d/m/Y', $this->fecha);
                if ($fecha) {
                    $this->fecha = $fecha->format('Y-m-d');
                }
            }

            // Convertir hora de H:i a H:i:s si es necesario
            if (!empty($this->hora) && strlen($this->hora) <= 5) {
                $hora = \DateTime::createFromFormat('H:i', $this->hora);
                if ($hora) {
                    $this->hora = $hora->format('H:i:s');
                }
            }

            return true;
        }

        return false;
    }
    public function getDispositivoDerivacion()
    {
        return $this->hasOne(OrganismoDispositivo::class, ['iddispositivo' => 'id_dispositivo_derivacion']);
    }
}
