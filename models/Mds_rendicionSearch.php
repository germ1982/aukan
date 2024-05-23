<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_rendicion;
use \yii\db\Expression;


/**
 * Mds_rendicionSearch represents the model behind the search form of `app\models\Mds_rendicion`.
 */
class Mds_rendicionSearch extends Mds_rendicion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'idrendicion',
                    'idtipo',
                    'idusuario_carga',
                    'idusuario_borra',
                ], 'integer'
            ],
            [
                [
                    'idlugar',
                    'idpersona',
                    'monto',
                    'fecha_comprobante',
                    'fecha_vale',
                    'deleted_at',
                    'sujeto',
                ], 'string'
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
        $query = Mds_rendicion::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idrendicion',
                    'idtipo',
                    'idlugar',
                    'idpersona',
                    'idusuario_carga',
                    'fecha_comprobante',
                    'sujeto',
                    'idusuario_comprobante',
                    'fecha_vale',
                    'deleted_at',
                    'monto' => [
                        'asc' => ['CONVERT(monto, UNSIGNED INTEGER)' => SORT_ASC],
                        'desc' => ['CONVERT(monto, UNSIGNED INTEGER)' => SORT_DESC],
                    ],
                    'idlugar' => [
                        'asc' => ['sds_gis_capa_item.descripcion' => SORT_ASC],
                        'desc' => ['sds_gis_capa_item.descripcion' => SORT_DESC],
                    ],
                    'sujeto' => [
                        'asc' => [new Expression('(CASE mds_rendicion.idpersona WHEN NOT NULL THEN sds_com_persona.apellido ELSE mds_seg_usuario.apellido END) ASC')],
                        'desc' => [new Expression('(CASE mds_rendicion.idpersona WHEN NOT NULL THEN sds_com_persona.apellido ELSE mds_seg_usuario.apellido END) DESC')],
                    ],
                ],
                'defaultOrder' => ['idrendicion' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith('persona');
        $query->joinWith('usuarioComprobante');
        $query->joinWith('lugar');


        $hasRolAdminGeneral = Mds_seg_usuario_rol::hasRol(Mds_rendicion::ID_ROL_ADMIN_GENERAL);
        if ($hasRolAdminGeneral) {
            if ($this->deleted_at === '0') {
                $query->andWhere(['not', ['mds_rendicion.deleted_at' => null]]);
            } else if ($this->deleted_at === '1') {
                $query->andWhere(['mds_rendicion.deleted_at' => null]);
            }
        } else {
            $query->andWhere(['mds_rendicion.deleted_at' => null]);
        }

        $query->andFilterWhere([
            'idrendicion' => $this->idrendicion,
            'idtipo' => $this->idtipo,
            'idusuario_carga' => $this->idusuario_carga,
        ]);

        $query
            ->andFilterWhere(['like', 'monto', $this->monto])
            ->andFilterWhere(['in', 'idlugar', $this->idlugar])
            ->andFilterWhere(['=', 'DATE_FORMAT(fecha_comprobante,"%d/%m/%Y")', $this->fecha_comprobante])
            ->andFilterWhere(['=', 'DATE_FORMAT(fecha_vale,"%d/%m/%Y")', $this->fecha_vale]);

        if ($this->sujeto) {
            $query->andFilterWhere([
                'or',
                ['like', 'sds_com_persona.documento', $this->sujeto],
                ['like', 'sds_com_persona.nombre', $this->sujeto],
                ['like', 'sds_com_persona.apellido', $this->sujeto],
                ['like', 'mds_seg_usuario.dni', $this->sujeto],
                ['like', 'mds_seg_usuario.nombre', $this->sujeto],
                ['like', 'mds_seg_usuario.apellido', $this->sujeto]
            ]);
        };

        return $dataProvider;
    }
}
