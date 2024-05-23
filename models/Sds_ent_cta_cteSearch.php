<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ent_cta_cte;

/**
 * Sds_ent_cta_cteSearch represents the model behind the search form about `app\models\Sds_ent_cta_cte`.
 */
class Sds_ent_cta_cteSearch extends Sds_ent_cta_cte
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['codigo', 'fecha_hora'], 'safe'],
            [['identrega', 'debe', 'haber', 'responsable', 'idtipo','saldo_acumulado'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Sds_ent_cta_cte::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->addSelect(["ctacte.*","ifnull((select sum(ifnull(debe,0)-ifnull(haber,0))
        from view_sds_ent_cta_cte acum
        where acum.idtipo=ctacte.idtipo and acum.responsable=ctacte.responsable
        and acum.fecha_hora<ctacte.fecha_hora),0)+debe-haber saldo_acumulado"]);
        $query->from(["view_sds_ent_cta_cte as ctacte"]);
        $query->andFilterWhere([
            'identrega' => $this->identrega,
            'fecha_hora' => $this->fecha_hora,
            'debe' => $this->debe,
            'haber' => $this->haber,
            'responsable' => $this->responsable,
            'idtipo' => $this->idtipo,
        ]);

        $query->andFilterWhere(['like', 'codigo', $this->codigo])
        ->orderBy(["fecha_hora"=>SORT_ASC]);

        return $dataProvider;
    }
}
