<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organismo_decreto".
 *
 * @property int $iddecreto
 * @property string $descripcion
 * @property string $periodo_inicio
 * @property string|null $periodo_final
 * @property string|null $periodo_prorroga
 * @property int|null $activo
 *
 * @property OrganismoOrgDec[] $organismoOrgDecs
 * @property Organismo[] $idorganismos
 */
class OrganismoDecreto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organismo_decreto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion', 'periodo_inicio'], 'required'],
            [['periodo_inicio', 'periodo_final', 'periodo_prorroga'], 'safe'],
            [['activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iddecreto' => 'Iddecreto',
            'descripcion' => 'Descripcion',
            'periodo_inicio' => 'Periodo Inicio',
            'periodo_final' => 'Periodo Final',
            'periodo_prorroga' => 'Periodo Prorroga',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[OrganismoOrgDecs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganismoOrgDecs()
    {
        return $this->hasMany(OrganismoOrgDec::className(), ['iddecreto' => 'iddecreto']);
    }

    /**
     * Gets query for [[Idorganismos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdorganismos()
{
    return $this->hasMany(Organismo::class, ['idorganismo' => 'idorganismo'])
        ->viaTable('organismo_org_dec', ['iddecreto' => 'iddecreto']);
}
    public function getIddecretos()
{
    // Reemplazá 'id_organismo' por el nombre exacto de la columna FK en la tabla organismo_decreto
    return $this->hasMany(OrganismoDecreto::class, ['id_organismo' => 'idorganismo']);
}
}
