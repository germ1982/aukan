<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_vio_agresor;

/**
 * Sds_vio_agresorSearch represents the model behind the search form about `app\models\Sds_vio_agresor`.
 */
class Sds_vio_agresorSearch extends Sds_vio_agresor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idagresor', 'agresor_dav'], 'integer'],
            [['idagresor', 'nombre', 'apellido', 'agresor_dato_denuncia', 'agresor_dav_datos', 'agresor_consumo', 'agresor_problematico',  'dni', 'genero', 'activo'], 'safe'],
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
        $query = Sds_vio_agresor::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->activo == '0') {
            $query->andWhere(['not', ['sds_vio_agresor.activo' => '1']]);
        } else {
            $query->andWhere(['sds_vio_agresor.activo' => '1']);
        }

        $query->andFilterWhere([
            'idagresor' => $this->idagresor,
            'genero' => $this->genero,
            'agresor_dav' => $this->agresor_dav,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'dni', $this->dni])
            ->andFilterWhere(['like', 'agresor_dato_denuncia', $this->agresor_dato_denuncia])
            ->andFilterWhere(['like', 'agresor_dav_datos', $this->agresor_dav_datos])
            ->andFilterWhere(['like', 'agresor_consumo', $this->agresor_consumo])
            ->andFilterWhere(['like', 'agresor_problematico', $this->agresor_problematico]);

        return $dataProvider;
    }
}
