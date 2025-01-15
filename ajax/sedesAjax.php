<?php

$peticionAjax = true;
require_once "../config/app.php";

/* recibe los datos de los furmularios. (los valores de POST estan en el name en los form) */
if (isset($_POST['sedes_nombre_reg']) || isset($_POST['sedes_id_del']) || isset($_POST['sedes_id_up'])) {
    
    /* ---------- instancia al controlador ----------- */
    require_once "../controladores/sedesControlador.php";
    $ins_sedes = new sedesControlador(); #se instancia al sedes controlador


    /* ---------- Agregar un sedes ------------ */
    if (isset($_POST['sedes_nombre_reg']) && isset($_POST['sedes_nombre_reg'])) {
        echo $ins_sedes->agregar_sedes_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Eliminar un sedes ------------ */
    if (isset($_POST['sedes_id_del'])) {/* recibe desde el paginador en el controlador */
        echo $ins_sedes->eliminar_sedes_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Actualizar un sedes ------------ */
    if (isset($_POST['sedes_id_up'])) {
        echo $ins_sedes->actualizar_sedes_controlador(); //con la -> accedemos a la funcion
    }


}
else {
    
    #si alguien quiere ingresar a sedesAjax.php desde la url
    session_start(['name'=>'instituto']); #definimos el nombre a la session (puede ser cualquier nombre)
    session_unset();#vaciar la sesion
    session_destroy();#para destruir o eliminar todas las variables
    Header("Location: ".URL."login/"); #para redireccionar al login
    exit();#para que no se ejecute codigos php
}