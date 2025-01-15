<?php
/* este controlador se va ejecutar dentro del archivo cursosajax
pero cuando no se utiliza la peticion ajax se va ajecutar en index.php  */

if ($peticionAjax) {
    # si es una peticion ajax, estamos en la carpeta ajax,
    #se sale de la carpeta ajax y entra en modelo
    require_once "../modelos/cursosModelo.php";
}
else {
    # si NO es una peticion ajax, estamos en el index.php,
    # y entra en modelo
    require_once "./modelos/cursosModelo.php";
}

class cursosControlador extends cursosModelo{

    /* ---------- Controlador agreagar cursos ----------- */
    public function agregar_cursos_controlador(){

        $codigo = mainModel::limpiar_cadenas($_POST['cursos_codigo_reg']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $nombre = mainModel::limpiar_cadenas($_POST['cursos_nombre_reg']); 
        $duracion = mainModel::limpiar_cadenas($_POST['cursos_duracion_reg']); 
        //$estado = mainModel::limpiar_cadenas($_POST['cursos_estado_reg']); 
        $detalle = mainModel::limpiar_cadenas($_POST['cursos_detalle_reg']);

        $sedes = mainModel::limpiar_cadenas($_POST['cursos_sedes_reg']);
        $precio = mainModel::limpiar_cadenas($_POST['cursos_precio_reg']);
        $mensualidad = mainModel::limpiar_cadenas($_POST['cursos_mensualidad_reg']);
        $cupos = mainModel::limpiar_cadenas($_POST['cursos_cupos_reg']);
        

        /* == verificar campos vacios == */
        if ($codigo=="" || $nombre=="" || $duracion=="" /*|| $estado==""*/ || $precio=="" || $mensualidad=="" || $cupos=="" || $sedes=="") {
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

        /* == verificar la integridad de los datos == */
        if (mainModel::verificar_datos("[a-zA-Z0-9-]{1,45}",$codigo)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de codigo incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}",$nombre)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de NOMBRE incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("(?:[1-9]|[1-9][0-9])",$duracion)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de duracion incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        
        /* ya que detalle es un campo que no es obligatorio primero comprobamos si hay texto */
        if ($detalle!="") {
            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$detalle)) {
                $alerta = [
                    "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "Formato de DETALLE incorrecto.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }
        }

        //verificamos si el value= del estado es otro valor
        // if ($estado!="Habilitado" && $estado!="Deshabilitado") {
        //     $alerta = [
        //         "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
        //         "Titulo" => "Error inesperado.",
        //         "Texto" => "Formato de ESTADO incorrecto.",
        //         "Tipo" => "error"
        //     ];
        //     echo json_encode($alerta); //convertimos a JSON
        //     exit();
        // }


        if ((mainModel::verificar_datos("(?:[1-9]|[1-9][0-9])",$sedes)) || $sedes <= 0) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de sedes incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        if (mainModel::verificar_datos("[1-9][0-9]{0,8}",$precio)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de precio incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("[1-9][0-9]{0,8}",$mensualidad)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de mensualidad incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        if (mainModel::verificar_datos("[1-9][0-9]{0,5}",$cupos)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de cupos incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }








        
        /* == verificar si existe codigo == */
        $check_codigo = mainModel::consultar_consultas_simples("SELECT cursos.cursos_codigo FROM cursos
            INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
            INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id
            WHERE cursos.cursos_codigo= '$codigo' AND cursos_sedes.sedes_id = $sedes;");
        if ($check_codigo->rowCount()>0) {
            $alerta = [
                "Alerta"=>"simple",  
                "Titulo" => "Error inesperado.",
                "Texto" => "El CÓDIGO del curso ya existe en esta sede.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* == verificar si existe codigo == */
        $check_nombre = mainModel::consultar_consultas_simples("SELECT cursos.cursos_nombre FROM cursos
            INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
            INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id
            WHERE cursos.cursos_nombre = '$nombre' AND cursos_sedes.sedes_id = $sedes;");

        if ($check_nombre->rowCount()>0) {
            $alerta = [
                "Alerta"=>"simple",  
                "Titulo" => "Error inesperado.",
                "Texto" => "El NOMBRE del curso ya existe en esta sede.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        /* alamcenamos en un array de datos los registros de los datos del cursos para guardarlo
        "Codigo" etc son los valores del modelo $datos['Codigo'] etc*/
        $datos_cursos_reg=[
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Duracion" => $duracion,
            // "Estado" => $estado,
            "Detalle" => $detalle
        ];

         /* para almacenar todo lo que devuelava agregar_cursos_modelo */
        $agregar_cursos = cursosModelo::agregar_cursos_modelo($datos_cursos_reg);

        
        // Obtener el último id registrado
        $id_max_result = mainModel::consultar_consultas_simples("SELECT MAX(cursos_id) AS max_id FROM cursos;");

        $row = $id_max_result->fetch(PDO::FETCH_ASSOC);
        $max_id = $row['max_id'];
        $max_id_string = (string)$max_id;


        $datos_detalles_reg=[
            "Cursos" => $max_id_string,
            "Sedes" => $sedes,
            "Precio" => $precio,
            "Mensualidad" => $mensualidad,
            "Cupos" => $cupos
        ];
        
        $agregar_detalles = cursosModelo::agregar_detalle_cursos_modelo($datos_detalles_reg);


        /* para comprobar si se insertó los registros en la bdd */
        if ($agregar_cursos->rowCount()==1 && $agregar_detalles->rowCount()==1) {
            $alerta = [
                "Alerta"=>"limpiar",
                "Titulo" => "Curso registrado.",
                "Texto" => "Datos del Curso registrado con éxito.",
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






    
    /* ---------- Controlador paginar cursos ----------- */
    public function paginador_cursos_controlador($pagina, $registros, $privilegio, $url, $busqueda){
        /*  */
        $pagina = mainModel::limpiar_cadenas($pagina);
        $registros = mainModel::limpiar_cadenas($registros);
        $privilegio = mainModel::limpiar_cadenas($privilegio);

        $url = mainModel::limpiar_cadenas($url);
        $url = URL.$url."/";

        $busqueda = mainModel::limpiar_cadenas($busqueda);
        $tabla = "";
        
        #para que solo tenga numeros enteros
        #hacemos con peperador ternario
        #si la pagian viene definida y es mayor a 0, entonces es un numero que esta en la url (...cursos-list/1/), si no, la pagina devuelve 1
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

        //condicion para saber si esta en la página de busqueda o no
        if (isset($busqueda) && $busqueda!="") {
            $consulta = "SELECT SQL_CALC_FOUND_ROWS cursos.cursos_id, cursos.cursos_codigo, cursos.cursos_nombre, sedes.sedes_nombre, cursos.cursos_duracion,
                cursos_sedes.cursos_precio, cursos_sedes.cursos_mensualidad, cursos_sedes.cursos_cupos, cursos.cursos_detalle, cursos.cursos_estado
                FROM cursos
                INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
                INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id
            WHERE
            cursos_codigo LIKE '%$busqueda%' OR
            cursos_nombre LIKE '%$busqueda%' ORDER BY cursos_nombre ASC LIMIT $inicio, $registros ";
        }
        else{
            /* para que la consulta solo seleccione un determinado listado
            y no se seleccione todos los registros (para que no se sature)
            
            */
            $consulta = "SELECT SQL_CALC_FOUND_ROWS cursos.cursos_id, cursos.cursos_codigo, cursos.cursos_nombre, sedes.sedes_nombre, cursos.cursos_duracion,
                cursos_sedes.cursos_precio, cursos_sedes.cursos_mensualidad, cursos_sedes.cursos_cupos, cursos.cursos_detalle, cursos.cursos_estado
                FROM cursos
                INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
                INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id ORDER BY cursos_nombre ASC LIMIT $inicio, $registros ";
        }
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
                            <th>NOMBRE</th>
                            <th>SEDE</th>
                            <th>PRECIO INSC.</th>
                            <th>MENSUALIDAD</th>
                            <th>DURACIÓN</th>
                            <th>CUPOS</th>
                            <th>DETALLE</th>
                            <th>ESTADO</th>';
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
                    <td>'.$rows['cursos_codigo'].'</td>
                    <td>'.$rows['cursos_nombre'].'</td>
                    
                    <td>'.$rows['sedes_nombre'].'</td> 
                    <td>'.number_format($rows['cursos_precio'], 0,'', '.').'</td> 
                    <td>'.number_format($rows['cursos_mensualidad'], 0,'', '.').'</td>
                    <td>'.$rows['cursos_duracion'].'</td>
                    <td>'.$rows['cursos_cupos'].'</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="popover" data-trigger="hover" title="'.$rows['cursos_nombre'].' " data-content="'.$rows['cursos_detalle'].'">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </td>
                    <td>'.$rows['cursos_estado'].'</td>';

                        if ($privilegio==1 || $privilegio==2) {
                            $tabla.='<td>
                                <a href="'.URL.'cursos-update/'.mainModel::encryption($rows['cursos_id']).'/" class="btn btn-warning btn-sm">
                                    <i class="fas fa-sync-alt"></i> 
                                </a>
                            </td>';
                        }
                        
                        if ($privilegio==1 ) {
                            $tabla.='
                                <td>
                                    <form class="FormularioAjax" action="'.URL.'ajax/cursosAjax.php" method="POST" data-form="eliminar" autocomplete="off">
                                        <input type="hidden" name="cursos_id_del" value="'.mainModel::encryption($rows['cursos_id']).'">
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
                    <td colspan="12">
                        <a href= "'.$url.'" class="btn btn-raised btn-primary btn-sm" >click para recargar el listado.</a>
                    </td><
                /tr>'; //colspan="8" porque tenemos 8 columnas en total   
            }
            else { 
                //si no hay registros
                $tabla.='<tr class="text-center" > <td colspan="12"> NO HAY REGISTROS EN EL SISTEMA</td></tr>'; //colspan="9" porque tenemos 9 columnas en total
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
            $tabla.='<p class="text-right">Mostrando cursos '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' </p>';

            $tabla.=mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        
        return $tabla;
    }/* ---------- FIN Controlador paginar cursos ----------- */



    /* ---------- Controlador ELIMINAR cursos ----------- */
    public function eliminar_cursos_controlador(){
        //recibe el id del cursos
        $id=mainModel::decryption($_POST['cursos_id_del']);//para desencriptar
        $id=mainModel::limpiar_cadenas($id);//para seguridad

        /* comprobar si existe el curso*/
        $check_cursos = mainModel::consultar_consultas_simples("SELECT cursos_id FROM 
        cursos WHERE cursos_id = '$id'");
        if ($check_cursos->rowCount() <= 0) { //rowCount() para contar cuantos registros han sido afectados
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El Curso no existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* comprobar si esta relacionado con otras tablas */
        $check_pagos = mainModel::consultar_consultas_simples("SELECT cursos_id FROM 
        inscripciones WHERE cursos_id = '$id' LIMIT 1");//LIMIT 1 para seleccionar solo 1 registro de prestamo
        if ($check_pagos->rowCount() > 0) { 
            //si hay 1 o mas registros, significa que hay 1 o mas cursoss asociados al prestamo
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se puede eliminar este curso debido a que tiene 
                inscripciones asociados. Deshabilita el curso si ya no será utilizado",
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


        /* verificar si esta relacionado con otras tablas (cursos_sedes), y eliminarlo */
        $check_sedes = mainModel::consultar_consultas_simples("SELECT sedes_id FROM 
        cursos_sedes WHERE sedes_id = '$id'");
        $check_sedes = $check_sedes->rowCount();

        if ($check_sedes>0) {
            $eliminar_sedes=cursosModelo::eliminar_cursos_modelo($id, "Detalles");
            if ($eliminar_sedes->rowCount()!= $check_sedes){//si es distinto a check sedes, entonces no se elimino todos los datos
                
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
        $eliminar_cursos=cursosModelo::eliminar_cursos_modelo($id, "Cursos");
        if ($eliminar_cursos->rowCount()==1){// si se eliminó un registro
            $alerta = [
                "Alerta"=>"recargar", //alerta de tipo rergar para que se recargue la tabla
                "Titulo" => "Curso eliminado.",
                "Texto" => "El curso ha sido eliminado exitosamente.",
                "Tipo" => "success"
            ];
                
        }
        else {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se podo eliminar el curso, intentelo nuevamente.",
                "Tipo" => "error"
            ];
            
        }
        echo json_encode($alerta); //convertimos a JSON
        //exit();

    }/* ---------- FIN Controlador ELIMINAR cursos ----------- */

    /* ---------- Controlador datos de curso ----------- */
    public function datos_cursos_controlador($tipo, $id){// $tipo de consulta
        $tipo =mainModel::limpiar_cadenas($tipo);

        $id=mainModel::decryption($id);
        $id=mainModel::limpiar_cadenas($id);

        return cursosModelo::datos_cursos_modelo($tipo, $id);

    }/* FIN controlador datos del cursos */

    /* ---------- Controlador actualizar datos del cursos ----------- */
    public function actualizar_cursos_controlador(){// 
        // recibiendo el ID
        $id=mainModel::decryption($_POST['cursos_id_up']); //es el input hidden que estaba encriptado
        $id=mainModel::limpiar_cadenas($id);

        //verificar si existe el curso en la bdd
        $check_cursos = mainModel::consultar_consultas_simples("SELECT * FROM cursos
        WHERE cursos_id = '$id'");

        if ($check_cursos->rowCount()<=0) { //si no se encuentra el cursos
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha encontrado el curso en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); 
        }
        else {
            $campos=$check_cursos->fetch();
        }

        $codigo = mainModel::limpiar_cadenas($_POST['cursos_codigo_up']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $nombre = mainModel::limpiar_cadenas($_POST['cursos_nombre_up']); 
        $duracion = mainModel::limpiar_cadenas($_POST['cursos_duracion_up']); 
        $estado = mainModel::limpiar_cadenas($_POST['cursos_estado_up']); 
        $detalle = mainModel::limpiar_cadenas($_POST['cursos_detalle_up']);

        $sedes = mainModel::limpiar_cadenas($_POST['cursos_sedes_up']);
        $precio = mainModel::limpiar_cadenas($_POST['cursos_precio_up']);
        $mensualidad = mainModel::limpiar_cadenas($_POST['cursos_mensualidad_up']);
        $cupos = mainModel::limpiar_cadenas($_POST['cursos_cupos_up']);




        /* == verificar campos vacios == */
        if ($codigo=="" || $nombre=="" || $duracion=="" || $estado=="" || $precio=="" || $mensualidad=="" || $cupos=="" || $sedes=="") {
            
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


        /* == verificar la integridad de los datos == */
        if (mainModel::verificar_datos("[a-zA-Z0-9-]{1,45}",$codigo)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de codigo incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}",$nombre)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de NOMBRE incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("(?:[1-9]|[1-9][0-9])",$duracion)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de duracion incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        

        if ($detalle!="") {
            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$detalle)) {
                $alerta = [
                    "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "Formato de DIRECCIÓN incorrecto.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }
        }


        // verificar el estado
        if ($estado!="Habilitado" && $estado!="Deshabilitado") {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El formato de ESTADO es incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        if ((mainModel::verificar_datos("(?:[1-9]|[1-9][0-9])",$sedes)) || $sedes <= 0) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de sedes incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        if (mainModel::verificar_datos("[1-9][0-9]{0,8}",$precio)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de precio incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("[1-9][0-9]{0,8}",$mensualidad)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de mensualidad incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        if (mainModel::verificar_datos("[1-9][0-9]{0,5}",$cupos)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de cupos incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }



        /* == verificar si existe codigo == */
        if($codigo!=$campos['cursos_codigo']) { //si el codigo es distinto al codigo que se desea modificar
            $check_codigo = mainModel::consultar_consultas_simples("SELECT cursos.cursos_codigo FROM cursos
            INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
            INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id
            WHERE cursos.cursos_codigo= '$codigo' AND cursos_sedes.sedes_id = $sedes;");

            if ($check_codigo->rowCount()>0) { //si existe la consulta
                $alerta = [
                    "Alerta"=>"simple",  
                    "Titulo" => "Error inesperado.",
                    "Texto" => "El codigo del curso ya pertenece a una sede.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }
        }


        /* == verificar si existe codigo == */
        if($nombre!=$campos['cursos_nombre']) { //si el nombre es distinto al nombre que se desea modificar
            $check_nombre = mainModel::consultar_consultas_simples("SELECT cursos.cursos_nombre FROM cursos
            INNER JOIN cursos_sedes ON cursos.cursos_id = cursos_sedes.cursos_id
            INNER JOIN sedes ON cursos_sedes.sedes_id = sedes.sedes_id
            WHERE cursos.cursos_nombre = '$nombre' AND cursos_sedes.sedes_id = $sedes;");
            if ($check_nombre->rowCount()>0) { //si existe la consulta
                $alerta = [
                    "Alerta"=>"simple",  
                    "Titulo" => "Error inesperado.",
                    "Texto" => "El nombre del curso ya pertenece a una sede.",
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

        /* == preparar los datos para enviarlo al modelo == */
        // los datos que estan entre comillas deben coincidir con los del modelo
        $datos_cursos_up=[
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Duracion" => $duracion,
            "Estado" => $estado,
            "Detalle" => $detalle,
            "ID" => $id
        ];

        $datos_detalles_up=[
            "ID" => $id,
            "Sedes" => $sedes,
            "Precio" => $precio,
            "Mensualidad" => $mensualidad,
            "Cupos" => $cupos
        ];


        if (cursosModelo::actualizar_cursos_modelo($datos_cursos_up) && cursosModelo::actualizar_detalle_cursos_modelo($datos_detalles_up)) {
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

    }/* FIN Controlador actualizar datos del cursos */



    public function lista_sedes_cursos_controlador() {
        $lista = "";
        $consulta = "SELECT * FROM sedes
        ORDER BY sedes_nombre ASC";
        $conexion = mainModel::conectar();
        //variable para alamacenar los datos
        $datos = $conexion -> query($consulta);
        $datos = $datos ->fetchAll();

        foreach ($datos as $rows) {
            $lista .= '<option value="'.$rows['sedes_id'].'">'.$rows['sedes_nombre'].'</option>';
        }

        return $lista;
    }








}
