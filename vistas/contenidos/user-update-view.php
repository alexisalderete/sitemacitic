<!-- Page header -->
<?php
    /* verificar si el id (encriptado) que se esta pasando en la url es distinto al usuario que se logueó.
    $pagina se definió en plantilla
    $pagina[1] posicion 1 porque en la url, la id que esta encriptado está en la posicion 1
    OBS: la posicion 0 es la vista (user-update) 
    */
    if ($lc->encryption($_SESSION['id_instituto']) != $pagina[1]) {
        /*si no tiene el privilegio nivel 1 e intenta acceder mediante la url, le cerramos la sesion */
        if ($_SESSION['privilegio_instituto'] != 1) {
            # le cerramos la sesion
            echo $lc-> forzar_cierre_sesion_controlador();
            exit();
        }
    }
?>
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR USUARIO
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit nostrum rerum animi natus beatae ex. Culpa blanditiis tempore amet alias placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
    </p> -->
</div>
<?php
        /* esas opciones solo se muestran a los usuarios de privilegio nivel 1  */
    if ($_SESSION['privilegio_instituto'] == 1) { ?>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo URL;?>user-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO USUARIO</a>
        </li>
        <li>
            <a href="<?php echo URL;?>user-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE USUARIOS</a>
        </li>
        <li>
            <a href="<?php echo URL;?>user-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR USUARIO</a>
        </li>
    </ul>	
</div>
<?php } ?>



<!-- Content -->
<div class="container-fluid">
    <?php
        require_once "./controladores/usuarioControlador.php";
        $ins_usuario = new usuarioControlador();

        $datos_usuario = $ins_usuario -> datos_usuario_controlador("Unico", $pagina[1]);

        if ($datos_usuario->rowCount() == 1) {// si es el usuario existe 
            $campos = $datos_usuario->fetch();//para obtener los datos de los usuarios.
    ?>
    <form class="form-neon FormularioAjax" action="<?php echo URL;?>ajax/usuarioAjax.php" 
    method="POST" data-form="actualizar" autocomplete="off">
        <input type="hidden" name="usuario_id_up" value="<?php echo $pagina[1]?>"> 
        <!-- $pagina[1] es el id encryptado -->
        <fieldset>
            <legend><i class="far fa-address-card"></i> &nbsp; Información personal</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_dni" class="bmd-label-floating">DNI</label>
                            <input type="text" pattern="[0-9\-]{6,10}" class="form-control" name="usuario_dni_up" id="usuario_dni" maxlength="20"
                            value="<?php echo $campos['usuario_dni']; ?>">
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_nombre" class="bmd-label-floating">Nombres</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" class="form-control" name="usuario_nombre_up" id="usuario_nombre" maxlength="35"
                            value="<?php echo $campos['usuario_nombre']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_apellido" class="bmd-label-floating">Apellidos</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,35}" class="form-control" name="usuario_apellido_up" id="usuario_apellido" maxlength="35"
                            value="<?php echo $campos['usuario_apellido']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_telefono" class="bmd-label-floating">Teléfono</label>
                            <input type="text" pattern="[0-9()+]{8,20}" class="form-control" name="usuario_telefono_up" id="usuario_telefono" maxlength="20"
                            value="<?php echo $campos['usuario_telefono']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="usuario_direccion_up" id="usuario_direccion" maxlength="190"
                            value="<?php echo $campos['usuario_direccion']; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br>



        <fieldset>
            <legend><i class="fas fa-user-lock"></i> &nbsp; Información de la cuenta</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_usuario" class="bmd-label-floating">Nombre de usuario</label>
                            <input type="text" pattern="[a-zA-Z0-9]{1,35}" class="form-control" name="usuario_usuario_up" id="usuario_usuario" maxlength="35"
                            value="<?php echo $campos['usuario_usuario']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_email" class="bmd-label-floating">Email</label>
                            <input type="email" class="form-control" name="usuario_email_up" id="usuario_email" maxlength="70"
                            value="<?php echo $campos['usuario_email']; ?>">
                        </div>
                    </div>
                    <!-- solo si tiene privilegio nivel 1 y la cuenta que se intenta actualizar es distinto a id 1 (el id 1 es el admin)
                    puede modificar el estado -->
                    <?php if ($_SESSION['privilegio_instituto'] == 1 && $campos['usuario_id'] != 1 ) {?>

                    <div class="col-12 col-md-4">
                        <div class="form-group" >

                            <label>Estado de la cuenta  &nbsp; <?php if($campos['usuario_estado'] =="Activa" ){
                                echo '<span class="badge badge-info">Activa</span>';}else{
                                echo '<span class="badge badge-danger">Deshabilitada</span>';
                                } ?> 
                            </label>
                            <select class="form-control" name="usuario_estado_up">
                                <option value="Activa"<?php if ($campos['usuario_estado'] =="Activa") {
                                    echo 'selected=""';
                                } ?> >Activa</option>
                                <option value="Deshabilitada" <?php if ($campos['usuario_estado'] =="Deshabilitada") {
                                    echo 'selected=""';
                                } ?>>Deshabilitada</option>
                            </select>
                            
                        </div>
                    </div>

                    <?php } ?>

                </div>
            </div>
        </fieldset>



        <fieldset>
            <legend style="margin-top: 40px;"><i class="fas fa-lock"></i> &nbsp; Nueva contraseña</legend>
            <p>Para actualizar la contraseña de esta cuenta ingrese una nueva y vuelva a escribirla. En caso que no desee actualizarla debe dejar vacíos los dos campos de las contraseñas.</p>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_clave_nueva_1" class="bmd-label-floating">Contraseña</label>
                            <input type="password" class="form-control" name="usuario_clave_nueva_1" id="usuario_clave_nueva_1" pattern="[a-zA-Z0-9$@.-]{8,100}" maxlength="100" >
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_clave_nueva_2" class="bmd-label-floating">Repetir contraseña</label>
                            <input type="password" class="form-control" name="usuario_clave_nueva_2" id="usuario_clave_nueva_2" pattern="[a-zA-Z0-9$@.-]{8,100}" maxlength="100" >
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br>
        <!-- solo si tiene privilegio nivel 1 y la cuenta que se intenta actualizar es distinto a id 1 (el id 1 es el admin)
        puede modificar el nivel de privilegio -->
        <?php if ($_SESSION['privilegio_instituto'] == 1 && $campos['usuario_id'] != 1 ) {?>
        <fieldset>
            <legend><i class="fas fa-medal"></i> &nbsp; Nivel de privilegio</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <p><span class="badge badge-info">Control total</span> Permisos para registrar, actualizar y eliminar</p>
                        <p><span class="badge badge-success">Edición</span> Permisos para registrar y actualizar</p>
                        <p><span class="badge badge-dark">Registrar</span> Solo permisos para registrar</p>
                        <div class="form-group">
                            <select class="form-control" name="usuario_privilegio_up">
                                
                                <option value="1" <?php if ($campos['usuario_privilegio'] == 1) {
                                    echo 'selected=""';
                                }?> >Control total <?php if ($campos['usuario_privilegio'] == 1) {
                                    echo '(Actual)';
                                }?> </option>

                                <option value="2" <?php if ($campos['usuario_privilegio'] == 2) {
                                    echo 'selected=""';
                                }?> >Edición  <?php if ($campos['usuario_privilegio'] == 2) {
                                    echo '(Actual)';
                                }?></option>
                                
                                <option value="3"<?php if ($campos['usuario_privilegio'] == 3) {
                                    echo 'selected=""';
                                }?> >Registrar  <?php if ($campos['usuario_privilegio'] == 3) {
                                    echo '(Actual)';
                                }?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <?php } ?>
        <br>
        <fieldset>
            <p class="">Para poder guardar los cambios en esta cuenta debe de ingresar su nombre de usuario y contraseña</p>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="usuario_admin" class="bmd-label-floating">Nombre de usuario</label>
                            <input type="text" pattern="[a-zA-Z0-9]{1,35}" class="form-control" name="usuario_admin" id="usuario_admin" maxlength="35" required="" >
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="clave_admin" class="bmd-label-floating">Contraseña</label>
                            <input type="password" class="form-control" name="clave_admin" id="clave_admin" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required="" >
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
            <!-- verificar si la cuenta es propia o no es propia -->
        <?php if ($lc->encryption($_SESSION['id_instituto']) != $pagina[1]) { ?>
            <input type="hidden" name="tipo_cuenta" value="Impropia">

        <?php }else{ ?>
            <input type="hidden" name="tipo_cuenta" value="Propia">

        <?php } ?>
        <p class="text-center">
            <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp; ACTUALIZAR</button>
        </p>
    </form>
    <?php
        }
        else{  //si el usuario no existe
            // si no existe ningun usuario con el ID de usuario (encriptado) en la URL

    ?>

    <div class="alert alert-danger text-center" role="alert">
        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
        <h4 class="alert-heading">¡Error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>

    <?php } ?>

</div>