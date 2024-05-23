<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_ent_solicitud;

/**
 * Sds_ent_solicitudSearch represents the model behind the search form about `app\models\Sds_ent_solicitud`.
 */
class Sds_ent_solicitudSearch extends Sds_ent_solicitud
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idsolicitud', 'cantidad', 'dni', 'idtipo', 'idusuario', 'estado'], 'integer'],
            [['fecha_hora', 'observaciones', 'fecha_aprobacion'], 'safe'],
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
        $query = Sds_ent_solicitud::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha_hora','dni', 'cantidad', 'idtipo', 'observaciones','idusuario','entidad','estado'],                
                'defaultOrder'=>['fecha_hora'=>SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $user = Yii::$app->user->identity;
        $idusuario = $user != null ? $user->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }    

        $query->addSelect(["(SELECT externo FROM mds_seg_usuario usu where usu.idusuario=sds_ent_solicitud.idusuario) entidad","sds_ent_solicitud.*"]);
        $query->andFilterWhere([
            'idsolicitud' => $this->idsolicitud,
            'fecha_hora' => $this->fecha_hora,
            'cantidad' => $this->cantidad,
            'dni' => $this->dni,
            'idtipo' => $this->idtipo,
            'idusuario' => $this->idusuario,
            'estado' => $this->estado,
            'fecha_aprobacion' => $this->fecha_aprobacion,            
        ]);
        $query->andFilterWhere(['like', 'observaciones', $this->observaciones]);
        $query->having("entidad=".$user->externo);

        return $dataProvider;
    }
}
