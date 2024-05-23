<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_vio_intervencion_agresor".
 *
 * @property int $idintervencionagresor
 * @property int $idintervencion
 * @property int $idagresor
 * @property int $parentezco
 * @property int $activo
 *
 * @property Sds_vio_agresor $idagresor0
 * @property Sds_vio_intervencion $idintervencion0
 * @property SdsComConfiguracion $parentezco0
 */
class Sds_vio_intervencion_agresor extends \yii\db\ActiveRecord
{
    public $agresores;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_vio_intervencion_agresor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idintervencion', 'idagresor', 'parentezco', 'activo'], 'required'],
            [['idintervencion', 'idagresor', 'parentezco', 'activo'], 'integer'],
            [['agresores'], 'safe'],

            [['idagresor'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_vio_agresor::class, 'targetAttribute' => ['idagresor' => 'idagresor']],
            [['idintervencion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_vio_intervencion::class, 'targetAttribute' => ['idintervencion' => 'idintervencion']],
            [['parentezco'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['parentezco' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idintervencionagresor' => '#',
            'idintervencion' => 'Intervencion #',
            'idagresor' => 'Agresor #',
            'parentezco' => 'Parentesco',
        ];
    }

    /**
     * Gets query for [[Idagresor0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdagresor0()
    {
        return $this->hasOne(Sds_vio_agresor::class, ['idagresor' => 'idagresor']);
    }

    /**
     * Gets query for [[Idintervencion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdintervencion0()
    {
        return $this->hasOne(Sds_vio_intervencion::class, ['idintervencion' => 'idintervencion']);
    }

    /**
     * Gets query for [[Parentezco0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentezco0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'parentezco']);
    }

    public static function getAgresoresByIntervencion($idintervencion)
    {
        return Sds_vio_intervencion_agresor::find()
            ->select('*,
            sds_com_configuracion.descripcion as parentesco,
            generoConfiguracion.descripcion as generoDetalle,
            vinculoConfiguracion.descripcion as vinculoPersonalSeguridad,
            escolaridadConfiguracion.descripcion as escolaridadDetalle
            ')
            ->innerJoin('sds_vio_agresor', 'sds_vio_intervencion_agresor.idagresor = sds_vio_agresor.idagresor')
            ->leftJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion = sds_vio_intervencion_agresor.parentezco')
            ->leftJoin('sds_com_configuracion generoConfiguracion', 'generoConfiguracion.idconfiguracion = sds_vio_agresor.genero')
            ->leftJoin('sds_com_configuracion vinculoConfiguracion', 'vinculoConfiguracion.idconfiguracion = sds_vio_agresor.vinculo_personal_seguridad')
            ->leftJoin('sds_com_configuracion escolaridadConfiguracion', 'escolaridadConfiguracion.idconfiguracion = sds_vio_agresor.escolaridad')
            ->where(['idintervencion' => $idintervencion])
            ->andWhere(['sds_vio_intervencion_agresor.activo' => 1])
            ->asArray()
            ->all();
    }

    public static function getIntervencion($idintervencion, $idagresor)
    {
        return Sds_vio_intervencion_agresor::find()->where(['idintervencion' => $idintervencion, 'idagresor' => $idagresor])->asArray()->one();
    }
}
