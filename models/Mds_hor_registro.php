<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_hor_registro".
 *
 * @property int $idregistrohorario
 * @property int $idcontacto
 * @property string $fecha
 * @property int $origen 0=Importado; 1=Manual
 * @property string|null $observaciones
 * @property int $activo
 *
 * @property MdsOrgContacto $idcontacto0
 */
class Mds_hor_registro extends \yii\db\ActiveRecord
{
    const ORIGEN_IMPORTACION = 0;
    const ORIGEN_MANUAL = 1;
    const ORIGEN_CICLO = 2;
    const ORIGEN_GUARDIA = 3;

    public $fdesde;
    public $fhasta;
    //Lo uso para visualizar en vista el mapa de donde se ficho
    public $coordenadas;
    public $hora;
    public $legajo;
    public $ingreso;
    public $egreso;
    public $contacto;
    public $usuario_carga;
    public $fecha_carga;
    public $presente;
    public $reset_form; //Lo uso para saber cuando el usuario quiere limpiar el formulario en la carga de registros.
    public $dni; //Utilizada para fichada con DNI. No persistente


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_hor_registro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcontacto', 'fecha'], 'required'],
            [['idcontacto', 'origen', 'activo', 'presente', 'legajo', 'reset_form'], 'integer'],
            [['fecha', 'fdesde', 'fhasta', 'hora', 'ingreso', 'egreso', 'horario_nocturno','fecha_carga'], 'safe'],
            [['idcontacto', 'fecha'], 'unique', 'targetAttribute' => ['idcontacto', 'fecha']],
            [['observaciones', 'contacto', 'usuario_carga'], 'string'],
            [['latitud', 'longitud'], 'number'],
            [['idcontacto'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_org_contacto::class, 'targetAttribute' => ['idcontacto' => 'idcontacto']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistrohorario' => 'Idregistrohorario',
            'idcontacto' => 'Contacto',
            'fecha' => 'Fecha',
            'origen' => 'Origen',
            'observaciones' => 'Observaciones',
            'latitud' => 'Latitud',
            'longitud' => 'Longitud',
            'activo' => 'Activo',
            'presente' => 'Presente',
            'reset_form' => 'Limpiar Formulario',
            'idcertificacion' => 'Certificación',
            'horario_nocturno' => 'Horario Nocturno'
        ];
    }

    public static function getContadores()
    {
        /* "select (select count(distinct idcontacto) total from mds_hor_registro
        where origen=2) activos_ciclo,
        (select count(distinct idcontacto) total from mds_hor_registro
        where origen=2 and DATEDIFF(fecha,curdate())=0) hoy_ciclo,
        (select count(distinct idcontacto) total from mds_hor_registro
        where origen=0 and DATEDIFF(fecha,curdate())=0) hoy_reloj,
        (select count(distinct idcontacto) total from mds_hor_registro
        where origen=1 and DATEDIFF(fecha,curdate())=0) hoy_manual,
        (select count(distinct idcontacto) total from mds_hor_registro
        where origen=3 and DATEDIFF(fecha,curdate())=0) hoy_guardia;*/
        $query = (new \yii\db\Query())
            ->select([
                '(select count(distinct idcontacto) total from mds_hor_registro
        where origen=2) activos_ciclo',
                '(select count(distinct idcontacto) total from mds_hor_registro
        where origen=2 and DATEDIFF(fecha,curdate())=0) hoy_ciclo',
                '(select count(distinct idcontacto) total from mds_hor_registro
        where origen=0 and DATEDIFF(fecha,curdate())=0) hoy_reloj',
                '(select count(distinct idcontacto) total from mds_hor_registro
        where origen=1 and DATEDIFF(fecha,curdate())=0) hoy_manual',
                '(select count(distinct idcontacto) total from mds_hor_registro
        where origen=3 and DATEDIFF(fecha,curdate())=0) hoy_guardia'
            ])->one();

        return "<div class='col-md-2'><b>Activos Ciclo: " . $query['activos_ciclo'] . "</b></div>" .
            "<div class='col-md-2'>Ciclo (hoy): " . $query['hoy_ciclo'] . "</div>" .
            "<div class='col-md-2'>Reloj (hoy): " . $query['hoy_reloj'] . "</div>" .
            "<div class='col-md-2'>Manual (hoy): " . $query['hoy_manual'] . "</div>" .
            "<div class='col-md-2'>Guardia (hoy): " . $query['hoy_guardia'] . "</div>";
    }
}
