<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_reproam_mandato;
use Yii;

/**
 * Mds_reproam_mandatoSearch represents the model behind the search form of `app\models\Mds_reproam_mandato`.
 */
class Mds_reproam_mandatoSearch extends Mds_reproam_mandato
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idmandato',], 'integer'],
            [['idmandato', 'idregistro', 'fecha_desde', 'fecha_hasta', 'deleted_at', 'idlocalidad', 'idzona'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $hasRolUsuario = Mds_legales_oficio::tieneRol(Mds_reproam_registro::ID_ROL_USUARIO);
        $usuarioAuth = Yii::$app->user->identity;
        $query = Mds_reproam_mandato::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['idmandato' => SORT_DESC],
                'attributes' => ['idmandato', 'idregistro', 'fecha_desde', 'fecha_hasta', 'deleted_at', 'idlocalidad', 'idzona'],
            ],
        ]);


        $this->load($params);
        if (isset($params['Mds_reproam_mandatoSearch']) && $params['Mds_reproam_mandatoSearch']['fecha_desde']) {
            $fecha_desde = $params['Mds_reproam_mandatoSearch']['fecha_desde'];
            $fecha_desde = armarDateParaMySql($fecha_desde);
            $fecha_desde = date_create($fecha_desde);
            $fecha_desde = date_format($fecha_desde, 'Y-m-d');
            $this->fecha_desde = $fecha_desde;
        }
        if (isset($params['Mds_reproam_mandatoSearch']) && $params['Mds_reproam_mandatoSearch']['fecha_hasta']) {
            $fecha_hasta = $params['Mds_reproam_mandatoSearch']['fecha_hasta'];
            $fecha_hasta = armarDateParaMySql($fecha_hasta);
            $fecha_hasta = date_create($fecha_hasta);
            $fecha_hasta = date_format($fecha_hasta, 'Y-m-d');
            $this->fecha_hasta = $fecha_hasta;
        }

        if ($this->deleted_at == null) {
            $query->andWhere(['mds_reproam_mandato.deleted_at' => null]);
        }

        $query->joinWith('registro');
        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['mds_reproam_mandato.deleted_at' => null]]);
        } else if ($this->deleted_at === '1') {
            $query->andWhere(['mds_reproam_mandato.deleted_at' => null]);
        }

        if ($this->idregistro) {
            $query->andWhere(
                ['in', 'mds_reproam_mandato.idregistro', $this->idregistro]
            );
        }

        if ($this->idlocalidad) {
            $query->andWhere(
                ['in', 'mds_reproam_registro.idlocalidad', $this->idlocalidad]
            );
        }

        if ($this->idzona) {
            $query->andWhere(
                ['in', 'mds_reproam_registro.idzona', $this->idzona]
            );
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idmandato' => $this->idmandato,
        ]);
        $query->andFilterWhere(['>=', 'fecha_desde', $this->fecha_desde]);
        $query->andFilterWhere(['<=', 'fecha_hasta', $this->fecha_hasta]);

        $this->fecha_desde ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_desde))) :  null;
        $this->fecha_hasta ? date('d/m/Y', strtotime(str_replace('-', '/', $this->fecha_hasta))) :  null;

        if (isset($params['Mds_reproam_mandatoSearch']) && $params['Mds_reproam_mandatoSearch']['fecha_desde']) {
            $this->fecha_desde = $params['Mds_reproam_mandatoSearch']['fecha_desde'];
        }
        if (isset($params['Mds_reproam_mandatoSearch']) && $params['Mds_reproam_mandatoSearch']['fecha_hasta']) {
            $this->fecha_hasta = $params['Mds_reproam_mandatoSearch']['fecha_hasta'];
        }
        return $dataProvider;
    }
}

function armarDateParaMySql($fecha)
{
    if ($fecha == null) {
        return null;
    }
    $anio = substr($fecha, 6, 4);
    $mes  = substr($fecha, 3, 2);
    $dia = substr($fecha, 0, 2);
    $DT = "$anio-$mes-$dia";
    return $DT;
}
