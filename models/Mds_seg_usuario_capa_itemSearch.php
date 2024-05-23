<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_seg_usuario_capa_item;

/**
 * Mds_seg_usuario_capa_itemSearch represents the model behind the search form about `app\models\Mds_seg_usuario_capa_item`.
 */
class Mds_seg_usuario_capa_itemSearch extends Mds_seg_usuario_capa_item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idusuariocapaitem', 'idusuario', 'idcapaitem'], 'integer'],
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
        $query = Mds_seg_usuario_capa_item::find();

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
            'idusuariocapaitem' => $this->idusuariocapaitem,
            'idusuario' => $this->idusuario,
            'idcapaitem' => $this->idcapaitem,
        ]);

        return $dataProvider;
    }
}
