<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_ans_jubilacion;

/**
 * Mds_ans_jubilacionSearch represents the model behind the search form about `app\models\Mds_ans_jubilacion`.
 */
class Mds_ans_jubilacionSearch extends Mds_ans_jubilacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idjubilacion'], 'integer'],
            [['tipo_dni', 'dni', 'cuil', 'nombre_apellido', 'beneficio', 'periodo','beneficio_grupo'], 'safe'],
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
        $query = mds_ans_jubilacion::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['tipo_dni', 'dni', 'cuil', 'nombre_apellido', 'beneficio', 'periodo','beneficio_grupo'],
                'defaultOrder' => ['nombre_apellido' => SORT_ASC]
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idjubilacion' => $this->idjubilacion,
            'tipo_dni' => $this->tipo_dni,
            'dni' => $this->dni,
            'cuil' => $this->cuil,
            //ANOTEZE: ESTA LA VUELO PORQUE SINO NO TOMA EL OTRO FILTRO QUE PONGAMOS
            //'beneficio' => $this->beneficio,
            'periodo' => $this->periodo,

        ]);
        //ANOTEZE: Aca verificio si el filtro viene sin setear, entonces le mando un -1 como comodín para que traiga todo
        if ($this->beneficio_grupo==null){
            $this->beneficio_grupo = -1;
        }
        //ANOTEZE: ACA VENIA BIEN. HAY QUE CAMBIAR EL SELECT. VOY A PONER 0 PARA JUBILACION Y 1 PARA PENSION.
        //ESTA PARTE ES DEL LADO SQL, EL SUBSTRING QUE USO ES LA FUNCION SQL, LO MISMO EL IF.
        //$query->addSelect("*");
        $query->select(['mds_ans_jubilacion.*','if(SUBSTRING(beneficio,3,1)<=4,0,1) beneficio_grupo']);
        $query->from("mds_ans_jubilacion");
  
        $query->andFilterWhere(['like', 'tipo_dni', $this->tipo_dni])
            ->andFilterWhere(['like', 'dni', $this->dni])
            ->andFilterWhere(['like', 'cuil', $this->cuil])
            ->andFilterWhere(['like', 'nombre_apellido', $this->nombre_apellido])
            //->andFilterWhere(['like', 'beneficio', $this->beneficio]) ANOTEZE: ESTE LO SACO TAMBIEN
            ->andFilterWhere(['like', 'periodo', $this->periodo]);        
        
        //ANOTEZE: EL HAVING VA SIEMPRE AL FINAL DEL TODO, O AL MENOS SIEMPRE TRATO DE PONERLO AL FINAL.
        //ACA HAY QUE COMPARAR CON EL CAMPO DE LA BASE QUE AGREGAMOS EN EL SELECT CON EL CAMPO DE FILTRO DEL MODELO
        $query->having("beneficio_grupo=".$this->beneficio_grupo." or ".$this->beneficio_grupo."<0 ");

        return $dataProvider;
    }
}
