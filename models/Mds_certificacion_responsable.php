<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mds_certificacion_responsable".
 *
 * @property int $idresponsable
 * @property int $idcertificacion
 * @property string|null $nombre_apellido
 * @property int $dni
 * @property int|null $curador_legal
 * @property int|null $tipo_responsable
 * @property int|null $rendicion
 * @property string $cbu_alias
 * @property int|null $idparentesco
 * @property string|null $parentesco_otro
 * @property int|null $idpersona
 * @property string|null $motivo_cambio
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $idusuario_modifica Usuario que modifica
 * @property int|null $idusuario_carga Usuario que carga
 *
 * @property SdsComConfiguracion $idparentesco0
 * @property SdsComPersona $idpersona0
 * @property MdsCertificacion $idcertificacion0
 */
class Mds_certificacion_responsable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mds_certificacion_responsable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificacion', 'dni', 'nombre_apellido'], 'required'],
            [['idcertificacion', 'dni', 'idparentesco', 'idpersona', 'idusuario_modifica', 'idusuario_carga','curador_legal','rendicion'], 'integer'],
            [['motivo_cambio'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nombre_apellido', 'cbu_alias', 'parentesco_otro'], 'string', 'max' => 150],
            [['idparentesco'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['idparentesco' => 'idconfiguracion']],
            [['tipo_responsable'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_configuracion::class, 'targetAttribute' => ['tipo_responsable' => 'idconfiguracion']],
            [['idpersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sds_com_persona::class, 'targetAttribute' => ['idpersona' => 'idpersona']],
            [['idcertificacion'], 'exist', 'skipOnError' => true, 'targetClass' => Mds_certificacion::class, 'targetAttribute' => ['idcertificacion' => 'idcertificacion']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idresponsable' => 'Idresponsable',
            'idcertificacion' => 'Certificación',
            'nombre_apellido' => 'Nombre y Apellido',
            'dni' => 'Dni',
            'cbu_alias' => 'CBU/Alias',
            'idparentesco' => 'Parentesco',
            'parentesco_otro' => 'Parentesco otro',
            'idpersona' => 'Idpersona',
            'motivo_cambio' => 'Motivo de cambio',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'idusuario_modifica' => 'Idusuario modifica',
            'idusuario_carga' => 'Idusuario carga',
            'curador_legal' => '¿Es Curador/Tutor Legal?',
            'rendicion' => '¿Debe presentar la rendición?',
            'tipo_responsable' => 'Tipo de responsable de cobro/Tutor especial',
        ];
    }

    /**
     * Gets query for [[Idparentesco0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParentesco0()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'idparentesco']);
    }

    /**
     * Gets query for [[TipoResponsable]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoResponsable()
    {
        return $this->hasOne(Sds_com_configuracion::class, ['idconfiguracion' => 'tipo_responsable']);
    }

    /**
     * Gets query for [[Idpersona0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdpersona0()
    {
        return $this->hasOne(Sds_com_persona::class, ['idpersona' => 'idpersona']);
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

    public function getResponsablesHistorial($listado, $idcertificacion)
    {
        $connection = Yii::$app->getDb();
        $usuarioAuth = Yii::$app->user->identity;
        $responsables = $connection->createCommand("SELECT responsable.nombre_apellido,
            responsable.dni,responsable.idcertificacion,
            responsable.idresponsable,
            DATE_FORMAT(responsable.created_at, '%d/%m/%Y %H:%ih') as created_at,
            DATE_FORMAT(responsable.deleted_at, '%d/%m/%Y %H:%ih') as deleted_at,
            responsable.motivo_cambio,
            responsable.idparentesco,
            SUBSTRING_INDEX(configuracion_parentesco.descripcion, '.', -1) as parentesco,
            responsable.parentesco_otro,
            responsable.curador_legal,
            responsable.rendicion,
            configuracion_tipoResponsable.descripcion as tipoResponsable
            FROM mds_certificacion_responsable responsable
            INNER JOIN mds_certificacion certificacion ON responsable.idcertificacion = certificacion.idcertificacion 
            LEFT JOIN sds_com_configuracion configuracion_parentesco ON responsable.idparentesco = configuracion_parentesco.idconfiguracion
            LEFT JOIN sds_com_configuracion configuracion_tipoResponsable ON responsable.tipo_responsable = configuracion_tipoResponsable.idconfiguracion
            WHERE responsable.idcertificacion = '$idcertificacion'
            ORDER BY responsable.created_at DESC
            ")
            ->queryAll();
        $listado['responsables'] = $responsables;
        return $listado;
    }
}
