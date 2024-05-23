<?php

namespace app\models;

use app\models\Mds_rendicion_comprobante;

use Yii;

/**
 * This is the model class for table "mds_rendicion".
 *
 * @property int $idrendicion
 * 
 * @property int $idtipo
 * @property int|null $idpersona
 * @property int $idlugar
 * @property string $fecha_comprobante
 * @property string|null $fecha_vale
 * @property string $monto
 * @property string|null $observaciones
 * 
 * @property int $idusuario_carga Usuario que carga
 * @property int|null $idusuario_modifica Usuario que modifica
 * @property int|null $idusuario_borra Usuario que borra
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property SdsComConfiguracion $idtipo
 * @property SdsComPersona $idpersona
 * @property SdsGisCapaItem $idlugar
 * @property MdsSegUsuario $idusuario_carga
 * @property MdsSegUsuario $idusuario_modifica
 * @property MdsSegUsuario $idusuario_borra
 */
class Mds_rendicion extends \yii\db\ActiveRecord
{
    const PATH = "uploads/rendicion/";

    const ID_ROL_ADMIN_GENERAL = 212;
    const ID_ROL_ADMINISTRADOR = 213;
    const ID_ROL_ADMINISTRATIVO = 214;
    const ID_ROLES_RENDICION = [212, 213, 214];

    const TIPO_AUH = 6398;
    const TIPO_ALIMENTAR = 6399;
    const TIPO_COMBUSTIBLE = 6400;

    public $idcapa;
    public $sujeto;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_rendicion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtipo', 'idlugar', 'monto', 'idusuario_carga'], 'required'],
            [['idrendicion', 'idpersona', 'idtipo', 'idlugar', 'idusuario_comprobante', 'idusuario_carga', 'idusuario_modifica', 'idusuario_borra'], 'integer'],
            [[
                'monto', 'observaciones', 'fecha_comprobante', 'fecha_vale',
                'created_at', 'updated_at', 'deleted_at'
            ], 'string'],
            [['idusuario_carga', 'created_at'], 'safe'],

            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idtipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idtipo' => 'idconfiguracion']],
            [['idlugar'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_gis_capa_item::class, 'targetAttribute' => ['idlugar' => 'idcapaitem']],
            [['idusuario_comprobante'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_comprobante' => 'idusuario']],
            [['idusuario_carga'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_carga' => 'idusuario']],
            [['idusuario_modifica'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_modifica' => 'idusuario']],
            [['idusuario_borra'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario_borra' => 'idusuario']],

            ['idpersona', 'required', 'when' => function ($model) {
                return ($model->idtipo == $this::TIPO_AUH) || ($model->idtipo == $this::TIPO_ALIMENTAR);
            }, 'whenClient' => "function (attribute, value) {
                return $('#tipo option:selected').val() == '" . $this::TIPO_AUH . "' || $('#tipo option:selected').val() == '" . $this::TIPO_ALIMENTAR . "';
            }"],
            ['fecha_comprobante', 'required', 'when' => function ($model) {
                return ($model->idtipo == $this::TIPO_AUH) || ($model->idtipo == $this::TIPO_ALIMENTAR);
            }, 'whenClient' => "function (attribute, value) {
                return $('#tipo option:selected').val() == '" . $this::TIPO_AUH . "' || $('#tipo option:selected').val() == '" . $this::TIPO_ALIMENTAR . "';
            }"],
            ['idusuario_comprobante', 'required', 'when' => function ($model) {
                return ($model->idtipo == $this::TIPO_COMBUSTIBLE);
            }, 'whenClient' => "function (attribute, value) {
                return $('#tipo option:selected').val() == '" . $this::TIPO_COMBUSTIBLE . "';
            }"],
            ['fecha_vale', 'required', 'when' => function ($model) {
                return ($model->idtipo == $this::TIPO_COMBUSTIBLE);
            }, 'whenClient' => "function (attribute, value) {
                return $('#tipo option:selected').val() == '" . $this::TIPO_COMBUSTIBLE . "';
            }"],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idrendicion' => '# Rendición',
            'idtipo' => 'Tipo de Rendición',
            'idpersona' => 'Los datos de Persona',
            'idusuario_comprobante' => 'Usuario',
            'sujeto' => 'Persona/Usuario',
            'idcapa' => 'Sector',
            'idlugar' => 'Lugar',
            'monto' => 'Monto Total',
            'observaciones' => 'Observaciones',
            'fecha_comprobante' => 'Fecha de comprobante',
            'fecha_vale' => 'Fecha del vale',
            'idusuario_vale' => 'Usuario',
            'idusuario_carga' => 'Usuario Carga',
            'idusuario_modifica' => 'Usuario Modifica',
            'idusuario_borra' => 'Usuario Borra',
            'created_at' => 'Created At',
            'updated_at' => 'Update At',
            'deleted_at' => 'Activo',
        ];
    }

    /**
     * Gets query for [[idpersona]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersona()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
    }

    /**
     * Gets query for [[idusuario_comprobante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioComprobante()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_comprobante']);
    }

    public function getComprobantes()
    {
        return $this->hasMany(Mds_rendicion_comprobante::class, ['idrendicion' => 'idrendicion'])->where(['deleted_at' => null])->orderBy([
            'idrendicion_comprobante' => SORT_DESC
        ]);
    }

    /**
     * Gets query for [[idtipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipo()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idtipo']);
    }

    /**
     * Gets query for [[idlugar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLugar()
    {
        return $this->hasOne(Sds_gis_capa_item::class, ['idcapaitem' => 'idlugar']);
    }

    /**
     * Gets query for [[idcapa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCapa()
    {
        return $this->hasOne(Sds_gis_capa::class, ['idcapa' => 'idcapa']);
    }

    /**
     * Gets query for [[idusuario_carga]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioCarga()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_carga']);
    }

    /**
     * Gets query for [[idusuario_modifica]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioModifica()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_modifica']);
    }

    /**
     * Gets query for [[idusuario_borra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioBorra()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario_borra']);
    }

    public static function getLugaresFiltro()
    {
        return Mds_rendicion::find()
            ->select("gis.idcapaitem, 
                    gis.descripcion as nombreLugar")
            ->from("mds_rendicion as rendicion")
            ->innerJoin('sds_gis_capa_item as gis', 'gis.idcapaitem = rendicion.idlugar')
            ->where("rendicion.deleted_at IS NULL 
                AND gis.activo = 1")
            ->orderBy(['nombreLugar' => SORT_ASC])
            ->asArray()
            ->all();
    }

    /**
     * Gets query for [[created_at]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaCarga()
    {
        $date = date_create($this->created_at);
        $fecha = date_format($date, 'd/m/Y');
        $hora = date_format($date, 'H:i');
        return $fecha . ' a las ' . $hora . ' hs';
    }
    /**
     * Gets query for [[updated_at]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaModifica()
    {
        $date = date_create($this->updated_at);
        $fecha = date_format($date, 'd/m/Y');
        $hora = date_format($date, 'H:i');
        return $fecha . ' a las ' . $hora . ' hs';
    }

    /**
     * Gets query for [[fecha_comprobante]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaComprobante()
    {
        $fecha = '';
        if (!is_null($this->fecha_comprobante)) {
            $date = date_create($this->fecha_comprobante);
            $fecha = date_format($date, 'd/m/Y');
        }
        return $fecha;
    }

    /**
     * Gets query for [[fecha_vale]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFechaVale()
    {
        $fecha = '';
        if (!is_null($this->fecha_vale)) {
            $date = date_create($this->fecha_vale);
            $fecha = date_format($date, 'd/m/Y');
        }
        return $fecha;
    }

    public function getOtrosAdjuntos()
    {
        $adjuntos =  Mds_legales_archivo::find()->where(
            [
                'id_objeto' => $this->idrendicion,
                'objeto' => 'mds_rendicion',
                'tipo' => 'registro_rendicion',
                'activo' => true
            ]
        )->all();
        foreach ($adjuntos as $adjunto) {
            $adjunto->path = self::PATH . $adjunto->path;
        }
        return $adjuntos;
    }
}
