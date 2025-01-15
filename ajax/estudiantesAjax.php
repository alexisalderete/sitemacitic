<?php

$peticionAjax = true;
require_once "../config/app.php";

/* recibe los datos de los furmularios. (los valores de POST estan en el name en los form) */
if (isset($_POST['estudiantes_dni_reg']) || isset($_POST['estudiantes_id_del']) || isset($_POST['estudiantes_id_up'])) {
    
    /* ---------- instancia al controlador ----------- */
    require_once "../controladores/estudiantesControlador.php";
    $ins_estudiantes = new estudiantesControlador(); #se instancia al estudiantes controlador


    /* ---------- Agregar un estudiantes ------------ */
    if (isset($_POST['estudiantes_dni_reg']) && isset($_POST['estudiantes_nombre_reg'])) {
        echo $ins_estudiantes->agregar_estudiantes_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Eliminar un estudiantes ------------ */
    if (isset($_POST['estudiantes_id_del'])) {/* recibe desde el paginador en el controlador */
        echo $ins_estudiantes->eliminar_estudiantes_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Actualizar un estudiantes ------------ */
    if (isset($_POST['estudiantes_id_up'])) {
        echo $ins_estudiantes->actualizar_estudiantes_controlador(); //con la -> accedemos a la funcion
    }


}
else {
    
    #si alguien quiere ingresar a estudiantesAjax.php desde la url
    session_start(['name'=>'instituto']); #definimos el nombre a la session (puede ser cualquier nombre)
    session_unset();#vaciar la sesion
    session_destroy();#para destruir o eliminar todas las variables
    Header("Location: ".URL."login/"); #para redireccionar al login
    exit();#para que no se ejecute codigos php
}