<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_seg_rol;

/**
 * Mds_seg_rolSearch represents the model behind the search form about `app\models\Mds_seg_rol`.
 */
class Mds_seg_rolSearch extends Mds_seg_rol
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idrol'], 'integer'],
            [['descripcion','deleted_at'], 'safe'],
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
        $query = Mds_seg_rol::find();

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
            'idrol' => $this->idrol,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        if ($this->deleted_at === '0') {
            $query->andWhere(['not', ['deleted_at' => null]]);
        } else if ($this->deleted_at === '1') {
            $query->andWhere(['deleted_at' => null]);
        }

        return $dataProvider;
    }
}