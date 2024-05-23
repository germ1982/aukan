<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sds_reg_registro_autosolicitud;

/**
 * Sds_reg_registro_autosolicitudSearch represents the model behind the search form about `app\models\Sds_reg_registro_autosolicitud`.
 */
class Sds_reg_registro_autosolicitudSearch extends Sds_reg_registro_autosolicitud
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idregistro', 'idorganismo', 'usuario_solicitante', 'usuario_derivacion', 'incidencia_relacionada', 'idtipo', 'usuario_ingreso', 'iddispositivo','estado'], 'integer'],
            [['fecha_hora', 'fdesde','fhasta', 'problema', 'registro_abierto', 'fecha_ingreso', 'fecha_solucion', 'equipo_detalle', 'ip', 'entidad'], 'safe'],
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
        $query = Sds_reg_registro_autosolicitud::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => ['fecha_hora','estado', 'problema','idtipo'],
                'defaultOrder' => ['fecha_hora' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $user = Yii::$app->user->identity;
        $idusuario = $user != null ? $user->idusuario : null;
        if (!isset($idusuario) || $idusuario == null) {
            $model = new \app\models\LoginForm();
            return Yii::$app->getResponse()->redirect([
                'site/login',
                'model' => $model,
            ]);
        }
        $usuario_logueado = "usuario_derivacion = $user->idusuario";

        /* ---------------------------------------------------------------------------------------------------------------------------------------- */
        //esto es para que ande el filtro de las fechas

        $sql_desde = '';
        $sql_hasta = '';
        if ($this->fdesde != null) { 
            $fecha_desde_aux = date('Y-m-d', strtotime(str_replace('/', '-', $this->fdesde)));
            $sql_desde = "fecha_hora >= '$fecha_desde_aux'";
        }
        if ($this->fhasta != null) {
            $fecha_hasta_aux = date('Y-m-d', strtotime(str_replace('/', '-', $this->fhasta)));
            $sql_hasta = "fecha_hora <= '$fecha_hasta_aux'";
        }    
        /* ---------------------------------------------------------------------------------------------------------------------------------------- */
        //elte filtro me trae la opcion elegida en las columnas y de acuerdo al id de la opcion elegida arma su where
        $estado = "";
        if ($this->estado != null){
            $estado = $this->estado;
            //echo '<div class="col-md-8 col-offset-2">'.$estado.' Entidad: '.$this->entidad.'</div>';
            switch ($estado){
                case 0://registro pendiente a asignar y abierto
                    switch($this->entidad){
                        case Sds_reg_registro::ENT_INFORMATICA:
                            $estado = 'sds_reg_registro.idtipo = 7 and sds_reg_registro.registro_abierto = 1';
                            break;
                        case Sds_reg_registro::ENT_MANTENIMIENTO:
                            $estado = 'sds_reg_registro.idtipo = 10 and sds_reg_registro.registro_abierto = 1';
                            break;
                        case Sds_reg_registro::ENT_RUMBO:
                            $estado = 'sds_reg_registro.idtipo = 11 and sds_reg_registro.registro_abierto = 1';
                            break;
                    }
                    break;
                case 1://registro ya asignado y abierto
                    $estado = 'sds_reg_registro.idtipo <> 7 and sds_reg_registro.idtipo <> 10 and sds_reg_registro.idtipo <> 11 and sds_reg_registro.registro_abierto = 1';
                    break;
                case 2://registro cerrado sin importar si esta o no asignado
                    $estado = 'registro_abierto = 0';
                    break;
            }
        }
        /* ---------------------------------------------------------------------------------------------------------------------------------------- */

        $query->select(['sds_reg_registro.*', 'entidad']);

        $query->innerJoin('sds_reg_tipo', 'sds_reg_registro.idtipo=sds_reg_tipo.idtipo');

        $query->andFilterWhere([
            'idregistro' => $this->idregistro,
            'fecha_hora' => $this->fecha_hora,
            'idorganismo' => $this->idorganismo,
            'usuario_solicitante' => $this->usuario_solicitante,
            'usuario_derivacion' => $this->usuario_derivacion,
            'incidencia_relacionada' => $this->incidencia_relacionada,
            'sds_reg_registro.idtipo' => $this->idtipo,
            'fecha_ingreso' => $this->fecha_ingreso,
            'usuario_ingreso' => $this->usuario_ingreso,
            'fecha_solucion' => $this->fecha_solucion,
            'iddispositivo' => $this->iddispositivo,
            'entidad' => $this->entidad
        ]);

        $query->andFilterWhere(['like', 'problema', $this->problema])
            ->andFilterWhere(['like', 'registro_abierto', $this->registro_abierto])
            ->andFilterWhere(['like', 'equipo_detalle', $this->equipo_detalle])
            ->andFilterWhere(['like', 'ip', $this->ip])
            //Las dos siguientes son para el filtro en columns
            ->andWhere($sql_desde)
            ->andWhere($sql_hasta)
            ->andWhere($estado)
            ->andWhere($usuario_logueado);

        return $dataProvider;
    }
}
