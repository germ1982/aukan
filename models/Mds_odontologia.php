<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_odontologia".
 *
 * @property int $idodontologia
 * @property int $idpersona
 * @property string $telefono
 * @property int|null $vacunas_obligatorias
 * @property int|null $cant_dientes
 * @property int|null $cant_caries
 * @property int|null $cant_dientes_temporales
 * @property int|null $cant_caries_temporales
 * @property int|null $cant_obturados
 * @property int|null $cant_perdidos
 * @property int|null $cant_obturados_temporales
 * @property int|null $cant_perdidos_temporales
 * @property string|null $observaciones
 * @property int $iddispositivo
 * @property int|null $idescolaridad
 * @property int|null $idtipointervencion HC o Visita
 * @property int|null $idtipovisita Derivación/Preventiva/Taller/Urgencia
 * @property string|null $enfermedad_periodontal
 * @property string|null $enfermedad_base
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $idusuario_carga Usuario que carga
 * @property int|null $idusuario_modifica Usuario que modifica
 *
 * @property SdsComConfiguracion $iddispositivo0
 * @property SdsComConfiguracion $idescolaridad0
 * @property SdsComPersona $idpersona0
 * @property SdsComConfiguracion $idtipointervencion0
 * @property MdsSegUsuario $idusuarioCarga
 * @property MdsSegUsuario $idusuarioModifica
 */
class Mds_odontologia extends \yii\db\ActiveRecord
{
    const PATH = "uploads/odontologia/";
    const ID_ITEM_SEGURIDAD = 137;
    const ID_ROL_ADMIN = 131;
    const ID_ROL_ADMIN_GENERAL = 175;
    const TIPO_INTERVENCION_HC = 2821;
    const TIPO_INTERVENCION_VISITA = 2822;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_odontologia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idpersona', 'idtipointervencion', 'fecha_atencion', 'cant_dientes', 'cant_caries', 'cant_dientes_temporales', 'cant_caries_temporales', 'cant_obturados', 'cant_obturados_temporales', 'cant_perdidos', 'cant_perdidos_temporales', 'vacuna_covid19', 'vacunas_obligatorias'], 'required'],
            [['idpersona', 'cant_dientes', 'cant_caries', 'cant_dientes_temporales', 'cant_caries_temporales', 'cant_obturados', 'cant_perdidos', 'cant_obturados_temporales', 'cant_perdidos_temporales', 'iddispositivo', 'idescolaridad', 'idtipointervencion', 'idtipovisita', 'idusuario_carga', 'idusuario_modifica', 'vacunas_obligatorias'], 'integer'],
            [['observaciones', 'enfermedad_periodontal', 'enfermedad_base', 'telefono'], 'string'],
            [['created_at', 'updated_at', 'deleted_at', 'fecha_atencion'], 'safe'],
            [['iddispositivo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['iddispositivo' => 'idconfiguracion']],
            [['vacuna_covid19'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vacuna_covid19' => 'idconfiguracion']],
            [['idescolaridad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idescolaridad' => 'idconfiguracion']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idtipointervencion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idtipointervencion' => 'idconfiguracion']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_modifica'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_modifica' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idodontologia' => 'Idodontologia',
            'idpersona' => 'Persona',
            'telefono' => 'Teléfono',
            'vacunas_obligatorias' => 'Vacunas obligatorias',
            'vacuna_covid19' => 'Vacunas COVID19',
            'cant_dientes' => 'Cantidad de dientes permanentes',
            'cant_caries' => 'Cantidad de caries en permanentes',
            'cant_dientes_temporales' => 'Cantidad de dientes temporales',
            'cant_caries_temporales' => 'Cantidad de caries en temporales',
            'cant_obturados' => 'Cantidad de obturados',
            'cant_perdidos' => 'Cantidad de perdidos',
            'cant_obturados_temporales' => 'Cantidad de obturados temporales',
            'cant_perdidos_temporales' => 'Cantidad de perdidos temporales',
            'observaciones' => 'Observaciones',
            'iddispositivo' => 'Institución/Dispositivo',
            'idescolaridad' => 'Estado de escolaridad',
            'idtipointervencion' => 'Tipo de intervención',
            'idtipovisita' => 'Tipo de visita',
            'enfermedad_periodontal' => 'Enfermedad Periodontal',
            'enfermedad_base' => 'Enfermedad de base',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'fecha_atencion' => 'Fecha atención',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_modifica' => 'Idusuario Modifica',
        ];
    }

    /**
     * Gets query for [[Iddispositivo0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddispositivo0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'iddispositivo']);
    }

    /**
     * Gets query for [[Idescolaridad0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdescolaridad0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idescolaridad']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona0()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[Idtipointervencion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdtipointervencion0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idtipointervencion']);
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

    public function getPersona()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }

    public function getEscolaridad()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idescolaridad']);
    }
    public function getTipointervencion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idtipointervencion']);
    }
    public function getTipovisita()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idtipovisita']);
    }
    public function getDispositivo()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'iddispositivo']);
    }
    public function getUsuariocarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }
    public function getVacunacovid19()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vacuna_covid19']);
    }

    public function getAdjuntos()
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->where(['objeto' => 'mds_odontologia', 'tipo' => 'registro_odontologia', 'activo' => true])
            ->andWhere(['=', 'id_objeto', $this->idodontologia])->all();
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
