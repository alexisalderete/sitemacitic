<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ESTUDIANTES
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit nostrum rerum animi natus beatae ex. Culpa blanditiis tempore amet alias placeat, obcaecati quaerat ullam, sunt est, odio aut veniam ratione.
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo URL;?>estudiantes-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR ESTUDIANTE</a>
        </li>
        <li>
            <a class="active" href="<?php echo URL;?>estudiantes-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE ESTUDIANTE</a>
        </li>
        <li>
            <a href="<?php echo URL;?>estudiantes-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR ESTUDIANTES</a>
        </li>
    </ul>	
</div>

<!-- Content here-->
<div class="container-fluid">
    <?php
        require_once "./controladores/estudiantesControlador.php";
        $ins_estudiantes = new estudiantesControlador();

        echo $ins_estudiantes->paginador_estudiantes_controlador($pagina[1], 5, $_SESSION['privilegio_instituto'],
        $pagina[0], "");
    ?>
</div>