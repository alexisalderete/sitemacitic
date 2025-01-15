<?php

$peticionAjax = true;
require_once "../config/app.php";

/* recibe los datos de los furmularios. (los valores de POST estan en el name en los form) */
if (

    isset($_POST['buscar_inscripciones']) ||
    isset($_POST['codigo_agregar_inscripciones']) || 
    isset($_POST['id_eliminar_inscripciones']) ||
    
    
    isset($_POST['id_agregar_conceptos_inscripciones']) || 
    isset($_POST['id_agregar_conceptos_materiales']) || 
    isset($_POST['id_agregar_conceptos_mensualidad']) || 
    
    isset($_POST['id_eliminar_conceptos']) ||


    isset($_POST['buscar_productos']) ||
    isset($_POST['id_agregar_productos']) ||
    isset($_POST['detalle_cantidad']) ||

    
    isset($_POST['id_agregar_cuotas']) ||


    // isset($_POST['id_eliminar_productos']) ||


    isset($_POST['pagos_fecha_inicio_reg']) ||
    isset($_POST['pagos_agregar_desde_inscripciones']) ||
    // isset($_POST['pagos_codigo_del']) ||








    isset($_POST['id_eliminar_conceptos']) || isset($_POST['tipo_eliminar_conceptos']) ||














    isset($_POST['pagos_codigo_up'])) {
        
    /* ---------- instancia al controlador ----------- */ 
    require_once "../controladores/pagosControlador.php";
    $ins_pagos = new pagosControlador(); #se instancia al pagos controlador

    /* ---------- Buscar inscripciones  ------------ */
    if (isset($_POST['buscar_inscripciones'])) {
        echo $ins_pagos->buscar_inscripciones_pagos_controlador(); //con la -> accedemos a la funcion
    }

    /* ---------- agregar inscripciones al formulario de pagos  ------------ */
    if (isset($_POST['codigo_agregar_inscripciones'])) {
        echo $ins_pagos->agregar_inscripciones_pagos_controlador(); //con la -> accedemos a la funcion
    }

    
    /* ---------- eliminar inscripciones al formulario de pagos  ------------ */
    if (isset($_POST['id_eliminar_inscripciones'])) {
        echo $ins_pagos->eliminar_inscripciones_pagos_controlador(); //con la -> accedemos a la funcion
    }
    
    
    
    
    /* ---------- agregar conceptos a la tabla de pagos  ------------ */
    if (isset($_POST['id_agregar_conceptos_inscripciones'])) {
        echo $ins_pagos->agregar_conceptos_inscripciones_controlador(); //con la -> accedemos a la funcion
    }
    

    



















    /* ---------- eliminar inscripciones al formulario de pagos  ------------ */
if (isset($_POST['id_eliminar_conceptos']) && isset($_POST['tipo_eliminar_conceptos'])) {
    $id = $_POST['id_eliminar_conceptos'];
    $tipo = $_POST['tipo_eliminar_conceptos'];

    if ($tipo == "inscripcion") {
        echo $ins_pagos->eliminar_conceptos_inscripciones_controlador($id);
    } elseif ($tipo == "producto") {
        echo $ins_pagos->eliminar_productos_inscripciones_controlador($id);
    }
    elseif ($tipo == "cuota") {
        echo $ins_pagos->eliminar_cuotas_controlador($id);
    }
    
    else {
        $alerta = [
            "Alerta" => "simple",
            "Titulo" => "Error inesperado.",
            "Texto" => "Tipo de concepto no reconocido.",
            "Tipo" => "error"
        ];
        echo json_encode($alerta);
    }
}





































    /* ---------- Agregar un pago ------------ */
    if (isset($_POST['pagos_fecha_inicio_reg'])) {
        echo $ins_pagos->agregar_pagos_controlador(); //con la -> accedemos a la funcion
    }
    
    /* ---------- Eliminar un pago ------------ */
    if (isset($_POST['pagos_codigo_del'])) {/* recibe desde el paginador en el controlador */
        echo $ins_pagos->eliminar_pagos_controlador(); //con la -> accedemos a la funcion
    }
    
    
    /* ---------- Actualizar un pagos ------------ */
    if (isset($_POST['pagos_codigo_up'])) {
        echo $ins_pagos->actualizar_pagos_controlador(); //con la -> accedemos a la funcion
    }
    
    
    
    /* ---------- Agregar un inscripciones ------------ */
    if (isset($_POST['pagos_agregar_desde_inscripciones'])) {
        echo $ins_pagos->agregar_pagos_desde_inscripciones_controlador(); //con la -> accedemos a la funcion
    }



    /* ---------- Buscar productos  ------------ */
    if (isset($_POST['buscar_productos'])) {
        echo $ins_pagos->buscar_productos_inscripciones_controlador(); //con la -> accedemos a la funcion
    }

     /* ---------- agregar productos  ------------ */
    if (isset($_POST['id_agregar_productos'])) {
        echo $ins_pagos->agregar_productos_inscripciones_controlador(); //con la -> accedemos a la funcion
    }

    // /* ---------- eliminar estudiantes al formulario de inscripciones  ------------ */
    // if (isset($_POST['id_eliminar_productos'])) {
    //     echo $ins_pagos->eliminar_productos_inscripciones_controlador(); //con la -> accedemos a la funcion
    // }


     /* ---------- agregar cuotas  ------------ */
    if (isset($_POST['id_agregar_cuotas'])) {
        echo $ins_pagos->agregar_cuotas_controlador(); //con la -> accedemos a la funcion
    }




}
else {
    #si alguien quiere ingresar a pagosAjax.php desde la url
    session_start(['name'=>'instituto']); #definimos el nombre a la session (puede ser cualquier nombre)
    session_unset();#vaciar la sesion
    session_destroy();#para destruir o eliminar todas las variables
    Header("Location: ".URL."login/"); #para redireccionar al login
    exit();#para que no se ejecute codigos php
}