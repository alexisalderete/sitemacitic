<?php

$peticionAjax = true;
require_once "../config/app.php";

/* recibe los datos de los furmularios. (los valores de POST estan en el name en los form) */
if (isset($_POST['usuario_dni_reg']) || isset($_POST['usuario_id_del']) || isset($_POST['usuario_id_up']) ) {
    
    /* ---------- instancia al controlador ----------- */
    require_once "../controladores/usuarioControlador.php";
    $ins_usuario = new usuarioControlador(); #se instancia al usuario controlador

    /* ---------- Agregar un usuario ------------ */
    if (isset($_POST['usuario_dni_reg']) && isset($_POST['usuario_nombre_reg'])) {
        echo $ins_usuario->agregar_usuario_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Eliminar un usuario ------------ */
    if (isset($_POST['usuario_id_del'])) {/* recibe desde el paginador en el controlador */
        echo $ins_usuario->eliminar_usuario_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Actualizar un usuario ------------ */
    if (isset($_POST['usuario_id_up'])) {
        echo $ins_usuario->actualizar_usuario_controlador(); //con la -> accedemos a la funcion
    }


}
else {
    #si alguien quiere ingresar a usuarioAjax.php desde la url

    session_start(['name'=>'instituto']); #definimos el nombre a la session (puede ser cualquier nombre)
    session_unset();#vaciar la sesion
    session_destroy();#para destruir o eliminar todas las variables
    Header("Location: ".URL."login/"); #para redireccionar al login
    exit();#para que no se ejecute codigos php
}