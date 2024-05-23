<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_certificacion_programa;

/**
 * Mds_certificacion_programaSearch represents the model behind the search form of `app\models\Mds_certificacion_programa`.
 */
class Mds_certificacion_programaSearch extends Mds_certificacion_programa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idcertificacionprograma', 'idcertificaciondireccion', 'idprograma', 'idtipo_subsidio', 'idusuario_carga', 'cambio_responsable', 'requiere_autorizacion', 'idusuario_borra'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at', 'monto'], 'safe'],
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
        $query = Mds_certificacion_programa::find()
            ->addSelect(['mds_certificacion_programa.*', 'mds_certificacion_programa_monto.monto as monto'])
            ->leftJoin('mds_certificacion_programa_monto', 'mds_certificacion_programa.idcertificacionprograma = mds_certificacion_programa_monto.idcertificacionprograma AND mds_certificacion_programa_monto.fecha_fin IS NULL AND mds_certificacion_programa_monto.deleted_at IS NULL');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'attributes' => [
                    'idcertificacionprograma',
                    'idcertificaciondireccion',
                    'idprograma',
                    'cambio_responsable',
                    'requiere_autorizacion',
                    'idtipo_subsidio',
                    'deleted_at',
                    'monto' => [
                        'asc' => ['CONVERT(mds_certificacion_programa_monto.monto, UNSIGNED INTEGER)' => SORT_ASC],
                        'desc' => ['CONVERT(mds_certificacion_programa_monto.monto, UNSIGNED INTEGER)' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['idcertificacionprograma' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_certificacion::ID_ROL_ADMINISTRADOR_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['mds_certificacion_programa.deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['mds_certificacion_programa.deleted_at' => null]);
            }
        } else {
            $query->andWhere(['mds_certificacion_programa.deleted_at' => null]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'mds_certificacion_programa.idcertificacionprograma' => $this->idcertificacionprograma,
            'idcertificaciondireccion' => $this->idcertificaciondireccion,
            'idprograma' => $this->idprograma,
            'idusuario_carga' => $this->idusuario_carga,
            'idusuario_borra' => $this->idusuario_borra,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'cambio_responsable' => $this->cambio_responsable,
            'requiere_autorizacion' => $this->requiere_autorizacion,
            'idtipo_subsidio' => $this->idtipo_subsidio
        ]);

        $query->andFilterWhere(['like', 'monto', $this->monto]);

        return $dataProvider;
    }
}
