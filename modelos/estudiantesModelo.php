<?php
/* se encarga de recibir los datos que son enviados desde los formularios */
require_once "mainModel.php";

class estudiantesModelo extends mainModel {

    /* ---------- Modelo agreagar estudiantes ----------- */
    protected static function agregar_estudiantes_modelo($datos){
        $sql = mainModel::conectar()->prepare("INSERT INTO estudiantes(
            estudiantes_dni, estudiantes_nombre, estudiantes_apellido, estudiantes_telefono, estudiantes_direccion)
            VALUES(:DNI, :Nombre, :Apellido, :Telefono, :Direccion);");
    
        $sql->bindParam(":DNI", $datos['DNI']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Apellido", $datos['Apellido']);
        $sql->bindParam(":Telefono", $datos['Telefono']);
        $sql->bindParam(":Direccion", $datos['Direccion']);
        
        $sql->execute();
        
        return $sql;
    
    }

    /* ---------- Modelo eliminar estudiantes ----------- */
    protected static function eliminar_estudiantes_modelo($id){
        $sql = mainModel::conectar()->prepare("DELETE FROM estudiantes WHERE estudiantes_id=:ID");
        $sql->bindParam(":ID", $id);
        $sql->execute();

        return $sql;
    }

    /* ---------- Modelo datos del estudiante ----------- */
    protected static function datos_estudiantes_modelo($tipo, $id){// $tipo de consulta
        if ($tipo == "Unico") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM estudiantes WHERE estudiantes_id=:ID");
            $sql->bindParam(":ID", $id);
            $sql->execute();
            
        }
        elseif ($tipo="Conteo") {//Conteo para realizar un conteo de la cantidad de estudiantess existentes, esos datos aparen en el HOME 
            $sql = mainModel::conectar()->prepare("SELECT estudiantes_id FROM estudiantes");
        }
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo actualizar datos del estudiantes ----------- */
    protected static function actualizar_estudiantes_modelo($datos){// 
        $sql = mainModel::conectar()->prepare("UPDATE estudiantes SET
        estudiantes_dni = :DNI,
        estudiantes_nombre = :Nombre,
        estudiantes_apellido = :Apellido,
        estudiantes_telefono = :Telefono,
        estudiantes_direccion = :Direccion
        WHERE estudiantes_id = :ID
        ");

        $sql->bindParam(":DNI", $datos['DNI']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Apellido", $datos['Apellido']);
        $sql->bindParam(":Telefono", $datos['Telefono']);
        $sql->bindParam(":Direccion", $datos['Direccion']);
        $sql->bindParam(":ID", $datos['ID']);

        $sql->execute();
        return $sql;


    }
}