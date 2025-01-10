<?php

namespace app\models;

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
            [['descripcion'], 'string', 'max' => 45],
            [['modelo'], 'string', 'max' => 30],
            [['imagen'], 'string', 'max' => 100],
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
        $filtro = $idrubro ? " AND a.idrubro = $idrubro" : '';

        $sql_disponible = "SELECT 
                            COALESCE(
                                (SELECT SUM(sii.cantidad) 
                                FROM stock_informatica_ingreso_detalle sii 
                                WHERE sii.idarticulo = a.idarticulo), 0
                            )
                            -
                            COALESCE(
                                (SELECT SUM(sie.cantidad) 
                                FROM stock_informatica_egreso_detalle sie 
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
                $filtro
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
}
