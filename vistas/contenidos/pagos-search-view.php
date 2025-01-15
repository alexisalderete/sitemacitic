<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR PAGO POR FECHA
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia fugiat est ducimus inventore, repellendus deserunt cum aliquam dignissimos, consequuntur molestiae perferendis quae, impedit doloribus harum necessitatibus magnam voluptatem voluptatum alias!
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo URL;?>pagos-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA PAGO</a>
        </li>
        <li>
            <a href="<?php echo URL;?>pagos-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PAGOS</a>
        </li>
        <li>
            <a class="active" href="<?php echo URL;?>pagos-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
    </ul>
</div>


<?php
    //para iniciar la busqueda
    // busqueda_ se copia del buscador buscadorAjax
    // y estudiantes porque es el value del modulo en el formulario <input type="hidden" name="modulo" value="estudiantes">
    if (!isset($_SESSION['fecha_inicio_pagos']) && empty($_SESSION['fecha_inicio_pagos']) &&
    !isset($_SESSION['fecha_final_pagos']) && empty($_SESSION['fecha_final_pagos'])
    ){ //si no esta difinida o no existe
?>


<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo URL; ?>ajax/buscadorAjax.php" method="POST" data-form="default" autocomplete="off">
        <input type="hidden" name="modulo" value="pagos">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="busqueda_inicio_pagos" >Fecha inicial (día/mes/año)</label>
                        <input type="date" class="form-control" name="fecha_inicio" id="busqueda_inicio_pagos" maxlength="30">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="busqueda_final_pagos" >Fecha final (día/mes/año)</label>
                        <input type="date" class="form-control" name="fecha_final" id="busqueda_final_pagos" maxlength="30">
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center" style="margin-top: 40px;">
                        <button type="submit" class="btn btn-raised btn-info"><i class="fas fa-search"></i> &nbsp; BUSCAR</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<?php }else{?>

<div class="container-fluid">
    <form class="FormularioAjax" action="<?php echo URL; ?>ajax/buscadorAjax.php" method="POST" data-form="buscar" autocomplete="off">
        <input type="hidden" name="modulo" value="pagos">
        <input type="hidden" name="eliminar_busqueda" value="eliminar">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <p class="text-center" style="font-size: 20px;">
                        
                        Fecha de busqueda: <strong><?php echo date("d-m-Y",strtotime( $_SESSION['fecha_inicio_pagos']));?>
                        &nbsp; a &nbsp; <?php echo date("d-m-Y",strtotime( $_SESSION['fecha_final_pagos']));?></strong>
                    </p>
                </div>
                <div class="col-12">
                    <p class="text-center">
                        <button type="submit" class="btn btn-raised btn-danger"><i class="far fa-trash-alt"></i> &nbsp; ELIMINAR BÚSQUEDA</button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>


<div class="container-fluid">
<?php
        require_once "./controladores/pagosControlador.php";
        $ins_pagos = new pagosControlador();
        /* 
        parametros: 
        -$pagina[1] el indice 1 de la url (el indice 0 es cursos-list-view)
        -5 es la cantidad maxima de registros que se muestra en la tabla
        -$_SESSION['privilegio_instituto'] el usuario que esta viendo la vista
        -$pagina[0] el indice 0 porque es cursos-list-view, para identificar la vista en el que nos encotramos
        -"" vacio porque es el listado normal, no es la busqueda
        */
        echo $ins_pagos->paginador_pagos_controlador($pagina[1], 5, $_SESSION['privilegio_instituto'],
        $pagina[0], "Busqueda", $_SESSION['fecha_inicio_pagos'],$_SESSION['fecha_final_pagos']);
    ?>
</div>

<?php }?>