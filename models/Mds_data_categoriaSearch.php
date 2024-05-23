<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_data_categoria;

/**
 * Mds_data_categoriaSearch represents the model behind the search form about `app\models\Mds_data_categoria`.
 */
class Mds_data_categoriaSearch extends Mds_data_categoria
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcategoria'], 'integer'],
            [['nombre', 'descripcion', 'icono', 'imagen_fondo'], 'safe'],
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
        $query = Mds_data_categoria::find();

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
            'idcategoria' => $this->idcategoria,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'icono', $this->icono])
            ->andFilterWhere(['like', 'imagen_fondo', $this->imagen_fondo]);

        return $dataProvider;
    }
}
