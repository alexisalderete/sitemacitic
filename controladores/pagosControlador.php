<?php
/* este controlador se va ejecutar dentro del archivo pagosajax
pero cuando no se utiliza la peticion ajax se va ajecutar en index.php  */

if ($peticionAjax) {
    # si es una peticion ajax, estamos en la carpeta ajax,
    #se sale de la carpeta ajax y entra en modelo
    require_once "../modelos/pagosModelo.php";
}
else {
    # si NO es una peticion ajax, estamos en el index.php,
    # y entra en modelo
    require_once "./modelos/pagosModelo.php";
}

class pagosControlador extends pagosModelo{

        /* ---------- Controlador buscar inscripciones pagos ----------- */
        public function buscar_inscripciones_pagos_controlador(){
            //recuperar los textos enviados
            $inscripciones = mainModel::limpiar_cadenas($_POST['buscar_inscripciones']); 
    
            // verificar si esta vacio el campo de busquda de inscripciones
            if ($inscripciones == "") {
                //copiamos de la alerta de pagos-new
                return '
                    <div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                            Introduce el código de la inscripción
                        </p>
                    </div>';
                exit();
            }
    
            /* seleccionar los datos de los inscripciones */
            $datos_inscripciones = mainModel::consultar_consultas_simples("SELECT * FROM inscripciones
                INNER JOIN estudiantes ON inscripciones.estudiantes_id = estudiantes.estudiantes_id
                INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id
                WHERE inscripciones_codigo LIKE '$inscripciones' OR
                    estudiantes.estudiantes_nombre LIKE '$inscripciones' OR
                    estudiantes.estudiantes_apellido LIKE '$inscripciones' OR
                    estudiantes.estudiantes_dni LIKE '$inscripciones'
                ORDER BY inscripciones_codigo ASC;");
    
            //verifiar si existe datos
            if ($datos_inscripciones->rowCount() >= 1) {
    
                //array de datos, para seleccionar todos los datos de los inscripciones
                $datos_inscripciones = $datos_inscripciones->fetchAll();
    
                $tabla='
                    <div class="table-responsive-1">
                        <table class="table table-hover table-bordered table-sm">
                            <tbody>';
    
                foreach($datos_inscripciones as $rows){
                    $tabla.='
                        <tr class="text-center">
                            <td>'.'Estudiante: '.$rows['estudiantes_nombre'].' '.$rows['estudiantes_apellido'].' - Curso: '.$rows['cursos_nombre'].'</td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="agregar_inscripciones('.$rows['inscripciones_id'].')"><i class="fas fa-user-plus"></i></button>
                            </td>
                        </tr>
                    ';
                }
                
                $tabla.='
                            </tbody>
                        </table>
                    </div>';
                    return $tabla;
            }else{
                return '
                    <div class="alert alert-warning" role="alert">
                        <p class="text-center mb-0">
                            <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                            No hemos encontrado ningún estudiante inscrito en el sistema
                            que coincida con <strong>“'.$inscripciones.'”</strong>
                        </p>
                    </div>';
                exit();
            } 
            
        } /* ---- FIN Controlador buscar inscripciones pagos ---- */
    
    
        /* ---------- agregar inscripciones a pagos ----------- */
        public function agregar_inscripciones_pagos_controlador(){
            //recuperar los textos enviados
            $codigo = mainModel::limpiar_cadenas($_POST['codigo_agregar_inscripciones']);
    
            /* verificar si existe estudiante en la bdd */
            $check_inscripciones = mainModel::consultar_consultas_simples("SELECT * FROM inscripciones
                INNER JOIN estudiantes ON inscripciones.estudiantes_id = estudiantes.estudiantes_id
                INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id
            WHERE inscripciones_id = '$codigo'");

            if ($check_inscripciones ->rowCount()<=0) {
                $alerta = [
                    "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "No se ha podido encontrar la inscripción en el sistema.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }
            else{
                $campos = $check_inscripciones->fetch();
            }
    
            //utilizamos la variable session para garantizar que,
            //al agregar un estudiante al pago, dicha variable no se elimine.
            session_start(['name'=>'instituto']);
            if (empty($_SESSION['datos_inscripciones'])) {
    
                $_SESSION['datos_inscripciones'] = [
                    "ID" => $campos['inscripciones_id'],
                    "Codigo" => $campos['inscripciones_codigo'],
                    "Fecha" => $campos['inscripciones_fecha'],
                    "Costo" => $campos['inscripciones_costo'],
                    "Mensualidad" => $campos['inscripciones_mensualidad'],
                    
                    "IDEstudiantes" => $campos['estudiantes_id'],
                    "DNI" => $campos['estudiantes_dni'],
                    "Nombre" => $campos['estudiantes_nombre'],
                    "Apellido" => $campos['estudiantes_apellido'],

                    "IDCursos" => $campos['cursos_id'],
                    "NombreCurso" => $campos['cursos_nombre'],
                    "Duracion" => $campos['cursos_duracion'],
                    "Detalle" => $campos['cursos_detalle']

                ];
    
                $alerta = [
                    "Alerta"=>"recargar", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                    "Titulo" => "Agregado.",
                    "Texto" => "Inscripción agregado.",
                    "Tipo" => "success"
                ];
                echo json_encode($alerta);
    
            }
            else {
                $alerta = [
                    "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "No se ha podido agregar el estudiante a la inscripción.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
            }
            
    
        } 
    
        /* ---------- eliminar inscripciones a pagos ----------- */
        public function eliminar_inscripciones_pagos_controlador(){          

            session_start(['name'=>'instituto']);
            unset($_SESSION['datos_inscripciones']);//eliminar los datos de la inscripcion de la sesion
            unset($_SESSION['datos_conceptos_inscripciones']);
            


            //si esta vacio
            if (empty($_SESSION['datos_inscripciones'])) {
                $alerta = [
                    "Alerta"=>"recargar", 
                    "Titulo" => "Inscripción removido.",
                    "Texto" => "Datos de la inscripción fueron removidos.",
                    "Tipo" => "success"
                ];
            }
            else{ //si contiend los datos de inscripciones
                $alerta = [
                    "Alerta"=>"simple",
                    "Titulo" => "Error inesperado.",
                    "Texto" => "No se ha podido remover los datos de la inscripción.",
                    "Tipo" => "error"
                ];
            }
            echo json_encode($alerta); //fuera de la condicional doble
        }/* Fin */
        
        
        
        
        
        
        
        

        
        


        
    /* ---------- agregar conceptos a la tabla de pagos ----------- */
    public function agregar_conceptos_inscripciones_controlador(){
        //recuperar los textos enviados
        $id = mainModel::limpiar_cadenas($_POST['id_agregar_conceptos_inscripciones']);
        
        /* verificar si existe estudiante en la bdd */
        $check_inscripciones = mainModel::consultar_consultas_simples("SELECT * FROM inscripciones
                    INNER JOIN estudiantes ON inscripciones.estudiantes_id = estudiantes.estudiantes_id
                    INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id

                    INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
                    INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id

                WHERE inscripciones_id = '$id'");
        
        if ($check_inscripciones ->rowCount()<=0) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha podido encontrar la inscripción en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        else{
            $campos = $check_inscripciones->fetch();
        }
        
        
        //utilizamos la variable session para garantizar que,
        //al agregar un estudiante al pago, dicha variable no se elimine.
        session_start(['name'=>'instituto']);
        
        if (empty($_SESSION['datos_conceptos_inscripciones'][$id])) {
            //$costo = $campos['inscripciones_costo'];
            
            $_SESSION['datos_conceptos_inscripciones'][$id] = [
                
                "ID" => $campos['inscripciones_id'],
                "IDCursos" => $campos['cursos_id'],
                
                //"NombreCurso" => $campos['cursos_nombre'],
                "Concepto" => "Pago de inscripción a ".$campos['cursos_nombre'],
                "Cantidad" => 1,
                "Costo" => $campos['cursos_precio'],
                "tipo" => "inscripcion"
                
                
                //"ID" => $campos['inscripciones_id'],
                /*"Codigo" => $campos['inscripciones_codigo'],
                "Fecha" => $campos['inscripciones_fecha'],
                //"Costo" => $campos['inscripciones_costo'],
                "Mensualidad" => $campos['inscripciones_mensualidad'],
                
                "IDEstudiantes" => $campos['estudiantes_id'],
                "DNI" => $campos['estudiantes_dni'],
                "Nombre" => $campos['estudiantes_nombre'],
                "Apellido" => $campos['estudiantes_apellido'],
                
                //"IDCursos" => $campos['cursos_id'],
                "NombreCurso" => $campos['cursos_nombre'],
                "Duracion" => $campos['cursos_duracion'],
                "Detalle" => $campos['cursos_detalle']*/
                
            ];
            
            $alerta = [
                "Alerta"=>"recargar", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Agregado.",
                "Texto" => "Inscripción agregado.",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);
            
        }
        else {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El pago de la incripción ya se encuentra seleccionado.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
        }
        
    } 
    
    
    public function eliminar_conceptos_inscripciones_controlador($id) {
        session_start(['name' => 'instituto']);
        unset($_SESSION['datos_conceptos_inscripciones'][$id]);

        if (empty($_SESSION['datos_conceptos_inscripciones'][$id])) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Inscripción removida.",
                "Texto" => "Datos de inscripción fueron removidos.",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha podido remover los datos de la inscripción.",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }
    


















/* ---------- Controlador buscar productos inscripciones ----------- */
    public function buscar_productos_inscripciones_controlador(){
        //recuperar los textos enviados
        $productos = mainModel::limpiar_cadenas($_POST['buscar_productos']); 

        // verificar si esta vacio el campo de busquda de estudiantes
        if ($productos == "") {
            //copiamos de la alerta de inscripciones-new
            return '
                <div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debes introducir el Código o Nombre del curso
                    </p>
                </div>';
            exit();
        }

        /* seleccionar los datos de los productos */
        $datos_productos = mainModel::consultar_consultas_simples("SELECT * FROM productos
        WHERE productos_codigo LIKE '%$productos%' OR
        productos_nombre LIKE '%$productos%'
        ORDER BY productos_nombre ASC");

        //verifiar si existe datos
        if ($datos_productos->rowCount() >= 1) {

            //array de datos, para seleccionar todos los datos de los estudiantes
            $datos_productos = $datos_productos->fetchAll();

            $tabla='
                <div class="table-responsive-1">
                    <table class="table table-hover table-sm">
                        <tbody>';

            foreach($datos_productos as $rows){
                $tabla.='
                    <tr class="text-center">
                        <td>'
                            .$rows['productos_codigo'].' - '.$rows['productos_nombre'].' - '.number_format($rows['productos_precio'], 0,'', '.').' Gs. 
                            - Stock: '.$rows['productos_cantidad'].' - Cantidad:
                                <input type="number" class="form-control d-inline-block w-25 cantidad-producto" 
                                name="cantidad_producto_' . $rows['productos_id'] . '" 
                                id="cantidad_producto_' . $rows['productos_id'] . '" value="1" max=5000 min=1>
                        </td>
                        
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" onclick="agregar_productos('.$rows['productos_id'].')"><i class="fa-solid fa-plus"></i></button>
                        </td>
                    </tr>
                ';
            }
            
            $tabla.='
                        </tbody>
                    </table>
                </div>';
                return $tabla;
        }else{
            return '
                <div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        No hemos encontrado ningún curso en el sistema
                        que coincida con <strong>“'.$productos.'”</strong>
                    </p>
                </div>';
            exit();
        } 
        
    } /* ---- FIN Controlador buscar productos inscripciones ---- */
    
    
    /* ---------- agregar productos a pagos ----------- */
    public function agregar_productos_inscripciones_controlador(){
        session_start(['name' => 'instituto']);

        //recuperar los textos enviados
        $id = mainModel::limpiar_cadenas($_POST['id_agregar_productos']);
        $detalle_cantidad = mainModel::limpiar_cadenas($_POST['detalle_cantidad']);

        // Validar la cantidad ingresada
        if (empty($detalle_cantidad) || $detalle_cantidad <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Cantidad inválida.",
                "Texto" => "Debe ingresar una cantidad mayor a 0.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        

        /* verificar si existe productos en la bdd */
        $check_productos = mainModel::consultar_consultas_simples("SELECT * FROM productos WHERE productos_id = '$id'");
        if ($check_productos ->rowCount()<=0) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha podido encontrar el producto en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
    
        $campos = $check_productos->fetch();



        // Verificar si el producto ya está en la sesión
        if (!isset($_SESSION['datos_conceptos_inscripciones'])) {
            $_SESSION['datos_conceptos_inscripciones'] = [];
        }
        foreach ($_SESSION['datos_conceptos_inscripciones'] as $item) {
            if ($item['ID'] == $id) {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Error inesperado.",
                    "Texto" => "El producto ya está agregado.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }


        // Agregar el producto a la sesión
        $nuevo_producto = [
            "ID" => $campos['productos_id'],
            "Concepto" => $campos['productos_nombre'],
            "Cantidad" => $detalle_cantidad,
            "Costo" => $campos['productos_precio'],
            "tipo" => "producto"

        ];
        $_SESSION['datos_conceptos_inscripciones'][] = $nuevo_producto;

        $alerta = [
            "Alerta" => "recargar",
            "Titulo" => "Producto agregado.",
            "Texto" => "El producto se ha agregado correctamente.",
            "Tipo" => "success"
        ];
        echo json_encode($alerta);



        //utilizamos la variable session para garantizar que,
        //al agregar un produto al pago, dicha variable no se elimine.
        // session_start(['name'=>'instituto']);
        // if (empty($_SESSION['datos_productos'])) {

        //     $_SESSION['datos_productos'] = [
        //         "ID" => $campos['productos_id'],
        //         "Codigo" => $campos['productos_codigo'],
        //         "Nombre" => $campos['productos_nombre'],
        //         "Precio" => $campos['productos_precio'],
        //         "Detalle_cantidad" => $detalle_cantidad,
        //         "Iva" => $campos['productos_iva']
        //     ];

        //     $alerta = [
        //         "Alerta"=>"recargar", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
        //         "Titulo" => "Agregado.",
        //         "Texto" => "Productos agregados.",
        //         "Tipo" => "success"
        //     ];
        //     echo json_encode($alerta);

        // }
        // else {
        //     $alerta = [
        //         "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
        //         "Titulo" => "Error inesperado.",
        //         "Texto" => "El producto ya se encuentra seleccionado.",
        //         "Tipo" => "error"
        //     ];
        //     echo json_encode($alerta);
        // }



    } 

    /* ---------- eliminar productos a inscripciones ----------- */
    public function eliminar_productos_inscripciones_controlador($id) {
        session_start(['name' => 'instituto']);
        foreach ($_SESSION['datos_conceptos_inscripciones'] as $key => $item) {
            if ($item['ID'] == $id) {
                unset($_SESSION['datos_conceptos_inscripciones'][$key]);
                break;
            }
        }
    
        if (empty(array_filter($_SESSION['datos_conceptos_inscripciones'], fn($item) => $item['ID'] == $id))) {
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Producto removido.",
                "Texto" => "Datos del producto fueron removidos.",
                "Tipo" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha podido remover los datos del producto.",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
    }
    /* Fin */









        /* ---------- agregar cuotas a pagos ----------- */
        public function agregar_cuotas_controlador(){
            session_start(['name' => 'instituto']);
    
            //recuperar los textos enviados
            $id = mainModel::limpiar_cadenas($_POST['id_agregar_cuotas']);
            //$detalle_cantidad = mainModel::limpiar_cadenas($_POST['detalle_cantidad']);
    
    
            /* verificar si existe cuotas en la bdd */
            $check_cuotas = mainModel::consultar_consultas_simples("SELECT * FROM cuotas
                INNER JOIN inscripciones ON inscripciones.inscripciones_id = cuotas.inscripciones_id
                INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id
                INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
                WHERE cuotas.cuotas_id = '$id'");
            if ($check_cuotas ->rowCount()<=0) {
                $alerta = [
                    "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "No se ha podido encontrar el producto en el sistema.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }
        
            $campos = $check_cuotas->fetch();
    
    
    
            // Verificar si el producto ya está en la sesión
            if (!isset($_SESSION['datos_conceptos_inscripciones'])) {
                $_SESSION['datos_conceptos_inscripciones'] = [];
            }
            foreach ($_SESSION['datos_conceptos_inscripciones'] as $item) {
                if ($item['ID'] == $id) {
                    $alerta = [
                        "Alerta" => "simple",
                        "Titulo" => "Error inesperado.",
                        "Texto" => "La cuota ya está agregado.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
            }
    
    
            // Agregar el cuota a la sesión
            $nueva_cuota = [
                "ID" => $campos['cuotas_id'],
                "Concepto" => 'Cuota de '.$campos['cuotas_mes'],
                "Cantidad" => 1,
                "Costo" => $campos['cursos_mensualidad'],
                "tipo" => "cuota"

                
    
            ];
            $_SESSION['datos_conceptos_inscripciones'][] = $nueva_cuota;
    
            $alerta = [
                "Alerta" => "recargar",
                "Titulo" => "Cuota agregada.",
                "Texto" => "La cuota se ha agregado correctamente.",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);

    
    
        } 



        public function eliminar_cuotas_controlador($id) {
            session_start(['name' => 'instituto']);
            foreach ($_SESSION['datos_conceptos_inscripciones'] as $key => $item) {
                if ($item['ID'] == $id) {
                    unset($_SESSION['datos_conceptos_inscripciones'][$key]);
                    break;
                }
            }
        
            if (empty(array_filter($_SESSION['datos_conceptos_inscripciones'], fn($item) => $item['ID'] == $id))) {
                $alerta = [
                    "Alerta" => "recargar",
                    "Titulo" => "Cuota removido.",
                    "Texto" => "Datos de la cuota fueron removidos.",
                    "Tipo" => "success"
                ];
            } else {
                $alerta = [
                    "Alerta" => "simple",
                    "Titulo" => "Error inesperado.",
                    "Texto" => "No se ha podido remover los datos del producto.",
                    "Tipo" => "error"
                ];
            }
            echo json_encode($alerta);
        }






    
    /* ---------- Controlador agreagar pagos ----------- */
    public function agregar_pagos_controlador(){

        //$codigo_inscripciones = mainModel::limpiar_cadenas($_POST['codigo_agregar_pagos']);

        session_start(['name'=>'instituto']);

        /* si no hay un inscripcion seleccionado */
        if(empty($_SESSION['datos_inscripciones'])){
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Inscripción no seleccionado.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        
        /*  */

        
        $fecha = mainModel::limpiar_cadenas($_POST['pagos_fecha_inicio_reg']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $hora = mainModel::limpiar_cadenas($_POST['pagos_hora_inicio_reg']); 
        $monto = mainModel::limpiar_cadenas($_POST['pagos_monto_reg']); 
        //$mensualidad = mainModel::limpiar_cadenas($_POST['pagos_monto_mensualidad_reg']); 
        

        /* == verificar campos vacios == */
        if ($fecha=="" || $hora=="" || $monto=="") {
            //alerta es array de datos, luego lo convertimos a JSON
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "Llena los campos obligatorios.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); //para que no se ejecute mas el codigo
        }

        /* ===== verificar la integridad de los datos ===== */
        if (mainModel::verificar_fecha($fecha)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de FECHA incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$hora)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de HORA incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[0-9.]{1,10}",$monto)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de monto incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        /*if (mainModel::verificar_datos("[0-9.]{1,10}",$mensualidad)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de MENSUALIDAD incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }*/


        /* == formateando los montos, fechas y horas == */
        /*number_format($costo, 2,'.', '')
        2 indica cuantos decimales
        '.' separador de decimales
        '' seperador de millares
        */
        $monto = number_format($monto, 0,'', '.');
        //$mensualidad = number_format($mensualidad, 2,'.', '');
        $fecha = date("Y-m-d", strtotime($fecha));
        $hora = date("h:i a", strtotime($hora));
        

        /* generar codigo */
        $correlativo = mainModel::consultar_consultas_simples("SELECT pagos_id FROM pagos;");
        $correlativo = ($correlativo->rowCount())+1;
        
        $codigo = mainModel::generar_codigo_aleatorios("PAG-",7,$correlativo);
        
        /* alamcenamos en un array de datos los registros de los datos del pagos para guardarlo
        "Codigo" etc son los valores del modelo $datos['Codigo'] etc*/

        $datos_pagos_reg=[
            "Codigo" => $codigo,
            "Fecha" => $fecha,
            "Hora" => $hora,
            "Monto" => $monto,
            "Inscripciones" => $_SESSION['datos_inscripciones']['Codigo'],
            "Usuario" => $_SESSION['id_instituto'],
            "Productos" => $_SESSION['datos_productos']['ID']
        ];
        /* para almacenar todo lo que devuelava agregar_pagos_modelo */
        $agregar_pagos = pagosModelo::agregar_pagos_modelo($datos_pagos_reg);

        /* para comprobar si se insertó los registros en la bdd */
        if ($agregar_pagos->rowCount()==1) {
            //vaciamos los datos de inscripciones
            unset($_SESSION['datos_inscripciones']);
            unset($_SESSION['datos_productos']);
            $alerta = [
                "Alerta"=>"recargar",
                "Titulo" => "Pago registrado.",
                "Texto" => "Datos de la pago registrado con éxito.",
                "Tipo" => "success"
            ];
            
        }
        else{
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha guardado los registros en la base de datos.",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
        
    } /* ---- FIN controlador agregar ---- */













        
    /* ---------- Controlador agregar pagos desde inscripciones ----------- */
    //este controlador agrega el pago haciendo click desde la lista de inscripciones en la columna de pagos
    public function agregar_pagos_desde_inscripciones_controlador(){
        $codigo_inscripciones = mainModel::limpiar_cadenas($_POST['codigo_agregar_pagos']);

        session_start(['name'=>'instituto']);


        /* si no hay un inscripcion en el concepto seleccionado */
        if(empty($_SESSION['datos_conceptos_inscripciones'])){
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha seleccionado ningun concepto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }



        /* si no hay un inscripcion seleccionado */
        if(empty($codigo_inscripciones)){
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "La inscripción no se ha seleccionado.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        
        /*  */

        
        $fecha = mainModel::limpiar_cadenas($_POST['pagos_fecha_reg']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $hora = mainModel::limpiar_cadenas($_POST['pagos_hora_inicio_reg']); 
        $monto = mainModel::limpiar_cadenas($_POST['pagos_monto_reg']); 
        //$mensualidad = mainModel::limpiar_cadenas($_POST['pagos_monto_mensualidad_reg']); 
        

        /* == verificar campos vacios == */
        if ($fecha=="" || $hora=="" || $monto=="") {
            //alerta es array de datos, luego lo convertimos a JSON
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "Llena los campos obligatorios.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); //para que no se ejecute mas el codigo
        }




        /* ===== verificar la integridad de los datos ===== */
        if (mainModel::verificar_fecha($fecha)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de FECHA incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$hora)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de HORA incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[0-9.]{1,10}",$monto)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de monto incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        /*if (mainModel::verificar_datos("[0-9.]{1,10}",$mensualidad)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de MENSUALIDAD incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }*/


        /* == formateando los montos, fechas y horas == */
        /*number_format($costo, 2,'.', '')
        2 indica cuantos decimales
        '.' separador de decimales
        '' seperador de millares
        */
        $monto = number_format($monto, 2,'.', '');
        //$mensualidad = number_format($mensualidad, 2,'.', '');
        $fecha = date("Y-m-d", strtotime($fecha));
        $hora = date("h:i a", strtotime($hora));
        

        /* generar codigo */
        $correlativo = mainModel::consultar_consultas_simples("SELECT pagos_id FROM pagos;");
        $correlativo = ($correlativo->rowCount())+1;
        
        $codigo = mainModel::generar_codigo_aleatorios("PAG-",7,$correlativo);
        
        /* alamcenamos en un array de datos los registros de los datos del pagos para guardarlo
        "Codigo" etc son los valores del modelo $datos['Codigo'] etc*/


        $datos_pagos_reg=[
            "Codigo" => $codigo,
            "Fecha" => $fecha,
            "Hora" => $hora,
            "Monto" => $monto,
            "Inscripciones" => $codigo_inscripciones,
            "Usuario" => $_SESSION['id_instituto']
        ];
        /* para almacenar todo lo que devuelava agregar_pagos_modelo */
        $agregar_pagos = pagosModelo::agregar_pagos_modelo($datos_pagos_reg);

        /* para comprobar si se insertó los registros en la bdd */
        if ($agregar_pagos->rowCount()==1) {
            //vaciamos los datos de inscripciones
            unset($_SESSION['datos_conceptos_inscripciones']);
            $alerta = [
                "Alerta"=>"recargar",
                "Titulo" => "Pago registrado.",
                "Texto" => "Datos de la pago registrado con éxito.",
                "Tipo" => "success"
            ];
            
        }
        else{
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha guardado los registros en la base de datos.",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
        
        
    } /* ---- FIN controlador agregar ---- */




    
    /* ---------- Controlador paginar pagos ----------- */
    public function paginador_pagos_controlador($pagina, $registros, $privilegio, $url, $tipo, $fecha_inicio, $fecha_final){
        /*  */
        $pagina = mainModel::limpiar_cadenas($pagina);
        $registros = mainModel::limpiar_cadenas($registros);
        $privilegio = mainModel::limpiar_cadenas($privilegio);

        $url = mainModel::limpiar_cadenas($url);
        $url = URL.$url."/";

        $tipo = mainModel::limpiar_cadenas($tipo);

        $fecha_inicio = mainModel::limpiar_cadenas($fecha_inicio);
        $fecha_final = mainModel::limpiar_cadenas($fecha_final);
        $tabla = "";
        
        #para que solo tenga numeros enteros
        #hacemos con peperador ternario
        #si la pagian viene definida y es mayor a 0, entonces es un numero que esta en la url (...pagos-list/1/), si no, la pagina devuelve 1
        $pagina = (isset($pagina) && $pagina>=0) ? (int) $pagina : 1 ;

        /*
            EJEMPLO del funcionamiento del siguiente codigo
            si se tiene
            20 registros en la bdd
            y queremos
            10 registros por pagina 
            entonces se generan 2 páginas

            entonces si estamos en la página 2
            $pagina = 2
            $registros = 10
            2 * 10 = 20
            20 - 10 = 10 
            entonces se cuenta desde el registro 10

            si estamos en la pagina 1
            1 * 10 = 10
            10 - 10 = 0
            entonces se cuenta el array desde el indice 0
        */
        $inicio = ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

        //verificar si las fechas son validas
        if ($tipo=="Busqueda") {
            if (mainModel::verificar_fecha($fecha_inicio) || mainModel::verificar_fecha($fecha_final)) {
                return '
                    <div class="alert alert-danger text-center" role="alert">
                        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
                        <h4 class="alert-heading">¡Error inesperado!</h4>
                        <p class="mb-0">Lo sentimos, no podemos mostrar la
                        información solicitada ya que ha ingresado una fecha incorrecta.</p>
                    </div>
                ';
                exit();
            }
        }

        $campos=
            "pagos.pagos_id,
            pagos.pagos_codigo,
            pagos.pagos_fecha,
            pagos.pagos_hora,
            pagos.pagos_monto,
            pagos.pagos_estado,
            pagos.inscripciones_codigo,
            inscripciones.inscripciones_costo,
            estudiantes.estudiantes_nombre,
            estudiantes.estudiantes_apellido,
            cursos.cursos_nombre";

        //condicion para saber si esta en la página de busqueda o no
        if ($tipo == "Busqueda" && $fecha_inicio!="" && $fecha_final!="") {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos FROM pagos
            INNER JOIN inscripciones ON pagos.inscripciones_codigo = inscripciones.inscripciones_codigo
            INNER JOIN estudiantes ON inscripciones.estudiantes_id = estudiantes.estudiantes_id
            INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id
            WHERE (pagos.pagos_fecha BETWEEN '$fecha_inicio' AND '$fecha_final')
            ORDER BY pagos.pagos_fecha DESC LIMIT $inicio, $registros";
        }
        else{
            /* para que la consulta solo seleccione un determinado listado
            y no se seleccione todos los registros (para que no se sature)
            
            */
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos FROM pagos
            INNER JOIN inscripciones ON pagos.inscripciones_codigo = inscripciones.inscripciones_codigo
            INNER JOIN estudiantes ON inscripciones.estudiantes_id = estudiantes.estudiantes_id
            INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id
            ORDER BY pagos.pagos_fecha DESC LIMIT $inicio, $registros";
        }//
        $conexion = mainModel::conectar();
        //variable para alamacenar los datos
        $datos = $conexion -> query($consulta);
        $datos = $datos ->fetchAll();

        //total de registros
        $total = $conexion->query("SELECT FOUND_ROWS()"); //FOUND_ROWS() cuenta los registros que se esta haciendo culquiera de las 2 consultas
        $total = (int) $total->fetchColumn(); //fetchColumn() es para contar todas las columnas

        // para contar el numero de paginas totales
        $Npaginas = ceil($total/$registros); // ceil para redondear para abajo.

        $tabla.=
            '<div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>#</th>
                            <th>CÓDIGO</th>
                            <th>ESTUDIANTE</th>
                            <th>CURSO</th>
                            <th>FECHA DE PAGO</th>
                            <th>MONTO A PAGAR</th>
                            <th>MONTO PAGADO</th>
                            <th>CONCEPTO</th>
                            <th>ESTADO</th>
                            <th>FACTURA</th>';
                            
                            /*if ($privilegio==1 || $privilegio==2) { //los que tengan privilegio 1 o 2 pueden actualizar
                                $tabla.='<th>ACTUALIZAR</th>';
                                
                            }*/
                            /*if ($privilegio==1) { //los que tengan privilegio 1 pueden eliminar
                                $tabla.='<th>ELIMINAR</th>';
                            }*/
                        $tabla.='</tr>
                    </thead>
                    <tbody>';

        if ($total>=1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            $reg_inicio = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla.='
                <tr class="text-center" >
                    <td>'.$contador.'</td>
                    <td>'.$rows['pagos_codigo'].'</td>
                    <td>'.$rows['estudiantes_nombre']." ".$rows['estudiantes_apellido'].'</td>
                    <td>'.$rows['cursos_nombre'].'</td>
                    <td>'.date("d-m-Y",strtotime($rows['pagos_fecha'])).'</td>
                    <td>'.MONEDA." ".number_format($rows['pagos_monto'], 0, ',', '.').'</td>
                    <td>'.MONEDA." ".number_format(0, 0, ',', '.').'</td>
                    <td>Pago de inscripción</td>';
                    

                    if ($rows['pagos_estado']=="Pendiente") {
                        $tabla.='<td><span class="badge badge-danger">Pendiente</span></td>';
                    }
                    elseif($rows['pagos_estado']=="Pagado"){
                        $tabla.='<td><span class="badge badge-primary">Pagado</span></td>';
                    }

                    $tabla.='<td>
                        <a href="'.URL.'facturas/invoice.php?id='.mainModel::encryption($rows['pagos_id']).'" class="btn btn-success btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </td>';
                    
                    

                    if ($privilegio==1 || $privilegio==2) {
                        /* PERIMINTIR MODIFICAR SI TODAVIA NO SE PAGÓ TODO (para pagos)
                        if ( $rows['pagos_pagado'] == $rows['pagos_total']) {
                            $tabla.='<td>
                            <button class="btn btn-success" disabled>
                                <i class="fas fa-sync-alt"></i>
                            </a>
                            </button>';
                        }
                        else{
                            $tabla.='<td>
                                <a href="'.URL.'pagos-update/'.mainModel::encryption($rows['pagos_id']).'/" class="btn btn-success">
                                    <i class="fas fa-sync-alt"></i> 
                                </a>
                            </td>';
                        }*/

                        /*$tabla.='<td>
                            <a href="'.URL.'pagos-update/'.mainModel::encryption($rows['pagos_id']).'/" class="btn btn-success">
                                <i class="fas fa-sync-alt"></i> 
                            </a>
                        </td>';*/
                    }
                    
                    /*if ($privilegio==1 ) {
                        $tabla.='
                            <td>
                                <form class="FormularioAjax" action="'.URL.'ajax/pagosAjax.php" method="POST" data-form="eliminar" autocomplete="off">
                                    <input type="hidden" name="pagos_codigo_del" value="'.mainModel::encryption($rows['pagos_codigo']).'">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>';
                    }*/


                $tabla.='</tr>'; //el boton tiene que ser de tipo submit para enviar los formularios
                $contador++;
            }
            $reg_final=$contador-1;
        }
        else {
            if ($total>=1) { //si el total de los registros es mayor o igual a 1
                //si hay registros pero en la url se pone otro numero de pagina que no existe, entonces se mostrara el mensaje:
                $tabla.='
                <tr class="text-center">
                    <td colspan="10">
                        <a href= "'.$url.'" class="btn btn-raised btn-primary btn-sm" >click para recargar el listado.</a>
                    </td><
                /tr>'; //colspan="8" porque tenemos 9 columnas en total   
            }
            else { 
                //si no hay registros
                $tabla.='<tr class="text-center" > <td colspan="10"> NO HAY REGISTROS EN EL SISTEMA</td></tr>'; //colspan="9" porque tenemos 9 columnas en total
            }
            
        }
        
        $tabla.=
                    '</tbody>
            </table>
        </div>
            ';

        /* para mostrar un texto en donde indica la cantidad de usurios que hay en una pagina de listado */
        // mostrar solo cuando 1 o mas registros y cuando sea menor a la cantidad maxima de paginas que tiene
        if ($total>=1 && $pagina <= $Npaginas) {
            $tabla.='<p class="text-right">Mostrando pagos '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' </p>';

            $tabla.=mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        
        return $tabla;
    }/* ---------- FIN Controlador paginar pagos ----------- */



    /* ---------- Controlador ELIMINAR pagos ----------- */
    public function eliminar_pagos_controlador(){
        //recibe el id del pagos
        $codigo=mainModel::decryption($_POST['pagos_codigo_del']);//para desencriptar
        $codigo=mainModel::limpiar_cadenas($codigo);//para seguridad

        /* comprobar si existe la inscripcion*/
        $check_pagos = mainModel::consultar_consultas_simples("SELECT pagos_codigo FROM 
        pagos WHERE pagos_codigo = '$codigo'");
        if ($check_pagos->rowCount() <= 0) { //rowCount() para contar cuantos registros han sido afectados
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "La inscripción no existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* verificar privilegios */
        session_start(['name'=>'instituto']);
        if ($_SESSION['privilegio_instituto'] != 1) {
            //si es distinto a 1, entonces no tiene permiso, solo el administrador puede eliminar
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No tiene los permisos necesarios para realizar esta operación.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* verificar si esta relacionado con otras tablas (pagos), y eliminarlo */
        $check_pagos = mainModel::consultar_consultas_simples("SELECT pagos_codigo FROM 
        pagos WHERE pagos_codigo = '$codigo' LIMIT 1");//LIMIT 1 para seleccionar solo 1 registro de prestamo
        if ($check_pagos->rowCount() > 0) {
            $eliminar_pagos=pagosModelo::eliminar_pagos_modelo($codigo, "Pagos");
            if ($eliminar_pagos->rowCount()!= $check_pagos){//si es distinto a check pagos, entonces no se elimino todos los datos
                
                $alerta = [
                    "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "No se pudo eliminar la inscripción, intentelo nuevamente.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();       
            }
        }

        /* VERIFICAR SI SE ELIMINÓ */
        $eliminar_pagos=pagosModelo::eliminar_pagos_modelo($codigo, "pagos");
        if ($eliminar_pagos->rowCount()==1){// si se eliminó un registro
            $alerta = [
                "Alerta"=>"recargar", //alerta de tipo rergar para que se recargue la tabla
                "Titulo" => "Inscripción eliminada.",
                "Texto" => "La inscripción se ha sido eliminado exitosamente.",
                "Tipo" => "success"
            ];
                
        }
        else {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se pudo eliminar la inscripción, intentelo nuevamente.",
                "Tipo" => "error"
            ];
            
        }
        echo json_encode($alerta); //convertimos a JSON
        //exit();

    }/* ---------- FIN Controlador ELIMINAR pagos ----------- */

    /* ---------- Controlador datos de pagos ----------- */
    public function datos_pagos_controlador($tipo, $id){// $tipo de consulta
        $tipo =mainModel::limpiar_cadenas($tipo);

        $id=mainModel::decryption($id);
        $id=mainModel::limpiar_cadenas($id);

        $id=mainModel::limpiar_cadenas($id);

        return pagosModelo::datos_pagos_modelo($tipo, $id);

    }/* FIN controlador datos del pagos */

    /* ---------- Controlador actualizar datos del pagos ----------- */
    public function actualizar_pagos_controlador(){// 
        // recibiendo el codigo
        $codigo=mainModel::decryption($_POST['pagos_codigo_up']); //es el input hidden que estaba encriptado
        $codigo=mainModel::limpiar_cadenas($codigo);

        //verificar si existe el pagos en la bdd
        $check_pagos = mainModel::consultar_consultas_simples("SELECT * FROM pagos
        WHERE pagos_codigo = '$codigo'");

        if ($check_pagos->rowCount()<=0) { //si no se encuentra el pagos
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha encontrado la inscripción en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); 
        }

        $fecha = mainModel::limpiar_cadenas($_POST['pagos_fecha_inicio_up']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $hora = mainModel::limpiar_cadenas($_POST['pagos_hora_inicio_up']);
        $costo = mainModel::limpiar_cadenas($_POST['pagos_costo_up']); 
        $mensualidad = mainModel::limpiar_cadenas($_POST['pagos_mensualidad_up']);
        $estado = mainModel::limpiar_cadenas($_POST['pagos_estado_up']); 

        /* == verificar campos vacios == */
        if ($fecha=="" || $hora=="" || $costo=="" || $estado=="" || $mensualidad==""){
            
            //alerta es array de datos, luego lo convertimos a JSON
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "Llena los campos obligatorios.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); //para que no se ejecute mas el codigo
        }

        /* ===== verificar la integridad de los datos ===== */
        if (mainModel::verificar_fecha($fecha)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de FECHA incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("([0-1][0-9]|[2][0-3])[\:]([0-5][0-9])",$hora)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de HORA incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        if (mainModel::verificar_datos("[0-9.]{1,10}",$costo)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de COSTO incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        if (mainModel::verificar_datos("[0-9.]{1,10}",$mensualidad)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de MENSUALIDAD incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }





        // verificar el estado
        if ($estado!="Activo" && $estado!="Inactivo") {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El formato de ESTADO es incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* verificar privilegio */

        session_start(['name'=>'instituto']); //para utilizar las variables de sesion, exactamente la del privilegio

        // si el nivel es 3 o mas, entonces no tiene permiso para actualizar datos
        if ($_SESSION['privilegio_instituto'] < 1 || $_SESSION['privilegio_instituto'] > 2) { 
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "NO tienes los permisos necesarios para realizar esta oparacion.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $fecha = date("Y-m-d", strtotime($fecha));
        $hora = date("h:i a", strtotime($hora));

        /* == preparar los datos para enviarlo al modelo == */
        // los datos que estan entre comillas deben coincidir con los del modelo
        $datos_pagos_up=[
            "Fecha" => $fecha,
            "Hora" => $hora,
            "Costo" => $costo,
            "Mensualidad" => $mensualidad,
            "Estado" => $estado,
            "Codigo" => $codigo
        ];

        if (pagosModelo::actualizar_pagos_modelo($datos_pagos_up)) {
            $alerta = [
                "Alerta"=>"recargar", 
                "Titulo" => "Datos Actualizados.",
                "Texto" => "Los datos han sido actualizados con éxito.",
                "Tipo" => "success"
            ];
        }
        else {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "NO se ha podido actualizar los datos. Intente nuevamente.",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta);
        //no se coloca el exit porque ya es el final del codigo

    }/* FIN Controlador actualizar datos del pagos */

    public function datos_cuotas_controlador($codigo_inscripciones){

        $lista = "";
    
        /* verificar si existe estudiante en la bdd */
        $sql = "SELECT * FROM cuotas
            INNER JOIN inscripciones ON inscripciones.inscripciones_id = cuotas.inscripciones_id
            INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id
            INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
            WHERE cuotas.inscripciones_id = '$codigo_inscripciones'";

        $conexion = mainModel::conectar();

        $datos = $conexion -> query($sql);
        $datos = $datos ->fetchAll();

        /*<td>
            <label class="form-check-label" for="flexCheckDefault" style= "color: #777" > '.$rows['cuotas_mes'].'</label>
        </td> 
        
        <!-- <td><span class="badge badge-success">Pagado</span></td> -->
        
        */

        $lista .= 
            '
                <thead>
                    <tr class="text-center roboto-medium">
                        <th>Mes</th>
                        <th>Vencimiento</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Agregar</th>
                    </tr>
                </thead>
            ';

        foreach ($datos as $rows) {
            $lista .= '
                <tbody>
                    <tr class="text-center">
                        <td>
                            '.$rows['cuotas_mes'].'
                        </td>

                        <td>
                            '.$rows['cuotas_fecha_venci'].'
                        </td>


                        <td>
                            '.number_format($rows['cursos_mensualidad'], 0,'', '.').'
                        </td>

                        <td><span class="badge badge-danger">'. $rows['cuotas_estado'].'</span></td>

                        <td>
                            <div class="form-check">
                                <button type="button" class="btn btn-primary btn-sm"  onclick="agregar_cuotas('.$rows['cuotas_id'].')"><i class="fa-solid fa-plus"></i></button>
                            </div>
                        </td>

                    </tr>
                </tbody>
            ';
        }

        return $lista;
    }
}

