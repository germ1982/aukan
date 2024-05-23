<?php

namespace app\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "sds_com_persona".
 *
 * @property int $idpersona
 * @property int $documento
 * @property int $documento_tipo
 * @property int $nacionalidad
 * @property int $genero
 * @property int $genero_autopercibido
 * @property string $fecha_nacimiento
 * @property string $nombre
 * @property string $apellido
 * @property int|null $padre
 * @property int $conviviente
 * @property string|null $domicilio_calle
 * @property string|null $domicilio_numero
 * @property int|null $idlocalidad
 * 
 * 
 *
 * @property MdsCapDocente $mdsCapDocente
 * @property MdsCapPersona $mdsCapPersona
 * @property MdsCatPersona $mdsCatPersona
 * @property MdsCorIntervencion[] $mdsCorIntervencions
 * @property MdsInvPersona $mdsInvPersona
 * @property MdsOrgContacto $mdsOrgContacto
 * @property MdsRumPersona[] $mdsRumPersonas
 * @property Sds800AtencionFamilia[] $sds800AtencionFamilias
 * @property Sds800Persona $sds800Persona
 * @property SdsComConfiguracion $documentoTipo
 * @property SdsComConfiguracion $genero0
 * @property SdsComLocalidad $idlocalidad0
 * @property SdsComConfiguracion $nacionalidad0
 * @property Sds_com_persona $padre0
 * @property Sds_com_persona[] $sdsComPersonas
 * @property SdsEntEntrega[] $sdsEntEntregas
 * @property SdsEntEntrega[] $sdsEntEntregas0
 * @property SdsPenPension[] $sdsPenPensions
 * @property SdsPenPension[] $sdsPenPensions0
 * @property SdsRisPersona[] $sdsRisPersonas
 * @property SdsVioPersona $sdsVioPersona
 */
class Sds_com_persona extends \yii\db\ActiveRecord
{
    const TIPO_DNI = 83;

    public $lugar_voto;
    public $localidad;
    public $georeferencia;
    public $georeferencia_query;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_com_persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documento', 'documento_tipo', 'nacionalidad', 'genero', 'nombre', 'apellido', 'fecha_nacimiento'], 'required'],
            [['documento', 'documento_tipo', 'nacionalidad', 'genero', 'padre', 'conviviente', 'idlocalidad', 'genero_autopercibido'], 'integer'],
            [['fecha_nacimiento', 'georeferencia', 'localidad', 'georeferencia_query', 'lugar_voto'], 'safe'],
            [['nombre', 'apellido'], 'string', 'max' => 100],
            [['domicilio_calle', 'localidad'], 'string', 'max' => 255],
            [['domicilio_numero'], 'string', 'max' => 45],
            [['latitud'], 'double'],
            [['longitud'], 'double'],
            [['documento_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['documento_tipo' => 'idconfiguracion']],
            [['genero'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['genero' => 'idconfiguracion']],
            [['genero_autopercibido'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['genero_autopercibido' => 'idconfiguracion']],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::className(), 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
            [['nacionalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::className(), 'targetAttribute' => ['nacionalidad' => 'idconfiguracion']],
            [['padre'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::className(), 'targetAttribute' => ['padre' => 'idpersona']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idpersona' => 'Idpersona',
            'documento' => 'Documento',
            'documento_tipo' => 'Documento Tipo',
            'nacionalidad' => 'Nacionalidad',
            'genero' => 'Género',
            'genero_autopercibido' => 'Género Autopercibido',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'padre' => 'Padre',
            'conviviente' => 'Conviviente',
            'domicilio_calle' => 'Domicilio Calle',
            'domicilio_numero' => 'Domicilio Numero',
            'idlocalidad' => 'Idlocalidad',
            'georeferencia' => 'Georeferenciado',
            'localidad' => 'Localidad'

        ];
    }

    /**
     * Gets query for [[DocumentoTipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentoTipo()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'documento_tipo']);
    }

    /**
     * Gets query for [[Nacionalidad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNacionalidad()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'nacionalidad']);
    }
    public function getNacionalidad0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'nacionalidad']);
    }
    /**
     * Gets query for [[Genero0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenero0()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'genero']);
    }
    public function getGenero()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'genero']);
    }
    public function getGeneroAutopercibido()
    {
        return $this->hasOne(Sds_com_configuracion::className(), ['idconfiguracion' => 'genero_autopercibido']);
    }

    static public function getEdad($fecha_nacimiento)
    {
        $date_birth = new DateTime($fecha_nacimiento); //Crea el objeto DateTime a partir de un string de fecha
        $date_hoy = new DateTime(); //devuelve la fecha actual
        $edad = $date_birth->diff($date_hoy); //Aplicamos la diferencia entre fechas
        return $edad->y;
    }
}
