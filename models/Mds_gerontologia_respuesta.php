<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_gerontologia_respuesta".
 *
 * @property int $idgerontologiarespuesta
 * @property int $idgerontologia
 * @property int|null $abvd_lavado
 * @property int|null $abvd_vestido
 * @property int|null $abvd_banio
 * @property int|null $abvd_movilizacion
 * @property int|null $abvd_continencia
 * @property int|null $abvd_alimentacion
 * @property int|null $abvd
 * @property int|null $aivd_capacidad_telefono
 * @property int|null $aivd_compras
 * @property int|null $aivd_preparacion_comida
 * @property int|null $aivd_cuidado_casa
 * @property int|null $aivd_lavado_ropa
 * @property int|null $aivd_uso_transporte
 * @property int|null $aivd_responsabilidad_medicacion
 * @property int|null $aivd_manejo_asuntos_economicos
 * @property int|null $aivd
 * @property int|null $idsituacionfamiliar
 * @property int|null $idrelacionessociales
 * @property int|null $idredsocial
 * @property int|null $ev_social_total
 * @property int|null $icope_detcog_responde_incorrectamente
 * @property int|null $icope_detcog_no_responde
 * @property int|null $icope_perdida_movilidad
 * @property int|null $icope_nut_def_perdida_peso
 * @property int|null $icope_nut_def_perdida_apetito
 * @property int|null $icope_discapacidad_visual
 * @property int|null $icope_perdida_auditiva
 * @property int|null $icope_sin_dep_sentimientos
 * @property int|null $icope_sin_dep_interes
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property SdsComConfiguracion $idredsocial0
 * @property SdsComConfiguracion $idrelacionessociales0
 * @property SdsComConfiguracion $idsituacionfamiliar0
 * @property SdsComConfiguracion $abvdAlimentacion
 * @property SdsComConfiguracion $abvdBanio
 * @property SdsComConfiguracion $abvdContinencia
 * @property SdsComConfiguracion $abvdLavado
 * @property SdsComConfiguracion $abvdMovilizacion
 * @property SdsComConfiguracion $abvdVestido
 * @property SdsComConfiguracion $aivdCapacidadTelefono
 * @property SdsComConfiguracion $aivdCompras
 * @property SdsComConfiguracion $aivdCuidadoCasa
 * @property SdsComConfiguracion $aivdManejoAsuntosEconomicos
 * @property SdsComConfiguracion $aivdPreparacionComida
 * @property SdsComConfiguracion $aivdResponsabilidadMedicacion
 * @property SdsComConfiguracion $aivdUsoTransporte
 * @property MdsGerontologia $idgerontologia0
 */
class Mds_gerontologia_respuesta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_gerontologia_respuesta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idgerontologia', 'abvd_lavado', 'abvd_vestido', 'abvd_banio', 'abvd_movilizacion', 'abvd_continencia', 'abvd_alimentacion', 'aivd_capacidad_telefono', 'aivd_compras', 'aivd_preparacion_comida', 'aivd_cuidado_casa', 'aivd_lavado_ropa', 'aivd_uso_transporte', 'aivd_responsabilidad_medicacion', 'aivd_manejo_asuntos_economicos', 'idsituacionfamiliar', 'idrelacionessociales', 'idredsocial'], 'required'],
            [['idgerontologia', 'abvd_lavado', 'abvd_vestido', 'abvd_banio', 'abvd_movilizacion', 'abvd_continencia', 'abvd_alimentacion', 'abvd', 'aivd_capacidad_telefono', 'aivd_compras', 'aivd_preparacion_comida', 'aivd_cuidado_casa', 'aivd_lavado_ropa', 'aivd_uso_transporte', 'aivd_responsabilidad_medicacion', 'aivd_manejo_asuntos_economicos', 'aivd', 'idsituacionfamiliar', 'idrelacionessociales', 'idredsocial', 'ev_social_total', 'icope_detcog_responde_incorrectamente', 'icope_detcog_no_responde', 'icope_perdida_movilidad', 'icope_nut_def_perdida_peso', 'icope_nut_def_perdida_apetito', 'icope_discapacidad_visual', 'icope_perdida_auditiva', 'icope_sin_dep_sentimientos', 'icope_sin_dep_interes'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['idredsocial'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idredsocial' => 'idconfiguracion']],
            [['idrelacionessociales'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idrelacionessociales' => 'idconfiguracion']],
            [['idsituacionfamiliar'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idsituacionfamiliar' => 'idconfiguracion']],
            [['abvd_alimentacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['abvd_alimentacion' => 'idconfiguracion']],
            [['abvd_banio'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['abvd_banio' => 'idconfiguracion']],
            [['abvd_continencia'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['abvd_continencia' => 'idconfiguracion']],
            [['abvd_lavado'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['abvd_lavado' => 'idconfiguracion']],
            [['abvd_movilizacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['abvd_movilizacion' => 'idconfiguracion']],
            [['abvd_vestido'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['abvd_vestido' => 'idconfiguracion']],
            [['aivd_capacidad_telefono'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['aivd_capacidad_telefono' => 'idconfiguracion']],
            [['aivd_compras'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['aivd_compras' => 'idconfiguracion']],
            [['aivd_cuidado_casa'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['aivd_cuidado_casa' => 'idconfiguracion']],
            [['aivd_manejo_asuntos_economicos'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['aivd_manejo_asuntos_economicos' => 'idconfiguracion']],
            [['aivd_preparacion_comida'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['aivd_preparacion_comida' => 'idconfiguracion']],
            [['aivd_responsabilidad_medicacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['aivd_responsabilidad_medicacion' => 'idconfiguracion']],
            [['aivd_uso_transporte'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['aivd_uso_transporte' => 'idconfiguracion']],
            [['idgerontologia'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_gerontologia::class, 'targetAttribute' => ['idgerontologia' => 'idgerontologia']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idgerontologiarespuesta' => 'Idgerontologiarespuesta',
            'idgerontologia' => 'Idgerontologia',
            'abvd_lavado' => 'Lavado',
            'abvd_vestido' => 'Vestido',
            'abvd_banio' => 'Uso del baño',
            'abvd_movilizacion' => 'Movilización',
            'abvd_continencia' => 'Continencia',
            'abvd_alimentacion' => 'Alimentación',
            'abvd' => 'ABVD',
            'aivd_capacidad_telefono' => 'Capacidad para usar el teléfono',
            'aivd_compras' => 'Compras',
            'aivd_preparacion_comida' => 'Preparación de la comida',
            'aivd_cuidado_casa' => 'Cuidado de la casa',
            'aivd_lavado_ropa' => 'Lavado de ropa',
            'aivd_uso_transporte' => 'Uso de medios de transporte',
            'aivd_responsabilidad_medicacion' => 'Responsabilidad respecto a su medicación',
            'aivd_manejo_asuntos_economicos' => 'Manejo de asuntos económicos',
            'aivd' => 'AIVD',
            'idsituacionfamiliar' => 'Situación familiar',
            'idrelacionessociales' => 'Relaciones sociales',
            'idredsocial' => 'Apoyos de la red social',
            'ev_social_total' => 'Ev Social Total',
            'icope_detcog_responde_incorrectamente' => '2) Responde incorrectamente a las dos preguntas o no sabe',
            'icope_detcog_no_responde' => '3) No recuerda las tres palabras',
            'icope_perdida_movilidad' => 'No',
            'icope_nut_def_perdida_peso' => '1) Sí',
            'icope_nut_def_perdida_apetito' => '2) Sí',
            'icope_discapacidad_visual' => 'Sí',
            'icope_perdida_auditiva' => 'Sí',
            'icope_sin_dep_sentimientos' => '1) Sí',
            'icope_sin_dep_interes' => '2) Sí',
            'created_at' => 'Creado',
            'updated_at' => 'Actualizado',
            'deleted_at' => 'Eliminado',
        ];
    }

    /**
     * Gets query for [[Idredsocial0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRedsocial()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idredsocial']);
    }

    /**
     * Gets query for [[Idrelacionessociales0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRelacionessociales()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idrelacionessociales']);
    }

    /**
     * Gets query for [[Idsituacionfamiliar0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSituacionfamiliar()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idsituacionfamiliar']);
    }

    /**
     * Gets query for [[AbvdAlimentacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbvdalimentacion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'abvd_alimentacion']);
    }

    /**
     * Gets query for [[AbvdBanio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbvdbanio()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'abvd_banio']);
    }

    /**
     * Gets query for [[AbvdContinencia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbvdcontinencia()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'abvd_continencia']);
    }

    /**
     * Gets query for [[AbvdLavado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbvdlavado()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'abvd_lavado']);
    }

    /**
     * Gets query for [[AbvdMovilizacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbvdmovilizacion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'abvd_movilizacion']);
    }

    /**
     * Gets query for [[AbvdVestido]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAbvdvestido()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'abvd_vestido']);
    }

    /**
     * Gets query for [[AivdCapacidadTelefono]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAivdcapacidadtelefono()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'aivd_capacidad_telefono']);
    }

    /**
     * Gets query for [[AivdCompras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAivdcompras()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'aivd_compras']);
    }

    /**
     * Gets query for [[AivdCuidadoCasa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAivdcuidadocasa()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'aivd_cuidado_casa']);
    }

    /**
     * Gets query for [[AivdManejoAsuntosEconomicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAivdasuntoseconomicos()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'aivd_manejo_asuntos_economicos']);
    }

    /**
     * Gets query for [[AivdPreparacionComida]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAivdpreparacioncomida()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'aivd_preparacion_comida']);
    }

    /**
     * Gets query for [[AivdResponsabilidadMedicacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAivdresponsabilidadmedicacion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'aivd_responsabilidad_medicacion']);
    }

    /**
     * Gets query for [[AivdUsoTransporte]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAivdusotransporte()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'aivd_uso_transporte']);
    }
    public function getAivdlavadoropa()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'aivd_lavado_ropa']);
    }


    public function getAivdsituacionfamiliar()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idsituacionfamiliar']);
    }
    public function getAivdrelacionsocial()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idrelacionessociales']);
    }
    public function getAivdredsocial()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idredsocial']);
    }

    /**
     * Gets query for [[Idgerontologia0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdgerontologia0()
    {
        return $this->hasOne(Mds_gerontologia::class, ['idgerontologia' => 'idgerontologia']);
    }

    public function getRespuestaByidgerontologia($id)
    {
        return $this->hasOne(Mds_gerontologia::class, ['idgerontologia' => $id]);
    }
}
