<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Articulo;

/**
 * ArticuloSearch represents the model behind the search form about `app\models\Articulo`.
 */
class ArticuloSearch extends Articulo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idarticulo', 'idtipo', 'idmarca', 'idrubro', 'id_unidad_medida'], 'integer'],
            [['descripcion', 'modelo', 'activo', 'imagen','busquedaGlobal'], 'safe'],
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
        $query = Articulo::find();

                // *** CAMBIO CLAVE AQUI: Definir alias explícitos para los JOINs ***
        // Usamos los nombres de las relaciones como alias, para que coincidan con los LIKEs
        $query->joinWith([
            'idtipo0 idtipo_alias',        // 'idtipo0' es el nombre de la relación, 'idtipo_alias' es el alias en la consulta SQL
            'idmarca0 idmarca_alias',      // 'idmarca0' es el nombre de la relación, 'idmarca_alias' es el alias en la consulta SQL
            'idrubro0 idrubro_alias',      // 'idrubro0' es el nombre de la relación, 'idrubro_alias' es el alias en la consulta SQL
            'idUnidadMedida idunidad_alias' // 'idUnidadMedida' es el nombre de la relación, 'idunidad_alias' es el alias en la consulta SQL
        ]);

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
            'idarticulo' => $this->idarticulo,
            'idtipo' => $this->idtipo,
            'idmarca' => $this->idmarca,
            'idrubro' => $this->idrubro,
            'id_unidad_medida' => $this->id_unidad_medida,
        ]);

        $query->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'activo', $this->activo])
            ->andFilterWhere(['like', 'imagen', $this->imagen]);


        // *** ESTE ES EL CAMBIO MÁS IMPORTANTE AHORA: USAR LOS ALIAS CORRECTOS ***
        if (!empty($this->busquedaGlobal)) {
            $query->andFilterWhere(['or',
                ['like', 'articulo.descripcion', $this->busquedaGlobal],
                ['like', 'articulo.modelo', $this->busquedaGlobal],
                ['like', 'idtipo_alias.descripcion', $this->busquedaGlobal],      // ¡CAMBIADO!
                ['like', 'idmarca_alias.descripcion', $this->busquedaGlobal],     // ¡CAMBIADO!
                ['like', 'idrubro_alias.descripcion', $this->busquedaGlobal],     // ¡CAMBIADO!
                ['like', 'idunidad_alias.descripcion', $this->busquedaGlobal],    // ¡CAMBIADO!
            ]);
        }
        return $dataProvider;
    }
}
