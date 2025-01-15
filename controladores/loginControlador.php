<?php

if ($peticionAjax) {
    # si es una peticion ajax, estamos en la carpeta ajax,
    #se sale de la carpeta ajax y entra en modelo
    require_once "../modelos/loginModelo.php";
}
else {
    # si NO es una peticion ajax, estamos en el index.php,
    # y entra en modelo
    require_once "./modelos/loginModelo.php";
}

class loginControlador extends loginModelo{

    /* ------- controlador iniciar sesion ------- */
    public function iniciar_sesion_controlador(){
        $usuario = mainModel::limpiar_cadenas($_POST['usuario_log']);
        $clave = mainModel::limpiar_cadenas($_POST['clave_log']);

        /** == verificar los campos vacios == */
        if ($usuario == "" || $clave=="") {
            echo
            '<script>
                Swal.fire({
                    title: "Error inesperado.",
                    text: "Campos vacíos",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            </script>';
        }
        /** == verificar los campos vacios == */
        if ($usuario == "" || $clave=="") {
            /* en login no utilizamos ajax, entonces hacemos la alerta directamente */
            echo
            '<script>
                Swal.fire({
                    title: "Error inesperado.",
                    text: "Campos vacíos",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            </script>';
            exit();
        }

        /* == verificar la integridad de los datos == */
        if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)) {
            echo
            '<script>
                Swal.fire({
                    title: "Error inesperado.",
                    text: "El nombre de usuario contiene caracteres no válidos.",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            </script>';
            exit();
        }

        /* == verificar la integridad de los datos == */
        if (mainModel::verificar_datos("[a-zA-Z0-9$@.\-]{7,100}",$clave)) {
            echo
            '<script>
                Swal.fire({
                    title: "Error inesperado.",
                    text: "La contraeña contiene caracteres no válidos.",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            </script>';
            exit();
        }
        

        /* para encriptar la contrasseña */
        $clave=mainModel::encryption($clave);

        /* array de datos para mandar al modelo */
        $datos_login=[
            "Usuario" => $usuario,
            "Clave" => $clave
        ];

        /* para guardar la consulta */
        $datos_cuenta = loginModelo::iniciar_sesion_modelo($datos_login);
        
        if ($datos_cuenta->rowCount()==1) {
            $row=$datos_cuenta->fetch(); //para que se convierta en array de datos la consulta hecha en el modelo
            session_start(['name'=>'instituto']);

            /* varialbes de sesion para utilizar esos datos una vez iniciada la sesion */
            $_SESSION['id_instituto']=$row['usuario_id']; //id de la sesion es igual al id del usuario de la bdd
            $_SESSION['nombre_instituto']=$row['usuario_nombre'];
            $_SESSION['apellido_instituto']=$row['usuario_apellido'];
            $_SESSION['usuario_instituto']=$row['usuario_usuario'];
            $_SESSION['privilegio_instituto']=$row['usuario_privilegio'];

            //para evitar que cierre la sesion desde otro dispositivo
            $_SESSION['token_instituto'] = md5(uniqid(mt_rand(),true)); //id unica para cada sesion

            return header("Location: ".URL."home/");
        }
        else{
        echo
            '<script>
                Swal.fire({
                    title: "Error inesperado.",
                    text: "El nombre de usuario o contraseña son incorrectos.",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            </script>';
        }
    } /* fin controlador */

     /* ------- controlador cierre de sesion ------- */
    public function forzar_cierre_sesion_controlador(){
        session_unset();/* para vaciar la sesion */
        session_destroy(); /**para eliminar la sesion */

        if (headers_sent()) { /* headers_sent() verifica si se esta enviando encabezados mediante php */
            #Se esta enviando encabezados
            return "
                <script> window.location.href='".URL."login/'; </script>
            ";
        }else{
            return header("Location: ".URL."login/");
        }
    }/* fin controlador */

     /* ------- controlador cierre de sesion ------- */
    public function cerrar_sesion_controlador(){
        session_start(['name'=>'instituto']);
        $token = mainModel::decryption($_POST['token']);
        $usuario = mainModel::decryption($_POST['usuario']);

        if ($token==$_SESSION['token_instituto'] && $usuario == $_SESSION['usuario_instituto']) {
            session_unset(); //vaciar la sesion
            session_destroy(); //eliminamos la sesion

            $alerta = [
                "Alerta" => "redireccionar", //tipo de alerta es redireccionar 
                "URLALERTA" => URL."login/"
            ];
        }
        else{
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta (A mayuscula) porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se pudo cerrar la sesion.",
                "Tipo" => "error"
            ];
            
        }
        echo json_encode($alerta);
    }/* fin controlador */
}