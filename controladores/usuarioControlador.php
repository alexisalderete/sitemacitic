<?php
/* este controlador se va ejecutar dentro del archivo Usuarioajax
pero cuando no se utiliza la peticion ajax se va ajecutar en index.php  */

if ($peticionAjax) {
    # si es una peticion ajax, estamos en la carpeta ajax,
    #se sale de la carpeta ajax y entra en modelo
    require_once "../modelos/usuarioModelo.php";
}
else {
    # si NO es una peticion ajax, estamos en el index.php,
    # y entra en modelo
    require_once "./modelos/usuarioModelo.php";
}

class usuarioControlador extends usuarioModelo{

    /* ---------- Controlador agreagar usuario ----------- */
    public function agregar_usuario_controlador(){
        $dni = mainModel::limpiar_cadenas($_POST['usuario_dni_reg']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $nombre = mainModel::limpiar_cadenas($_POST['usuario_nombre_reg']); 
        $apellido = mainModel::limpiar_cadenas($_POST['usuario_apellido_reg']); 
        $telefono = mainModel::limpiar_cadenas($_POST['usuario_telefono_reg']); 
        $direccion = mainModel::limpiar_cadenas($_POST['usuario_direccion_reg']);
        
        $usuario = mainModel::limpiar_cadenas($_POST['usuario_usuario_reg']); 
        $email = mainModel::limpiar_cadenas($_POST['usuario_email_reg']); 
        $clave1 = mainModel::limpiar_cadenas($_POST['usuario_clave_1_reg']); 
        $clave2 = mainModel::limpiar_cadenas($_POST['usuario_clave_2_reg']);

        $privilegio = mainModel::limpiar_cadenas($_POST['usuario_privilegio_reg']);

        /* == verificar campos vacios == */
        if ($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $clave1=="" || $clave2=="" || $privilegio=="") {
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
        if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de USUARIO incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9$@.\-]{8,100}",$clave1) ||  
        mainModel::verificar_datos("[a-zA-Z0-9$@.\-]{8,100}",$clave2)) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de CONTRASEÑA incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        
        /* == verificar si existe DNI == */
        $check_dni = mainModel::consultar_consultas_simples("SELECT usuario_dni FROM
        usuario WHERE usuario_dni='$dni'");
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

        /* == verificar si existe nombre de usuario == */
        $check_user = mainModel::consultar_consultas_simples("SELECT usuario_usuario FROM
        usuario WHERE usuario_usuario='$usuario'");
        if ($check_user->rowCount()>0) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "El NOMBRE DE USUARIO ya existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /* == verificar si existe el email == */

        if ($email!="") { #si el campo email no es vacio
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) { #comprobamos si es un email valido
                
                $check_email = mainModel::consultar_consultas_simples("SELECT usuario_email FROM
                    usuario WHERE usuario_email='$email'");
                if ($check_email->rowCount()>0) { #comprobamos si ya existe un email
                    $alerta = [
                        "Alerta"=>"simple", 
                        "Titulo" => "Error inesperado.",
                        "Texto" => "El Email ya existe en el sistema.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
        
            }
            else{ #si no es valido
                $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "Correo no válido.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }

        /* == verificar si la clave 1 y 2 son iguales == */
        if ($clave1!=$clave2) {
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Las contraseñas no coinciden.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        else{
            $clave=mainModel::encryption($clave1); #para encriptar la clave
        }

        /* == verificar los privilegios == */
        if ($privilegio<1 || $privilegio>3) { #si esta fuera del rango 
            $alerta = [
                "Alerta"=>"simple", 
                "Titulo" => "Error inesperado.",
                "Texto" => "Privilegio no válido.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        /* alamcenamos en un array de datos los registros de los datos del usuario para guardarlo */
        $datos_usuario_reg=[
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
            "Usuario" => $usuario,
            "Email" => $email,
            "Clave" => $clave,
            "Estado" => "Activa",
            "Privilegio" => $privilegio
        ];

        /* para almacenar todo lo que devuelava agregar_usuario_modelo */
        $agregar_usuario = usuarioModelo::agregar_usuario_modelo($datos_usuario_reg);

        /* para comprobar si se insertó los registros en la bdd */
        if ($agregar_usuario->rowCount()==1) {
            $alerta = [
                "Alerta"=>"limpiar",
                "Titulo" => "Usuario registrado.",
                "Texto" => "Datos del usuario registrado con éxito.",
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

    /* ---------- Controlador paginar usuario ----------- */
    public function paginador_usuario_controlador($pagina, $registros, $privilegio, $id, $url, $busqueda){
        /*  */
        $pagina = mainModel::limpiar_cadenas($pagina);
        $registros = mainModel::limpiar_cadenas($registros);
        $privilegio = mainModel::limpiar_cadenas($privilegio);
        $id = mainModel::limpiar_cadenas($id);

        $url = mainModel::limpiar_cadenas($url);
        $url = URL.$url."/";

        $busqueda = mainModel::limpiar_cadenas($busqueda);
        $tabla = "";
        
        #para solo tenga numeros enteros
        #hacemos con peperador ternario
        #si la pagian viene definida y es mayor a 0, entonces es un numero que esta en la url, si no, la pagina devuelve 1
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
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE ((usuario_id != '$id'
            AND usuario_id != '1') AND (usuario_dni LIKE '%$busqueda%' OR
            usuario_nombre LIKE '%$busqueda%' OR
            usuario_apellido LIKE '%$busqueda%' OR
            usuario_telefono LIKE '%$busqueda%' OR
            usuario_email LIKE '%$busqueda%' OR
            usuario_usuario LIKE '%$busqueda%') ) ORDER BY usuario_nombre ASC LIMIT $inicio, $registros ";
        }
        else{
            /* para que la consulta solo seleccione un determinado listado
            y no se seleccione todos los registros (para que no se sature)
            
            */
            $consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM usuario WHERE usuario_id != '$id'
            AND usuario_id != '1' ORDER BY usuario_nombre ASC LIMIT $inicio, $registros ";
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
                            <th>USUARIO</th>
                            <th>EMAIL</th>
                            <th>ACTUALIZAR</th>
                            <th>ELIMINAR</th>
                        </tr>
                    </thead>
                    <tbody>';

        if ($total>=1 && $pagina <= $Npaginas) {
            $contador = $inicio + 1;
            $reg_inicio = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla.='
                <tr class="text-center" >
                    <td>'.$contador.'</td>
                    <td>'.$rows['usuario_dni'].'</td>
                    <td>'.$rows['usuario_nombre'].'</td>
                    <td>'.$rows['usuario_apellido'].'</td>
                    <td>'.$rows['usuario_telefono'].'</td>
                    <td>'.$rows['usuario_usuario'].'</td>
                    <td>'.$rows['usuario_email'].'</td>
                    <td>
                        <a href="'.URL.'user-update/'.mainModel::encryption($rows['usuario_id']).'/" class="btn btn-warning btn-sm">
                            <i class="fas fa-sync-alt"></i>	
                        </a>
                    </td>
                    <td>
                        <form class="FormularioAjax" action="'.URL.'ajax/usuarioAjax.php" method="POST" data-form="eliminar" autocomplete="off">
                        <input type="hidden" name="usuario_id_del" value="'.mainModel::encryption($rows['usuario_id']).'">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    </td>
                </tr>'; //el boton tiene que ser de tipo submit para enviar los formularios
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
            $tabla.='<p class="text-right">Mostrando usuario '.$reg_inicio.' al '.$reg_final.' de un total de '.$total.' </p>';

            $tabla.=mainModel::paginador_tablas($pagina, $Npaginas, $url, 7);
        }
        
        return $tabla;
    }/* ---------- FIN Controlador paginar usuario ----------- */

    /* ---------- Controlador paginar usuario ----------- */
    public function eliminar_usuario_controlador(){
        //recibe el id del usuario
        $id=mainModel::decryption($_POST['usuario_id_del']);//para desencriptar
        $id=mainModel::limpiar_cadenas($id);//para seguridad

        /* verificar usuario principal */
        if ($id==1) {
            //alerta es array de datos, luego lo convertimos a JSON
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se puede eliminar el usuario principal del sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); //para que no se ejecute mas el codigo
        }

        /* comprobar si existe el usuario */
        $check_usuario = mainModel::consultar_consultas_simples("SELECT usuario_id FROM 
        usuario WHERE usuario_id = '$id'");
        if ($check_usuario->rowCount() <= 0) { //rowCount() para contar cuantos registros han sido afectados
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El usuario no existe en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* comprobar si esta relacionado con otras tablas */
        $check_pagos = mainModel::consultar_consultas_simples("SELECT usuario_id FROM 
        pagos WHERE usuario_id = '$id' LIMIT 1");//LIMIT 1 para seleccionar solo 1 registro de prestamo
        if ($check_pagos->rowCount() > 0) { 
            //si hay 1 o mas registros, significa que hay 1 o mas usuarios asociados al prestamo
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se puede eliminar este usuario debido a que tiene 
                pagos asociados. Deshabilita el usuario si ya no será utilizado",
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

        $eliminar_usuario=usuarioModelo::eliminar_usuario_modelo($id);

        if ($eliminar_usuario->rowCount()==1){// si se eliminó un registro
            $alerta = [
                "Alerta"=>"recargar", //alerta de tipo rergar para que se recargue la tabla
                "Titulo" => "Usuario eliminado.",
                "Texto" => "El usuario ha sido eliminado exitosamente.",
                "Tipo" => "success"
            ];
                
        }
        else {

            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se podo eliminar el usuario, intentelo nuevamente.",
                "Tipo" => "error"
            ];
            
        }
        echo json_encode($alerta); //convertimos a JSON
        exit();


    }/* ---------- FIN Controlador paginar usuario ----------- */

    /* ---------- Controlador datos del usuario ----------- */
    public function datos_usuario_controlador($tipo, $id){// $tipo de consulta
        $tipo =mainModel::limpiar_cadenas($tipo);

        $id=mainModel::decryption($id);
        $id=mainModel::limpiar_cadenas($id);

        return usuarioModelo::datos_usuario_modelo($tipo, $id);

    }/* FIN controlador datos del usuario */

    /* ---------- Controlador actualizar datos del usuario ----------- */
    public function actualizar_usuario_controlador(){// 
        // recibiendo el ID
        $id=mainModel::decryption($_POST['usuario_id_up']); //es el input hidden que estaba encriptado
        $id=mainModel::limpiar_cadenas($id);


        //verificar si existe el usuario en la bdd
        $check_user = mainModel::consultar_consultas_simples("SELECT * FROM usuario
        WHERE usuario_id = '$id'");

        if ($check_user->rowCount()<=0) { //si no se encuentra el usuario
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "No se ha encontrado el usuario en el sistema.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit(); 
        }
        else {
            $campos=$check_user->fetch();
        }


        $dni = mainModel::limpiar_cadenas($_POST['usuario_dni_up']); //utilizamos limpiar_cadenas para evitar inyeccion sql etc
        $nombre = mainModel::limpiar_cadenas($_POST['usuario_nombre_up']); 
        $apellido = mainModel::limpiar_cadenas($_POST['usuario_apellido_up']); 
        $telefono = mainModel::limpiar_cadenas($_POST['usuario_telefono_up']); 
        $direccion = mainModel::limpiar_cadenas($_POST['usuario_direccion_up']);
        
        $usuario = mainModel::limpiar_cadenas($_POST['usuario_usuario_up']); 
        $email = mainModel::limpiar_cadenas($_POST['usuario_email_up']);

        //si viene definido guardamos el dato del formulario
        if (isset($_POST['usuario_estado_up'])) {
            $estado = mainModel::limpiar_cadenas($_POST['usuario_estado_up']);
        }else { //si no viene definido guardamos el dato de la base de datos
            $estado = $campos['usuario_estado'];
        }

        //si viene definido guardamos el dato del formulario
        if (isset($_POST['usuario_privilegio_up'])) {
            $privilegio = mainModel::limpiar_cadenas($_POST['usuario_privilegio_up']);
        }else { //si no viene definido guardamos el dato de la base de datos
            $privilegio = $campos['usuario_privilegio'];
        }

        //admin_usuario y admin_clave es el usuario que quiere actualizar
        $admin_usuario=mainModel::limpiar_cadenas($_POST['usuario_admin']);
        $admin_clave=mainModel::limpiar_cadenas($_POST['clave_admin']);

        

        $tipo_cuenta = mainModel::limpiar_cadenas($_POST['tipo_cuenta']);

        /* == verificar campos vacios == */
        if ($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $admin_usuario=="" || $admin_clave==""){
            
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
        if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "Formato de USUARIO incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9]{1,35}",$admin_usuario)) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El formato del USUARIO para realizar los cambios es incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{8,100}",$admin_clave)) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El formato de la CONTRASEÑA para realizar los cambios es incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }
        // se coloca la encriptacion aca porque primero se debe verificar si el formato es correcto
        $admin_clave = mainModel::encryption($admin_clave);

        //verificar si el rango del nivel de privilegio es valido
        if ($privilegio<1 || $privilegio>3) {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El formato de PRIVILEGIO seleccionado es incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        // verificar el estado
        if ($estado!="Activa" && $estado!="Deshabilitada") {
            $alerta = [
                "Alerta"=>"simple", //alerta de tipo simple || Alerta porque es el el indice Alerta (alerta.Alerta) 
                "Titulo" => "Error inesperado.",
                "Texto" => "El formato de ESTADO es incorrecto.",
                "Tipo" => "error"
            ];
            echo json_encode($alerta); //convertimos a JSON
            exit();
        }

        /* == verificar si existe DNI == */
        if($dni!=$campos['usuario_dni']) { //si el dni es distinto al dni que se desea modificar
            $check_dni = mainModel::consultar_consultas_simples("SELECT usuario_dni FROM
                usuario WHERE usuario_dni='$dni'");
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

        /* == verificar si existe nombre de usuario == */
        if($usuario!=$campos['usuario_usuario']) { //si el usuario es distinto al usuario que se desea modificar
            $check_user = mainModel::consultar_consultas_simples("SELECT usuario_usuario FROM
                usuario WHERE usuario_usuario='$usuario'");
            if ($check_user->rowCount()>0) { //si existe la consulta
                $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "El NOMBRE DE USUARIO ya existe en el sistema.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }


        /* == verificar si existe el email == */
        if($email!=$campos['usuario_email'] && $email!="") { //si el email es distinto al email que se desea modificar y no esta vacio
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) { //si ES valido el formato del email
                $check_email = mainModel::consultar_consultas_simples("SELECT usuario_email FROM
                    usuario WHERE usuario_email='$email'");
                if ($check_email->rowCount()>0) { //si existe la consulta
                    $alerta = [
                        "Alerta"=>"simple", 
                        "Titulo" => "Error inesperado.",
                        "Texto" => "El nuevo en EMAIL ingresado ya existe en el sistema.",
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
                    "Texto" => "El EMAIL ingresado no es válidio",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
        }




        /* == verificar claves == */
        if ($_POST['usuario_clave_nueva_1'] != "" || $_POST['usuario_clave_nueva_2'] != "") { //si hay algo ingresado en uno de los campos
            if ($_POST['usuario_clave_nueva_1'] != $_POST['usuario_clave_nueva_2']) {
                $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "Las nuevas CONTRASEÑAS ingresadas no coinciden.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            else { 
                if (mainModel::verificar_datos("[a-zA-Z0-9$@.-]{8,100}",$_POST['usuario_clave_nueva_1']) ||
                mainModel::verificar_datos("[a-zA-Z0-9$@.-]{8,100}",$_POST['usuario_clave_nueva_2']) ) {
                    $alerta = [
                        "Alerta"=>"simple", 
                        "Titulo" => "Error inesperado.",
                        "Texto" => "Formato de las nuevas CONTRASEÑAS ingresadas son incorrectas.",
                        "Tipo" => "error"
                    ];
                    echo json_encode($alerta);
                    exit();
                }
                $clave = mainModel::encryption($_POST['usuario_clave_nueva_1']);
            }
        }
        else {
            $clave = $campos['usuario_clave'];
        }


        /* == verificar las credenciales para actualizar los datos, si es propia del usuario o es distinta == */
        if ($tipo_cuenta == "Propia") {
            $check_cuenta = mainModel::consultar_consultas_simples("SELECT usuario_id FROM
                    usuario WHERE
                    usuario_usuario='$admin_usuario' AND
                    usuario_clave='$admin_clave' AND
                    usuario_id = '$id'
                    ");
        }
        else {
            session_start(['name'=>'instituto']); //para utilizar las variables de sesion, exactamente la del privilegio
            if ($_SESSION['privilegio_instituto'] != 1) { // si es distinto al nivel 1, entonces no tiene permiso para actualizar datos que no es propia
                $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "NO tienes los permisos necesarios para realizar esta oparacion.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
            }
            $check_cuenta = mainModel::consultar_consultas_simples("SELECT usuario_id FROM
                    usuario WHERE
                    usuario_usuario='$admin_usuario' AND
                    usuario_clave='$admin_clave'
                    ");
        }
        
        if ($check_cuenta->rowCount()<=0) {
            $alerta = [
                    "Alerta"=>"simple", 
                    "Titulo" => "Error inesperado.",
                    "Texto" => "El usuario o la contraseña para guardar los cambios son incorrectos. Por favor, inténtalo de nuevo.",
                    "Tipo" => "error"
                ];
                echo json_encode($alerta);
                exit();
        }




        /* == preparar los datos para enviarlo al modelo == */
        // los datos que estan entre comillas deben coincidir con los del modelo
        $datos_usuario_up=[
            "DNI" => $dni,
            "Nombre" => $nombre,
            "Apellido" => $apellido,
            "Telefono" => $telefono,
            "Direccion" => $direccion,
            "Email" => $email,
            "Usuario" => $usuario,
            "Clave" => $clave,
            "Estado" => $estado,
            "Privilegio" => $privilegio,
            "ID" => $id
        ];

        if (usuarioModelo::actualizar_usuario_modelo($datos_usuario_up)) {
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

    }/* FIN Controlador actualizar datos del usuario */



}
