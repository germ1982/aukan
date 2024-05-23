<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_reg_movimiento;

/**
 * Sds_reg_movimientoSearch represents the model behind the search form about `app\models\Sds_reg_movimiento`.
 */
class Sds_reg_movimientoSearch extends Sds_reg_movimiento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmovimiento', 'idregistro', 'idusuario', 'idtecnico', 'tipo'], 'integer'],
            [['fecha', 'descripcion'], 'safe'],
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
        $query = Sds_reg_movimiento::find();

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
            'idmovimiento' => $this->idmovimiento,
            'idregistro' => $this->idregistro,
            'fecha' => $this->fecha,
            'idusuario' => $this->idusuario,
            'idtecnico' => $this->idtecnico,
            'tipo' => $this->tipo,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
