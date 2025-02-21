<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR cursos
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Harum delectus eos enim numquam fugit optio accusantium, aperiam eius facere architecto facilis quibusdam asperiores veniam omnis saepe est et, quod obcaecati.
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo URL;?>cursos-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CURSO</a>
        </li>
        <li>
            <a href="<?php echo URL;?>cursos-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CURSOS</a>
        </li>
        <li>
            <a class="active" href="<?php echo URL;?>cursos-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CURSOS</a>
        </li>
    </ul>
</div>

<?php
    //para iniciar la busqueda
    // busqueda_ se copia del buscador buscadorAjax
    // y cursos porque es el value del modulo en el formulario <input type="hidden" name="modulo" value="cursos">
    if (!isset($_SESSION['busqueda_cursos']) && empty($_SESSION['busqueda_cursos']) ){ //si no esta difinida o no existe
?>

<!--CONTENT-->
<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo URL; ?>ajax/buscadorAjax.php" method="POST" data-form="default" autocomplete="off">
    <input type="hidden" name="modulo" value="cursos">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="inputSearch" class="bmd-label-floating">¿Qué cursos estas buscando?</label>
                        <input type="text" class="form-control" name="busqueda_inicial" id="inputSearch" maxlength="30">
                    </div>
                </div>
                <div class="col-12">
                    <p class="text-center">
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
        <input type="hidden" name="modulo" value="cursos">
        <input type="hidden" name="eliminar_busqueda" value="eliminar">
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-12 col-md-6">
                    <p class="text-center" style="font-size: 20px;">
                        Resultados de la busqueda <strong>“<?php echo $_SESSION['busqueda_cursos'];?>”</strong>
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
        require_once "./controladores/cursosControlador.php";
        $ins_cursos = new cursosControlador();

        echo $ins_cursos->paginador_cursos_controlador($pagina[1], 5, $_SESSION['privilegio_instituto'],
        $pagina[0], $_SESSION['busqueda_cursos']);
    ?>
</div>

<?php }?>

