<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Articulo;

/**
 * ArticuloSearch represents the model behind the search form about `app\models\Articulo`.
 */
class ArticuloSearch extends Articulo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idarticulo', 'idtipo', 'idmarca', 'idrubro', 'id_unidad_medida'], 'integer'],
            [['descripcion', 'modelo', 'activo', 'imagen'], 'safe'],
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
        $query = Articulo::find();

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
            'idarticulo' => $this->idarticulo,
            'idtipo' => $this->idtipo,
            'idmarca' => $this->idmarca,
            'idrubro' => $this->idrubro,
            'id_unidad_medida' => $this->id_unidad_medida,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'activo', $this->activo])
            ->andFilterWhere(['like', 'imagen', $this->imagen]);

        return $dataProvider;
    }
}
