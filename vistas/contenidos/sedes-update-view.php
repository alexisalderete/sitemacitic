<?php

    /*si no tiene el privilegio nivel 1 e intenta acceder mediante la url, le cerramos la sesion */
    if ($_SESSION['privilegio_instituto'] < 1 || $_SESSION['privilegio_instituto'] >2) {
        # le cerramos la sesion
        echo $lc-> forzar_cierre_sesion_controlador();
        exit();
    }
    
?>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR SEDES
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem odit amet asperiores quis minus, dolorem repellendus optio doloremque error a omnis soluta quae magnam dignissimos, ipsam, temporibus sequi, commodi accusantium!
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo URL;?>sedes-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SEDES</a>
        </li>
        <li>
            <a href="<?php echo URL;?>sedes-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE SEDES</a>
        </li>
        <li>
            <a href="<?php echo URL;?>sedes-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR SEDES</a>
        </li>
    </ul>	
</div>

<!-- Content here-->
<div class="container-fluid">
<?php
        require_once "./controladores/sedesControlador.php";
        $ins_sedes = new sedesControlador();

        /*$pagina se definió en plantilla
            $pagina[1] posicion 1 porque en la url, la id que esta encriptado está en la posicion 1
            OBS: la posicion 0 es la vista (user-update) 
        */
        $datos_sedes = $ins_sedes -> datos_sedes_controlador("Unico", $pagina[1]);


        if ($datos_sedes->rowCount() == 1) {// si es el sedes existe 
            $campos = $datos_sedes->fetch();//para obtener los datos de los sedes.
    ?>

    <form class="form-neon FormularioAjax" action="<?php echo URL;?>ajax/sedesAjax.php" 
        method="POST" data-form="actualizar" autocomplete="off">

        <input type="hidden" name="sedes_id_up" value="<?php echo $pagina[1]?>"> 
        <!-- $pagina[1] es el id encryptado -->
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="sedes_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="sedes_nombre_up" id="sedes_nombre" maxlength="40"
                            value="<?php echo $campos['sedes_nombre']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="sedes_direccion" class="bmd-label-floating">Dirección</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="sedes_descripcion_up" id="sedes_direccion" maxlength="150"
                            value="<?php echo $campos['sedes_descripcion']; ?>">
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <br><br><br>
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
        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>

    <?php } ?>

</div>