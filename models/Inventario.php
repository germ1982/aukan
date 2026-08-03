<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventario".
 *
 * @property int $idinventario
 * @property int|null $idarticulo
 * @property int|null $iddispositivo
 * @property int|null $idempleado
 * @property int|null $idpersona
 * @property int|null $idestado
 * @property string|null $observacion 
 * @property int|null $activo
 * @property int|null $idtipo
 */
class Inventario extends \yii\db\ActiveRecord
{
    public $idpersona;
    public $origen_alta;

    // Atributos virtuales de CPU actualizados
    public $idmicro;
    public $idmother;
    public $idfuente;
    public $idso;
    public $total_ram_gb;
    public $total_disco_gb;
    public $componentes_cpu = []; // Array para los IDs de componentes (RAMs, Discos, etc.)


    // Atributos virtuales de Red
    public $tiene_red;
    public $es_cpu;
    public $idseñal;
    public $idtecnologia_señal;
    public $tiene_ip;
    public $idip;
    public $idpuerta_enlace;
    public $idmascara_red;
    public $iddns_red;
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'inventario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            // Atributos auxiliares/virtuales
            [['es_cpu', 'tiene_red', 'tiene_ip', 'total_ram_gb', 'total_disco_gb', 'idmother', 'idfuente'], 'integer'],

            // Validamos campos base de CPU solo si es_cpu == 1
            [['idmicro', 'idso'], 'required', 'when' => function ($model) {
                return $model->es_cpu == 1;
            }, 'whenClient' => "function (attribute, value) { return $('#input_es_cpu').val() == 1; }"],

            // Validamos campos de Red solo si tiene_red == 1
            [['idseñal', 'idtecnologia_señal'], 'required', 'when' => function ($model) {
                return $model->tiene_red == 1;
            }, 'whenClient' => "function (attribute, value) { return $('#input_tiene_red').val() == 1; }"],

            // Validamos campos de IP solo si tiene_ip == 1
            [['idip', 'idmascara_red', 'idpuerta_enlace', 'iddns_red'], 'required', 'when' => function ($model) {
                return $model->tiene_red == 1 && $model->tiene_ip == 1;
            }, 'whenClient' => "function (attribute, value) { return $('#input_tiene_ip').val() == 1; }"],

            [['idarticulo', 'iddispositivo', 'idempleado', 'idestado', 'activo', 'idpersona'], 'integer'],
            [['observacion'], 'string'],

            // Se agregan componentes_cpu, total_ram_gb y total_disco_gb a safe; se quitan idram_uno, idram_dos e iddisco viejos
            [['origen_alta', 'idmother', 'idfuente', 'idmicro', 'idso', 'total_ram_gb', 'total_disco_gb', 'componentes_cpu', 'tiene_red', 'es_cpu', 'idseñal', 'idtecnologia_señal', 'tiene_ip', 'idip', 'idpuerta_enlace', 'idmascara_red', 'iddns_red'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idinventario' => 'Id',
            'idarticulo' => 'Articulo',
            'iddispositivo' => 'Dispositivo Deposito',
            'idempleado' => 'Empleado a cargo',
            'idestado' => 'Estado',
            'observacion' => 'Observacion',
            'activo' => 'Activo',
            'idmother' => 'Placa Madre',
            'idmicro' => 'Microprocesador',
            'idfuente' => 'Fuente',
            'idso' => 'Sistema Operativo',
            'total_ram_gb' => 'Total RAM (GB)',
            'total_disco_gb' => 'Total Disco (GB)',
            'componentes_cpu' => 'Componentes de CPU',
            'tiene_red' => 'Red',
            'es_cpu' => 'CPU',
            'idseñal' => 'Señal',
            'idtecnologia_señal' => 'Tecnologia',
            'tiene_ip' => 'Tiene IP',
            'idip' => 'IP',
            'idpuerta_enlace' => 'Puerta de Enlace',
            'idmascara_red' => 'Máscara de Red',
            'iddns_red' => 'DNS de Red',
        ];
    }

    public static function get_por_dispositivo($iddispositivo)
    {
        $sql = "SELECT 
                a.idarticulo,

                CONCAT(ct.descripcion,' ',
                    cm.descripcion,' ',
                    a.modelo,' ',
                    cum.descripcion,' ',
                    a.descripcion
                ) AS descripcion
            FROM inventario i
            JOIN articulo a ON a.idarticulo = i.idarticulo
            JOIN configuracion ct ON ct.id_configuracion = a.idtipo
            JOIN configuracion cm ON cm.id_configuracion = a.idmarca
            JOIN configuracion cum ON cum.id_configuracion = a.id_unidad_medida
            where i.iddispositivo = $iddispositivo and i.activo = 1
            GROUP BY a.idarticulo
            ORDER BY ct.descripcion,
                    cm.descripcion,
                    a.modelo,
                    cum.descripcion,
                    a.descripcion;
                    ";
        return Articulo::findBySql($sql)->asArray()->all();
    }

    // En app\models\Inventario.php

    public static function getArticulosCpuIds()
    {
        return (new \yii\db\Query())
            ->select(['a.idarticulo'])
            ->from(['a' => 'articulo'])
            ->innerJoin(['c' => 'configuracion'], 'c.descripcion = CAST(a.idtipo AS CHAR)')
            ->where(['c.id_configuracion_tipo' => ConfiguracionTipo::TIPO_ARTICULO_CPU])
            ->column();
    }

    public static function getArticulosRedIds()
    {
        return (new \yii\db\Query())
            ->select(['a.idarticulo'])
            ->from(['a' => 'articulo'])
            ->innerJoin(['c' => 'configuracion'], 'c.descripcion = CAST(a.idtipo AS CHAR)')
            ->where(['c.id_configuracion_tipo' => ConfiguracionTipo::TIPO_ARTICULO_RED])
            ->column();
    }

    // En app\models\Inventario.php
    public static function obtenerIpAsignada($idinventario)
    {
        if (empty($idinventario)) return null;

        return (new \yii\db\Query())
            ->select(['i.idip'])
            ->from(['idr' => 'inventario_dispositivo_red'])
            ->innerJoin(['i' => 'informatica_ip'], 'i.iddispositivo_red = idr.iddispositivo_red')
            ->where(['idr.idinventario' => $idinventario])
            ->scalar() ?: null;
    }

    /**
     * Orquestador principal de guardado de relaciones
     */
    public function guardarRelacionesSecundarias($postData)
    {
        // 1. Guardar CPU si corresponde
        if ($this->es_cpu == 1) {
            if (!$this->guardarCpu($postData)) {
                return false;
            }
        }

        // 2. Guardar Red e IP si corresponde
        if ($this->tiene_red == 1) {
            if (!$this->guardarRedEIp($postData)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Guarda o actualiza los datos del procesador/hardware en inventario_cpu
     */
    /**
     * Guarda o actualiza los datos del procesador/hardware en inventario_cpu
     */
    /** @var yii\widgets\ActiveForm $form */
    /** @var app\models\Inventario $model */

    public function guardarCpu($postData)
    {
        $cpu = \app\models\InventarioCpu::findOne(['idinventario' => $this->idinventario])
            ?? new \app\models\InventarioCpu();

        $cpu->idinventario     = $this->idinventario;
        $cpu->idmicro          = $postData['Inventario']['idmicro'] ?? null;
        $cpu->idmother         = $postData['Inventario']['idmother'] ?? null;
        $cpu->idfuente         = $postData['Inventario']['idfuente'] ?? null;
        $cpu->total_ram_gb     = $postData['Inventario']['total_ram_gb'] ?? null;
        $cpu->total_disco_gb   = $postData['Inventario']['total_disco_gb'] ?? null;
        $cpu->idso             = $postData['Inventario']['idso'] ?? null;

        if (!$cpu->save()) {
            $this->addErrors($cpu->getErrors());
            return false;
        }

        // Procesamiento de componentes (RAMs, Discos, Placa de Video)
        $componentes = $postData['Inventario']['componentes_cpu'] ?? [];
        if (!empty($componentes) && is_array($componentes)) {
            // Limpiamos los componentes previos de este CPU usando 'idcpu'
            \app\models\InventarioCpuComponente::deleteAll(['idcpu' => $cpu->idcpu]);

            foreach ($componentes as $idarticulo) {
                if (!empty($idarticulo)) {
                    $articuloObj = \app\models\Articulo::findOne($idarticulo);

                    $comp = new \app\models\InventarioCpuComponente();
                    $comp->idcpu = $cpu->idcpu;
                    $comp->idarticulo = $idarticulo;

                    // Asignamos el idtipo del artículo si existe
                    if ($articuloObj) {
                        $comp->id_tipo_componente = $articuloObj->idtipo;
                    }

                    if (!$comp->save()) {
                        $this->addErrors($comp->getErrors());
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Guarda la interfaz de red y deriva el guardado de la IP
     */
    public function guardarRedEIp($postData)
    {
        $red = \app\models\InventarioDispositivoRed::findOne(['idinventario' => $this->idinventario])
            ?? new \app\models\InventarioDispositivoRed();

        $red->idinventario   = $this->idinventario;
        $red->tipo_senal      = $postData['Inventario']['idseñal'] ?? null;
        $red->tipo_tecnologia = $postData['Inventario']['idtecnologia_señal'] ?? null;

        if (!$red->save()) {
            $this->addErrors($red->getErrors());
            return false;
        }

        // Si además tiene IP seleccionada, delegamos a la función de asignación de IP
        if ($this->tiene_ip == 1 && !empty($postData['Inventario']['idip'])) {
            return $this->asignarIp($red->iddispositivo_red, $postData);
        }

        return true;
    }

    /**
     * Asigna la IP seleccionada al dispositivo de red
     */
    public function asignarIp($iddispositivo_red, $postData)
    {
        $ip = \app\models\InformaticaIp::findOne($postData['Inventario']['idip']);

        if (!$ip) {
            $this->addError('idip', 'La IP seleccionada no existe.');
            return false;
        }

        $ip->iddispositivo_red = $iddispositivo_red;
        $ip->mascara           = $postData['Inventario']['idmascara_red'] ?? null;
        $ip->puerta_enlace     = $postData['Inventario']['idpuerta_enlace'] ?? null;

        if (!$ip->save()) {
            $this->addErrors($ip->getErrors());
            return false;
        }

        return true;
    }
}
