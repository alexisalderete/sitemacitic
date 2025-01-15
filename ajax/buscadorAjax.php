<?php
    session_start(['name'=>'instituto']); #definimos el nombre a la session (puede ser cualquier nombre)

    //para usar la URL del servidor
    require_once "../config/app.php";

    if (isset($_POST['busqueda_inicial']) || isset($_POST['eliminar_busqueda']) || isset($_POST['fecha_inicio'])
    || isset($_POST['fecha_final']) ) {

        $data_url = [
            "usuario" => "user-search",
            "estudiantes" => "estudiantes-search",
            "cursos" => "cursos-search",
            "pagos" => "pagos-search",
            "inscripciones" => "inscripciones-search",
            "sedes" => "sedes-search",
            "productos" => "productos-search"
        ];

        /*se crea todo un input hidden en todos los formularios de busqueda
        para saber desde que formulario se esta enviando los datos para le busqueda*/

        if (isset($_POST['modulo'])) {
            $modulo = $_POST['modulo'];
            if (!isset($data_url[$modulo])) { // si no existe el modulo usuario u otro mudulo
                $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "No se pudo continuar con la busqueda.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }
        else {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se pudo continuar con la busqueda, debido a un error de configuración.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        //hacemos otro de pagos porque tiene otro forma de hacer la busqueda
        //pagos tiene 2 variables de sesion de las 2 fechas
        if ($modulo == "pagos" || $modulo == "inscripciones") {
            $fecha_inicio = "fecha_inicio_".$modulo;
            $fecha_final = "fecha_final_".$modulo;

            // iniciar la busquda
            if (isset($_POST['fecha_inicio']) || isset($_POST['fecha_final'])) {

                if ($_POST['fecha_inicio'] == "" || $_POST['fecha_final'] == "") { //si NO se coloco una fecha 
                    $alerta = [
                        "Alerta"=>"simple", 
                        "Titulo" => "Error inesperado.",
                        "Texto" => "Debe ingresar los rangos fechas.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                $_SESSION[$fecha_inicio] = $_POST['fecha_inicio'];
                $_SESSION[$fecha_final] = $_POST['fecha_final'];

            }
            
            //eliminar la busqueda
            if (isset($_POST['eliminar_busqueda'])) {
                //se elimina la variable de sesion de las fechas
                unset($_SESSION[$fecha_inicio]);
                unset($_SESSION[$fecha_final]);
            }
        
        }
        else { //solo una variable de sesion

            //$name_var para crear dinámicamente las variables de sesion (si es usuario, estudiante o curso etc.)
            $name_var = "busqueda_".$modulo; 
            
            //iniciar busqueda
            if (isset($_POST['busqueda_inicial'])) {
                if ($_POST['busqueda_inicial'] == "") { //si el campo de busqueda esta vacia
                    $alerta = [
                        "Alerta"=>"simple", 
                        "Titulo" => "Error inesperado.",
                        "Texto" => "Debe introducir un texto en la busquda.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }

                //variable de sesion para busquda
                $_SESSION[$name_var] = $_POST['busqueda_inicial'];
            }

            // eliminar la variable de sesion
            if (isset($_POST['eliminar_busqueda'])) {
                unset($_SESSION[$name_var]);
            }
        }

        // redireccionar
        // los valores de $modulo puede ser: usuario, estdiante etc.
        $url = $data_url[$modulo];
        $alerta = [
            "Alerta" => "redireccionar",
            "URL" => URL.$url."/"
        ];
        echo json_encode($alerta);
    }
    else {
        session_unset();#vaciar la sesion
        session_destroy();#para destruir o eliminar todas las variables
        Header("Location: ".URL."login/"); #para redireccionar al login
        exit();#para que no se ejecute codigos php
    }