<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ESTUDIANTES
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit nostrum rerum animi natus beatae ex. Culpa blanditiis tempore amet alias placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo URL;?>estudiantes-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ESTUDIANTES</a>
        </li>
        <li>
            <a href="<?php echo URL;?>estudiantes-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ESTUDIANTES</a>
        </li>
        <li>
            <a class="active" href="<?php echo URL;?>estudiantes-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ESTUDIANTES</a>
        </li>
    </ul>	
</div>

<?php
    //para iniciar la busqueda
    // busqueda_ se copia del buscador buscadorAjax
    // y estudiantes porque es el value del modulo en el formulario <input type="hidden" name="modulo" value="estudiantes">
    if (!isset($_SESSION['busqueda_estudiantes']) && empty($_SESSION['busqueda_estudiantes']) ){ //si no esta difinida o no existe
?>

<!-- Content here-->
<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo URL; ?>ajax/buscadorAjax.php" method="POST" data-form="default" autocomplete="off">
        <input type="hidden" name="modulo" value="estudiantes">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="inputSearch" class="bmd-label-floating">¿Qué ESTUDIANTE estas buscando?</label>
                        <input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="30">
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
        <input type="hidden" name="modulo" value="estudiantes">
        <input type="hidden" name="eliminar_busqueda" value="eliminar">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <p class="text-center" style="font-size: 20px;">
                        Resultados de la busqueda <strong>“<?php echo $_SESSION['busqueda_estudiantes'];?>”</strong>
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
        require_once "./controladores/estudiantesControlador.php";
        $ins_estudiantes = new estudiantesControlador();

        echo $ins_estudiantes->paginador_estudiantes_controlador($pagina[1], 5, $_SESSION['privilegio_instituto'],
        $pagina[0], $_SESSION['busqueda_estudiantes']);
    ?>
</div>

<?php }?>