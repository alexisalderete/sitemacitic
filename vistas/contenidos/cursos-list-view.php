<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CURSOS
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
            <a class="active" href="<?php echo URL;?>cursos-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE CURSOS</a>
        </li>
        <li>
            <a href="<?php echo URL;?>cursos-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CURSOS</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
    <?php
        require_once "./controladores/cursosControlador.php";
        $ins_cursos = new cursosControlador();
        /* 
        parametros: 
        -$pagina[1] el indice 1 de la url (el indice 0 es cursos-list-view)
        -5 es la cantidad maxima de registros que se muestra en la tabla
        -$_SESSION['privilegio_instituto'] el usuario que esta viendo la vista
        -$pagina[0] el indice 0 porque es cursos-list-view, para identificar la vista en el que nos encotramos
        -"" vacio porque es el listado normal, no es la busqueda
        */
        echo $ins_cursos->paginador_cursos_controlador($pagina[1], 5, $_SESSION['privilegio_instituto'],
        $pagina[0], "");
    ?>
</div>