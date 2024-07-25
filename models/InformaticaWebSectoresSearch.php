<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InformaticaWebSectores;

/**
 * InformaticaWebSectoresSearch represents the model behind the search form about `app\models\InformaticaWebSectores`.
 */
class InformaticaWebSectoresSearch extends InformaticaWebSectores
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idsector', 'orden'], 'integer'],
            [['nombre', 'descripcion', 'fotos', 'activo'], 'safe'],
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
        $query = InformaticaWebSectores::find();

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
            'idsector' => $this->idsector,
            'orden' => $this->orden,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'fotos', $this->fotos])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
