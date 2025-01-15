<?php

#require_once porque va a ser requerido una sola vez
require_once "./modelos/vistasModelo.php";

#extends porque hereda o extiende de vistasModelo
class vistasControlador extends vistasModelo{

    /* ------ controlador para obtener plantilla ------ */

    #public porque todos los controladores son publicos
    public function obtenerPantillaControlador(){
        return require_once "./vistas/plantilla.php";
    }

    /* ------ controlador para obtener vostas ------ */

    public function obtenerVistasControlador(){
        if (isset($_GET['views'])) { #views porque asi esta en .htaccess

            #explode() divide un string mediante parametros
            $ruta = explode("/", $_GET['views']); #separamos los valores con /
            $respuesta = vistasModelo::obtenerVistasModelo($ruta[0]);#si ponemos static a vistasModelo accedeomos solo con ::
        }
        else{
            $respuesta = "login";
        }
        return $respuesta;
    }

}