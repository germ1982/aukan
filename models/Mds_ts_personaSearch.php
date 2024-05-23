<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_ts_persona;

/**
 * Mds_ts_personaSearch represents the model behind the search form about `app\models\Mds_ts_persona`.
 */
class Mds_ts_personaSearch extends Mds_ts_persona
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idtspersona', 'idlocalidad'], 'integer'],
            [['dni','estado','nombre', 'apellido', 'domicilio', 'telefono', 'mail', 'foto_dni_frente', 'foto_dni_dorso', 'recibo_sueldo', 'factura_luz', 'fecha_hora', 'campania', 'tipo_beneficiario', 'nombre_institucion'], 'safe'],
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
        $query = Mds_ts_persona::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idtspersona' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        
        $query->andFilterWhere([
            'idtspersona' => $this->idtspersona,  
            'dni' => $this->dni,
            'estado' => $this->estado,            
            'idlocalidad' => $this->idlocalidad,
            'fecha_hora' => $this->fecha_hora,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'domicilio', $this->domicilio])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'mail', $this->mail])
            ->andFilterWhere(['like', 'campania', $this->campania])
            ->andFilterWhere(['like', 'foto_dni_frente', $this->foto_dni_frente])
            ->andFilterWhere(['like', 'foto_dni_dorso', $this->foto_dni_dorso])
            ->andFilterWhere(['like', 'recibo_sueldo', $this->recibo_sueldo])
            ->andFilterWhere(['like', 'tipo_beneficiario', $this->tipo_beneficiario])
            ->andFilterWhere(['like', 'nombre_institucion', $this->nombre_institucion])
            ->andFilterWhere(['like', 'factura_luz', $this->factura_luz]);

        return $dataProvider;
    }
}
