<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_org_organismo;

/**
 * Mds_org_organismoSearch represents the model behind the search form about `app\models\Mds_org_organismo`.
 */
class Mds_org_organismoSearch extends Mds_org_organismo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idorganismo', 'padre', 'nivel'], 'integer'],
            [['descripcion','abreviatura', 'activo'], 'safe'],
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
        $query = Mds_org_organismo::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idorganismo' => $this->idorganismo,
            'padre' => $this->padre,
            'nivel' => $this->nivel,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
