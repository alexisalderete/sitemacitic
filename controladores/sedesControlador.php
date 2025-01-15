<?php
/* este controlador se va ejecutar dentro del archivo sedesajax
pero cuando no se utiliza la peticion ajax se va ajecutar en index.php  */

if ($peticionAjax) {
    # si es una peticion ajax, estamos en la carpeta ajax,
    #se sale de la carpeta ajax y entra en modelo
    require_once "../modelos/sedesModelo.php";
}
else {
    # si NO es una peticion ajax, estamos en el index.php,
    # y entra en modelo
    require_once "./modelos/sedesModelo.php";
}

class sedesControlador extends sedesModelo{

    /* ---------- Controlador agreagar sedes ----------- */
    public function agregar_sedes_controlador(){
        
        $nombre = mainModel::limpiar_cadenas($_POST['sedes_nombre_reg']); 
        $descripcion = mainModel::limpiar_cadenas($_POST['sedes_descripcion_reg']);

        /* == verificar campos vacios == */
        if ($nombre=="") {
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

        if ($descripcion!="") {
            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}",$descripcion)) {
                $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "Formato de descripción incorrecta.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }
        }
        

        
        /* == verificar si existe DNI == */
        $check_sedes = mainModel::consultar_consultas_simples("SELECT sedes_nombre FROM
        sedes WHERE sedes_nombre='$nombre'");
        if ($check_sedes->rowCount()>0) {
            $alerta = [
                "Alerta"=>"simple",  
                "Titulo" => "Error inesperado.",
                "Texto" => "La sede ya existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        /* alamcenamos en un array de datos los registros de los datos del sedes para guardarlo */
        $datos_sedes_reg=[
            "Nombre" => $nombre,
            "Descripcion" => $descripcion
        ];

        /* para almacenar todo lo que devuelava agregar_sedes_modelo */
        $agregar_sedes = sedesModelo::agregar_sedes_modelo($datos_sedes_reg);

        /* para comprobar si se insertó los registros en la bdd */
        if ($agregar_sedes->rowCount()==1) {
            $alerta = [
                "Alerta"=>"limpiar",
                "Titulo" => "Sede registrado.",
                "Texto" => "Datos del sede registrado con éxito.",
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














    
    /* ---------- Controlador paginar sedes ----------- */
    public function paginador_sedes_controlador($pagina, $registros, $privilegio, $url, $busqueda){
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
        #si la pagian viene definida y es mayor a 0, entonces es un numero que esta en la url (...sedes-list/1/), si no, la pagina devuelve 1
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
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM sedes WHERE
            sedes_nombre LIKE '%$busqueda%' ORDER BY sedes_nombre ASC LIMIT $inicio, $registros ";
        }
        else{
            /* para que la consulta solo seleccione un determinado listado
            y no se seleccione todos los registros (para que no se sature)
            
            */
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM sedes ORDER BY sedes_nombre ASC LIMIT $inicio, $registros ";
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
                            <th>NOMBRE</th>
                            <th>DESCRIPCIÓN</th>';
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
                    <td>'.$rows['sedes_nombre'].'</td>
                    <td>'.$rows['sedes_descripcion'].'</td>';

                        if ($privilegio==1 || $privilegio==2) {
                            $tabla.='<td>
                                <a href="'.URL.'sedes-update/'.mainModel::encryption($rows['sedes_id']).'/" class="btn btn-warning btn-sm">
                                    <i class="fas fa-sync-alt"></i> 
                                </a>
                            </td>';
                        }
                        
                        if ($privilegio==1 ) {
                            $tabla.='<td>
                                <form class="FormularioAjax" action="'.URL.'ajax/sedesAjax.php" method="POST" data-form="eliminar" autocomplete="off">
                                    <input type="hidden" name="sedes_id_del" value="'.mainModel::encryption($rows['sedes_id']).'">
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
                    <td colspan="3">
                        <a href= "'.$url.'" class="btn btn-raised btn-primary btn-sm" >click para regargar el listado.</a>
                    </td></tr>'; //colspan="9" porque tenemos 9 columnas en total   
            }
            else { 
                //si no hay registros
                $tabla.='<tr class="text-center" > <td colspan="3"> NO HAY REGISTROS EN EL SISTEMA</td></tr>'; //colspan="9" porque tenemos 9 columnas en total
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
            $tabla.='<p class="text-right">Mostrando sedes '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' </p>';

            $tabla.=mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        
        return $tabla;
    }/* ---------- FIN Controlador paginar sedes ----------- */



    /* ---------- Controlador ELIMINAR sedes ----------- */
    public function eliminar_sedes_controlador(){
        //recibe el id del sedes
        $id=mainModel::decryption($_POST['sedes_id_del']);//para desencriptar
        $id=mainModel::limpiar_cadenas($id);//para seguridad

        /* comprobar si existe el sedes */
        $check_sedes = mainModel::consultar_consultas_simples("SELECT sedes_id FROM 
        sedes WHERE sedes_id = '$id'");
        if ($check_sedes->rowCount() <= 0) { //rowCount() para contar cuantos registros han sido afectados
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "La sede no existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* comprobar si esta relacionado con otras tablas */
        $check_cursos = mainModel::consultar_consultas_simples("SELECT sedes_id FROM 
        cursos_sedes WHERE sedes_id = '$id' LIMIT 1");//LIMIT 1 para seleccionar solo 1 registro de prestamo
        if ($check_cursos->rowCount() > 0) { 
            //si hay 1 o mas registros, significa que hay 1 o mas sedess asociados al prestamo
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se puede eliminar este sedes debido a que tiene 
                cursos asociados.",
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

        $eliminar_sedes=sedesModelo::eliminar_sedes_modelo($id);

        if ($eliminar_sedes->rowCount()==1){// si se eliminó un registro
            $alerta = [
                "Alerta"=>"recargar", //alerta de tipo rergar para que se recargue la tabla
                "Titulo" => "Sede eliminada.",
                "Texto" => "La sede ha sido eliminado exitosamente.",
                "Tipo" => "success"
            ];
                
        }
        else {

            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se podo eliminar, intentelo nuevamente.",
                "Tipo" => "error"
            ];
            
        }
        echo json_encode($alerta); //convertimos a JSON
        //exit();

    }/* ---------- FIN Controlador ELIMINAR sedes ----------- */

    /* ---------- Controlador datos de sedes ----------- */
    public function datos_sedes_controlador($tipo, $id){// $tipo de consulta
        $tipo =mainModel::limpiar_cadenas($tipo);

        $id=mainModel::decryption($id);
        $id=mainModel::limpiar_cadenas($id);

        return sedesModelo::datos_sedes_modelo($tipo, $id);

    }/* FIN controlador datos del sedes */

    /* ---------- Controlador actualizar datos del sedes ----------- */
    public function actualizar_sedes_controlador(){// 
        // recibiendo el ID
        $id=mainModel::decryption($_POST['sedes_id_up']); //es el input hidden que estaba encriptado
        $id=mainModel::limpiar_cadenas($id);

        //verificar si existe el sedes en la bdd
        $check_sedes = mainModel::consultar_consultas_simples("SELECT * FROM sedes
        WHERE sedes_id = '$id'");

        if ($check_sedes->rowCount()<=0) { //si no se encuentra el sedes
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha encontrado la sede en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); 
        }
        else {
            $campos=$check_sedes->fetch();
        }

        $nombre = mainModel::limpiar_cadenas($_POST['sedes_nombre_up']); 
        $descripcion = mainModel::limpiar_cadenas($_POST['sedes_descripcion_up']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc


        /* == verificar campos vacios == */
        if ($nombre==""){
            
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
        

        if ($descripcion!="") {
            if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}",$descripcion)) {
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


        /* == verificar si existe el nombre == */
        if($nombre!=$campos['sedes_nombre']) { //si el dni es distinto al dni que se desea modificar
            $check_nombre = mainModel::consultar_consultas_simples("SELECT sedes_nombre FROM
                sedes WHERE sedes_nombre='$nombre'");
            if ($check_nombre->rowCount()>0) { //si existe la consulta
                $alerta = [
                    "Alerta"=>"simple",  
                    "Titulo" => "Error inesperado.",
                    "Texto" => "El nombre ya existe en el sistema.",
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
        $datos_sedes_up=[
            "Nombre" => $nombre,
            "Descripcion" => $descripcion,
            "ID" => $id
        ];

        if (sedesModelo::actualizar_sedes_modelo($datos_sedes_up)) {
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

    }/* FIN Controlador actualizar datos del sedes */


}
