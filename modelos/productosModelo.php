<?php
/* se encarga de recibir los datos que son enviados desde los formularios */
require_once "mainModel.php";

class productosModelo extends mainModel {

    /* ---------- Modelo agreagar productos ----------- */
    protected static function agregar_productos_modelo($datos){
        $sql = mainModel::conectar()->prepare("INSERT INTO productos(
            productos_codigo, productos_nombre, productos_precio, productos_cantidad, productos_iva)
            VALUES(:Codigo, :Nombre, :Precio, :Cantidad, :Iva);");
    
        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Precio", $datos['Precio']);
        $sql->bindParam(":Cantidad", $datos['Cantidad']);
        $sql->bindParam(":Iva", $datos['Iva']);
        
        $sql->execute();
        
        return $sql;
    
    }

    /* ---------- Modelo eliminar productos ----------- */
    protected static function eliminar_productos_modelo($id){
        $sql = mainModel::conectar()->prepare("DELETE FROM productos WHERE productos_id=:ID");
        $sql->bindParam(":ID", $id);
        $sql->execute();

        return $sql;
    }

    /* ---------- Modelo datos del estudiante ----------- */
    protected static function datos_productos_modelo($tipo, $id){// $tipo de consulta
        if ($tipo == "Unico") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM productos WHERE productos_id=:ID");
            $sql->bindParam(":ID", $id);
            $sql->execute();
            
        }
        elseif ($tipo="Conteo") {//Conteo para realizar un conteo de la cantidad de productoss existentes, esos datos aparen en el HOME 
            $sql = mainModel::conectar()->prepare("SELECT productos_id FROM productos");
        }
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo actualizar datos del productos ----------- */
    protected static function actualizar_productos_modelo($datos){// 
        $sql = mainModel::conectar()->prepare("UPDATE productos SET
        productos_codigo = :Codigo,
        productos_nombre = :Nombre,
        productos_precio = :Precio,
        productos_cantidad = :Cantidad,
        productos_iva = :Iva
        WHERE productos_id = :ID
        ");

        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Precio", $datos['Precio']);
        $sql->bindParam(":Cantidad", $datos['Cantidad']);
        $sql->bindParam(":Iva", $datos['Iva']);
        $sql->bindParam(":ID", $datos['ID']);

        $sql->execute();
        return $sql;


    }
}