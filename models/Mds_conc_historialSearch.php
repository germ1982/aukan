<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_conc_historial;

/**
 * Mds_conc_historialSearch represents the model behind the search form about `app\models\Mds_conc_historial`.
 */
class Mds_conc_historialSearch extends Mds_conc_historial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'idhistorial',
                ], 'integer'
            ],
            [
                [
                    'idhistorial',
                    'idpostulacion',
                    'observacion',
                    'observacion_publica',
                    'estado_nuevo',
                    'estado_anterior',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ], 'safe'
            ],
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
    public function search($params, $idpostulacion)
    {
        $query = Mds_conc_historial::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idhistorial',
                    'idpostulacion',
                    'observacion',
                    'observacion_publica',
                    'estado_nuevo',
                    'estado_anterior',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ],
                'defaultOrder' => ['idhistorial' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['mds_conc_historial.deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['mds_conc_historial.deleted_at' => null]);
            }
        } else {
            $query->andWhere(['mds_conc_historial.deleted_at' => null]);
        }

        if ($idpostulacion) {
            $query->andWhere(['mds_conc_historial.idpostulacion' => $idpostulacion]);
        }

        $query
        ->andFilterWhere(['=', 'idhistorial', $this->idhistorial])
        ->andFilterWhere(['=', 'idpostulacion', $this->idpostulacion])
        ->andFilterWhere(['like', 'observacion', $this->observacion])
        ->andFilterWhere(['like', 'observacion_publica', $this->observacion_publica])
        ->andFilterWhere(['in', 'estado_nuevo', $this->estado_nuevo])
        ->andFilterWhere(['in', 'estado_anterior', $this->estado_anterior])
        ->andFilterWhere(['=', 'DATE_FORMAT(created_at,"%d-%m-%Y")', $this->created_at]);
        
        return $dataProvider;
    }
}
