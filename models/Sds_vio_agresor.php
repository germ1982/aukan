<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sds_vio_agresor".
 *
 * @property int $idagresor
 * @property int|null $dni
 * @property string $nombre
 * @property string $apellido
 * @property int $genero
 * @property string|null $agresor_dato_denuncia
 * @property int|null $agresor_dav
 * @property string|null $agresor_dav_datos
 * @property int|null $activo
 *
 * @property int|null $escolaridad
 * @property int|null $funcionario
 * @property int|null $desc_actividad
 * @property int|null $desc_jubilacion
 * @property int|null $acceso_armas
 * @property int|null $antecedente_penales
 * @property int|null $antecedente_violencia
 * @property int|null $antecedente_restricciones
 * @property int|null $vinculo_ilicito
 * @property int|null $vinculo_personal_seguridad
 * @property int|null $consumo_problematico
 * 
 * @property Sds_com_configuracion $genero0
 * @property SdsVioIntervencionAgresor[] $sdsVioIntervencionAgresors
 * @property SdsVioIntervencion[] $idintervencions
 */
class Sds_vio_agresor extends \yii\db\ActiveRecord
{
    public $parentezco;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sds_vio_agresor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'apellido', 'activo'], 'required'],
            [[
                'idagresor', 'genero', 'agresor_dav', 'activo',
                'escolaridad', 'funcionario',
                'desc_actividad', 'desc_jubilacion',
                'acceso_armas', 'antecedente_penales',
                'antecedente_violencia', 'antecedente_restricciones',
                'vinculo_ilicito', 'vinculo_personal_seguridad',
                'consumo_problematico'
            ], 'integer'],
            [[
                'parentezco', 'genero', 'agresor_dav', 'agresor_dato_denuncia',
                'agresor_dav_datos',
            ], 'safe'],
            [['nombre', 'apellido'], 'string', 'max' => 100],
            [['dni'], 'string', 'max' => 255],
            ['dni', 'match', 'pattern' => '/^[a-zA-Z0-9-]\w*$/i', 'message' => 'Solo puede contener caracteres alfanuméricos y/o guiones medios'],
            [['dni'], 'unique', 'when' => function ($model, $attribute) {
                return $model->{$attribute} !== $model->getOldAttribute($attribute);
            }],
            [['idagresor'], 'unique'],
            [['genero'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['genero' => 'idconfiguracion']],
            [['escolaridad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['escolaridad' => 'idconfiguracion']],
            [['vinculo_personal_seguridad'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['vinculo_personal_seguridad' => 'idconfiguracion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idagresor' => 'Agresor',
            'dni' => 'DNI',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'genero' => 'Género',
            'agresor_dato_denuncia' => 'Agresor Dato Denuncia',
            'agresor_dav' => 'Agresor DAV',
            'agresor_dav_datos' => 'Agresor DAV Datos',
            'activo' => 'Activo',
            'parentezco' => 'Parentesco',

            'escolaridad' => 'Escolaridad alcanzada',
            'funcionario' => 'Es o fue funcionario/a público',
            'desc_actividad' => 'Realiza alguna actividad por la que le descuentan dinero',
            'desc_jubilacion' => 'Por esa actividad le descuentan para la jubilación',
            'acceso_armas' => 'Acceso a armas de fuego',
            'antecedente_penales' => 'Antecedentes penales',
            'antecedente_violencia' => 'Antecedentes de violencia con parejas o ex parejas',
            'antecedente_restricciones' => 'Antecendentes de violación de medidas de restrición',
            'vinculo_ilicito' => 'Vínculo con actividades ilícitas',
            'vinculo_personal_seguridad' => 'Vínculo con personal de seguridad',
            'consumo_problematico' => 'Consumo problemático',
        ];
    }


    /**
     * Obtener agresor by dni
     */

    public function getAgresorByDni($dni)
    {
        return $this::findOne(['dni' => $dni]);
    }

    /**
     * Gets query for [[Genero0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGenero0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'genero']);
    }

    /**
     * Gets query for [[Parentesco0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentesco0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'parentezco']);
    }

    /**
     * Gets query for [[SdsVioIntervencionAgresors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSdsVioIntervencionAgresors()
    {
        return $this->hasMany(Sds_vio_intervencion_agresor::class, ['idagresor' => 'idagresor']);
    }

    /**
     * Gets query for [[Idintervencions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdintervencions()
    {
        return $this->hasMany(Sds_vio_intervencion::class, ['idintervencion' => 'idintervencion'])->viaTable('sds_vio_intervencion_agresor', ['idagresor' => 'idagresor']);
    }

    /**
     * Gets query for [[VinculoPersonalSeg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVinculoPersonalSeg()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'vinculo_personal_seguridad']);
    }

    /**
     * Gets query for [[escolaridad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEscolaridadAlcanzada()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'escolaridad']);
    }

    public function getConsumos()
    {
        $modelConsumo = Sds_vio_agresor_consumo::find()->where(['idagresor' => $this->idagresor, 'deleted_at' => null])->all();
        return $modelConsumo;
    }
}
