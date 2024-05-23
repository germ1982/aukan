<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_relevamiento_registro".
 *
 * @property int $idrelevamientoregistro
 * @property int|null $idcapaitem
 * @property string|null $observaciones
 * @property int|null $idusuario_carga
 * @property int|null $idusuario_borra
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property SdsGisCapaItem $idcapaitem0
 * @property MdsSegUsuario $idusuarioBorra
 * @property MdsSegUsuario $idusuarioCarga
 * @property MdsRelevamientoRespuesta[] $mdsRelevamientoRespuestas
 */
class Mds_relevamiento_registro extends \yii\db\ActiveRecord
{
    //idconfiguraciontipo y el nombre que debe mostrar
    const ARRAY_AGRUPADORES = [
        213 => 'Aire acondicionado',
        214 => 'Calefacción',
        215 => 'Alarma',
        216 => 'Cocina',
        217 => 'Living',
        218 => 'Comedor',
        219 => 'Habitación',
        220 => 'Deposito',
        221 => 'Baño',
        222 => 'Sala operador',
        223 => 'Botiquin',
        224 => 'Matafuego',
        225 => 'Iluminación',
        226 => 'Ventana',
        227 => 'Puerta',
        228 => 'Varios',
        229 => 'Patio',
        230 => 'Techo'
    ];
    const ARRAY_CAPAS = [2, 3, 8, 11, 76];

    const ID_ROL_RELEVAMIENTO_ADMINISTRADOR_GENERAL = 198;
    const ID_ROL_RELEVAMIENTO_ADMINISTRADOR = 199;
    const ID_ROL_RELEVAMIENTO_CARGA = 200;
    const ID_ROL_RELEVAMIENTO_CONSULTA = 201;
    const PATH = "uploads/relevamiento/";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_relevamiento_registro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcapaitem', 'fecha'], 'required'],
            [['idcapaitem', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['observaciones'], 'string'],
            [['created_at', 'updated_at', 'deleted_at', 'fecha'], 'safe'],
            [['idcapaitem'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa_item::class, 'targetAttribute' => ['idcapaitem' => 'idcapaitem']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idrelevamientoregistro' => 'Idrelevamientoregistro',
            'idcapaitem' => 'Edificio',
            'observaciones' => 'Observaciones',
            'idusuario_carga' => 'Idusuario Carga',
            'idusuario_borra' => 'Idusuario Borra',
            'fecha' => 'Fecha',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ];
    }

    /**
     * Gets query for [[Idcapaitem0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCapaitem()
    {
        return $this->hasOne(Sds_gis_capa_item::class, ['idcapaitem' => 'idcapaitem']);
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
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }

    /**
     * Gets query for [[MdsRelevamientoRespuestas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsRelevamientoRespuestas()
    {
        return $this->hasMany(Mds_relevamiento_respuesta::class, ['idrelevamientoregistro' => 'idrelevamientoregistro']);
    }
    public function getItem($elemento)
    {
        $items = Sds_com_configuracion::find()
            ->where(['sds_com_configuracion.idconfiguraciontipo' => $elemento, 'sds_com_configuracion.activo' => 1])
            ->asArray()
            ->all();
        return $items;
    }

    public function getOtrosAdjuntos()
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->where(['objeto' => 'mds_relevamiento_registro', 'tipo' => 'relevamiento', 'activo' => true])
            ->andWhere(['=', 'id_objeto', $this->idrelevamientoregistro])
            ->all();
        foreach ($adjuntos as $adjunto) {
            $adjunto->path = self::PATH . $adjunto->path;
        }
        return $adjuntos;
    }
}
