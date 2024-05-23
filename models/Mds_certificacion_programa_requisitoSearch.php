<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_certificacion_programa_requisito;

/**
 * Mds_certificacion_programa_requisitoSearch represents the model behind the search form of `app\models\Mds_certificacion_programa_requisito`.
 */
class Mds_certificacion_programa_requisitoSearch extends Mds_certificacion_programa_requisito
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idprogramarequisito', 'idrequisito', 'idcertificacionprograma', 'idusuario_carga', 'idusuario_borra'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Mds_certificacion_programa_requisito::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idprogramarequisito' => $this->idprogramarequisito,
            'idrequisito' => $this->idrequisito,
            'idcertificacionprograma' => $this->idcertificacionprograma,
            'idusuario_carga' => $this->idusuario_carga,
            'idusuario_borra' => $this->idusuario_borra,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        return $dataProvider;
    }
}
