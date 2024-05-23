<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_gerontologia".
 *
 * @property int $idgerontologia
 * @property string|null $fecha_atencion
 * @property int $idpersona
 * @property int|null $idobrasocial
 * @property string|null $domicilio
 * @property int|null $idestadocivil
 * @property int|null $idvivienda
 * @property string|null $telefono
 * @property string|null $residencia
 * @property string|null $familia Path con un archivo adjunto
 * @property string|null $lugar_nacimiento
 * @property int|null $idescolaridad
 * @property string|null $vivencias
 * @property string|null $tiempo_libre
 * @property int|null $fuma
 * @property int|null $suenio_adecuado
 * @property int|null $ejercicio_fisico
 * @property int|null $vacunas_obligatorias
 * @property int|null $idvacunascovid
 * @property int|null $diuresis
 * @property int|null $catarsis
 * @property int|null $antecedentes_hta
 * @property int|null $antecedentes_acv
 * @property int|null $antecedentes_cardiaca
 * @property int|null $antecedentes_diabetes
 * @property int|null $antecedentes_cancer
 * @property string|null $antecedentes_otras
 * @property int|null $caidas
 * @property string|null $medicacion_actual
 * @property string|null $estudios_complementarios
 * @property string|null $examen_fis_ta
 * @property string|null $examen_fis_sato2
 * @property string|null $examen_fis_fc
 * @property string|null $examen_fis_abdomen
 * @property string|null $examen_fis_aparato_respiratorio
 * @property string|null $examen_fis_miembros_inferiores
 * @property string|null $examen_fis_observaciones
 * @property string|null $problemas_actuales
 * @property string|null $recomendaciones
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property int $idusuario_carga Usuario que carga
 * @property int|null $idusuario_modifica Usuario que modifica
 *
 * @property SdsComConfiguracion $idescolaridad0
 * @property SdsComConfiguracion $idestadocivil0
 * @property SdsComConfiguracion $idobrasocial0
 * @property SdsComPersona $idpersona
 * @property MdsSegUsuario $idusuarioCarga
 * @property MdsSegUsuario $idusuarioModifica
 * @property SdsComConfiguracion $idvacunascov
 * @property MdsGerontologiaRespuesta[] $mdsGerontologiaRespuestas
 */
class Mds_gerontologia extends \yii\db\ActiveRecord
{
    const PATH = "uploads/gerontologia/";

    const ID_VIVIENDA_RESIDENCIA = 3236;

    const ID_ITEM_SEGURIDAD = 138;
    const ID_ROL_ADMIN = 134;
    const ID_ROL_ADMIN_GENERAL = 176;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_gerontologia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_atencion', 'created_at', 'updated_at', 'deleted_at', 'abvd_lavado'], 'safe'],
            [['idpersona', 'idusuario_carga', 'fecha_atencion', 'idobrasocial', 'vacunas_obligatorias', 'idvacunascovid', 'caidas', 'idvivienda', 'idescolaridad'], 'required'],
            [['idpersona', 'idobrasocial', 'idestadocivil', 'idvivienda', 'idescolaridad', 'fuma', 'suenio_adecuado', 'ejercicio_fisico', 'vacunas_obligatorias', 'idvacunascovid', 'diuresis', 'catarsis', 'antecedentes_hta', 'antecedentes_acv', 'antecedentes_cardiaca', 'antecedentes_diabetes', 'antecedentes_cancer', 'caidas', 'idusuario_carga', 'idusuario_modifica'], 'integer'],
            [['domicilio', 'familia', 'lugar_nacimiento', 'vivencias', 'tiempo_libre', 'antecedentes_otras', 'medicacion_actual', 'estudios_complementarios', 'examen_fis_ta', 'examen_fis_sato2', 'examen_fis_fc', 'examen_fis_abdomen', 'examen_fis_aparato_respiratorio', 'examen_fis_miembros_inferiores', 'examen_fis_observaciones', 'problemas_actuales', 'recomendaciones', 'residencia'], 'string'],
            [['telefono'], 'string', 'max' => 255],
            [['idescolaridad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idescolaridad' => 'idconfiguracion']],
            [['idestadocivil'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idestadocivil' => 'idconfiguracion']],
            [['idobrasocial'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idobrasocial' => 'idconfiguracion']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_modifica'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_Usuario::class, 'targetAttribute' => ['idusuario_modifica' => 'idusuario']],
            [['idvacunascovid'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idvacunascovid' => 'idconfiguracion']],
            [['idvivienda'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idvivienda' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idgerontologia' => 'Idgerontologia',
            'fecha_atencion' => 'Fecha de atención',
            'idpersona' => 'Persona',
            'idobrasocial' => 'Obra social',
            'domicilio' => 'Domicilio',
            'idestadocivil' => 'Estado civil',
            'idvivienda' => 'Vivienda',
            'telefono' => 'Teléfono',
            'residencia' => 'Residencia',
            'familia' => 'Familia',
            'lugar_nacimiento' => 'Lugar de Nacimiento',
            'idescolaridad' => 'Escolaridad',
            'vivencias' => 'Vivencias',
            'tiempo_libre' => 'Tiempo Libre',
            'fuma' => 'Fuma',
            'suenio_adecuado' => 'Sueño Adecuado',
            'ejercicio_fisico' => 'Ejercicio Fisico',
            'vacunas_obligatorias' => 'Vacunas Obligatorias',
            'idvacunascovid' => 'Vacunas COVID19',
            'diuresis' => 'Diuresis',
            'catarsis' => 'Catarsis',
            'antecedentes_hta' => 'HTA',
            'antecedentes_acv' => 'ACV',
            'antecedentes_cardiaca' => 'Enfermedades cardiovasculares',
            'antecedentes_diabetes' => 'Diabetes',
            'antecedentes_cancer' => 'Cáncer',
            'antecedentes_otras' => 'Otras',
            'caidas' => 'Caídas',
            'medicacion_actual' => 'Medicación Actual',
            'estudios_complementarios' => 'Estudios Complementarios',
            'examen_fis_ta' => 'Examen Fis Ta',
            'examen_fis_sato2' => 'Examen Fis Sato2',
            'examen_fis_fc' => 'Examen Fis Fc',
            'examen_fis_abdomen' => 'Examen Fis Abdomen',
            'examen_fis_aparato_respiratorio' => 'Examen Fis Aparato Respiratorio',
            'examen_fis_miembros_inferiores' => 'Examen Fis Miembros Inferiores',
            'examen_fis_observaciones' => 'Examen fisico observaciones',
            'problemas_actuales' => 'Problemas Actuales',
            'recomendaciones' => 'Recomendaciones',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_modifica' => 'Idusuario Modifica',
        ];
    }

    /**
     * Gets query for [[IdusuarioCarga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }

    /**
     * Gets query for [[IdusuarioModifica]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuarioModifica()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_modifica']);
    }

    /**
     * Gets query for [[Idvacunascov]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdvacunascov()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idvacunascovid']);
    }

    /**
     * Gets query for [[MdsGerontologiaRespuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsGerontologiaRespuestas()
    {
        //  return $this->hasMany(Mds_gerontologia_respuesta::class, ['idgerontologia' => 'idgerontologia']);
    }
    public function getPersona()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }
    public function getObrasocial()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idobrasocial']);
    }
    public function getEstadocivil()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idestadocivil']);
    }
    public function getEscolaridad()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idescolaridad']);
    }
    public function getVacunascovid19()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idvacunascovid']);
    }
    public function getVivienda()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idvivienda']);
    }

    public function getAdjuntos()
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->where(['objeto' => 'mds_gerontologia', 'tipo' => 'registro_gerontologia', 'activo' => true])
            ->andWhere(['=', 'id_objeto', $this->idgerontologia])->all();
        foreach ($adjuntos as $adjunto) {
            $adjunto->path = self::PATH . $adjunto->path;
        }
        return $adjuntos;
    }

    public static function getRoles()
    {
        if (Yii::$app->user && Yii::$app->user->identity) {
            $usuarioAuth = Yii::$app->user->identity;
            $roles = Mds_seg_usuario_rol::find()->where(['idusuario' => $usuarioAuth->idusuario])->all();
        } else {
            $roles = [];
        }
        return $roles;
    }
    public static function tieneRol($idRol)
    {
        $roles = self::getRoles();
        $existe = false;
        $columna = array_column($roles, 'idrol');
        if (in_array($idRol, $columna)) {
            $existe = true;
        }

        return $existe;
    }
}
