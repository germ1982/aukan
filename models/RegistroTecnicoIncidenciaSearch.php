<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RegistroTecnicoIncidencia;

/**
 * RegistroTecnicoIncidenciaSearch represents the model behind the search form about `app\models\RegistroTecnicoIncidencia`.
 */
class RegistroTecnicoIncidenciaSearch extends RegistroTecnicoIncidencia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idincidencia', 'idregistro', 'idinventario', 'idingresante', 'idrecepciona', 'iddispositivo', 'idestado', 'iddespacha', 'idretira'], 'integer'],
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
        $query = RegistroTecnicoIncidencia::find();

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
            'idincidencia' => $this->idincidencia,
            'idregistro' => $this->idregistro,
            'idinventario' => $this->idinventario,
            'idingresante' => $this->idingresante,
            'idrecepciona' => $this->idrecepciona,
            'iddispositivo' => $this->iddispositivo,
            'idestado' => $this->idestado,
            'iddespacha' => $this->iddespacha,
            'idretira' => $this->idretira,
        ]);

        return $dataProvider;
    }
}
