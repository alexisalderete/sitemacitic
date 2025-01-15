<?php
/* este controlador se va ejecutar dentro del archivo productosajax
pero cuando no se utiliza la peticion ajax se va ajecutar en index.php  */

if ($peticionAjax) {
    # si es una peticion ajax, estamos en la carpeta ajax,
    #se sale de la carpeta ajax y entra en modelo
    require_once "../modelos/productosModelo.php";
}
else {
    # si NO es una peticion ajax, estamos en el index.php,
    # y entra en modelo
    require_once "./modelos/productosModelo.php";
}

class productosControlador extends productosModelo{

    /* ---------- Controlador agreagar productos ----------- */
    public function agregar_productos_controlador(){
        
        $codigo = mainModel::limpiar_cadenas($_POST['productos_codigo_reg']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $nombre = mainModel::limpiar_cadenas($_POST['productos_nombre_reg']); 
        $precio = mainModel::limpiar_cadenas($_POST['productos_precio_reg']); 
        $stock = mainModel::limpiar_cadenas($_POST['productos_cantidad_reg']); 
        $iva = mainModel::limpiar_cadenas($_POST['productos_iva_reg']);
        

        /* == verificar campos vacios == */
        if ($codigo=="" || $nombre=="" || $precio=="" || $stock=="" || $iva=="" ) {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,20}",$codigo)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de código incorrecto.",
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
        if (mainModel::verificar_datos("[0-9]{1,10}",$precio)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de precio incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        if (mainModel::verificar_datos("[0-9]{1,10}",$stock)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de stock incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[0-9]{1,10}",$iva)) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de DIRECCIÓN incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }



        /* == verificar si existe codigo == */
        $check_codigo = mainModel::consultar_consultas_simples("SELECT productos_codigo FROM
        productos WHERE productos_codigo='$codigo'");
        if ($check_codigo->rowCount()>0) {
            $alerta = [
                "Alerta"=>"simple",  
                "Titulo" => "Error inesperado.",
                "Texto" => "El codigo ya existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }


        /* alamcenamos en un array de datos los registros de los datos del productos para guardarlo */
        $datos_productos_reg=[
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Precio" => $precio,
            "Cantidad" => $stock,
            "Iva" => $iva
        ];

        /* para almacenar todo lo que devuelava agregar_productos_modelo */
        $agregar_productos = productosModelo::agregar_productos_modelo($datos_productos_reg);

        /* para comprobar si se insertó los registros en la bdd */
        if ($agregar_productos->rowCount()==1) {
            $alerta = [
                "Alerta"=>"limpiar",
                "Titulo" => "producto registrado.",
                "Texto" => "Datos de producto registrado con éxito.",
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








    
    /* ---------- Controlador paginar productos ----------- */
    public function paginador_productos_controlador($pagina, $registros, $privilegio, $url, $busqueda){
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
        #si la pagian viene definida y es mayor a 0, entonces es un numero que esta en la url (...productos-list/1/), si no, la pagina devuelve 1
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
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM productos WHERE
            productos_codigo LIKE '%$busqueda%' OR
            productos_nombre LIKE '%$busqueda%' ORDER BY productos_nombre ASC LIMIT $inicio, $registros ";
        }
        else{
            /* para que la consulta solo seleccione un determinado listado
            y no se seleccione todos los registros (para que no se sature)
            
            */
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM productos ORDER BY productos_nombre ASC LIMIT $inicio, $registros ";
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
                            <th>CODIGO</th>
                            <th>NOMBRE</th>
                            <th>PRECIO</th>
                            <th>STOCK</th>
                            <th>IVA</th>';
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
                    <td>'.$rows['productos_codigo'].'</td>
                    <td>'.$rows['productos_nombre'].'</td>
                    <td>'.$rows['productos_precio'].'</td>
                    <td>'.$rows['productos_cantidad'].'</td>
                    <td>'.$rows['productos_iva'].'</td>
                    ';

                        if ($privilegio==1 || $privilegio==2) {
                            $tabla.='<td>
                                <a href="'.URL.'productos-update/'.mainModel::encryption($rows['productos_id']).'/" class="btn btn-warning btn-sm">
                                    <i class="fas fa-sync-alt"></i> 
                                </a>
                            </td>';
                        }
                        
                        if ($privilegio==1 ) {
                            $tabla.='<td>
                                <form class="FormularioAjax" action="'.URL.'ajax/productosAjax.php" method="POST" data-form="eliminar" autocomplete="off">
                                    <input type="hidden" name="productos_id_del" value="'.mainModel::encryption($rows['productos_id']).'">
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
            $tabla.='<p class="text-right">Mostrando productos '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' </p>';

            $tabla.=mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        
        return $tabla;
    }/* ---------- FIN Controlador paginar productos ----------- */



    /* ---------- Controlador ELIMINAR productos ----------- */
    public function eliminar_productos_controlador(){
        //recibe el id del productos
        $id=mainModel::decryption($_POST['productos_id_del']);//para desencriptar
        $id=mainModel::limpiar_cadenas($id);//para seguridad

        /* comprobar si existe el productos */
        $check_productos = mainModel::consultar_consultas_simples("SELECT productos_id FROM 
        productos WHERE productos_id = '$id'");
        if ($check_productos->rowCount() <= 0) { //rowCount() para contar cuantos registros han sido afectados
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El producto no existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* comprobar si esta relacionado con otras tablas */
        $check_pagos = mainModel::consultar_consultas_simples("SELECT productos_id FROM 
        detalle_pagos WHERE productos_id = '$id' LIMIT 1");//LIMIT 1 para seleccionar solo 1 registro de prestamo
        if ($check_pagos->rowCount() > 0) { 
            //si hay 1 o mas registros, significa que hay 1 o mas productoss asociados al prestamo
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se puede eliminar este productos debido a que tiene 
                inscripciones asociados.",
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

        $eliminar_productos=productosModelo::eliminar_productos_modelo($id);

        if ($eliminar_productos->rowCount()==1){// si se eliminó un registro
            $alerta = [
                "Alerta"=>"recargar", //alerta de tipo rergar para que se recargue la tabla
                "Titulo" => "productos eliminado.",
                "Texto" => "El producto ha sido eliminado exitosamente.",
                "Tipo" => "success"
            ];
                
        }
        else {

            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se pudo eliminar el producto, intentelo nuevamente.",
                "Tipo" => "error"
            ];
            
        }
        echo json_encode($alerta); //convertimos a JSON
        //exit();

    }/* ---------- FIN Controlador ELIMINAR productos ----------- */

    /* ---------- Controlador datos de estudiante ----------- */
    public function datos_productos_controlador($tipo, $id){// $tipo de consulta
        $tipo =mainModel::limpiar_cadenas($tipo);

        $id=mainModel::decryption($id);
        $id=mainModel::limpiar_cadenas($id);

        return productosModelo::datos_productos_modelo($tipo, $id);

    }/* FIN controlador datos del productos */

    /* ---------- Controlador actualizar datos del productos ----------- */
    public function actualizar_productos_controlador(){// 
        // recibiendo el ID
        $id=mainModel::decryption($_POST['productos_id_up']); //es el input hidden que estaba encriptado
        $id=mainModel::limpiar_cadenas($id);

        //verificar si existe el estudiante en la bdd
        $check_productos = mainModel::consultar_consultas_simples("SELECT * FROM productos
        WHERE productos_id = '$id'");

        if ($check_productos->rowCount()<=0) { //si no se encuentra el productos
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha encontrado el productos en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); 
        }
        else {
            $campos=$check_productos->fetch();
        }

        $codigo = mainModel::limpiar_cadenas($_POST['productos_codigo_up']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $nombre = mainModel::limpiar_cadenas($_POST['productos_nombre_up']); 
        $precio = mainModel::limpiar_cadenas($_POST['productos_precio_up']); 
        $stock = mainModel::limpiar_cadenas($_POST['productos_stock_up']); 
        $iva = mainModel::limpiar_cadenas($_POST['productos_iva_up']);


        /* == verificar campos vacios == */
        if ($codigo=="" || $nombre=="" || $precio=="" || $stock=="" || $iva==""){
            
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
        if (mainModel::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,20}",$codigo)) {
            $alerta = [
                "Alerta"=>"simple",
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de codigo incorrecto.",
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
        if (mainModel::verificar_datos("[0-9]{1,10}",$precio)) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de precio incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
    

            if (mainModel::verificar_datos("[0-9]{1,10}",$stock)) {
                $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "Formato de stock incorrecto.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }

            if (mainModel::verificar_datos("[0-9]{1,10}",$iva)) {
                $alerta = [
                    "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "Formato de DIRECCIÓN incorrecto.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta); //convertimos a JSON
                exit();
            }



        /* == verificar si existe codigo == */
        if($codigo!=$campos['productos_codigo']) { //si el codigo es distinto al codigo que se desea modificar
            $check_codigo = mainModel::consultar_consultas_simples("SELECT productos_codigo FROM
                productos WHERE productos_codigo='$codigo'");
            if ($check_codigo->rowCount()>0) { //si existe la consulta
                $alerta = [
                    "Alerta"=>"simple",  
                    "Titulo" => "Error inesperado.",
                    "Texto" => "El codigo ya existe en el sistema.",
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
        $datos_productos_up=[
            "Codigo" => $codigo,
            "Nombre" => $nombre,
            "Precio" => $precio,
            "Cantidad" => $stock,
            "Iva" => $iva,
            "ID" => $id
        ];

        if (productosModelo::actualizar_productos_modelo($datos_productos_up)) {
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

    }/* FIN Controlador actualizar datos del productos */


}
