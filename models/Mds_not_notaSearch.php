<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_not_nota;

/**
 * Mds_not_notaSearch represents the model behind the search form about `app\models\Mds_not_nota`.
 */
class Mds_not_notaSearch extends Mds_not_nota
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idnota', 'numero', 'idusuario', 'expediente_guarismo', 'expediente_numero', 'expediente_anio'], 'integer'],
            [['fecha', 'fdesde', 'fhasta', 'destinatario_nombre', 'destinatario_cargo', 'destinatario_area', 'referencia', 'detalle', 'enviada','anulada', 'fecha_carga'], 'safe'],
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
        $query = Mds_not_nota::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create($this->fdesde), 'Y-m-d');
            $sql_desde = "fecha >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create($this->fhasta), 'Y-m-d');
            $sql_hasta = "fecha <= '$fecha_hasta_aux'";
        }        
        
        $usuario = Yii::$app->user->identity;
        $idusuario = $usuario != null ? $usuario->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
        $dispositivo =  $contacto->getDispositivo()->one();
        $idorganismo = $dispositivo->idorganismo;

        $query->andFilterWhere([
            'idnota' => $this->idnota,
            'numero' => $this->numero,
            'idusuario' => $this->idusuario,
            'expediente_guarismo' => $this->expediente_guarismo,
            'expediente_numero' => $this->expediente_numero,
            'expediente_anio' => $this->expediente_anio,
        ])->andWhere($sql_desde)->andWhere($sql_hasta)->andWhere("idorganismo=".$idorganismo);

        $query->andFilterWhere(['like', 'destinatario_nombre', $this->destinatario_nombre])
            ->andFilterWhere(['like', 'destinatario_cargo', $this->destinatario_cargo])
            ->andFilterWhere(['like', 'destinatario_area', $this->destinatario_area])
            ->andFilterWhere(['like', 'referencia', $this->referencia])
            ->andFilterWhere(['like', 'detalle', $this->detalle])
            ->andFilterWhere(['like', 'enviada', $this->enviada])
            ->andFilterWhere(['like', 'anulada', $this->anulada])
            ->orderBy(["fecha" => SORT_DESC,"numero" => SORT_DESC ]);

        return $dataProvider;
    }
}
