<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_certificacion_director;

/**
 * Mds_certificacion_directorSearch represents the model behind the search form of `app\models\Mds_certificacion_director`.
 */
class Mds_certificacion_directorSearch extends Mds_certificacion_director
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificaciondirector', 'idusuario', 'idcertificaciondireccion', 'idusuario_carga', 'idusuario_borra', 'idfuncion'], 'integer'],
            [['fecha_desde', 'fecha_hasta', 'observaciones', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
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
        $query = Mds_certificacion_director::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idcertificaciondirector',
                    'idcertificaciondireccion',
                    'idusuario',
                    'idfuncion',
                    'fecha_desde',
                    'fecha_hasta',
                    'deleted_at',
                ],
                'defaultOrder' => ['idcertificaciondirector' => SORT_DESC]
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idcertificaciondirector' => $this->idcertificaciondirector,
            // 'idusuario' => $this->idusuario,
            'idcertificaciondireccion' => $this->idcertificaciondireccion,
            'fecha_desde' => $this->fecha_desde,
            'fecha_hasta' => $this->fecha_hasta,
            'idusuario_carga' => $this->idusuario_carga,
            'idusuario_borra' => $this->idusuario_borra,
            'idfuncion' => $this->idfuncion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'observaciones', $this->observaciones]);
        return $dataProvider;
    }
}
