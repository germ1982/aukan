<?php

namespace app\models;

use yii\helpers\ArrayHelper;

use Yii;

/**
 * This is the model class for table "mds_certificacion_programa".
 *
 * @property int $idcertificacionprograma
 * @property int $idcertificaciondireccion
 * @property int $idprograma
 * @property int $idusuario_carga
 * @property int|null $idusuario_borra
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property MdsCertificacionDireccion $idcertificaciondireccion0
 * @property SdsComConfiguracion $idprograma0
 * @property MdsSegUsuario $idusuarioBorra
 * @property MdsSegUsuario $idusuarioCarga
 */
class Mds_certificacion_programa extends \yii\db\ActiveRecord
{
    const ADJUNTO_BAJA = 6303;
    const ADJUNTO_OBSERVAR = 6304;
    const ADJUNTO_RECHAZAR = 6305;

    public $monto;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_programa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificaciondireccion', 'idprograma', 'idusuario_carga', 'cambio_responsable', 'requiere_autorizacion', 'idtipo_subsidio', 'created_at'], 'required'],
            [['idcertificaciondireccion', 'idprograma', 'idusuario_carga', 'idusuario_borra', 'cambio_responsable',  'requiere_autorizacion', 'idtipo_subsidio', 'cant_niveles_autorizacion'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['idcertificaciondireccion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion_direccion::class, 'targetAttribute' => ['idcertificaciondireccion' => 'idcertificaciondireccion']],
            [['idprograma'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idprograma' => 'idconfiguracion']],
            [['idtipo_subsidio'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idtipo_subsidio' => 'idconfiguracion']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            ['cant_niveles_autorizacion', 'required', 'when' => function ($model) {
                return $model->requiere_autorizacion == 1;
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idcertificacionprograma' => 'Idcertificacionprograma',
            'idcertificaciondireccion' => 'Dirección',
            'idprograma' => 'Programa',
            'cambio_responsable' => '¿Permitir cambio de responsable?',
            'requiere_autorizacion' => '¿Requiere autorización previa?',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_borra' => 'Idusuario Borra',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Activo',
            'idtipo_subsidio' => 'Tipo subsidio',
            'cant_niveles_autorizacion' => 'Cantidad de niveles de autorización',
        ];
    }

    /**
     * Gets query for [[Idcertificaciondireccion0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDireccion0()
    {
        return $this->hasOne(Mds_certificacion_direccion::class, ['idcertificaciondireccion' => 'idcertificaciondireccion']);
    }

    /**
     * Gets query for [[Idprograma0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrograma0()
    {
        return $this->hasOne(Sds_Com_configuracion::class, ['idconfiguracion' => 'idprograma']);
    }
    /**
     * Gets query for [[idtipo_subsidio0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoSubsidio0()
    {
        return $this->hasOne(Sds_Com_configuracion::class, ['idconfiguracion' => 'idtipo_subsidio']);
    }

    public function getAdjuntosObligatorios()
    {
        $obligatorio = 1;
        $adjuntos = Mds_certificacion_programa_adjunto::find()->where(['idcertificacionprograma' => $this->idcertificacionprograma, 'obligatorio' => $obligatorio, 'deleted_at' => NULL])->all();
        $idadjunto = ArrayHelper::map($adjuntos, 'idadjunto', 'idadjunto');
        return $idadjunto;
    }

    public function getAdjuntosSugeridos()
    {
        $obligatorio = 0;
        $adjuntos = Mds_certificacion_programa_adjunto::find()->where(['idcertificacionprograma' => $this->idcertificacionprograma, 'obligatorio' => $obligatorio, 'deleted_at' => NULL])->all();
        $idadjunto = ArrayHelper::map($adjuntos, 'idadjunto', 'idadjunto');
        return $idadjunto;
    }

    public function getRequisitos()
    {
        $requisitos = Mds_certificacion_programa_requisito::find()->where(['idcertificacionprograma' => $this->idcertificacionprograma, 'deleted_at' => NULL])->all();
        $idrequisito = ArrayHelper::map($requisitos, 'idrequisito', 'idrequisito');
        return $idrequisito;
    }

    /**
     * Gets query for [[IdusuarioBorra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
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
}
