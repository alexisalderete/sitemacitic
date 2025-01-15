<?php
/* se encarga de recibir los datos que son enviados desde los formularios */
require_once "mainModel.php";

class pagosModelo extends mainModel {

    /* ---------- Modelo agreagar pagos ----------- */
    protected static function agregar_pagos_modelo($datos){
        $sql = mainModel::conectar()->prepare("INSERT INTO pagos(
            pagos_codigo, pagos_fecha, pagos_hora,
            pagos_monto, pagos_estado, usuario_id, inscripciones_codigo)
            VALUES(:Codigo, :Fecha, :Hora, :Monto, 'Pendiente', :Usuario, :Inscripciones);");
    
        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Fecha", $datos['Fecha']);
        $sql->bindParam(":Hora", $datos['Hora']);
        $sql->bindParam(":Monto", $datos['Monto']);
        //$sql->bindParam(":Mensualidad", $datos['Mensualidad']);
        // $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Usuario", $datos['Usuario']);
        $sql->bindParam(":Inscripciones", $datos['Inscripciones']);
        
        $sql->execute();
        return $sql;
    }
    

    /* ---------- Modelo eliminar pagos ----------- */
    protected static function eliminar_pagos_modelo($codigo, $tipo){
        if ($tipo == "pagos") {
            $sql = mainModel::conectar()->prepare("DELETE FROM pagos WHERE pagos_codigo=:Codigo");
        }
        /*elseif ($tipo == "Pagos") {
            $sql = mainModel::conectar()->prepare("DELETE FROM pagos WHERE pagos_codigo=:Codigo");
        }*/

        $sql->bindParam(":Codigo", $codigo);
        $sql->execute();

        return $sql;
    }

    /* ---------- Modelo datos del curso ----------- */
    protected static function datos_pagos_modelo($tipo, $id){// $tipo de consulta
        if ($tipo == "Unico") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM pagos WHERE pagos_id=:ID");
            $sql->bindParam(":ID", $id);
            
        }
        elseif($tipo == "Reporte"){
            $sql = mainModel::conectar()->prepare("SELECT * FROM inscripciones
            INNER JOIN estudiantes ON inscripciones.estudiantes_id = estudiantes.estudiantes_id
            INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id
            INNER JOIN pagos ON inscripciones.inscripciones_codigo = pagos.inscripciones_codigo
            WHERE pagos_id=:ID");
            $sql->bindParam(":ID", $id);
        }
        
        elseif ($tipo == "Conteo") {//Conteo para realizar un conteo de la cantidad de pagoss existentes, esos datos aparen en el HOME 
            $sql = mainModel::conectar()->prepare("SELECT pagos_id FROM pagos");
        }

        elseif ($tipo == "Cuotas") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM cuotas
            INNER JOIN inscripciones ON inscripciones.inscripciones_id = cuotas.inscripciones_id
            WHERE cuotas.inscripciones_id = :ID");
            $sql->bindParam(":ID", $id);
        }

        
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo actualizar datos del pagos ----------- */
    protected static function actualizar_pagos_modelo($datos){// 
        $sql = mainModel::conectar()->prepare("UPDATE pagos SET
        pagos_fecha = :Fecha,
        pagos_hora = :Hora,
        pagos_costo = :Costo,
        pagos_mensualidad = :Mensualidad,
        pagos_estado = :Estado
        WHERE pagos_codigo = :Codigo
        ");

        $sql->bindParam(":Fecha", $datos['Fecha']);
        $sql->bindParam(":Hora", $datos['Hora']);
        $sql->bindParam(":Costo", $datos['Costo']);
        $sql->bindParam(":Mensualidad", $datos['Mensualidad']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Codigo", $datos['Codigo']);

        $sql->execute();
        return $sql;
    }




    
}


