<?php

namespace app\models;
use app\models\Configuracion; 
use Yii;

/**
 * This is the model class for table "articulo".
 *
 * @property int $idarticulo
 * @property string|null $descripcion
 * @property int $idtipo
 * @property int|null $idmarca
 * @property string|null $modelo
 * @property int $idrubro
 * @property int $id_unidad_medida
 * @property int $activo
 * @property string|null $imagen
 */
class Articulo extends \yii\db\ActiveRecord
{
    public $imageFile;

    // Agregamos una propiedad pública para el campo de búsqueda global
    public $busquedaGlobal; 

    public static function tableName()
    {
        return 'articulo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idtipo', 'idrubro', 'id_unidad_medida', 'activo'], 'required'],
            [['idtipo', 'idmarca', 'idrubro', 'id_unidad_medida', 'activo'], 'integer'],
            [['descripcion'], 'string', 'max' => 500],
            [['modelo'], 'string', 'max' => 30],
            [['imagen'], 'string', 'max' => 100],
            // Agregamos la regla 'safe' para la nueva propiedad de búsqueda
            [['busquedaGlobal'], 'safe'],
            [['idtipo', 'idmarca', 'modelo', 'idrubro', 'id_unidad_medida'], 'unique', 
                'targetAttribute' => ['idtipo', 'idmarca', 'modelo', 'idrubro', 'id_unidad_medida'], 
                'message' => 'Ya existe un artículo con la misma combinación de Tipo, Marca, Modelo, Rubro y Unidad de Medida.'],
            
            /* [['idtipo'], 'unique', 'targetAttribute' => ['idtipo', 'idmarca', 'modelo', 'idrubro', 'id_unidad_medida'], 'message' => 'Ya existe un artículo con la misma combinación de Tipo, Marca, Modelo, Rubro y Unidad de Medida.'],
            [['idmarca'], 'unique', 'targetAttribute' => ['idtipo', 'idmarca', 'modelo', 'idrubro', 'id_unidad_medida'], 'message' => 'Ya existe un artículo con la misma combinación de Tipo, Marca, Modelo, Rubro y Unidad de Medida.'],
            [['modelo'], 'unique', 'targetAttribute' => ['idtipo', 'idmarca', 'modelo', 'idrubro', 'id_unidad_medida'], 'message' => 'Ya existe un artículo con la misma combinación de Tipo, Marca, Modelo, Rubro y Unidad de Medida.'],
            [['idrubro'], 'unique', 'targetAttribute' => ['idtipo', 'idmarca', 'modelo', 'idrubro', 'id_unidad_medida'], 'message' => 'Ya existe un artículo con la misma combinación de Tipo, Marca, Modelo, Rubro y Unidad de Medida.'],
            [['id_unidad_medida'], 'unique', 'targetAttribute' => ['idtipo', 'idmarca', 'modelo', 'idrubro', 'id_unidad_medida'], 'message' => 'Ya existe un artículo con la misma combinación de Tipo, Marca, Modelo, Rubro y Unidad de Medida.'], */
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idarticulo' => 'Id',
            'descripcion' => 'Descripcion',
            'idtipo' => 'Tipo Articulo',
            'idmarca' => 'Marca',
            'modelo' => 'Modelo',
            'idrubro' => 'Rubro',
            'id_unidad_medida' => 'Unidad Medida',
            'activo' => 'Activo',
            'imagen' => 'Imagen',
            'busquedaGlobal' => 'Buscar', // Etiqueta para el nuevo campo
        ];
    }
    public static function get_articulos($modulo = '')
    {
        $filtro = $modulo ? " and idarticulo in (SELECT idarticulo from $modulo)" : '';
        $sql = "SELECT  a.idarticulo,concat( ct.descripcion ,' ', cm.descripcion ,' ' ,a.modelo ,' ' , cum.descripcion ,' ', a.descripcion) as descripcion
                from articulo a 
                join configuracion ct on ct.id_configuracion=a.idtipo
                join configuracion cm on cm.id_configuracion=a.idmarca
                join configuracion cum on cum.id_configuracion=a.id_unidad_medida
                where a.activo=1 $filtro
                order by ct.descripcion,cm.descripcion,a.modelo,cum.descripcion,a.descripcion";
        $articulos = Articulo::findBySql($sql)->all();
        return $articulos;
    }

    public static function get_articulos_rubro($idrubro = null)
    {
        $filtro = $idrubro ? " and idrubro = $idrubro" : '';
        $sql = "SELECT  a.idarticulo,concat( ct.descripcion ,' ', cm.descripcion ,' ' ,a.modelo ,' ' , cum.descripcion ,' ', a.descripcion) as descripcion
                from articulo a 
                join configuracion ct on ct.id_configuracion=a.idtipo
                join configuracion cm on cm.id_configuracion=a.idmarca
                join configuracion cum on cum.id_configuracion=a.id_unidad_medida
                where a.activo=1 $filtro
                order by ct.descripcion,cm.descripcion,a.modelo,cum.descripcion,a.descripcion";
        $articulos = Articulo::findBySql($sql)->all();
        return $articulos;
    }

    public static function get_articulos_rubro_disponible($idrubro = null)
    {
        switch ($idrubro) {
            case 115:
                $tabla_ingreso = "stock_informatica_ingreso_detalle";
                $tabla_egreso = "stock_informatica_egreso_detalle";
                break;

            case 116:
                $tabla_ingreso = "stock_deposito_ingreso_detalle";
                $tabla_egreso = "stock_deposito_egreso_detalle";
                break;
        }



        $sql_disponible = "SELECT 
                            COALESCE(
                                (SELECT SUM(sii.cantidad) 
                                FROM $tabla_ingreso sii 
                                WHERE sii.idarticulo = a.idarticulo), 0
                            )
                            -
                            COALESCE(
                                (SELECT SUM(sie.cantidad) 
                                FROM $tabla_egreso sie 
                                WHERE sie.idarticulo = a.idarticulo), 0
                            )";


        $sql = "SELECT 
                    a.idarticulo,
                    CONCAT(
                        ct.descripcion, ' ', 
                        cm.descripcion, ' ', 
                        a.modelo, ' ', 
                        cum.descripcion, ' ', 
                        a.descripcion, ' (disponible ', 
                        ($sql_disponible), 
                        ')'
                    ) AS descripcion
                FROM articulo a
                JOIN configuracion ct ON ct.id_configuracion = a.idtipo
                JOIN configuracion cm ON cm.id_configuracion = a.idmarca
                JOIN configuracion cum ON cum.id_configuracion = a.id_unidad_medida
                WHERE a.activo = 1

                AND ($sql_disponible) > 0
                ORDER BY ct.descripcion, cm.descripcion, a.modelo, cum.descripcion, a.descripcion
            ";

        $articulos = Articulo::findBySql($sql)->all();
        return $articulos;
    }



    public static function get_articulo($id)
    {
        $sql = "SELECT  a.idarticulo,concat( ct.descripcion ,' ', cm.descripcion ,' ' ,a.modelo ,' ' , cum.descripcion ,' ', a.descripcion) as descripcion
                               from articulo a 
                               join configuracion ct on ct.id_configuracion=a.idtipo
                               join configuracion cm on cm.id_configuracion=a.idmarca
                               join configuracion cum on cum.id_configuracion=a.id_unidad_medida
                               where a.activo=1 and a.idarticulo = $id
                               order by ct.descripcion,cm.descripcion,a.modelo,cum.descripcion,a.descripcion";
        $articulo = Articulo::findBySql($sql)->one();
        return $articulo;
    }


     // Relaciones:
     public function getIdtipo0() // Este nombre es común si Gii lo autogenera. Puedes cambiarlo a getTipo()
     {
         return $this->hasOne(Configuracion::class, ['id_configuracion' => 'idtipo']);
     }
 
     public function getIdmarca0() // O getMarca()
     {
         return $this->hasOne(Configuracion::class, ['id_configuracion' => 'idmarca']);
     }
 
     public function getIdrubro0() // O getRubro()
     {
         return $this->hasOne(Configuracion::class, ['id_configuracion' => 'idrubro']);
     }
 
     public function getIdUnidadMedida() // O getUnidadMedida()
     {
         return $this->hasOne(Configuracion::class, ['id_configuracion' => 'id_unidad_medida']);
     }
}
