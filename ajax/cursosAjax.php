<?php

$peticionAjax = true;
require_once "../config/app.php";

/* recibe los datos de los furmularios. (los valores de POST estan en el name en los form) */
if (isset($_POST['cursos_codigo_reg']) || isset($_POST['cursos_id_del']) || isset($_POST['cursos_id_up'])) {
    
    /* ---------- instancia al controlador ----------- */
    require_once "../controladores/cursosControlador.php";
    $ins_cursos = new cursosControlador(); #se instancia al cursos controlador
    

    /* ---------- Agregar un cursos ------------ */
    if (isset($_POST['cursos_codigo_reg'])) {
        echo $ins_cursos->agregar_cursos_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Eliminar un cursos ------------ */
    if (isset($_POST['cursos_id_del'])) {/* recibe desde el paginador en el controlador */
        echo $ins_cursos->eliminar_cursos_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Actualizar un cursos ------------ */
    if (isset($_POST['cursos_id_up'])) {
        echo $ins_cursos->actualizar_cursos_controlador(); //con la -> accedemos a la funcion
    }


}
else {
    
    #si alguien quiere ingresar a cursosAjax.php desde la url
    session_start(['name'=>'instituto']); #definimos el nombre a la session (puede ser cualquier nombre)
    session_unset();#vaciar la sesion
    session_destroy();#para destruir o eliminar todas las variables
    Header("Location: ".URL."login/"); #para redireccionar al login
    exit();#para que no se ejecute codigos php
}