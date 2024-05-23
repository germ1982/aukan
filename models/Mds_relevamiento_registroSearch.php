<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_relevamiento_registro;

/**
 * Mds_relevamiento_registroSearch represents the model behind the search form of `app\models\Mds_relevamiento_registro`.
 */
class Mds_relevamiento_registroSearch extends Mds_relevamiento_registro
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idrelevamientoregistro', 'idcapaitem', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['observaciones', 'created_at', 'updated_at', 'deleted_at', 'fecha'], 'safe'],
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
        $query = Mds_relevamiento_registro::find()->orderBy(['idrelevamientoregistro' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (isset($params['Mds_relevamiento_registroSearch']) && $params['Mds_relevamiento_registroSearch']['fecha']) {
            $fecha = $params['Mds_relevamiento_registroSearch']['fecha'];
            $fecha = armarDateParaMySql($fecha);
            $fecha = date_create($fecha);
            $fecha = date_format($fecha, 'Y-m-d H:i:s');
            $query->andFilterWhere([
                'fecha' => $fecha
            ]);
        }
        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_relevamiento_registro::ID_ROL_RELEVAMIENTO_ADMINISTRADOR_GENERAL);
        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['mds_relevamiento_registro.deleted_at' => null]]);
        } else if ($this->deleted_at === '1' || !$hasRolAdminGeneral) {
            $query->andWhere(['mds_relevamiento_registro.deleted_at' => null]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idrelevamientoregistro' => $this->idrelevamientoregistro,
            'idcapaitem' => $this->idcapaitem,
            'idusuario_carga' => $this->idusuario_carga,
            'idusuario_borra' => $this->idusuario_borra,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones]);

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
