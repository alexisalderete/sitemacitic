<?php
/* se encarga de recibir los datos que son enviados desde los formularios */
require_once "mainModel.php";

class cursosModelo extends mainModel {

    /* ---------- Modelo agreagar cursos ----------- */
    protected static function agregar_cursos_modelo($datos){
        $sql = mainModel::conectar()->prepare("INSERT INTO cursos(
            cursos_codigo, cursos_nombre, cursos_duracion, cursos_estado, cursos_detalle)
            VALUES(:Codigo, :Nombre, :Duracion, 'Habilitado', :Detalle);");

        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Duracion", $datos['Duracion']);
        //$sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Detalle", $datos['Detalle']);
        
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo agreagar detalles del curso ----------- */
    protected static function agregar_detalle_cursos_modelo($datos){
        $sql = mainModel::conectar()->prepare("INSERT INTO cursos_sedes(
            cursos_id, sedes_id, cursos_precio, cursos_mensualidad, cursos_cupos)
            VALUES(:Cursos, :Sedes, :Precio,:Mensualidad, :Cupos);");
    
        $sql->bindParam(":Cursos", $datos['Cursos']);
        $sql->bindParam(":Sedes", $datos['Sedes']);
        $sql->bindParam(":Precio", $datos['Precio']);
        $sql->bindParam(":Mensualidad", $datos['Mensualidad']);
        $sql->bindParam(":Cupos", $datos['Cupos']);
        
        $sql->execute();
        return $sql;
    }


    /* ---------- Modelo eliminar cursos ----------- */
    protected static function eliminar_cursos_modelo($id, $tipo){
        if ($tipo == "Cursos"){
            $sql = mainModel::conectar()->prepare("DELETE FROM cursos WHERE cursos_id=:ID");
        }
        elseif ($tipo == "Detalles"){
            $sql = mainModel::conectar()->prepare("DELETE FROM cursos_sedes WHERE cursos_id=:ID");
        }

        $sql->bindParam(":ID", $id);
        $sql->execute();

        return $sql;
    }

    /* ---------- Modelo datos del curso ----------- */
    protected static function datos_cursos_modelo($tipo, $id){// $tipo de consulta
        if ($tipo == "Unico") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM cursos
                INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
                INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id WHERE cursos.cursos_id=:ID");
            $sql->bindParam(":ID", $id);
            $sql->execute();
            
        }
        elseif ($tipo="Conteo") {//Conteo para realizar un conteo de la cantidad de cursoss existentes, esos datos aparecen en el HOME 
            $sql = mainModel::conectar()->prepare("SELECT cursos_id FROM cursos");
        }
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo actualizar datos del cursos ----------- */
    protected static function actualizar_cursos_modelo($datos){//
        
        $sql = mainModel::conectar()->prepare("UPDATE cursos SET
        cursos_codigo = :Codigo,
        cursos_nombre = :Nombre,
        cursos_duracion = :Duracion,
        cursos_estado = :Estado,
        cursos_detalle = :Detalle
        WHERE cursos_id = :ID
        ");

        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Duracion", $datos['Duracion']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Detalle", $datos['Detalle']);
        $sql->bindParam(":ID", $datos['ID']);

        $sql->execute();
        return $sql;

    }

    /* ---------- Modelo actualizar datos de cursos_sedes ----------- */
    protected static function actualizar_detalle_cursos_modelo($datos){//
        
        $sql = mainModel::conectar()->prepare("UPDATE cursos_sedes SET
        sedes_id = :Sedes,
        cursos_precio = :Precio,
        cursos_mensualidad = :Mensualidad,
        cursos_cupos = :Cupos
        WHERE cursos_id = :ID
        ");

        $sql->bindParam(":Sedes", $datos['Sedes']);
        $sql->bindParam(":Precio", $datos['Precio']);
        $sql->bindParam(":Mensualidad", $datos['Mensualidad']);
        $sql->bindParam(":Cupos", $datos['Cupos']);
        $sql->bindParam(":ID", $datos['ID']);

        $sql->execute();
        return $sql;

    }


}