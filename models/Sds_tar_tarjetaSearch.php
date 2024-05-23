<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_tar_tarjeta;

/**
 * Sds_tar_tarjetaSearch represents the model behind the search form about `app\models\Sds_tar_tarjeta`.
 */
class Sds_tar_tarjetaSearch extends Sds_tar_tarjeta
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idtarjeta', 'dni', 'referente', 'empresa', 'idusuario'], 'integer'],
            [['numero', 'observaciones', 'fecha','estado'], 'safe'],
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
        $query = Sds_tar_tarjeta::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->addSelect(["tarjeta.*","if(dni is null,0,1) estado"]);
        $query->from("sds_tar_tarjeta tarjeta");
        $query->andFilterWhere([
            'idtarjeta' => $this->idtarjeta,
            'dni' => $this->dni,
            'referente' => $this->referente,
            'empresa' => $this->empresa,
            'idusuario' => $this->idusuario,
            'fecha' => $this->fecha,
        ]);

        if ($this->estado==''){
            $this->estado = -1;
        }
        $query->andFilterWhere(['like', 'numero', $this->numero])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->having("(estado = ".$this->estado." or -1 = ".$this->estado.")");

        return $dataProvider;
    }
}
