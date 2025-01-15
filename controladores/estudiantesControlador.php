<?php
/* este controlador se va ejecutar dentro del archivo estudiantesajax
pero cuando no se utiliza la peticion ajax se va ajecutar en index.php  */

if ($peticionAjax) {
    # si es una peticion ajax, estamos en la carpeta ajax,
    #se sale de la carpeta ajax y entra en modelo
    require_once "../modelos/estudiantesModelo.php";
}
else {
    # si NO es una peticion ajax, estamos en el index.php,
    # y entra en modelo
    require_once "./modelos/estudiantesModelo.php";
}

class estudiantesControlador extends estudiantesModelo{

    /* ---------- Controlador agreagar estudiantes ----------- */
    public function agregar_estudiantes_controlador(){
        
        $dni = mainModel::limpiar_cadenas($_POST['estudiantes_dni_reg']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $nombre = mainModel::limpiar_cadenas($_POST['estudiantes_nombre_reg']); 
        $apellido = mainModel::limpiar_cadenas($_POST['estudiantes_apellido_reg']); 
        $telefono = mainModel::limpiar_cadenas($_POST['estudiantes_telefono_reg']); 
        $direccion = mainModel::limpiar_cadenas($_POST['estudiantes_direccion_reg']);
        

        /* == verificar campos vacios == */
        if ($dni=="" || $nombre=="" || $apellido=="" || $telefono=="") {
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
        if (mainModel::verificar_datos("[0-9\-]{6,10}",$dni)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de DNI incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$nombre)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de NOMBRE incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}",$apellido)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de APELLIDO incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if ($telefono!="") {
            if (mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)) {
                $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "Formato de TELEFONO incorrecto.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }
        }

        if ($direccion!="") {
            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$direccion)) {
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
        

        
        /* == verificar si existe DNI == */
        $check_dni = mainModel::consultar_consultas_simples("SELECT estudiantes_dni FROM
        estudiantes WHERE estudiantes_dni='$dni'");
        if ($check_dni->rowCount()>0) {
            $alerta = [
                "Alerta"=>"simple",  
                "Titulo" => "Error inesperado.",
                "Texto" => "El DNI ya existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        /* alamcenamos en un array de datos los registros de los datos del estudiantes para guardarlo */
        $datos_estudiantes_reg=[
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion
        ];

        /* para almacenar todo lo que devuelava agregar_estudiantes_modelo */
        $agregar_estudiantes = estudiantesModelo::agregar_estudiantes_modelo($datos_estudiantes_reg);

        /* para comprobar si se insertó los registros en la bdd */
        if ($agregar_estudiantes->rowCount()==1) {
            $alerta = [
                "Alerta"=>"limpiar",
                "Titulo" => "estudiante registrado.",
                "Texto" => "Datos del estudiante registrado con éxito.",
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














    
    /* ---------- Controlador paginar estudiantes ----------- */
    public function paginador_estudiantes_controlador($pagina, $registros, $privilegio, $url, $busqueda){
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
        #si la pagian viene definida y es mayor a 0, entonces es un numero que esta en la url (...estudiantes-list/1/), si no, la pagina devuelve 1
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
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM estudiantes WHERE
            estudiantes_dni LIKE '%$busqueda%' OR
            estudiantes_nombre LIKE '%$busqueda%' OR
            estudiantes_apellido LIKE '%$busqueda%' OR
            estudiantes_telefono LIKE '%$busqueda%' ORDER BY estudiantes_nombre ASC LIMIT $inicio, $registros ";
        }
        else{
            /* para que la consulta solo seleccione un determinado listado
            y no se seleccione todos los registros (para que no se sature)
            
            */
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM estudiantes ORDER BY estudiantes_nombre ASC LIMIT $inicio, $registros ";
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
                        <tr class="text-center roboto-medium">
                            <th>#</th>
                            <th>DNI</th>
                            <th>NOMBRE</th>
                            <th>APELLIDO</th>
                            <th>TELÉFONO</th>
                            <th>DIRECCIÓN</th>';
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
                    <td>'.$rows['estudiantes_dni'].'</td>
                    <td>'.$rows['estudiantes_nombre'].'</td>
                    <td>'.$rows['estudiantes_apellido'].'</td>
                    <td>'.$rows['estudiantes_telefono'].'</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="popover" data-trigger="hover" title="'.$rows['estudiantes_nombre'].' '.$rows['estudiantes_apellido'].' " data-content="'.$rows['estudiantes_direccion'].'">
                            <i class="fas fa-info-circle"></i>
                        </button></td>';

                        if ($privilegio==1 || $privilegio==2) {
                            $tabla.='<td>
                                <a href="'.URL.'estudiantes-update/'.mainModel::encryption($rows['estudiantes_id']).'/" class="btn btn-warning btn-sm">
                                    <i class="fas fa-sync-alt"></i> 
                                </a>
                            </td>';
                        }
                        
                        if ($privilegio==1 ) {
                            $tabla.='<td>
                                <form class="FormularioAjax" action="'.URL.'ajax/estudiantesAjax.php" method="POST" data-form="eliminar" autocomplete="off">
                                    <input type="hidden" name="estudiantes_id_del" value="'.mainModel::encryption($rows['estudiantes_id']).'">
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
                $tabla.='<tr class="text-center">
                    <td colspan="9">
                        <a href= "'.$url.'" class="btn btn-raised btn-primary btn-sm" >click para regargar el listado.</a>
                    </td></tr>'; //colspan="9" porque tenemos 9 columnas en total   
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
            $tabla.='<p class="text-right">Mostrando estudiantes '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' </p>';

            $tabla.=mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        
        return $tabla;
    }/* ---------- FIN Controlador paginar estudiantes ----------- */



    /* ---------- Controlador ELIMINAR estudiantes ----------- */
    public function eliminar_estudiantes_controlador(){
        //recibe el id del estudiantes
        $id=mainModel::decryption($_POST['estudiantes_id_del']);//para desencriptar
        $id=mainModel::limpiar_cadenas($id);//para seguridad

        /* comprobar si existe el estudiantes */
        $check_estudiantes = mainModel::consultar_consultas_simples("SELECT estudiantes_id FROM 
        estudiantes WHERE estudiantes_id = '$id'");
        if ($check_estudiantes->rowCount() <= 0) { //rowCount() para contar cuantos registros han sido afectados
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El estudiante no existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* comprobar si esta relacionado con otras tablas */
        $check_pagos = mainModel::consultar_consultas_simples("SELECT estudiantes_id FROM 
        inscripciones WHERE estudiantes_id = '$id' LIMIT 1");//LIMIT 1 para seleccionar solo 1 registro de prestamo
        if ($check_pagos->rowCount() > 0) { 
            //si hay 1 o mas registros, significa que hay 1 o mas estudiantess asociados al prestamo
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se puede eliminar este estudiantes debido a que tiene 
                inscripciones asociados. Deshabilita el estudiantes si ya no será utilizado",
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

        $eliminar_estudiantes=estudiantesModelo::eliminar_estudiantes_modelo($id);

        if ($eliminar_estudiantes->rowCount()==1){// si se eliminó un registro
            $alerta = [
                "Alerta"=>"recargar", //alerta de tipo rergar para que se recargue la tabla
                "Titulo" => "Estudiantes eliminado.",
                "Texto" => "El estudiante ha sido eliminado exitosamente.",
                "Tipo" => "success"
            ];
                
        }
        else {

            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se podo eliminar el estudiante, intentelo nuevamente.",
                "Tipo" => "error"
            ];
            
        }
        echo json_encode($alerta); //convertimos a JSON
        //exit();

    }/* ---------- FIN Controlador ELIMINAR estudiantes ----------- */

    /* ---------- Controlador datos de estudiante ----------- */
    public function datos_estudiantes_controlador($tipo, $id){// $tipo de consulta
        $tipo =mainModel::limpiar_cadenas($tipo);

        $id=mainModel::decryption($id);
        $id=mainModel::limpiar_cadenas($id);

        return estudiantesModelo::datos_estudiantes_modelo($tipo, $id);

    }/* FIN controlador datos del estudiantes */

    /* ---------- Controlador actualizar datos del estudiantes ----------- */
    public function actualizar_estudiantes_controlador(){// 
        // recibiendo el ID
        $id=mainModel::decryption($_POST['estudiantes_id_up']); //es el input hidden que estaba encriptado
        $id=mainModel::limpiar_cadenas($id);

        //verificar si existe el estudiante en la bdd
        $check_estudiantes = mainModel::consultar_consultas_simples("SELECT * FROM estudiantes
        WHERE estudiantes_id = '$id'");

        if ($check_estudiantes->rowCount()<=0) { //si no se encuentra el estudiantes
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha encontrado el estudiantes en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); 
        }
        else {
            $campos=$check_estudiantes->fetch();
        }

        $dni = mainModel::limpiar_cadenas($_POST['estudiantes_dni_up']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $nombre = mainModel::limpiar_cadenas($_POST['estudiantes_nombre_up']); 
        $apellido = mainModel::limpiar_cadenas($_POST['estudiantes_apellido_up']); 
        $telefono = mainModel::limpiar_cadenas($_POST['estudiantes_telefono_up']); 
        $direccion = mainModel::limpiar_cadenas($_POST['estudiantes_direccion_up']);


        /* == verificar campos vacios == */
        if ($dni=="" || $nombre=="" || $apellido=="" || $telefono=="" || $direccion==""){
            
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
        if (mainModel::verificar_datos("[0-9\-]{6,10}",$dni)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de DNI incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$nombre)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de NOMBRE incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}",$apellido)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de APELLIDO incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if ($telefono!="") {
            if (mainModel::verificar_datos("[0-9()+]{8,20}",$telefono)) {
                $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "Formato de TELEFONO incorrecto.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }
        }

        if ($direccion!="") {
            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$direccion)) {
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


        /* == verificar si existe DNI == */
        if($dni!=$campos['estudiantes_dni']) { //si el dni es distinto al dni que se desea modificar
            $check_dni = mainModel::consultar_consultas_simples("SELECT estudiantes_dni FROM
                estudiantes WHERE estudiantes_dni='$dni'");
            if ($check_dni->rowCount()>0) { //si existe la consulta
                $alerta = [
                    "Alerta"=>"simple",  
                    "Titulo" => "Error inesperado.",
                    "Texto" => "El DNI ya existe en el sistema.",
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
        $datos_estudiantes_up=[
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
            "ID" => $id
        ];

        if (estudiantesModelo::actualizar_estudiantes_modelo($datos_estudiantes_up)) {
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

    }/* FIN Controlador actualizar datos del estudiantes */


}
