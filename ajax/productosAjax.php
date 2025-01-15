<?php

$peticionAjax = true;
require_once "../config/app.php";

/* recibe los datos de los furmularios. (los valores de POST estan en el name en los form) */
if (isset($_POST['productos_codigo_reg']) || isset($_POST['productos_id_del']) || isset($_POST['productos_id_up'])) {
    
    /* ---------- instancia al controlador ----------- */
    require_once "../controladores/productosControlador.php";
    $ins_productos = new productosControlador(); #se instancia al productos controlador


    /* ---------- Agregar un productos ------------ */
    if (isset($_POST['productos_codigo_reg'])) {
        echo $ins_productos->agregar_productos_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Eliminar un productos ------------ */
    if (isset($_POST['productos_id_del'])) {/* recibe desde el paginador en el controlador */
        echo $ins_productos->eliminar_productos_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- Actualizar un productos ------------ */
    if (isset($_POST['productos_id_up'])) {
        echo $ins_productos->actualizar_productos_controlador(); //con la -> accedemos a la funcion
    }


}
else {
    
    #si alguien quiere ingresar a productosAjax.php desde la url
    session_start(['name'=>'instituto']); #definimos el nombre a la session (puede ser cualquier nombre)
    session_unset();#vaciar la sesion
    session_destroy();#para destruir o eliminar todas las variables
    Header("Location: ".URL."login/"); #para redireccionar al login
    exit();#para que no se ejecute codigos php
}