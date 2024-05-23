<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_gis_capa_item;

/**
 * Sds_gis_capa_itemSearch represents the model behind the search form about `app\models\Sds_gis_capa_item`.
 */
class Sds_gis_capa_itemSearch extends Sds_gis_capa_item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcapaitem', 'idcapa', 'estado'], 'integer'],
            [['descripcion', 'detalle', 'activo', 'direccion'], 'safe'],
            [['latitud', 'longitud'], 'number'],
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
        $query = Sds_gis_capa_item::find();

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
            'idcapaitem' => $this->idcapaitem,
            'idcapa' => $this->idcapa,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andFilterWhere(['like', 'activo', $this->activo])
            ->andFilterWhere(['like', 'direccion', $this->direccion]);

        return $dataProvider;
    }
}
