<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_atp_solicitud;

/**
 * Mds_atp_solicitudSearch represents the model behind the search form about `app\models\Mds_atp_solicitud`.
 */
class Mds_atp_solicitudSearch extends Mds_atp_solicitud
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'carga_grupo_familiar', 'ingreso_grupo_familiar', 'estado', 'created_at', 'updated_at', 'retirada'], 'integer'],
            [['cuil','tipo_documento','sexo','direccion','localidad','tutor_cuil','tutor_sexo','tutor_tipo_documento','documento', 'nombre', 'apellido', 'fecha_nacimiento', 'foto_dni', 'foto_certificado', 'telefono', 'telefono_alternativo', 'email', 'tutor_documento', 'tutor_nombre', 'tutor_apellido', 'tutor_parentesco', 'tutor_fecha_nacimiento', 'tutor_foto_dni'], 'safe'],
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
        $query = Mds_atp_solicitud::find()->select(['id', 'documento', 'nombre', 'apellido', 'fecha_nacimiento', 'telefono', 'email', 'estado','created_at','updated_at', 'retirada']);       

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'fecha_nacimiento' => $this->fecha_nacimiento,            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'documento', $this->documento])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])           
            ->andFilterWhere(['like', 'telefono', $this->telefono])            
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'retirada', $this->retirada]);
        
        return $dataProvider;
    }
}
