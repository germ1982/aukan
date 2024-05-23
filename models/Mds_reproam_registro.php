<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_reproam_registro".
 *
 * @property int $idregistro
 * @property string|null $numero_legajo_reproam
 * @property string $nombre
 * @property string $direccion
 * @property int|null $idbarrio
 * @property int $idlocalidad
 * @property string|null $telefono
 * @property string|null $telefono_movil
 * @property int $idzona
 * @property string|null $nombre_presidente
 * @property string|null $nombre_vicepresidente
 * @property string|null $nombre_secretario
 * @property int|null $personeria_juridica
 * @property string|null $personeria_juridica_resolucion
 * @property string|null $personeria_juridica_fecha_vencimiento
 * @property string $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Mds_reproam_mandato[] $mdsReproamMandatos
 * @property Sds_com_barrio $idbarrio
 * @property Sds_com_localidad $idlocalidad
 * @property Sds_com_configuracion $idzona
 */
class Mds_reproam_registro extends \yii\db\ActiveRecord
{
    const PATH = "uploads/reproam/";
    const ID_ITEM_SEGURIDAD = 123;
    const ID_ROL_GLOBAL = 113;
    const ID_ROL_USUARIO = 114;
    const ID_ROL_ADMIN_GENERAL = 174;
    const ID_PROVINCIA_NEUQUEN = 58;
    const ID_LOCALIDAD_NEUQUEN_CAPITAL = 58035070;
    const CONFIGURACION_TIPO_ZONAS = 101;
    const DIAS_VENCIMIENTO_PERSONERIA = '+90 day';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_reproam_registro';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'direccion', 'idlocalidad', 'idbarrio', 'idzona', 'created_at', 'idusuario_carga', 'inscripto', 'personeria_juridica', 'entrega_constancia_inscripcion'], 'required'],
            [['idlocalidad', 'idusuario_carga', 'idusuario_borra', 'personeria_juridica', 'inscripto', 'entrega_constancia_inscripcion','situacion_habitacional'], 'integer'],
            [['idbarrio'], 'integer', 'message' => 'Barrio no puede estar vacío.'],
            [['idzona'], 'integer', 'message' => 'Zona no puede estar vacío.'],
            [['personeria_juridica_fecha_vencimiento', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['observaciones'], 'string'],
            [['numero_legajo_reproam', 'nombre', 'direccion', 'telefono', 'telefono_movil', 'mail', 'nombre_presidente', 'nombre_vicepresidente', 'nombre_secretario', 'personeria_juridica_resolucion', 'entrega_constancia_inscripcion_nombre'], 'string', 'max' => 255],
            [['idbarrio'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_barrio::class, 'targetAttribute' => ['idbarrio' => 'idbarrio']],
            [['idlocalidad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_localidad::class, 'targetAttribute' => ['idlocalidad' => 'idlocalidad']],
            [['idzona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idzona' => 'idconfiguracion']],
            [['situacion_habitacional'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['situacion_habitacional' => 'idconfiguracion']],
            ['mail', 'filter', 'filter' => 'trim'],
            ['mail', 'email'],
            ['numero_legajo_reproam', 'required', 'when' => function ($model) {
                return $model->inscripto == 1;
            }],
            ['entrega_constancia_inscripcion_nombre', 'required', 'when' => function ($model) {
                return $model->entrega_constancia_inscripcion == 1;
            }],
            [
                ['personeria_juridica_resolucion', 'personeria_juridica_fecha_vencimiento'],
                'required', 'when' => function ($model) {
                    return $model->personeria_juridica == 1;
                }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idregistro' => 'Registro',
            'numero_legajo_reproam' => 'N° Legajo ReProAM',
            'nombre' => 'Nombre',
            'direccion' => 'Dirección',
            'idbarrio' => 'Barrio',
            'idlocalidad' => 'Localidad',
            'situacion_habitacional' => 'Situación Habitacional de la Organización/Grupo',
            'telefono' => 'Teléfono Fijo',
            'telefono_movil' => 'Celular',
            'idzona' => 'Zona',
            'mail' => 'Email',
            'inscripto' => 'Inscripto',
            'nombre_presidente' => 'Nombre Presidente',
            'nombre_vicepresidente' => 'Nombre Vicepresidente',
            'nombre_secretario' => 'Nombre Secretario',
            'personeria_juridica' => 'Personería Jurídica',
            'personeria_juridica_resolucion' => 'Personería Jurídica Resolución',
            'personeria_juridica_fecha_vencimiento' => 'Personería Jurídica Fecha Vencimiento',
            'entrega_constancia_inscripcion' => 'Constancia Inscripcion Entregada',
            'entrega_constancia_inscripcion_nombre' => 'Responsable Entrega Constancia',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Activo',
        ];
    }

    /**
     * Gets query for [[MdsReproamMandatos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMdsReproamMandatos()
    {
        return $this->hasMany(Mds_reproam_mandato::class, ['idregistro' => 'idregistro']);
    }

    /**
     * Gets query for [[Idbarrio]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarrio()
    {
        return $this->hasOne(Sds_com_barrio::class, ['idbarrio' => 'idbarrio']);
    }

    /**
     * Gets query for [[Idlocalidad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalidad()
    {
        return $this->hasOne(Sds_com_localidad::class, ['idlocalidad' => 'idlocalidad']);
    }

    /**
     * Gets query for [[Idzona]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getZona()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idzona']);
    }

    /**
     * Gets query for [[situacion_habitacional]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSituacionHabitacional()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'situacion_habitacional']);
    }

    public function getAdjuntos()
    {
        $adjuntos =  Mds_legales_archivo::find()
            ->where(['objeto' => 'mds_reproam_registro', 'tipo' => 'registro', 'activo' => true])
            ->andWhere(['=', 'id_objeto', $this->idregistro])->all();
        foreach ($adjuntos as $adjunto) {
            $adjunto->path = self::PATH . $adjunto->path;
        }
        return $adjuntos;
    }
}
