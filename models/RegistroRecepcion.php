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
    public $nombre;
    public $apellido;
    public $documento_tipo;
    public $nacionalidad;
    public $genero;
    public $fecha_nacimiento;
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
            [['fecha'], 'required'],
            [['hora'], 'safe'],
            [['dni', 'acceso', 'id_dispositivo_derivacion', 'id_responsable_derivacion', 'id_tipo_recepcion','idpersona'], 'integer'],
            [['motivo', 'observacion'], 'string'],
            [['nombre', 'apellido', 'documento_tipo', 'nacionalidad', 'genero', 'fecha_nacimiento',], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_registro_recepcion' => 'Nº Registro',
            'fecha' => 'Fecha',
            'hora' => 'Hora',
            'dni' => 'Dni',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'documento_tipo' => 'Documento Tipo',
            'nacionalidad' => 'Nacionalidad',
            'genero' => 'Genero',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'motivo' => 'Motivo',
            'acceso' => 'Acceso',
            'id_dispositivo_derivacion' => 'Id Dispositivo Derivacion',
            'id_responsable_derivacion' => 'Id Responsable Derivacion',
            'id_tipo_recepcion' => 'Id Tipo Recepcion',
            'observacion' => 'Observacion',
            'idpersona' => 'Buscar por Documento',
        ];
    }
    public static function getRutaUploads()
    {
        return Yii::$app->params['rutaUploads'] . 'registro_recepcion/';
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

            // Si no se proporcionó una fecha, asignar la actual
            if (empty($this->fecha)) {
                $this->fecha = date('Y-m-d');
            }

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

            // Validación para evitar duplicados por DNI y fecha
            if ($this->dni) {
                $existe = self::find()
                    ->where(['dni' => $this->dni, 'fecha' => $this->fecha])
                    ->andFilterWhere(['<>', 'id_registro_recepcion', $this->id_registro_recepcion])
                    ->exists();

                if ($existe) {
                    $this->addError('dni', 'Ya existe un registro para esta persona en la fecha seleccionada.');
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function getNombreDispositivoDerivacion()
    {
        return $this->dispositivoDerivacion ? $this->dispositivoDerivacion->descripcion : 'Sin dato';
    }


    public function getDispositivoDerivacion()
    {
        return $this->hasOne(OrganismoDispositivo::class, ['iddispositivo' => 'id_dispositivo_derivacion']);
    } 
    public function getPersona()
    {
        return $this->hasOne(\app\models\Persona::class, ['documento' => 'dni']);
    }
}
