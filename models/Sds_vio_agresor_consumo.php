<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_vio_agresor_consumo".
 *
 * @property int $idagresorconsumo
 * @property int $idagresor
 * @property int $idconsumo
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at

 */
class Sds_vio_agresor_consumo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_vio_agresor_consumo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idagresorconsumo', 'idagresor', 'idconsumo'], 'integer'],
            [['created_at', 'deleted_at'], 'safe'],
            [['idagresor'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_vio_agresor::class, 'targetAttribute' => ['idagresor' => 'idagresor']],
            [['idconsumo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idconsumo' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idagresorconsumo' => 'Idagresorconsumo',
            'idagresor' => 'Idagresor',
            'idconsumo' => 'Idconsumo',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[getIdConsumo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdConsumo()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idconsumo']);
    }

    /**
     * Gets query for [[getIdAgresor0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdAgresor0()
    {
        return $this->hasOne(Sds_vio_agresor::class, ['idagresor' => 'idagresor']);
    }

    public static function getConsumoByAgresor($idAgresor)
    {
        $arrConsumos = Sds_vio_agresor_consumo::find()
            ->select('consumoConfiguracion.descripcion as consumoDetalle')
            ->innerJoin('sds_vio_agresor', 'sds_vio_agresor.idagresor = sds_vio_agresor_consumo.idagresor')
            ->leftJoin('sds_com_configuracion consumoConfiguracion', 'consumoConfiguracion.idconfiguracion = sds_vio_agresor_consumo.idconsumo')
            ->where(['sds_vio_agresor_consumo.idagresor' => $idAgresor])
            ->andWhere(['sds_vio_agresor_consumo.deleted_at' => null])
            ->asArray()
            ->all();
        return $arrConsumos;
    }
}
