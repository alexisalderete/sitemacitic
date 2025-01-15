<?php

$peticionAjax = true;
require_once "../config/app.php";

/* recibe los datos de los furmularios. (los valores de POST estan en el name en los form) */
if (isset($_POST['buscar_estudiantes']) ||
    isset($_POST['id_agregar_estudiantes']) || 
    isset($_POST['id_eliminar_estudiantes']) ||
    isset($_POST['buscar_cursos']) ||
    isset($_POST['id_agregar_cursos']) ||
    isset($_POST['id_eliminar_cursos']) ||


    // isset($_POST['buscar_productos']) ||
    // isset($_POST['id_agregar_productos']) ||
    // isset($_POST['id_eliminar_productos']) ||


    isset($_POST['inscripciones_fecha_inicio_reg']) ||
    isset($_POST['inscripciones_codigo_del']) ||

    isset($_POST['inscripciones_codigo_up'])) {
        
    /* ---------- instancia al controlador ----------- */ 
    require_once "../controladores/inscripcionesControlador.php";
    $ins_inscripciones = new inscripcionesControlador(); #se instancia al inscripciones controlador
    

    /* ---------- Buscar estudiantes  ------------ */
    if (isset($_POST['buscar_estudiantes'])) {
        echo $ins_inscripciones->buscar_estudiantes_inscripciones_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- agregar estudiantes al formulario de inscripciones  ------------ */
    if (isset($_POST['id_agregar_estudiantes'])) {
        echo $ins_inscripciones->agregar_estudiantes_inscripciones_controlador(); //con la -> accedemos a la funcion
    }


    /* ---------- eliminar estudiantes al formulario de inscripciones  ------------ */
    if (isset($_POST['id_eliminar_estudiantes'])) {
        echo $ins_inscripciones->eliminar_estudiantes_inscripciones_controlador(); //con la -> accedemos a la funcion
    }




    /* ---------- Buscar cursos  ------------ */
    if (isset($_POST['buscar_cursos'])) {
        echo $ins_inscripciones->buscar_cursos_inscripciones_controlador(); //con la -> accedemos a la funcion
    }

     /* ---------- agregar cursos  ------------ */
    if (isset($_POST['id_agregar_cursos'])) {
        echo $ins_inscripciones->agregar_cursos_inscripciones_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- eliminar estudiantes al formulario de inscripciones  ------------ */
    if (isset($_POST['id_eliminar_cursos'])) {
        echo $ins_inscripciones->eliminar_cursos_inscripciones_controlador(); //con la -> accedemos a la funcion
    }



    /* ---------- Buscar productos  ------------ */
    // if (isset($_POST['buscar_productos'])) {
    //     echo $ins_inscripciones->buscar_productos_inscripciones_controlador(); //con la -> accedemos a la funcion
    // }

    //  /* ---------- agregar productos  ------------ */
    // if (isset($_POST['id_agregar_productos'])) {
    //     echo $ins_inscripciones->agregar_productos_inscripciones_controlador(); //con la -> accedemos a la funcion
    // }

    // /* ---------- eliminar estudiantes al formulario de inscripciones  ------------ */
    // if (isset($_POST['id_eliminar_productos'])) {
    //     echo $ins_inscripciones->eliminar_productos_inscripciones_controlador(); //con la -> accedemos a la funcion
    // }




    /* ---------- Agregar un inscripciones ------------ */
    if (isset($_POST['inscripciones_fecha_inicio_reg'])) {
        echo $ins_inscripciones->agregar_inscripciones_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Eliminar un inscripciones ------------ */
    if (isset($_POST['inscripciones_codigo_del'])) {/* recibe desde el paginador en el controlador */
        echo $ins_inscripciones->eliminar_inscripciones_controlador(); //con la -> accedemos a la funcion
    }






    /* ---------- Actualizar un inscripciones ------------ */
    if (isset($_POST['inscripciones_codigo_up'])) {
        echo $ins_inscripciones->actualizar_inscripciones_controlador(); //con la -> accedemos a la funcion
    }


}
else {
    #si alguien quiere ingresar a inscripcionesAjax.php desde la url
    session_start(['name'=>'instituto']); #definimos el nombre a la session (puede ser cualquier nombre)
    session_unset();#vaciar la sesion
    session_destroy();#para destruir o eliminar todas las variables
    Header("Location: ".URL."login/"); #para redireccionar al login
    exit();#para que no se ejecute codigos php
}