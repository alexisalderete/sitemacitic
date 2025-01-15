<?php
/* se encarga de recibir los datos que son enviados desde los formularios */
require_once "mainModel.php";

class sedesModelo extends mainModel {

    /* ---------- Modelo agreagar sedes ----------- */
    protected static function agregar_sedes_modelo($datos){
        $sql = mainModel::conectar()->prepare("INSERT INTO sedes(
            sedes_nombre, sedes_descripcion)
            VALUES(:Nombre, :Descripcion);");
    
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        
        $sql->execute();
        
        return $sql;
    
    }

    /* ---------- Modelo eliminar sedes ----------- */
    protected static function eliminar_sedes_modelo($id){
        $sql = mainModel::conectar()->prepare("DELETE FROM sedes WHERE sedes_id=:ID");
        $sql->bindParam(":ID", $id);
        $sql->execute();

        return $sql;
    }

    /* ---------- Modelo datos del estudiante ----------- */
    protected static function datos_sedes_modelo($tipo, $id){// $tipo de consulta
        if ($tipo == "Unico") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM sedes WHERE sedes_id=:ID");
            $sql->bindParam(":ID", $id);
            $sql->execute();
            
        }
        elseif ($tipo="Conteo") {//Conteo para realizar un conteo de la cantidad de sedess existentes, esos datos aparen en el HOME 
            $sql = mainModel::conectar()->prepare("SELECT sedes_id FROM sedes");
        }
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo actualizar datos del sedes ----------- */
    protected static function actualizar_sedes_modelo($datos){// 
        $sql = mainModel::conectar()->prepare("UPDATE sedes SET
        sedes_nombre = :Nombre,
        sedes_descripcion = :Descripcion
        WHERE sedes_id = :ID
        ");

        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Descripcion", $datos['Descripcion']);
        $sql->bindParam(":ID", $datos['ID']);

        $sql->execute();
        return $sql;

    }
}