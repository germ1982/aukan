<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_conc_solicitud;

/**
 * Mds_conc_solicitudSearch represents the model behind the search form of `app\models\Mds_conc_solicitud`.
 */
class Mds_conc_solicitudSearch extends Mds_conc_solicitud
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'idsolicitud',
                    'idusuario',
                ], 'integer'
            ],
            [
                [
                    'idsolicitud',
                    'idconcurso',
                    'documento',
                    'apellido',
                    'nombre',
                    'legajo',
                    'telefono',
                    'mail',
                    'created_at',
                    'idusuario',
                    'deleted_at',
                    'categoria_actual',
                    'eventual'
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
        $query = Mds_conc_solicitud::find()->select('*, padron.categoria as categoria_actual');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idsolicitud',
                    'idconcurso',
                    'documento',
                    'apellido',
                    'nombre',
                    'legajo',
                    'telefono',
                    'mail',
                    'created_at',
                    'idusuario',
                    'deleted_at',
                    'categoria_actual',
                    'eventual'
                ],
                'defaultOrder' => ['idsolicitud' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['mds_conc_solicitud.deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['mds_conc_solicitud.deleted_at' => null]);
            }
        } else {
            $query->andWhere(['mds_conc_solicitud.deleted_at' => null]);
        }

        // $query->leftJoin('mds_org_padron', 'mds_conc_solicitud.documento = mds_org_padron.dni');

        /*
        Debo hacer LEFT JOIN con la ultima categoria (categoria actual), 
        por lo que debo hacer un "GROUP BY dni" y un "MAX(idpadron)" 
        para quedarme con el ultimo registro
        */
        $query->leftJoin(
            '(
                SELECT padron.categoria, padron.dni, padron.eventual
                FROM mds_org_padron padron
                INNER JOIN (
                    SELECT dni, MAX(idpadron) AS UltimoID
                    FROM mds_org_padron
                    GROUP BY dni
                    ) ultimos_ids 
                ON padron.dni = ultimos_ids.dni AND padron.idpadron = ultimos_ids.UltimoID
            ) padron',
            'mds_conc_solicitud.documento = padron.dni'
        );
        if ($this->categoria_actual) {
            $query->andFilterWhere(['like', 'categoria', $this->categoria_actual]);
        }

        if (!is_null($this->eventual)) {
            $query->andFilterWhere(['eventual' => $this->eventual]);
        }

        $query->andFilterWhere([
            'idsolicitud' => $this->idsolicitud,
        ]);

        $query->andFilterWhere(['=', 'documento', $this->documento])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['=', 'legajo', $this->legajo])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['=', 'DATE_FORMAT(created_at,"%d-%m-%Y")', $this->created_at])
            ->andFilterWhere(['=', 'idusuario', $this->idusuario])
            ->andFilterWhere(['in', 'idconcurso', $this->idconcurso]);
        return $dataProvider;
    }
}
