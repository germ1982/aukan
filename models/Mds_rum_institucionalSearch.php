<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_rum_institucional;

/**
 * Mds_rum_institucionalSearch represents the model behind the search form about `app\models\Mds_rum_institucional`.
 */
class Mds_rum_institucionalSearch extends Mds_rum_institucional
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'autor', 'comment_count', 'activo', 'publicado'], 'integer'],
            [['comment_status', 'contenido', 'titulo', 'fechamodificacion', 'horamodificacion', 'fechaalta', 'horaalta', 'fecha_publicacion', 'imagen'], 'safe'],
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
        $query = Mds_rum_institucional::find();

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
            'id' => $this->id,
            'autor' => $this->autor,
            'comment_count' => $this->comment_count,
            'activo' => $this->activo,
            'fechamodificacion' => $this->fechamodificacion,
            'horamodificacion' => $this->horamodificacion,
            'fechaalta' => $this->fechaalta,
            'horaalta' => $this->horaalta,
            'fecha_publicacion' => $this->fecha_publicacion,
            'publicado' => $this->publicado,
        ]);

        $query->andFilterWhere(['like', 'comment_status', $this->comment_status])
            ->andFilterWhere(['like', 'contenido', $this->contenido])
            ->andFilterWhere(['like', 'titulo', $this->titulo])
            ->andFilterWhere(['like', 'imagen', $this->imagen]);

        return $dataProvider;
    }
}
