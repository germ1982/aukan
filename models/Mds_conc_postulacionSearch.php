<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_conc_postulacion;

/**
 * Mds_conc_postulacionSearch represents the model behind the search form about `app\models\Mds_conc_postulacion`.
 */
class Mds_conc_postulacionSearch extends Mds_conc_postulacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'idpostulacion',
                ], 'integer'
            ],
            [
                [
                    'idpostulacion',
                    'idvacante',
                    'idsolicitud',
                    'idconcurso',
                    'estado',
                    'puntaje',
                    'documento',
                    'apellido',
                    'nombre',
                    'legajo',
                    'idusuario',
                    'idusuario_borra',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'categoria_actual',
                    'eventual'
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
    public function search($params, $idsolicitud)
    {
        $query = Mds_conc_postulacion::find()->select('*, mds_conc_postulacion.deleted_at, padron.categoria as categoria_actual');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idpostulacion',
                    'idvacante',
                    'idsolicitud',
                    'idconcurso',
                    'estado',
                    'puntaje',
                    'documento',
                    'apellido',
                    'nombre',
                    'legajo',
                    'idusuario',
                    'idusuario_borra',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'categoria_actual',
                    'eventual'
                ],
                'defaultOrder' => ['idpostulacion' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_conc_solicitud::ID_ROL_ADMIN_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['mds_conc_postulacion.deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['mds_conc_postulacion.deleted_at' => null]);
            }
        } else {
            $query->andWhere(['mds_conc_postulacion.deleted_at' => null]);
        }
        
        if ($idsolicitud) {
            $query->andWhere(['mds_conc_postulacion.idsolicitud' => $idsolicitud]);
        }

        $query->innerJoin('mds_conc_solicitud', 'mds_conc_solicitud.idsolicitud = mds_conc_postulacion.idsolicitud');

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

        if ($this->documento) {
            $query->andWhere([
                '=',
                'mds_conc_solicitud.documento', $this->documento
            ]);
        }

        if ($this->apellido) {
            $query->andWhere([
                'like',
                'mds_conc_solicitud.apellido', $this->apellido
            ]);
        }

        if ($this->nombre) {
            $query->andWhere([
                'like',
                'mds_conc_solicitud.nombre', $this->nombre
            ]);
        }

        if ($this->idconcurso) {
            $query->andWhere([
                'in',
                'mds_conc_solicitud.idconcurso', $this->idconcurso
            ]);
        }

        if ($this->legajo) {
            $query->andWhere([
                '=',
                'mds_conc_solicitud.legajo', $this->legajo
            ]);
        }

        $query
            ->andFilterWhere(['=', 'mds_conc_postulacion.idpostulacion', $this->idpostulacion])
            ->andFilterWhere(['=', 'mds_conc_postulacion.idsolicitud', $this->idsolicitud])
            ->andFilterWhere(['=', 'mds_conc_postulacion.puntaje', $this->puntaje])
            ->andFilterWhere(['in', 'mds_conc_postulacion.idvacante', $this->idvacante])
            ->andFilterWhere(['in', 'mds_conc_postulacion.estado', $this->estado])
            ->andFilterWhere(['=', 'DATE_FORMAT(mds_conc_postulacion.created_at,"%d-%m-%Y")', $this->created_at]);
        return $dataProvider;
    }
}
