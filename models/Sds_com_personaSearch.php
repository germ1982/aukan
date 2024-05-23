<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_com_persona;

/**
 * Sds_com_personaSearch represents the model behind the search form about `app\models\Sds_com_persona`.
 */
class Sds_com_personaSearch extends Sds_com_persona
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idpersona', 'documento', 'documento_tipo', 'nacionalidad', 'genero', 'padre', 'georeferencia'], 'integer'],
            [['fecha_nacimiento', 'nombre', 'apellido', 'domicilio_calle', 'domicilio_numero', 'localidad'], 'safe'],

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
        $query = Sds_com_persona::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'idpersona', 'documento', 'documento_tipo', 'nacionalidad', 'genero', 'padre', 'georeferencia',
                    'fecha_nacimiento', 'nombre', 'apellido', 'domicilio_calle', 'domicilio_numero', 'localidad', 'georeferencia_query'
                ],
                'defaultOrder' => ['documento' => SORT_ASC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->select(['p.*', 'loc.descripcion localidad']);
        $query->from('sds_com_persona p');
        $query->leftJoin('sds_com_localidad loc', 'loc.idlocalidad=p.idlocalidad');

        if($this->georeferencia_query!=null){
            $querys=Sds_com_persona_georef_query::find()->all();
            foreach($querys as $sql){
                switch ($sql->tipo){
                    case Sds_com_persona_georef_query::INNER_JOIN:
                        $query->join('inner join', $sql->descripcion, $sql->on);
                        break;
                    case Sds_com_persona_georef_query::LEFT_JOIN:
                        $query->join('left join', $sql->descripcion, $sql->on);
                        break;
                    case Sds_com_persona_georef_query::RIGHT_JOIN:
                        $query->join('right join', $sql->descripcion, $sql->on);
                        break;
                    case Sds_com_persona_georef_query::WHERE:
                        $query->andWhere($sql->descripcion);
                        break;
                }
            }
        }

        if ($this->georeferencia == '0') {
            $query->andWhere(['p.longitud' => null, 'p.latitud' => null]);
        }
        if ($this->georeferencia == '1') {
            $query->andWhere(['not', ['p.longitud' => null, 'p.latitud' => null]]);
        }
        $query->andWhere('p.documento >= 2000000 AND p.documento <= 99999999');

        $query->andFilterWhere([
            'p.idpersona' => $this->idpersona,
            'p.documento' => $this->documento,
            'p.documento_tipo' => $this->documento_tipo,
            'p.nacionalidad' => $this->nacionalidad,
            'p.padre' => $this->padre,
            'p.genero' => $this->genero,
            'p.fecha_nacimiento' => $this->fecha_nacimiento,
        ]);

        $query->andFilterWhere(['like', 'p.nombre', $this->nombre])
            ->andFilterWhere(['like', 'p.apellido', $this->apellido])
            ->andFilterWhere(['like', 'p.domicilio_calle', $this->domicilio_calle])
            ->andFilterWhere(['like', 'loc.descripcion', $this->localidad]);

        return $dataProvider;
    }
}
