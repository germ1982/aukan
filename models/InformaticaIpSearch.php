<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InformaticaIp;

/**
 * InformaticaIpSearch represents the model behind the search form about `app\models\InformaticaIp`.
 */
class InformaticaIpSearch extends InformaticaIp
{
    // Atributos para los checkboxes de filtro
    public $usada_check;
    public $nousada_check;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idip', 'iddispositivo_red', 'mascara', 'puerta_enlace', 'dns'], 'integer'],
            [['ip', 'mac', 'observacion', 'usada_check', 'nousada_check'], 'safe'],
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
        $query = InformaticaIp::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idip' => $this->idip,
            'iddispositivo_red' => $this->iddispositivo_red,
            'mascara' => $this->mascara,
            'puerta_enlace' => $this->puerta_enlace,
            'dns' => $this->dns,
        ]);

        // Lógica para filtrar según la combinación de checkboxes tildados
        if ($this->usada_check && !$this->nousada_check) {
            // Solo usadas (usada = 1)
            $query->andWhere(['usada' => 1]);
        } elseif (!$this->usada_check && $this->nousada_check) {
            // Solo no usadas (usada = 0 o NULL)
            $query->andWhere(['or', ['usada' => 0], ['usada' => null]]);
        }
        // Nota: Si ambos están tildados (1 y 1) o ninguno (0 y 0), no se aplica condición y muestra todo.

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'mac', $this->mac])
            ->andFilterWhere(['like', 'observacion', $this->observacion]);

        return $dataProvider;
    }
}