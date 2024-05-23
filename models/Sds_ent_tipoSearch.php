<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ent_tipo;

/**
 * Sds_ent_tipoSearch represents the model behind the search form about `app\models\Sds_ent_tipo`.
 */
class Sds_ent_tipoSearch extends Sds_ent_tipo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idtipo'], 'integer'],
            [['descripcion', 'activo', 'tiene_numero'], 'safe'],
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
        $query = Sds_ent_tipo::find();

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
            'idtipo' => $this->idtipo,
            'activo' => $this->activo,
            'tiene_numero' => $this->tiene_numero
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
