<?php

require_once "./config/app.php";
require_once "./controladores/vistasControlador.php";

$plantilla = new vistasControlador(); #instanciamos la clase vistasControlador()
$plantilla -> obtenerPantillaControlador(); #llamamos a la funcion obtenerPantillaControlador()
