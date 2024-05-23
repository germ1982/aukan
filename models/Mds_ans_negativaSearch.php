<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_ans_negativa;

/**
 * Mds_ans_negativaSearch represents the model behind the search form about `app\models\Mds_ans_negativa`.
 */
class Mds_ans_negativaSearch extends Mds_ans_negativa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cuit','dni'], 'string'],
            [['nombre', 'fallecido', 'trabajador_dependiente', 'autonomo', 'monotributista', 'ddjprovincial', 'casas_particulares', 'efectores_sociales', 'jubilado_pensionado', 'previsional_provincia', 'previsional_tramite', 'desempleo', 'programa_empleo', 'os_vigente', 'asignacion_familiar', 'auh', 'cuota_beca_progresar', 'beca_progresar', 'maternidad_casasparticulares', 'asignacion_familiar_jubilados', 'pnc', 'iniciacion_pnc', 'aaff_discontinuos'], 'safe'],
            [['periodo', 'fecha_fallecido', 'idnegativa'], 'integer'],
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
        $query = Mds_ans_negativa::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([            
            'periodo' => $this->periodo,
            'fecha_fallecido' => $this->fecha_fallecido,
            'idnegativa' => $this->idnegativa,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'fallecido', $this->fallecido])
            ->andFilterWhere(['like', 'trabajador_dependiente', $this->trabajador_dependiente])
            ->andFilterWhere(['like', 'autonomo', $this->autonomo])
            ->andFilterWhere(['like', 'monotributista', $this->monotributista])
            ->andFilterWhere(['like', 'ddjprovincial', $this->ddjprovincial])
            ->andFilterWhere(['like', 'casas_particulares', $this->casas_particulares])
            ->andFilterWhere(['like', 'efectores_sociales', $this->efectores_sociales])
            ->andFilterWhere(['like', 'jubilado_pensionado', $this->jubilado_pensionado])
            ->andFilterWhere(['like', 'previsional_provincia', $this->previsional_provincia])
            ->andFilterWhere(['like', 'previsional_tramite', $this->previsional_tramite])
            ->andFilterWhere(['like', 'desempleo', $this->desempleo])
            ->andFilterWhere(['like', 'programa_empleo', $this->programa_empleo])
            ->andFilterWhere(['like', 'os_vigente', $this->os_vigente])
            ->andFilterWhere(['like', 'asignacion_familiar', $this->asignacion_familiar])
            ->andFilterWhere(['like', 'auh', $this->auh])
            ->andFilterWhere(['like', 'cuota_beca_progresar', $this->cuota_beca_progresar])
            ->andFilterWhere(['like', 'beca_progresar', $this->beca_progresar])
            ->andFilterWhere(['like', 'maternidad_casasparticulares', $this->maternidad_casasparticulares])
            ->andFilterWhere(['like', 'asignacion_familiar_jubilados', $this->asignacion_familiar_jubilados])
            ->andFilterWhere(['like', 'pnc', $this->pnc])
            ->andFilterWhere(['like', 'iniciacion_pnc', $this->iniciacion_pnc])
            ->andFilterWhere(['like', 'cuit', $this->cuit])
            ->andFilterWhere(['like', 'dni', $this->dni])
            ->andFilterWhere(['like', 'aaff_discontinuos', $this->aaff_discontinuos]);
            

        return $dataProvider;
    }
}
