<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Mds_rum_empleador;

/**
 * Mds_rum_empleadorSearch represents the model behind the search form about `app\models\Mds_rum_empleador`.
 */
class Mds_rum_empleadorSearch extends Mds_rum_empleador
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idpersona', 'slogan', 'iddomicilio', 'id_categoria', 'activo'], 'integer'],
            [['id','estado2','activo2','id_categoria2','email2','iddomicilio2','nombre', 'imagen', 'email',  'telefono2', 'fechamodificacion', 'horamodificacion', 'fechaalta', 'horaalta','nombre_emp'], 'safe'],
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
        $query = Mds_rum_empleador::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['id','nombre_emp','iddomicilio2','email2','id_categoria2','activo2','estado2'],
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);
        $dataProvider->setSort([
            'attributes' => [
               
                'nombre_emp' => [
                    'asc' => ['mds_rum_empleador.nombre' => SORT_ASC],
                    'desc' => ['mds_rum_empleador.nombre' => SORT_DESC],                    
                    'label' => 'Nombre Empleador'
                ],
                'iddomicilio2' => [
                    'asc' => ['sds_com_localidad.descripcion' => SORT_ASC],
                    'desc' => ['sds_com_localidad.descripcion' => SORT_DESC],                    
                    'label' => 'Localidad'
                ],
                'email2' => [
                    'asc' => ['email' => SORT_ASC],
                    'desc' => ['email' => SORT_DESC],                    
                    'label' => 'Email'
                ],
                'id_categoria2' => [
                    'asc' => ['sds_com_configuracion.descripcion' => SORT_ASC],
                    'desc' => ['sds_com_configuracion.descripcion' => SORT_DESC],                    
                    'label' => 'Categoria'
                ],
                'activo2' => [
                    'asc' => ['mds_rum_empleador.activo' => SORT_ASC],
                    'desc' => ['mds_rum_empleador.activo' => SORT_DESC],                    
                    'label' => 'Activo'
                ],
                'estado2' => [
                    'asc' => ['estado' => SORT_ASC],
                    'desc' => ['estado' => SORT_DESC],                    
                    'label' => 'Estado'
                ],
                'id' => [
                    'asc' => ['id' => SORT_ASC],
                    'desc' => ['id' => SORT_DESC],                                    
                ],
            ],'defaultOrder' => ['id' => SORT_DESC]
            
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query 
        ->innerJoin('mds_rum_domicilio', 'mds_rum_domicilio.id = mds_rum_empleador.iddomicilio')   
        ->innerJoin('sds_com_localidad', 'sds_com_localidad.idlocalidad = mds_rum_domicilio.idlocalidad') 
        ->innerJoin('sds_com_configuracion', 'sds_com_configuracion.idconfiguracion = mds_rum_empleador.id_categoria')          
        ->all();
        $query->andFilterWhere([
            
            'idpersona' => $this->idpersona,
            'slogan' => $this->slogan,
            'iddomicilio' => $this->iddomicilio,            
            'fechamodificacion' => $this->fechamodificacion,
            'horamodificacion' => $this->horamodificacion,
            'fechaalta' => $this->fechaalta,
            'horaalta' => $this->horaalta,
            'mds_rum_empleador.activo' => $this->activo2,
            'mds_rum_empleador.id' => $this->nombre_emp,            
            'mds_rum_domicilio.idlocalidad' => $this->iddomicilio2,
            'sds_com_configuracion.idconfiguracion' => $this->id_categoria2, 
            'estado' => $this->estado2,       
        ]);       
        
        $query->andFilterWhere([        
            'mds_rum_empleador.email' => $this->email2,              
        ]);       
        $query
            ->andFilterWhere(['like', 'imagen', $this->imagen])
            ->andFilterWhere(['like', 'email', $this->email])           
            ->andFilterWhere(['like', 'telefono2', $this->telefono2]);

        return $dataProvider;
    }
}
