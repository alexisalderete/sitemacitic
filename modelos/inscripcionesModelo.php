<?php
/* se encarga de recibir los datos que son enviados desde los formularios */
require_once "mainModel.php";

class inscripcionesModelo extends mainModel {

    /* ---------- Modelo agregar inscripciones ----------- */
    protected static function agregar_inscripciones_modelo($datos){
        $sql = mainModel::conectar()->prepare("INSERT INTO inscripciones(
            inscripciones_codigo, inscripciones_fecha, inscripciones_hora,
            inscripciones_estado, estudiantes_id, cursos_id)
            VALUES(:Codigo, :Fecha, :Hora, :Estado, :Estudiantes, :Cursos);");
    
        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Fecha", $datos['Fecha']);
        $sql->bindParam(":Hora", $datos['Hora']);
        // $sql->bindParam(":Costo", $datos['Costo']);
        // $sql->bindParam(":Mensualidad", $datos['Mensualidad']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Estudiantes", $datos['Estudiantes']);
        $sql->bindParam(":Cursos", $datos['Cursos']);
        
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo disminuir cupos ----------- */
    protected static function disminuir_cupos_modelo($codigo){
        $sql = mainModel::conectar()->prepare("UPDATE cursos_sedes SET cursos_cupos = cursos_cupos - 1 WHERE cursos_id = :Codigo");
        $sql->bindParam(":Codigo", $codigo);
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo aumentar cupos ----------- */
    protected static function aumentar_cupos_modelo($codigo){
        $sql = mainModel::conectar()->prepare("UPDATE cursos_sedes SET cursos_cupos = cursos_cupos + 1 WHERE cursos_id = :Codigo");
        $sql->bindParam(":Codigo", $codigo);
        $sql->execute();
        return $sql;
    }

     /* ---------- agregar nueva cuota al insetar inscripciones ----------- */

    protected static function agregar_cuota_modelo($id_inscripciones) {
        // Meses de vencimiento: desde marzo hasta noviembre
        $meses = ['Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre'];
    
        // Obtener el año actual
        $anio_actual = date("Y");
    
        // Generar dinámicamente las fechas de vencimiento
        $fechas_vencimiento = [];
        for ($i = 4; $i <= 12; $i++) { // Meses de marzo (3) a noviembre (11)
            $fechas_vencimiento[] = date("Y-m-d", strtotime("$anio_actual-$i-05"));
        }
    
        // Iniciar la conexión
        $conexion = mainModel::conectar();
    
        // Construir la consulta con múltiples valores
        $valores = [];
        $params = [];
        foreach ($meses as $index => $mes) {
            $valores[] = "(?, ?, ?, ?, ?, ?, ?)";
            $params[] = $id_inscripciones; // inscripciones_id
            $params[] = $mes;              // cuotas_mes
            $params[] = $anio_actual;      // cuotas_anio
            $params[] = 0;                 // cuotas_monto_pagado
            $params[] = $fechas_vencimiento[$index]; // cuotas_fecha_venci
            $params[] = null;              // cuotas_fecha_pago
            $params[] = 'Pendiente';       // cuotas_estado
        }
    
        // Unir los valores para el INSERT
        $sql = "INSERT INTO cuotas (
                    inscripciones_id, cuotas_mes, cuotas_anio, cuotas_monto_pagado, cuotas_fecha_venci, cuotas_fecha_pago, cuotas_estado
                ) VALUES " . implode(", ", $valores);
    
        // Preparar la consulta
        $query = $conexion->prepare($sql);
    
        // Ejecutar la consulta con los parámetros
        $resultado = $query->execute($params);
    
        return $resultado;
    }
    
    
    


    // protected static function agregar_cuota_modelo($id_inscriciones) {
    //     $meses = ['Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre'];

    //     $anio_actual = date("Y");

    //     $fecha_venci = ['2025-04-05', '2025-05-05', '2025-06-05', '2025-07-05', '2025-08-05', '2025-09-05', '2025-10-05', '2025-11-05', '2025-12-05'];



    
    //     // Iniciar la conexión
    //     $conexion = mainModel::conectar();
    
    //     // Construir la consulta con múltiples valores
    //     $valores = [];
    //     $params = [];
    //     foreach ($meses as $index => $mes) {
    //         $valores[] = "(?, ?, ?, ?, ?, ?, ?)";
    //         $params[] = $id_inscriciones; // inscripciones_id
    //         $params[] = $mes;                   // cuotas_mes
    //         $params[] = $anio_actual;           // cuotas_anio
    //         $params[] = 0;                      // cuotas_monto_pagado

    //         $params[] = $fecha_venci;           // cuotas_fecha_venci
    //         $params[] = null;                   // cuotas_fecha_pago

    //         $params[] = 'Pendiente';            // cuotas_estado
    //     }
    
    //     // Unir los valores para el INSERT
    //     $sql = "INSERT INTO cuotas (
    //                 inscripciones_id, cuotas_mes, cuotas_anio, cuotas_monto_pagado, cuotas_fecha_venci, cuotas_fecha_pago, cuotas_estado
    //             ) VALUES " . implode(", ", $valores);
    
    //     // Preparar la consulta
    //     $query = $conexion->prepare($sql);
    
    //     // Ejecutar la consulta con los parámetros
    //     $resultado = $query->execute($params);
    
    //     return $resultado;
    // }

    
    

    /* ---------- Modelo eliminar inscripciones ----------- */
    protected static function eliminar_inscripciones_modelo($codigo, $tipo){
        if ($tipo == "Inscripciones") {
            $sql = mainModel::conectar()->prepare("DELETE FROM inscripciones WHERE inscripciones_codigo=:Codigo");
        }
        elseif ($tipo == "Pagos") {
            $sql = mainModel::conectar()->prepare("DELETE FROM pagos WHERE inscripciones_codigo=:Codigo");
        }

        $sql->bindParam(":Codigo", $codigo);
        $sql->execute();

        return $sql;
    }

    /* ---------- Modelo datos del curso ----------- */
    protected static function datos_inscripciones_modelo($tipo, $id){// $tipo de consulta
        if ($tipo == "Unico") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM inscripciones WHERE inscripciones_id=:ID");
            $sql->bindParam(":ID", $id);
            
        }
        elseif ($tipo == "Pagos") {//para seleccionar las inscripciones al formulario de pago
            $sql = mainModel::conectar()->prepare("SELECT * FROM inscripciones
            INNER JOIN estudiantes ON inscripciones.estudiantes_id = estudiantes.estudiantes_id
            INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id
            WHERE inscripciones_id=:ID");
            $sql->bindParam(":ID", $id);
            
        }

        elseif ($tipo=="Conteo") {//Conteo para realizar un conteo de la cantidad de inscripcioness existentes, esos datos aparen en el HOME 
            $sql = mainModel::conectar()->prepare("SELECT inscripciones_id FROM inscripciones");
        }

        elseif ($tipo == "Unico2") {
            $sql = mainModel::conectar()->prepare("SELECT * FROM inscripciones WHERE inscripciones_codigo=:ID");
            $sql->bindParam(":ID", $id);
        }
        
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo actualizar datos del inscripciones ----------- */
    protected static function actualizar_inscripciones_modelo($datos){// 
        $sql = mainModel::conectar()->prepare("UPDATE inscripciones SET
        inscripciones_fecha = :Fecha,
        inscripciones_hora = :Hora,
        -- inscripciones_costo = :Costo,
        -- inscripciones_mensualidad = :Mensualidad,
        inscripciones_estado = :Estado,
        cursos_id = :Cursos
        WHERE inscripciones_codigo = :Codigo
        ");

        $sql->bindParam(":Fecha", $datos['Fecha']);
        $sql->bindParam(":Hora", $datos['Hora']);
        // $sql->bindParam(":Costo", $datos['Costo']);
        // $sql->bindParam(":Mensualidad", $datos['Mensualidad']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Codigo", $datos['Codigo']);
        $sql->bindParam(":Cursos", $datos['Cursos']);

        $sql->execute();
        return $sql;
    }
    
}