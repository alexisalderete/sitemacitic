<?php

$peticionAjax = true;
require_once "../config/app.php";

if (isset($_POST['token']) && isset($_POST['usuario'])) { // son las variables que definimos en LogOut.php
    # si existe el token y usuario
    
    /* ---------- instancia al controlador ----------- */
    require_once "../controladores/loginControlador.php";
    $ins_login = new loginControlador(); #se instancia al usuario controlador

    //imprimimos lo que nos devuelva el controlador de cerrar la sesion
    echo $ins_login->cerrar_sesion_controlador();


}
else {
    #si alguien quiere ingresar a usuarioAjax.php desde la url

    session_start(['name'=>'instituto']); #definimos el nombre a la session (puede ser cualquier nombre)
    session_unset();#vaciar la sesion
    session_destroy();#para destruir o eliminar todas las variables
    Header("Location: ".URL."login/"); #para redireccionar al login
    exit();#para que no se ejecute codigos php
}