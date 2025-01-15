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
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR PRODUCTO
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem odit amet asperiores quis minus, dolorem repellendus optio doloremque error a omnis soluta quae magnam dignissimos, ipsam, temporibus sequi, commodi accusantium!
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo URL;?>productos-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PRODUCTO</a>
        </li>
        <li>
            <a href="<?php echo URL;?>productos-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PRODUCTOS</a>
        </li>
        <li>
            <a href="<?php echo URL;?>productos-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR PRODUCTO</a>
        </li>
    </ul>	
</div>

<!-- Content here-->
<div class="container-fluid">
<?php
        require_once "./controladores/productosControlador.php";
        $ins_productos = new productosControlador();

        /*$pagina se definió en plantilla
            $pagina[1] posicion 1 porque en la url, la id que esta encriptado está en la posicion 1
            OBS: la posicion 0 es la vista (user-update) 
        */
        $datos_productos = $ins_productos -> datos_productos_controlador("Unico", $pagina[1]);


        if ($datos_productos->rowCount() == 1) {// si es el productos existe 
            $campos = $datos_productos->fetch();//para obtener los datos de los productos.
    ?>

    <form class="form-neon FormularioAjax" action="<?php echo URL;?>ajax/productosAjax.php" 
        method="POST" data-form="actualizar" autocomplete="off">

        <input type="hidden" name="productos_id_up" value="<?php echo $pagina[1]?>"> 
        <!-- $pagina[1] es el id encryptado -->
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_dni" class="bmd-label-floating">CODIGO</label>
                            <input type="text" pattern="[0-9-]{1,27}" class="form-control" name="productos_codigo_up" id="productos_codigo" maxlength="27"
                            value="<?php echo $campos['productos_codigo']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="productos_nombre_up" id="productos_nombre" maxlength="40"
                            value="<?php echo $campos['productos_nombre']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_" class="bmd-label-floating">Precio</label>
                            <input type="text" class="form-control" name="productos_precio_up" id="productos_apellido" max="100000000" min="1"
                            value="<?php echo $campos['productos_precio']; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_telefono" class="bmd-label-floating">Stock</label>
                            <input type="text" class="form-control" name="productos_stock_up" id="productos_telefono" max="100000000" min="1"
                            value="<?php echo $campos['productos_cantidad']; ?>">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_iva" class="bmd-label-floating">Iva</label>
                            <select class="form-control" name="productos_iva_up">
                                <option value="0" <?php if ($campos['productos_iva'] == 0) { echo 'selected=""'; } ?>>
                                    0% <?php if ($campos['productos_iva'] == 0) { echo '(Actual)'; } ?>
                                </option>

                                <option value="5" <?php if ($campos['productos_iva'] == 5) { echo 'selected=""'; } ?>>
                                    5% <?php if ($campos['productos_iva'] == 5) { echo '(Actual)'; } ?>
                                </option>

                                <option value="10" <?php if ($campos['productos_iva'] == 10) { echo 'selected=""'; } ?>>
                                    10% <?php if ($campos['productos_iva'] == 10) { echo '(Actual)'; } ?>
                                </option>
                            </select>

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