<?php

namespace app\models;

use Exception;
use Yii;

/**
 * This is the model class for table "sds_ris_risneu".
 *
 * @property int $idrisneu
 * @property string $fecha_carga
 * @property string $fecha
 * @property string|null $calle_numero
 * @property string|null $casa
 * @property string|null $torre
 * @property string|null $piso
 * @property string|null $depto
 * @property string|null $manzana
 * @property string|null $parcela
 * @property string|null $lote
 * @property string|null $pilar
 * @property string|null $observaciones
 * @property string|null $mail
 * @property string|null $telefono
 * @property string|null $vivienda_tiempo_residencia
 * @property int $calle
 * @property int|null $calle_interseccion
 * @property int $idbarrio
 * @property int $idusuario
 * @property int $area
 * @property int $encuestador
 * @property int $realizado_por
 * @property int $vivienda_uso
 * @property int $vivienda_ubicacion
 * @property int $vivienda_propiedad
 * @property int $vivienda_habitaciones
 * @property int $vivienda_tipo
 * @property int $vivienda_piso
 * @property int $vivienda_agua_obtiene
 * @property int $vivienda_agua
 * @property int $vivienda_bano
 * @property int $vivienda_desague
 * @property int $vivienda_iluminacion
 * @property int $vivienda_medidor
 * @property int $vivienda_combustible_calefaccion
 * @property int $vivienda_combustible_cocina
 * @property int $vivienda_techo
 * @property int $vivienda_paredes
 * @property int $activo
 *
 * @property SdsRisPersona[] $sdsRisPersonas
 * @property SdsComConfiguracion $area0
 * @property SdsComBarrio $idbarrio0
 * @property SdsComCalle $calle0
 * @property SdsComCalle $calleInterseccion
 * @property SdsComConfiguracion $encuestador0
 * @property SdsComConfiguracion $realizadoPor
 * @property MdsSegUsuario $idusuario0
 * @property SdsComConfiguracion $vivendaAgua
 * @property SdsComConfiguracion $viviendaAguaObtiene
 * @property SdsComConfiguracion $viviendaBano
 * @property SdsComConfiguracion $vivendaCombustibleCalefaccion
 * @property SdsComConfiguracion $viviendaCombustibleCocina
 * @property SdsComConfiguracion $viviendaDesague
 * @property SdsComConfiguracion $viviendaIluminacion
 * @property SdsComConfiguracion $viviendaMedidor
 * @property SdsComConfiguracion $viviendaParedes
 * @property SdsComConfiguracion $viviendaPiso
 * @property SdsComConfiguracion $viviendaPropiedad
 * @property SdsComConfiguracion $viviendaTecho
 * @property SdsComConfiguracion $viviendaTipo
 * @property SdsComConfiguracion $viviendaUbicacion
 * @property SdsComConfiguracion $viviendaUso
 * @property SdsRisRisneuAlimentacion[] $sdsRisRisneuAlimentacions
 */
class Sds_ris_risneu extends \yii\db\ActiveRecord
{
    public $dni_beneficiario; //DNI para buscar risneu vinculado a beneficiario
    public $idlocalidad; //idlocalidad para filtrar barrios
    public $idprovincia; //idprovincia para filtrar localidades
    public $cod_postal; //Solo informativo al buscar localidad
    public $benef_nombre;
    public $benef_dni;
    public $fdesde;
    public $fhasta;
    public $estado;
    public $idencuestador;
    public $beneficiarios;
    const ID_PROVINCIA_NEUQUEN = 58;
    const ID_LOCALIDAD_NEUQUEN_CAPITAL = 58035070;
    const CODIGO_POSTAL_NEUQUEN_CAPITAL = 8300;
    const ID_ITEM_SEGURIDAD = 10;

    const ID_ROL_RISNEU_ADMINISTRADOR_GENERAL = 55;
    const ID_ROL_DASHBOARD = 192;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_ris_risneu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'fecha', 'calle', 'idbarrio', 'idusuario', 'area', 'encuestador', 'realizado_por', 'vivienda_uso',
                'vivienda_ubicacion', 'vivienda_propiedad', 'vivienda_habitaciones', 'vivienda_tipo', 'vivienda_piso',
                'vivienda_agua_obtiene', 'vivienda_agua', 'vivienda_bano', 'vivienda_desague', 'vivienda_iluminacion',
                'vivienda_medidor', 'vivienda_combustible_calefaccion', 'vivienda_combustible_cocina', 'vivienda_techo',
                'vivienda_paredes', 'oficial', 'dni_beneficiario'
            ], 'required'],
            [['fecha_carga', 'fecha', 'dni_beneficiario', 'idlocalidad', 'idprovincia', 'cod_postal', 'estado', 'updated_at', 'deleted_at', 'beneficiarios'], 'safe'],
            [['observaciones'], 'string'],
            [[
                'calle', 'calle_interseccion', 'idbarrio', 'idusuario', 'area', 'encuestador', 'realizado_por',
                'vivienda_uso', 'vivienda_ubicacion', 'vivienda_propiedad',
                'vivienda_habitaciones', 'vivienda_tipo', 'vivienda_piso', 'vivienda_agua_obtiene', 'vivienda_agua',
                'vivienda_bano', 'vivienda_desague', 'vivienda_iluminacion', 'vivienda_medidor', 'vivienda_combustible_calefaccion',
                'vivienda_combustible_cocina', 'vivienda_techo', 'vivienda_paredes', 'estado', 'activo', 'idusuario_actualiza', 'idusuario_borra',
                'en_sede'
            ], 'integer'],
            [['dni'], 'string', 'max' => 32],
            [['telefono'], 'string', 'max' => 50],
            [['mail', 'vivienda_tiempo_residencia'], 'string', 'max' => 255],
            [['calle_numero', 'casa', 'torre', 'piso', 'depto', 'manzana', 'parcela', 'lote', 'pilar'], 'string', 'max' => 45],
            [['area'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['area' => 'idconfiguracion']],
            [['idbarrio'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_barrio::class, 'targetAttribute' => ['idbarrio' => 'idbarrio']],
            [['calle'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_calle::class, 'targetAttribute' => ['calle' => 'idcalle']],
            [['calle_interseccion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_calle::class, 'targetAttribute' => ['calle_interseccion' => 'idcalle']],
            [['encuestador'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['encuestador' => 'idconfiguracion']],
            [['realizado_por'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['realizado_por' => 'idconfiguracion']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_seg_usuario::class, 'targetAttribute' => ['idusuario' => 'idusuario']],
            [['vivienda_agua'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_agua' => 'idconfiguracion']],
            [['vivienda_agua_obtiene'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_agua_obtiene' => 'idconfiguracion']],
            [['vivienda_bano'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_bano' => 'idconfiguracion']],
            [['vivienda_combustible_calefaccion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_combustible_calefaccion' => 'idconfiguracion']],
            [['vivienda_combustible_cocina'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_combustible_cocina' => 'idconfiguracion']],
            [['vivienda_desague'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_desague' => 'idconfiguracion']],
            [['vivienda_iluminacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_iluminacion' => 'idconfiguracion']],
            [['vivienda_medidor'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_medidor' => 'idconfiguracion']],
            [['vivienda_paredes'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_paredes' => 'idconfiguracion']],
            [['vivienda_piso'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_piso' => 'idconfiguracion']],
            [['vivienda_propiedad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_propiedad' => 'idconfiguracion']],
            [['vivienda_techo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_techo' => 'idconfiguracion']],
            [['vivienda_tipo'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_tipo' => 'idconfiguracion']],
            [['vivienda_ubicacion'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_ubicacion' => 'idconfiguracion']],
            [['vivienda_uso'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vivienda_uso' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dni_beneficiario' => 'DNI Responsable',
            'idrisneu' => 'Número',
            'fecha_carga' => 'Fecha Carga',
            'fecha' => 'Fecha',
            'calle_numero' => 'Número',
            'casa' => 'Casa',
            'torre' => 'Torre',
            'piso' => 'Piso',
            'depto' => 'Depto',
            'manzana' => 'Manzana',
            'parcela' => 'Parcela',
            'lote' => 'Lote',
            'pilar' => 'Pilar',
            'observaciones' => 'Observaciones',
            'calle' => 'Calle',
            'calle_interseccion' => 'Calle Intersección',
            'idbarrio' => 'Barrio',
            'idusuario' => 'Idusuario',
            'area' => 'Área',
            'encuestador' => 'Encuestador',
            'realizado_por' => 'Realizado Por',
            'vivienda_uso' => 'La vivienda es',
            'vivienda_ubicacion' => 'Esta ubicada en',
            'vivienda_propiedad' => 'Propiedad',
            'vivienda_habitaciones' => 'Cantidad de Habitaciones',
            'vivienda_tipo' => 'Tipo de Vivienda',
            'vivienda_piso' => 'Piso',
            'vivienda_agua_obtiene' => 'Obtiene el agua',
            'vivienda_agua' => 'Tiene Agua',
            'vivienda_bano' => 'Baño',
            'vivienda_desague' => 'Desagüe',
            'vivienda_iluminacion' => 'Iluminación',
            'vivienda_medidor' => 'Medidor',
            'vivienda_combustible_calefaccion' => 'Calefacción',
            'vivienda_combustible_cocina' => 'Cocina',
            'vivienda_techo' => 'Techo',
            'vivienda_paredes' => 'Paredes',
            'vivienda_tiempo_residencia' => 'Tiempo de residencia en Neuquén',
            'activo' => 'Activo',
            'idencuestador' => 'Encuestador',
            'mail' => 'Mail',
            'telefono' => 'Teléfono',
            'en_sede' => 'Realizado en sede',
        ];
    }

    /**
     * Gets query for [[SdsRisPersonas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRisPersonas()
    {
        return $this->hasMany(Sds_ris_persona::class, ['idrisneu' => 'idrisneu']);
    }

    /**
     * Gets query for [[Area0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'area']);
    }

    /**
     * Gets query for [[Idbarrio0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdbarrio0()
    {
        return $this->hasOne(Sds_com_barrio::class, ['idbarrio' => 'idbarrio']);
    }

    /**
     * Gets query for [[Calle0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalle0()
    {
        return $this->hasOne(Sds_com_calle::class, ['idcalle' => 'calle']);
    }

    /**
     * Gets query for [[CalleInterseccion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalleInterseccion()
    {
        return $this->hasOne(Sds_com_calle::class, ['idcalle' => 'calle_interseccion']);
    }

    /**
     * Gets query for [[Encuestador0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEncuestador0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'encuestador']);
    }

    /**
     * Gets query for [[RealizadoPor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRealizadoPor()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'realizado_por']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Mds_seg_usuario::class, ['idusuario' => 'idusuario']);
    }

    /**
     * Gets query for [[VivendaAgua]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVivendaAgua()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_agua']);
    }

    /**
     * Gets query for [[ViviendaAguaObtiene]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaAguaObtiene()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_agua_obtiene']);
    }

    /**
     * Gets query for [[ViviendaBano]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaBano()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_bano']);
    }

    /**
     * Gets query for [[VivendaCombustibleCalefaccion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVivendaCombustibleCalefaccion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_combustible_calefaccion']);
    }

    /**
     * Gets query for [[ViviendaCombustibleCocina]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaCombustibleCocina()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_combustible_cocina']);
    }

    /**
     * Gets query for [[ViviendaDesague]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaDesague()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_desague']);
    }

    /**
     * Gets query for [[ViviendaIluminacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaIluminacion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_iluminacion']);
    }

    /**
     * Gets query for [[ViviendaMedidor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaMedidor()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_medidor']);
    }

    /**
     * Gets query for [[ViviendaParedes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaParedes()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_paredes']);
    }

    /**
     * Gets query for [[ViviendaPiso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaPiso()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_piso']);
    }

    /**
     * Gets query for [[ViviendaPropiedad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaPropiedad()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_propiedad']);
    }

    /**
     * Gets query for [[ViviendaTecho]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaTecho()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_techo']);
    }

    /**
     * Gets query for [[ViviendaTipo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaTipo()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_tipo']);
    }

    /**
     * Gets query for [[ViviendaUbicacion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaUbicacion()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_ubicacion']);
    }

    /**
     * Gets query for [[ViviendaUso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getViviendaUso()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vivienda_uso']);
    }

    /**
     * Gets query for [[SdsRisRisneuAlimentacions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsRisRisneuAlimentacions()
    {
        return $this->hasMany(Sds_ris_risneu_alimentacion::class, ['idrisneu' => 'idrisneu']);
    }

    public static function getIdRisneuByPersonaDni($dni)
    {

        $idRisneu = null;
        $model = Sds_ris_persona::findBySql("SELECT risper.*
                                                FROM sds_ris_persona risper
                                                JOIN sds_com_persona persona ON persona.idpersona=risper.idpersona
                                                JOIN sds_ris_risneu risneu ON risneu.idrisneu=risper.idrisneu
                                                WHERE documento=$dni and risper.activo = 1
                                                ORDER BY risneu.updated_at DESC, risneu.idrisneu DESC")->one();
        if ($model) {
            $idRisneu = $model->idrisneu;
        }
        return $idRisneu;
    }
    /**
     * @string dni
     * Se duplica funcion para contemplar nuevo circuito y formato de risneu. Además, se contempla el atributo 'dni' de risneu.
     */
    public static function getLastIdRisneuByDni($dni)
    {
        $idRisneu = null;
        $risPersona = Sds_ris_persona::findBySql("SELECT ris_per.*
                                                FROM sds_ris_persona ris_per
                                                INNER JOIN sds_com_persona com_persona ON com_persona.idpersona=ris_per.idpersona
                                                INNER JOIN sds_ris_risneu risneu ON ris_per.idrisneu=risneu.idrisneu
                                                WHERE com_persona.documento=$dni
                                                AND ris_per.activo = 1
                                                AND risneu.activo = 1
                                                ORDER BY ris_per.idrisneu DESC
                                                ")->one();
        if ($risPersona) {
            $idRisneu = $risPersona->idrisneu;
        } else {

            $risRisneu =            Sds_ris_risneu::find()
                ->where(['dni' => $dni])
                ->andWhere(['activo' => 1])
                ->orderBy(['idrisneu' => SORT_DESC])
                ->limit(1)
                ->one();

            if ($risRisneu) {
                $idRisneu = $risRisneu->idrisneu;
            }
        }
        return $idRisneu;
    }

    public static function getEncuestadores()
    {
        return Sds_com_configuracion::find()
            ->where(['idconfiguraciontipo' => Sds_com_configuracion_tipo::TIPO_ENCUESTADOR])
            ->andWhere(['activo' => 1])
            ->orderBy(['descripcion' => SORT_ASC])
            ->all();
    }

    public function getFamiliares()
    {
        return Sds_ris_persona::find()->where(['idrisneu' => $this->idrisneu, 'activo' => 1])->orderBy(['parentezco' => SORT_ASC])->all();
    }

    public function getEncuestadoresCargadosEnRisneu($oficialSql) {
        $configuracionTipoEncuestador = Sds_com_configuracion_tipo::TIPO_ENCUESTADOR;
        return Sds_com_localidad::findBySql(
            "SELECT encuestador.idconfiguracion as idencuestador, 
                    encuestador.descripcion as descripcion 
            FROM sds_ris_risneu risneu 
            INNER JOIN sds_com_configuracion encuestador
            ON risneu.encuestador = encuestador.idconfiguracion
            WHERE risneu.activo = 1 AND encuestador.idconfiguraciontipo = $configuracionTipoEncuestador AND encuestador.activo = 1 $oficialSql
            GROUP BY encuestador
            ORDER BY descripcion ASC
            "
        )->asArray()->all();
    }
}
