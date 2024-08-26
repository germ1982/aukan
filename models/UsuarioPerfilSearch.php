<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UsuarioPerfil;

/**
 * UsuarioPerfilSearch represents the model behind the search form about `app\models\UsuarioPerfil`.
 */
class UsuarioPerfilSearch extends UsuarioPerfil
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_configuracion', 'id_configuracion_tipo'], 'integer'],
            [['descripcion', 'activo'], 'safe'],
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
        $query = UsuarioPerfil::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!isset($this->id_configuracion_tipo)) {
            $this->id_configuracion_tipo = ConfiguracionTipo::PERFIL_DE_USUARIO;  // Mostrar solo registros activos por defecto
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_configuracion' => $this->id_configuracion,
            'id_configuracion_tipo' => $this->id_configuracion_tipo,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'activo', $this->activo]);

        return $dataProvider;
    }
}
