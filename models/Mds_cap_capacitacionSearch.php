<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_cap_capacitacion;

/**
 * Mds_cap_capacitacionSearch represents the model behind the search form about `app\models\Mds_cap_capacitacion`.
 */
class Mds_cap_capacitacionSearch extends Mds_cap_capacitacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcapacitacion', 'tematica', 'idusuario', 'idorganismo'], 'integer'],
            [['descripcion', 'detalle', 'nombre_corto', 'objetivos', 'perfil'], 'safe'],
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
        $query = Mds_cap_capacitacion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /* $idcontacto  = Yii::$app->user->identity->idcontacto;
        $idusuario = Yii::$app->user->identity->idusuario;
        $permiso_global = Mds_seg_permiso::findBySql("select * from mds_seg_permiso where 
                                                idrol in (select idrol from mds_seg_usuario_rol where idusuario=$idusuario)
                                                and (iditem=" . Mds_seg_item::MODULO_CAP_GLOBAL . ")")->one();         */
        $query->andFilterWhere([
            'idcapacitacion' => $this->idcapacitacion,
            'tematica' => $this->tematica,
            'idusuario' => $this->idusuario,
            'perfil' => $this->perfil,
        ]);

        if ($this->idorganismo) {
            $query
                ->andFilterWhere(['idorganismo' => $this->idorganismo]);
        }

        if ($this->idorganismoexterno) {
            $query
                ->andFilterWhere(['idorganismoexterno' => $this->idorganismoexterno]);
        }

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'objetivos', $this->objetivos])
            ->andFilterWhere(['like', 'perfil', $this->perfil])
            ->andFilterWhere(['like', 'detalle', $this->detalle]);

        return $dataProvider;
    }
}
