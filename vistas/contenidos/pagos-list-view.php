<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="far fa-calendar-alt fa-fw"></i> &nbsp; LISTA DE PAGOS
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
            <a class="active" href="<?php echo URL;?>pagos-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PAGOS</a>
        </li>
        <li>
            <a href="<?php echo URL;?>pagos-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
    </ul>
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
        $pagina[0], "", "","");
    ?>
</div>