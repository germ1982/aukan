<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_conc_cronograma;
use app\models\Mds_conc_solicitud;

/**
 * Mds_conc_cronogramaSearch represents the model behind the search form of `app\models\Mds_conc_cronograma`.
 */
class Mds_conc_cronogramaSearch extends Mds_conc_cronograma
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'idetapa',
                ], 'integer'
            ],
            [
                [
                    'idetapa',
                    'idconcurso',
                    'nombre',
                    'detalle',
                    'estado',
                    'orden',
                    'fecha_inicio',
                    'fecha_fin',
                    'created_at',
                    'idusuario',
                    'deleted_at',
                ], 'safe'
            ],
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
        $query = Mds_conc_cronograma::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idetapa',
                    'idconcurso',
                    'nombre',
                    'detalle',
                    'estado',
                    'orden',
                    'fecha_inicio',
                    'fecha_fin',
                    'created_at',
                    'idusuario',
                    'deleted_at',
                ],
                'defaultOrder' => ['idetapa' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['mds_conc_cronograma.deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['mds_conc_cronograma.deleted_at' => null]);
            }
        } else {
            $query->andWhere(['mds_conc_cronograma.deleted_at' => null]);
        }
        
        if ($this->estado === '0') {
            $query->andWhere(['mds_conc_cronograma.estado' => 0]);
        } else if ($this->estado === '1') {
            $query->andWhere(['mds_conc_cronograma.estado' => 1]);
        }

        $query->andFilterWhere([
            'idetapa' => $this->idetapa,
        ]);

        $query
            ->andFilterWhere(['=', 'DATE_FORMAT(created_at,"%d-%m-%Y")', $this->created_at])
            ->andFilterWhere(['=', 'DATE_FORMAT(fecha_inicio,"%d-%m-%Y")', $this->fecha_inicio])
            ->andFilterWhere(['=', 'DATE_FORMAT(fecha_fin,"%d-%m-%Y")', $this->fecha_fin])
            ->andFilterWhere(['in', 'idconcurso', $this->idconcurso])
            ->andFilterWhere(['in', 'idusuario', $this->idusuario])
            ->andFilterWhere(['=', 'orden', $this->orden])
            ->andFilterWhere(['like', 'nombre', $this->nombre]);
        return $dataProvider;
    }
}
