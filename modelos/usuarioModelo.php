<?php
/* se encarga de recibir los datos que son enviados desde los formularios */
require_once "mainModel.php";

class usuarioModelo extends mainModel {

    /* ---------- Modelo agreagar usuario ----------- */
    protected static function agregar_usuario_modelo($datos){
        $sql= mainModel::conectar()->prepare("INSERT INTO usuario(
        usuario_dni, usuario_nombre, usuario_apellido, usuario_telefono,
        usuario_direccion, usuario_email, usuario_usuario, usuario_clave,
        usuario_estado, usuario_privilegio)
        VALUES(:DNI, :Nombre, :Apellido, :Telefono, :Direccion, :Email,
        :Usuario, :Clave, :Estado, :Privilegio)");

        // :DNI etc.  son denominados marcadores
        $sql->bindParam(":DNI", $datos['DNI']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Apellido", $datos['Apellido']);
        $sql->bindParam(":Telefono", $datos['Telefono']);
        $sql->bindParam(":Direccion", $datos['Direccion']);
        $sql->bindParam(":Email", $datos['Email']);
        $sql->bindParam(":Usuario", $datos['Usuario']);
        $sql->bindParam(":Clave", $datos['Clave']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Privilegio", $datos['Privilegio']);
        $sql->execute();

        return $sql;
    }

    /* ---------- Modelo eliminar usuario ----------- */
    protected static function eliminar_usuario_modelo($id){
        $sql = mainModel::conectar()->prepare("DELETE FROM usuario WHERE usuario_id=:ID");
        $sql->bindParam(":ID", $id);
        $sql->execute();

        return $sql;
    }

    /* ---------- Modelo datos del usuario ----------- */
    protected static function datos_usuario_modelo($tipo, $id){// $tipo de consulta
        if ($tipo == "Unico") {//unico se refiere a que se cargará los datos del usuario logueado para editarlo
            $sql = mainModel::conectar()->prepare("SELECT * FROM usuario WHERE usuario_id=:ID");
            $sql->bindParam(":ID", $id);
            $sql->execute();
            
        }
        elseif ($tipo="Conteo") {//Conteo para realizar un conteo de la cantidad de usuarios existentes, esos datos aparen en el HOME 
            $sql = mainModel::conectar()->prepare("SELECT usuario_id FROM usuario WHERE
            usuario_id!= '1'"); // distinto a 1 porque no se cuenta el administrador principal
        }
        $sql->execute();
        return $sql;
    }

    /* ---------- Modelo actualizar datos del usuario ----------- */
    protected static function actualizar_usuario_modelo($datos){// 
        $sql = mainModel::conectar()->prepare("UPDATE usuario SET
        usuario_dni=:DNI,
        usuario_nombre=:Nombre,
        usuario_apellido=:Apellido,
        usuario_telefono=:Telefono,
        usuario_direccion=:Direccion,
        usuario_email=:Email,
        usuario_usuario=:Usuario,
        usuario_clave=:Clave,
        usuario_estado=:Estado,
        usuario_privilegio=:Privilegio

        WHERE usuario_id=:ID
        ");

        $sql->bindParam(":DNI", $datos['DNI']);
        $sql->bindParam(":Nombre", $datos['Nombre']);
        $sql->bindParam(":Apellido", $datos['Apellido']);
        $sql->bindParam(":Telefono", $datos['Telefono']);
        $sql->bindParam(":Direccion", $datos['Direccion']);
        $sql->bindParam(":Email", $datos['Email']);
        $sql->bindParam(":Usuario", $datos['Usuario']);
        $sql->bindParam(":Clave", $datos['Clave']);
        $sql->bindParam(":Estado", $datos['Estado']);
        $sql->bindParam(":Privilegio", $datos['Privilegio']);
        $sql->bindParam(":ID", $datos['ID']);

        $sql->execute();
        return $sql;

    }
}