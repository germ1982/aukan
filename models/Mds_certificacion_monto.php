<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_monto".
 *
 * @property int $idcertificacionmonto
 * @property int $idcertificacion
 * @property string $monto
 * 
 * @property string $created_at
 * @property string|null $deleted_at
 * 
 * @property int $idusuario_carga Usuario que carga
 * @property int|null $idusuario_carga Usuario que borra
 *
 * @property MdsCertificacion $idcertificacion0
 */
class Mds_certificacion_monto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_monto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificacion', 'monto'], 'required'],
            [['idcertificacionmonto', 'idcertificacion', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['monto'], 'string'],
            [['created_at', 'deleted_at'], 'safe'],

            [['idcertificacion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion::class, 'targetAttribute' => ['idcertificacion' => 'idcertificacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcertificacionmonto' => 'Idcertificacionmonto',
            'idcertificacion' => 'Certificación',
            'monto' => 'Monto Solicitado',

            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_borra' => 'Idusuario Borra',

        ];
    }

    /**
     * Gets query for [[Idcertificacion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdcertificacion0()
    {
        return $this->hasOne(Mds_certificacion::class, ['idcertificacion' => 'idcertificacion']);
    }


    public function getMontoHistorial($listado, $idcertificacion)
    {
        $connection = Yii::$app->getDb();
        $usuarioAuth = Yii::$app->user->identity;
        $montos = $connection->createCommand(
            "SELECT monto.idcertificacionmonto,
            monto.idcertificacion,
            UPPER (CONCAT(usuario.apellido,' ',usuario.nombre))as user,
            DATE_FORMAT(monto.created_at, '%d/%m/%Y %H:%iHs') as created_at,
            DATE_FORMAT(monto.deleted_at, '%d/%m/%Y %H:%iHs') as deleted_at,
            monto.monto
            FROM mds_certificacion_monto monto 
            INNER JOIN mds_certificacion certificacion ON monto.idcertificacion = certificacion.idcertificacion 
            INNER JOIN mds_seg_usuario usuario ON monto.idusuario_carga = usuario.idusuario
            WHERE monto.idcertificacion = '$idcertificacion'
            ORDER BY monto.created_at DESC
            ")
            ->queryAll();
        $listado['montos'] = $montos;
        return $listado;
    }
}
