<?php
/* este controlador se va ejecutar dentro del archivo inscripcionesajax
pero cuando no se utiliza la peticion ajax se va ajecutar en index.php  */

if ($peticionAjax) {
    # si es una peticion ajax, estamos en la carpeta ajax,
    #se sale de la carpeta ajax y entra en modelo
    require_once "../modelos/inscripcionesModelo.php";
}
else {
    # si NO es una peticion ajax, estamos en el index.php,
    # y entra en modelo
    require_once "./modelos/inscripcionesModelo.php";
}

class inscripcionesControlador extends inscripcionesModelo{

    /* ---------- Controlador buscar estudiantes inscripciones ----------- */
    public function buscar_estudiantes_inscripciones_controlador(){
        //recuperar los textos enviados
        $estudiantes = mainModel::limpiar_cadenas($_POST['buscar_estudiantes']); 

        // verificar si esta vacio el campo de busquda de estudiantes
        if ($estudiantes == "") {
            //copiamos de la alerta de inscripciones-new
            return '
                <div class="alert alert-warning" role="alert">
                    <p class="text-center mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                        Debes introducir el DNI, Nombre o Apellido
                    </p>
                </div>';
            exit();
        }

        /* seleccionar los datos de los estudiantes */
        $datos_estudiantes = mainModel::consultar_consultas_simples("SELECT * FROM estudiantes
        WHERE estudiantes_dni LIKE '%$estudiantes%' OR
        estudiantes_nombre LIKE '%$estudiantes%' OR
        estudiantes_apellido LIKE '%$estudiantes%' 
        ORDER BY estudiantes_nombre ASC");

        //verifiar si existe datos
        if ($datos_estudiantes->rowCount() >= 1) {

            //array de datos, para seleccionar todos los datos de los estudiantes
            $datos_estudiantes = $datos_estudiantes->fetchAll();

            $tabla='
                <div class="table-responsive-1">
                    <table class="table table-hover table-sm">
                        <tbody>';

            foreach($datos_estudiantes as $rows){
                $tabla.='
                    <tr class="text-center">
                        <td>'.$rows['estudiantes_nombre'].' '.$rows['estudiantes_apellido'].' - '.$rows['estudiantes_dni'].' </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" onclick="agregar_estudiantes('.$rows['estudiantes_id'].')"><i class="fas fa-user-plus"></i></button>
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
                        No hemos encontrado ningún estudiante en el sistema
                        que coincida con <strong>“'.$estudiantes.'”</strong>
                    </p>
                </div>';
            exit();
        } 
        
    } /* ---- FIN Controlador buscar estudiantes inscripciones ---- */


    /* ---------- agregar estudiantes a inscripciones ----------- */
    public function agregar_estudiantes_inscripciones_controlador(){
        //recuperar los textos enviados
        $id = mainModel::limpiar_cadenas($_POST['id_agregar_estudiantes']);

        /* verificar si existe estudiante en la bdd */
        $check_estudiantes = mainModel::consultar_consultas_simples("SELECT * FROM estudiantes
        WHERE estudiantes_id = '$id'");

        if ($check_estudiantes ->rowCount()<=0) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha podido encontrar el estudiante en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        else{
            $campos = $check_estudiantes->fetch();
        }

        //utilizamos la variable session para garantizar que,
        //al agregar un estudiante al pago, dicha variable no se elimine.
        session_start(['name'=>'instituto']);
        if (empty($_SESSION['datos_estudiantes'])) {

            $_SESSION['datos_estudiantes'] = [
                "ID" => $campos['estudiantes_id'],
                "DNI" => $campos['estudiantes_dni'],
                "Nombre" => $campos['estudiantes_nombre'],
                "Apellido" => $campos['estudiantes_apellido']
            ];

            $alerta = [
                "Alerta"=>"recargar", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Agregado.",
                "Texto" => "Estudiante agregado para realizar la inscripción.",
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

    /* ---------- eliminar estudiantes a inscripciones ----------- */
    public function eliminar_estudiantes_inscripciones_controlador(){

        session_start(['name'=>'instituto']);
        unset($_SESSION['datos_estudiantes']);//eliminar los datos del cliente de la sesion

        //si esta vacio
        if (empty( $_SESSION['datos_estudiantes'])) {
            $alerta = [
                "Alerta"=>"recargar", 
                "Titulo" => "Estudiante removido.",
                "Texto" => "Datos del estudiante removidos.",
                "Tipo" => "success"
            ];
        }
        else{ //si contiend los datos de estudiantes
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha podido remover los datos del estudiante.",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta); //fuera de la condicional doble
    }/* Fin */






    /* ---------- Controlador buscar cursos inscripciones ----------- */
    public function buscar_cursos_inscripciones_controlador(){
        //recuperar los textos enviados
        $cursos = mainModel::limpiar_cadenas($_POST['buscar_cursos']); 

        // verificar si esta vacio el campo de busquda de estudiantes
        if ($cursos == "") {
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

        /* seleccionar los datos de los cursos */
        $datos_cursos = mainModel::consultar_consultas_simples("SELECT *
                FROM cursos
                INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
                INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id
            WHERE cursos_codigo LIKE '%$cursos%' OR
            cursos_nombre LIKE '%$cursos%' AND
            (cursos_estado ='Habilitado')
        ORDER BY cursos_nombre ASC");

        //verifiar si existe datos
        if ($datos_cursos->rowCount() >= 1) {

            //array de datos, para seleccionar todos los datos de los estudiantes
            $datos_cursos = $datos_cursos->fetchAll();

            $tabla='
                <div class="table-responsive-1">
                    <table class="table table-hover table-sm">
                        <tbody>';

            foreach($datos_cursos as $rows){
                $tabla.='
                    <tr class="text-center">
                        <td>'.$rows['cursos_codigo'].' - '.$rows['cursos_nombre'].' - '.$rows['sedes_nombre'].' - Cupos: '.$rows['cursos_cupos'].' </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" onclick="agregar_cursos('.$rows['cursos_id'].')"><i class="fa-solid fa-book-open-reader"></i></button>
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
                        que coincida con <strong>“'.$cursos.'”</strong>
                    </p>
                </div>';
            exit();
        } 
        
    } /* ---- FIN Controlador buscar cursos inscripciones ---- */
    
    
    /* ---------- agregar cursos a inscripciones ----------- */
    public function agregar_cursos_inscripciones_controlador(){
        //recuperar los textos enviados
        $id = mainModel::limpiar_cadenas($_POST['id_agregar_cursos']);

        /* verificar si existe cursos en la bdd */
        $check_cursos = mainModel::consultar_consultas_simples("SELECT *
                FROM cursos
                INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
                INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id
            WHERE cursos.cursos_id = '$id' AND cursos_estado = 'Habilitado'");
        if ($check_cursos ->rowCount()<=0) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha podido encontrar el curso en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        else{
            $campos = $check_cursos->fetch();
        }


        //utilizamos la variable session para garantizar que,
        //al agregar un estudiante al pago, dicha variable no se elimine.
        session_start(['name'=>'instituto']);
        if (empty($_SESSION['datos_cursos'])) {

            $_SESSION['datos_cursos'] = [
                "ID" => $campos['cursos_id'],
                "Codigo" => $campos['cursos_codigo'],
                "Nombre" => $campos['cursos_nombre'],
                "Duracion" => $campos['cursos_duracion'],
                "Detalle" => $campos['cursos_detalle'],

                "Sede" => $campos['sedes_nombre'],
                "Precio" => $campos['cursos_precio'],
                "Mensualidad" => $campos['cursos_mensualidad'],
                "Cupos" => $campos['cursos_cupos']
            ];

            $alerta = [
                "Alerta"=>"recargar", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Agregado.",
                "Texto" => "Curso agregado para realizar la inscripción.",
                "Tipo" => "success"
            ];
            echo json_encode($alerta);

        }
        else {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El curso ya se encuentra seleccionado.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
        }






    } 

    /* ---------- eliminar cursos a inscripciones ----------- */
    public function eliminar_cursos_inscripciones_controlador(){

        session_start(['name'=>'instituto']);
        unset($_SESSION['datos_cursos']);//eliminar los datos del cliente de la sesion

        //si esta vacio
        if (empty( $_SESSION['datos_cursos'])) {
            $alerta = [
                "Alerta"=>"recargar", 
                "Titulo" => "Curso removido.",
                "Texto" => "Datos del curso removidos.",
                "Tipo" => "success"
            ];
        }
        else{ //si contiend los datos de estudiantes
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha podido remover los datos del curso.",
                "Tipo" => "error"
            ];
        }
        echo json_encode($alerta); //fuera de la condicional doble
    }/* Fin */
    

    



    /* ---------- Controlador buscar productos inscripciones ----------- */
    // public function buscar_productos_inscripciones_controlador(){
    //     //recuperar los textos enviados
    //     $productos = mainModel::limpiar_cadenas($_POST['buscar_productos']); 

    //     // verificar si esta vacio el campo de busquda de estudiantes
    //     if ($productos == "") {
    //         //copiamos de la alerta de inscripciones-new
    //         return '
    //             <div class="alert alert-warning" role="alert">
    //                 <p class="text-center mb-0">
    //                     <i class="fas fa-exclamation-triangle fa-2x"></i><br>
    //                     Debes introducir el Código o Nombre del curso
    //                 </p>
    //             </div>';
    //         exit();
    //     }

    //     /* seleccionar los datos de los productos */
    //     $datos_productos = mainModel::consultar_consultas_simples("SELECT * FROM productos
    //     WHERE productos_codigo LIKE '%$productos%' OR
    //     productos_nombre LIKE '%$productos%'
    //     ORDER BY productos_nombre ASC");

    //     //verifiar si existe datos
    //     if ($datos_productos->rowCount() >= 1) {

    //         //array de datos, para seleccionar todos los datos de los estudiantes
    //         $datos_productos = $datos_productos->fetchAll();

    //         $tabla='
    //             <div class="table-responsive-1">
    //                 <table class="table table-hover table-sm">
    //                     <tbody>';

    //         foreach($datos_productos as $rows){
    //             $tabla.='
    //                 <tr class="text-center">
    //                     <td>'.$rows['productos_codigo'].' - '.$rows['productos_nombre'].' </td>
    //                     <td>
    //                         <button type="button" class="btn btn-primary btn-sm" onclick="agregar_productos('.$rows['productos_id'].')"><i class="fa-solid fa-book-open-reader"></i></button>
    //                     </td>
    //                 </tr>
    //             ';
    //         }
            
    //         $tabla.='
    //                     </tbody>
    //                 </table>
    //             </div>';
    //             return $tabla;
    //     }else{
    //         return '
    //             <div class="alert alert-warning" role="alert">
    //                 <p class="text-center mb-0">
    //                     <i class="fas fa-exclamation-triangle fa-2x"></i><br>
    //                     No hemos encontrado ningún curso en el sistema
    //                     que coincida con <strong>“'.$productos.'”</strong>
    //                 </p>
    //             </div>';
    //         exit();
    //     } 
        
    // } /* ---- FIN Controlador buscar productos inscripciones ---- */
    
    
    // /* ---------- agregar productos a inscripciones ----------- */
    // public function agregar_productos_inscripciones_controlador(){
    //     //recuperar los textos enviados
    //     $id = mainModel::limpiar_cadenas($_POST['id_agregar_productos']);

    //     /* verificar si existe productos en la bdd */
    //     $check_productos = mainModel::consultar_consultas_simples("SELECT * FROM productos
    //         WHERE productos_id = '$id'");
    //     if ($check_productos ->rowCount()<=0) {
    //         $alerta = [
    //             "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
    //             "Titulo" => "Error inesperado.",
    //             "Texto" => "No se ha podido encontrar el curso en el sistema.",
    //             "Tipo" => "error"
    //         ];
    //         echo json_encode($alerta); //convertimos a JSON
    //         exit();
    //     }
    //     else{
    //         $campos = $check_productos->fetch();
    //     }







    //     //utilizamos la variable session para garantizar que,
    //     //al agregar un estudiante al pago, dicha variable no se elimine.
    //     session_start(['name'=>'instituto']);
    //     if (empty($_SESSION['datos_productos'])) {

    //         $_SESSION['datos_productos'] = [
    //             "ID" => $campos['productos_id'],
    //             "Codigo" => $campos['productos_codigo'],
    //             "Nombre" => $campos['productos_nombre'],
    //             "Precio" => $campos['productos_precio'],
    //             "Cantidad" => $campos['productos_cantidad'],
    //             "Iva" => $campos['productos_iva']
    //         ];

    //         $alerta = [
    //             "Alerta"=>"recargar", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
    //             "Titulo" => "Agregado.",
    //             "Texto" => "Curso agregado para realizar la inscripción.",
    //             "Tipo" => "success"
    //         ];
    //         echo json_encode($alerta);

    //     }
    //     else {
    //         $alerta = [
    //             "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
    //             "Titulo" => "Error inesperado.",
    //             "Texto" => "El curso ya se encuentra seleccionado.",
    //             "Tipo" => "error"
    //         ];
    //         echo json_encode($alerta);
    //     }






    // } 

    // /* ---------- eliminar productos a inscripciones ----------- */
    // public function eliminar_productos_inscripciones_controlador(){

    //     session_start(['name'=>'instituto']);
    //     unset($_SESSION['datos_productos']);//eliminar los datos del cliente de la sesion

    //     //si esta vacio
    //     if (empty( $_SESSION['datos_productos'])) {
    //         $alerta = [
    //             "Alerta"=>"recargar", 
    //             "Titulo" => "Curso removido.",
    //             "Texto" => "Datos del curso removidos.",
    //             "Tipo" => "success"
    //         ];
    //     }
    //     else{ //si contiend los datos de estudiantes
    //         $alerta = [
    //             "Alerta"=>"simple",
    //             "Titulo" => "Error inesperado.",
    //             "Texto" => "No se ha podido remover los datos del curso.",
    //             "Tipo" => "error"
    //         ];
    //     }
    //     echo json_encode($alerta); //fuera de la condicional doble
    // }/* Fin */













    /* ---------- Controlador agreagar inscripciones ----------- */
    public function agregar_inscripciones_controlador(){

        session_start(['name'=>'instituto']);
        /* si no hay un curso seleccionado */
        if(empty($_SESSION['datos_cursos'])){
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Curso no seleccionado.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* si no hay un estudiante seleccionado */
        if(empty($_SESSION['datos_estudiantes'])){
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Estudiante no seleccionado.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /*  */

        
        $fecha = mainModel::limpiar_cadenas($_POST['inscripciones_fecha_inicio_reg']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $hora = mainModel::limpiar_cadenas($_POST['inscripciones_hora_inicio_reg']); 
        // $costo = mainModel::limpiar_cadenas($_POST['inscripciones_costo_reg']); 
        // $mensualidad = mainModel::limpiar_cadenas($_POST['inscripciones_mensualidad_reg']); 
        

        /* == verificar campos vacios == */
        if ($fecha=="" || $hora=="" /*|| $costo=="" || $mensualidad==""*/) {
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

        // if (mainModel::verificar_datos("[0-9.]{1,10}",$costo)) {
        //     $alerta = [
        //         "Alerta"=>"simple", 
        //         "Titulo" => "Error inesperado.",
        //         "Texto" => "Formato de COSTO incorrecto.",
        //         "Tipo" => "error"
        //     ];
        //     echo json_encode($alerta); //convertimos a JSON
        //     exit();
        // }


        // if (mainModel::verificar_datos("[0-9.]{1,10}",$mensualidad)) {
        //     $alerta = [
        //         "Alerta"=>"simple", 
        //         "Titulo" => "Error inesperado.",
        //         "Texto" => "Formato de MENSUALIDAD incorrecto.",
        //         "Tipo" => "error"
        //     ];
        //     echo json_encode($alerta); //convertimos a JSON
        //     exit();
        // }


        /* == verificar si el estudiante ya esta inscrito en un curso == */
        $estudiantes_id = $_SESSION['datos_estudiantes']['ID'];
        $cursos_id = $_SESSION['datos_cursos']['ID']; 
        $check_estudiantes = mainModel::consultar_consultas_simples("SELECT inscripciones_id FROM inscripciones WHERE estudiantes_id = $estudiantes_id AND cursos_id = $cursos_id;");
        if ($check_estudiantes->rowCount()>0) {
            $alerta = [
                "Alerta"=>"simple",  
                "Titulo" => "Error inesperado.",
                "Texto" => "El ESTUDIANTE ya se encuentra inscrito en este curso.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }



        /* == formateando los montos, fechas y horas == */
        /*number_format($costo, 2,'.', '')
        2 indica cuantos decimales
        '.' separador de decimales
        '' seperador de millares
        */
        // $costo = number_format($costo, 2,'.', '');
        // $mensualidad = number_format($mensualidad, 2,'.', '');
        $fecha = date("Y-m-d", strtotime($fecha));
        $hora = date("h:i a", strtotime($hora));
        

        /* generar codigo */
        $correlativo = mainModel::consultar_consultas_simples("SELECT inscripciones_id FROM inscripciones;");
        $correlativo = ($correlativo->rowCount())+1;
        
        $codigo = mainModel::generar_codigo_aleatorios("INSC",7,$correlativo);
        
        /* alamcenamos en un array de datos los registros de los datos del inscripciones para guardarlo
        "Codigo" etc son los valores del modelo $datos['Codigo'] etc*/
        $datos_inscripciones_reg=[
            "Codigo" => $codigo,
            "Fecha" => $fecha,
            "Hora" => $hora,
            // "Costo" => $costo,
            // "Mensualidad" => $mensualidad,
            "Estado" => 'Activo',

            "Estudiantes" => $_SESSION['datos_estudiantes']['ID'],
            "Cursos" => $_SESSION['datos_cursos']['ID'],
            // "Productos" => $_SESSION['datos_productos']['ID']
        ];



        /* para almacenar todo lo que devuelava agregar_inscripciones_modelo */
        $agregar_inscripciones = inscripcionesModelo::agregar_inscripciones_modelo($datos_inscripciones_reg);

        /* para comprobar si se insertó los registros en la bdd */
        if ($agregar_inscripciones->rowCount()==1) {
            inscripcionesModelo::disminuir_cupos_modelo($_SESSION['datos_cursos']['ID']);


            /* agregar datos de cuotas */
            $check_inscripciones = mainModel::consultar_consultas_simples("SELECT inscripciones_id FROM 
                inscripciones WHERE inscripciones_codigo = '$codigo'");
            $campos = $check_inscripciones->fetch();
            inscripcionesModelo::agregar_cuota_modelo($campos['inscripciones_id']);



            
            //vaciamos los datos del estudiante y del curso
            unset($_SESSION['datos_estudiantes']);
            unset($_SESSION['datos_cursos']);
            // unset($_SESSION['datos_productos']);


            $alerta = [
                "Alerta"=>"recargar",
                "Titulo" => "Inscripción registrada.",
                "Texto" => "Datos de la inscripción registrado con éxito.",
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


    
    /* ---------- Controlador paginar inscripciones ----------- */
    public function paginador_inscripciones_controlador($pagina, $registros, $privilegio, $url, $tipo, $fecha_inicio, $fecha_final){
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
        #si la pagian viene definida y es mayor a 0, entonces es un numero que esta en la url (...inscripciones-list/1/), si no, la pagina devuelve 1
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
            "inscripciones.inscripciones_id,
            inscripciones.inscripciones_codigo,
            inscripciones.inscripciones_fecha,
            inscripciones.inscripciones_hora,

            inscripciones.inscripciones_costo,
            inscripciones.inscripciones_mensualidad,

            sedes.sedes_nombre,
            cursos_sedes.cursos_precio,
            cursos_sedes.cursos_mensualidad,
            cursos_sedes.cursos_cupos,

            inscripciones.inscripciones_estado,
            inscripciones.cursos_id,
            inscripciones.estudiantes_id,
            cursos.cursos_nombre,
            estudiantes.estudiantes_nombre,
            estudiantes.estudiantes_apellido
            ";

        //condicion para saber si esta en la página de busqueda o no
        if ($tipo == "Busqueda" && $fecha_inicio!="" && $fecha_final!="") {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos FROM inscripciones
            INNER JOIN estudiantes ON inscripciones.estudiantes_id = estudiantes.estudiantes_id
            INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id

            INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
            INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id

            WHERE (inscripciones.inscripciones_fecha BETWEEN '$fecha_inicio' AND '$fecha_final')
            ORDER BY inscripciones.inscripciones_fecha DESC LIMIT $inicio, $registros";
        }
        else{
            /* para que la consulta solo seleccione un determinado listado
            y no se seleccione todos los registros (para que no se sature)
            
            */
            $consulta = "SELECT SQL_CALC_FOUND_ROWS $campos FROM inscripciones
            INNER JOIN estudiantes ON inscripciones.estudiantes_id = estudiantes.estudiantes_id
            INNER JOIN cursos ON inscripciones.cursos_id = cursos.cursos_id

            INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
            INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id
            
            ORDER BY inscripciones.inscripciones_fecha DESC LIMIT $inicio, $registros";
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
                        <tr class="text-center">
                            <th>#</th>
                            <th>CÓDIGO</th>
                            <th>ESTUDIANTE</th>
                            <th>SEDE</th>
                            <th>FECHA</th>
                            <th>CURSO</th>
                            <th>INSCRIPCIÓN</th>
                            <th>MENSUALIDAD</th>
                            <th>ESTADO</th>';
                            if ($privilegio==1 || $privilegio==2) { //los que tengan privilegio 1 o 2 pueden realizar pagos
                                $tabla.='<th>PAGAR</th>';
                                
                            }
                            if ($privilegio==1 || $privilegio==2) { //los que tengan privilegio 1 o 2 pueden actualizar
                                $tabla.='<th>ACTUALIZAR</th>';
                                
                            }
                            if ($privilegio==1) { //los que tengan privilegio 1 pueden eliminar
                                $tabla.='<th>ELIMINAR</th>';
                            }
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
                    <td>'.$rows['inscripciones_codigo'].'</td>
                    <td>'.$rows['estudiantes_nombre']." ".$rows['estudiantes_apellido'].'</td>
                    <td>'.$rows['sedes_nombre'].'</td>
                    <td>'.date("d-m-Y",strtotime($rows['inscripciones_fecha'])).'</td>
                    <td>'.$rows['cursos_nombre'].'</td>
                    <td>'.MONEDA." ".number_format($rows['cursos_precio'], 0, ',', '.').'</td>
                    <td>'.MONEDA." ".number_format($rows['cursos_mensualidad'], 0, ',', '.').'</td>
                    <td>'.$rows['inscripciones_estado'].'</td>';

                    if ($privilegio==1 || $privilegio==2) { //los que tengan privilegio 1 o 2 pueden realizar pagos
                        $tabla.='
                        <td>
                            <a href="'.URL.'pagos-update/'.mainModel::encryption($rows['inscripciones_id']).'/" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-guarani-sign"></i> 
                                <!-- <i class="fas fa-file-pdf"></i>  -->
                            </a>
                        </td>';
                    }

                    /*para factura (para pagos)
                        $tabla='
                        <td>
                            <a href="'.UTL.'facturas/invoice.php?id='.mainModel::encryption($rows['pagos_id']).'" class="btn btn-info" target="_blank">
                                <i class="fa-solid fa-guarani-sign"></i> 
                                <!-- <i class="fas fa-file-pdf"></i>  -->
                            </a>
                        </td>';
                    */
                    

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

                        $tabla.='<td>
                            <a href="'.URL.'inscripciones-update/'.mainModel::encryption($rows['inscripciones_id']).'/" class="btn btn-warning btn-sm">
                                <i class="fas fa-sync-alt"></i> 
                            </a>
                        </td>';
                    }
                    
                    if ($privilegio==1 ) {
                        $tabla.='
                            <td>
                                <form class="FormularioAjax" action="'.URL.'ajax/inscripcionesAjax.php" method="POST" data-form="eliminar" autocomplete="off">
                                    <input type="hidden" name="inscripciones_codigo_del" value="'.mainModel::encryption($rows['inscripciones_codigo']).'">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>';
                    }


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
                    <td colspan="9">
                        <a href= "'.$url.'" class="btn btn-raised btn-primary btn-sm" >click para recargar el listado.</a>
                    </td><
                /tr>'; //colspan="8" porque tenemos 9 columnas en total   
            }
            else { 
                //si no hay registros
                $tabla.='<tr class="text-center" > <td colspan="9"> NO HAY REGISTROS EN EL SISTEMA</td></tr>'; //colspan="9" porque tenemos 9 columnas en total
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
            $tabla.='<p class="text-right">Mostrando inscripciones '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' </p>';

            $tabla.=mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        
        return $tabla;
    }/* ---------- FIN Controlador paginar inscripciones ----------- */



    /* ---------- Controlador ELIMINAR inscripciones ----------- */
    public function eliminar_inscripciones_controlador(){
        //recibe el id del inscripciones
        $codigo=mainModel::decryption($_POST['inscripciones_codigo_del']);//para desencriptar
        $codigo=mainModel::limpiar_cadenas($codigo);//para seguridad

        /* comprobar si existe la inscripcion*/
        $check_inscripciones = mainModel::consultar_consultas_simples("SELECT inscripciones_codigo FROM 
        inscripciones WHERE inscripciones_codigo = '$codigo'");
        if ($check_inscripciones->rowCount() <= 0) { //rowCount() para contar cuantos registros han sido afectados
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
        $check_pagos = mainModel::consultar_consultas_simples("SELECT inscripciones_codigo FROM 
        pagos WHERE inscripciones_codigo = '$codigo'");
        $check_pagos = $check_pagos->rowCount();
        if ($check_pagos>0) {
            $eliminar_pagos=inscripcionesModelo::eliminar_inscripciones_modelo($codigo, "Pagos");
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

        /* datos necesarios para aumentar cupos */
        $datos_inscripciones = inscripcionesModelo::datos_inscripciones_modelo("Unico2", $codigo);
        $datos_inscripciones = $datos_inscripciones->fetch();

        /* VERIFICAR SI SE ELIMINÓ */
        $eliminar_inscripciones=inscripcionesModelo::eliminar_inscripciones_modelo($codigo, "Inscripciones");

        if ($eliminar_inscripciones->rowCount()==1){// si se eliminó un registro


            /* aumentar cupos de cursos al eliminar una inscripcion */
            inscripcionesModelo::aumentar_cupos_modelo($datos_inscripciones['cursos_id']);

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

    }/* ---------- FIN Controlador ELIMINAR inscripciones ----------- */

    /* ---------- Controlador datos de inscripciones ----------- */
    public function datos_inscripciones_controlador($tipo, $id){// $tipo de consulta
        $tipo =mainModel::limpiar_cadenas($tipo);

        $id=mainModel::decryption($id);
        $id=mainModel::limpiar_cadenas($id);

        return inscripcionesModelo::datos_inscripciones_modelo($tipo, $id);

    }/* FIN controlador datos del inscripciones */

    /* ---------- Controlador actualizar datos del inscripciones ----------- */
    public function actualizar_inscripciones_controlador(){// 
        // recibiendo el codigo
        $codigo=mainModel::decryption($_POST['inscripciones_codigo_up']); //es el input hidden que estaba encriptado
        $codigo=mainModel::limpiar_cadenas($codigo);

        //verificar si existe el inscripciones en la bdd
        $check_inscripciones = mainModel::consultar_consultas_simples("SELECT * FROM inscripciones
        WHERE inscripciones_codigo = '$codigo'");

        if ($check_inscripciones->rowCount()<=0) { //si no se encuentra el inscripciones
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha encontrado la inscripción en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); 
        }

        $fecha = mainModel::limpiar_cadenas($_POST['inscripciones_fecha_inicio_up']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $hora = mainModel::limpiar_cadenas($_POST['inscripciones_hora_inicio_up']);
        $estado = mainModel::limpiar_cadenas($_POST['inscripciones_estado_up']);
        $cursos = mainModel::limpiar_cadenas($_POST['cursos_id_up']);
        $estudiantes_id = mainModel::limpiar_cadenas($_POST['estudiantes_id']);
        // $costo = mainModel::limpiar_cadenas($_POST['inscripciones_costo_up']); 
        // $mensualidad = mainModel::limpiar_cadenas($_POST['inscripciones_mensualidad_up']);

        /* == verificar campos vacios == */
        if ($fecha=="" || $hora=="" || $estado=="" || $cursos=="") {
            
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


        // if (mainModel::verificar_datos("[0-9.]{1,10}",$costo)) {
        //     $alerta = [
        //         "Alerta"=>"simple", 
        //         "Titulo" => "Error inesperado.",
        //         "Texto" => "Formato de COSTO incorrecto.",
        //         "Tipo" => "error"
        //     ];
        //     echo json_encode($alerta); //convertimos a JSON
        //     exit();
        // }


        // if (mainModel::verificar_datos("[0-9.]{1,10}",$mensualidad)) {
        //     $alerta = [
        //         "Alerta"=>"simple", 
        //         "Titulo" => "Error inesperado.",
        //         "Texto" => "Formato de MENSUALIDAD incorrecto.",
        //         "Tipo" => "error"
        //     ];
        //     echo json_encode($alerta); //convertimos a JSON
        //     exit();
        // }



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


        /* == verificar si el estudiante ya esta inscrito en un curso == */
        $check_inscripciones = mainModel::consultar_consultas_simples("SELECT * FROM inscripciones WHERE inscripciones_codigo = '$codigo'");
        $check_inscripciones = $check_inscripciones->fetch();

        /* verificar el id del curso es diferentes a los modificados */
        if ($cursos != $check_inscripciones['cursos_id']) {

            $check_estudiantes = mainModel::consultar_consultas_simples("SELECT inscripciones_id FROM inscripciones WHERE estudiantes_id = $estudiantes_id AND cursos_id = $cursos;");
            if ($check_estudiantes->rowCount()>0 ) {
                $alerta = [
                    "Alerta"=>"simple",  
                    "Titulo" => "Error inesperado.",
                    "Texto" => "El ESTUDIANTE ya se encuentra inscrito en este curso.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }
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
        $datos_inscripciones_up=[
            // "Costo" => $costo,
            // "Mensualidad" => $mensualidad,
            "Fecha" => $fecha,
            "Hora" => $hora,
            "Estado" => $estado,
            "Codigo" => $codigo,
            "Cursos" => $cursos

        ];

        /* aumentar o disminuir cupos dependiendo de la actualizacion del curso */
        if ($cursos != $check_inscripciones['cursos_id']) {
            inscripcionesModelo::aumentar_cupos_modelo($check_inscripciones['cursos_id']);
            inscripcionesModelo::disminuir_cupos_modelo($cursos);
        }



        if (inscripcionesModelo::actualizar_inscripciones_modelo($datos_inscripciones_up)) {

            unset($_SESSION['datos_cursos']);

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

    }/* FIN Controlador actualizar datos del inscripciones */
}