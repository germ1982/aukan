<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_seg_usuario_status;

/**
 * Mds_seg_usuario_statusSearch represents the model behind the search form of `app\models\Mds_seg_usuario_status`.
 */
class Mds_seg_usuario_statusSearch extends Mds_seg_usuario_status
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'idseg_usuario_status',
                    'idusuario',
                    'idusuario_carga',
                    'idusuario_borra',
                    'idestado',
                ], 'integer'
            ],
            [
                [
                    'idseg_usuario_status',
                    'idusuario',
                    'idusuario_carga',
                    'idusuario_borra',
                    'idestado',
                    'created_at',
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
        $query = Mds_seg_usuario_status::find()->select('*');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idseg_usuario_status',
                    'idusuario',
                    'idusuario_carga',
                    'idusuario_borra',
                    'idestado',
                    'created_at'
                ],
                'defaultOrder' => ['idseg_usuario_status' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query
            ->andFilterWhere(['=', 'DATE_FORMAT(created_at,"%d-%m-%Y")', $this->created_at])
            ->andFilterWhere(['=', 'idseg_usuario_status', $this->idseg_usuario_status])
            ->andFilterWhere(['=', 'idusuario', $this->idusuario])
            ->andFilterWhere(['=', 'idusuario_carga', $this->idusuario_carga])
            ->andFilterWhere(['=', 'idusuario_borra', $this->idusuario_borra])
            ->andFilterWhere(['=', 'idestado', $this->idestado]);
        return $dataProvider;
    }
}
