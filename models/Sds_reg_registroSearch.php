<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_reg_registro;

/**
 * Sds_reg_registroSearch represents the model behind the search form about `app\models\Sds_reg_registro`.
 */
class Sds_reg_registroSearch extends Sds_reg_registro
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idregistro', 'idorganismo', 'usuario_solicitante', 'usuario_derivacion', 'incidencia_relacionada', 'idtipo', 'usuario_ingreso', 'iddispositivo', 'idpersona'], 'integer'],
            [['fecha_hora', 'problema', 'registro_abierto', 'fecha_ingreso', 'fecha_solucion', 'equipo_detalle', 'ip', 'fdesde', 'fhasta', 'iddispositivo', 'idpersona', 'idtipo', 'idcapaitem', 'entidad', 'mis_edificios'], 'safe'],
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
        $query = Sds_reg_registro::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['idregistro', 'fecha_hora', 'iddispositivo', 'idpersona', 'idcapaitem', 'problema', 'idtipo', 'registro_abierto', 'incidencia_relacionada', 'mis_edificios'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->idpersona == null) {
            $this->idpersona = -1;
        }

        if ($this->idcapaitem == null) {
            $this->idcapaitem = -1;
        }
        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) {
            $fecha_desde_aux = date_format(date_create(str_replace('/', '-', $this->fdesde)), 'Y-m-d');
            $sql_desde = "DATEDIFF(fecha_hora,'$fecha_desde_aux')>=0 ";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date_format(date_create(str_replace('/', '-', $this->fhasta)), 'Y-m-d');
            $sql_hasta = "DATEDIFF(fecha_hora,'$fecha_hasta_aux')<=0 ";
        }

        $query->select([
            'sds_reg_registro.*', 'entidad', '(SELECT idpersona FROM mds_org_contacto c where c.idcontacto = sds_reg_registro.usuario_solicitante) AS idpersona', '(SELECT idcapaitem FROM mds_org_dispositivo d Where d.iddispositivo = sds_reg_registro.iddispositivo) AS idcapaitem'
        ]);

        $query->innerJoin('sds_reg_tipo', 'sds_reg_registro.idtipo=sds_reg_tipo.idtipo');

        $query->andFilterWhere([
            'idregistro' => $this->idregistro,
            'fecha_hora' => $this->fecha_hora,
            'idorganismo' => $this->idorganismo,
            'usuario_solicitante' => $this->usuario_solicitante,
            'usuario_derivacion' => $this->usuario_derivacion,
            'incidencia_relacionada' => $this->incidencia_relacionada,
            'sds_reg_tipo.idtipo' => $this->idtipo,
            'fecha_ingreso' => $this->fecha_ingreso,
            'usuario_ingreso' => $this->usuario_ingreso,
            'fecha_solucion' => $this->fecha_solucion,
            'iddispositivo' => $this->iddispositivo,
            'entidad' => $this->entidad
        ]);

        //$sql_capa_item = "";
        if($this->mis_edificios==1){
            $usuario = Yii::$app->user->identity;
            $contacto = Mds_org_contacto::findOne($usuario->idcontacto);
            $dispositivo = Mds_org_dispositivo::findOne($contacto->iddispositivo);
            $this->idcapaitem = array();
            array_push($this->idcapaitem, $dispositivo->idcapaitem);
            $capa_items = Mds_seg_usuario_capa_item::find()->where(['idusuario' =>  $usuario->idusuario])->all();
            
            foreach ($capa_items as $capa_item) {
                array_push($this->idcapaitem, $capa_item->idcapaitem);
            }
            
            if(is_array($this->idcapaitem)) {
                $modulo_filter = implode(",", (array) $this->idcapaitem);
                $sql_capa_item = 'idcapaitem in (' . $modulo_filter . ')';
            }else{
                $modulo_filter = $this->idcapaitem;
                $sql_capa_item = '( 0>' . $this->idcapaitem . ' or idcapaitem in (' . $modulo_filter . '))';
            }
        }else{
            $sql_capa_item='1';
        }

        $query->andFilterWhere(['like', 'problema', $this->problema])
            ->andFilterWhere(['like', 'registro_abierto', $this->registro_abierto])
            ->andFilterWhere(['like', 'equipo_detalle', $this->equipo_detalle])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta);
        $query->having('(idpersona=' . $this->idpersona . ' or 0>' . $this->idpersona . ') and ' . $sql_capa_item);


        return $dataProvider;
    }
}
