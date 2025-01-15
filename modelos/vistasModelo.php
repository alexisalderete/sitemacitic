<?php

class vistasModelo{
    /*-----modelo obtener vistas-----*/
    protected static function obtenerVistasModelo($vistas){

        #listas de vistas permitidas dentro del sistema
        $listaBlanca = ["home",
        "inscripciones-new", "inscripciones-list","inscripciones-search", "inscripciones-update",
        "pagos-new", "pagos-list","pagos-search", "pagos-update",
        "estudiantes-list","estudiantes-new","estudiantes-search","estudiantes-update",
        "sedes-list","sedes-new","sedes-search","sedes-update",
        "productos-list","productos-new","productos-search","productos-update",
        "cursos-list","cursos-new","cursos-search","cursos-update",
        "user-list","user-new","user-search","user-update"]; #array
        #in_array() comprueba si un valor esta en un array
        #si el valor que viene en la url ($vistas) esta dentro de $listaBlanca
        if (in_array($vistas, $listaBlanca)) {
            
            #is_file() comprueba si existe un archivo
            if(is_file("./vistas/contenidos/".$vistas."-view.php")){ # "-view" debe coincidir con los "...-view" de la carpeta contenido
                $contenido = "./vistas/contenidos/".$vistas."-view.php"; # "-view" debe coincidir con los "...-view" de la carpeta contenido
            }else{
                $contenido = "404";
            }
        }
        elseif($vistas=="login" || $vistas=="index"){
            $contenido = "login";
        }
        else{
            $contenido = "404";
        }
        return $contenido;
    }
}