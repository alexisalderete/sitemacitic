<style>
    /* .tile{
        border-radius: 25px;
    }
    .tile:hover{
	text-decoration: none;
	border-color: var(--color-three);
    border-radius: 25px;
} */
</style>
<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <!-- <i class="fab fa-dashcube fa-fw"></i>  -->
            &nbsp; Menú principal
    </h3>
    <!-- <p class="text-justify">

    </p> -->
</div>

<!-- Content -->
<div class="full-box tile-container">
    <?php
        require_once "./controladores/estudiantesControlador.php";
        $ins_estudiantes = new estudiantesControlador();
        //cuantos registros hay
        $total_estudiantes = $ins_estudiantes->datos_estudiantes_controlador("Conteo", 0); // 0 porque no se utiliza ese parámetro

    ?>
    <a href="<?php echo URL;?>estudiantes-list/" class="tile">
        <div class="tile-tittle">Estudiantes</div>
        <div class="tile-icon">
            <i class="fas fa-users fa-fw"></i>
            <p><?php echo $total_estudiantes->rowCount(); ?> Registrados</p>
        </div>
    </a>


    <?php
        require_once "./controladores/productosControlador.php";
        $ins_productos = new productosControlador();
        //cuantos registros hay
        $total_productos = $ins_productos->datos_productos_controlador("Conteo", 0); // 0 porque no se utiliza ese parámetro

    ?>
    <a href="<?php echo URL;?>productos-list/" class="tile">
        <div class="tile-tittle">Productos</div>
        <div class="tile-icon">
            <i class="fas fa-users fa-fw"></i>
            <p><?php echo $total_productos->rowCount(); ?> Registrados</p>
        </div>
    </a>





    <?php
        require_once "./controladores/sedesControlador.php";
        $ins_sedes = new sedesControlador();
        //cuantos registros hay
        $total_sedes = $ins_sedes->datos_sedes_controlador("Conteo", 0); // 0 porque no se utiliza ese parámetro

    ?>
    <a href="<?php echo URL;?>sedes-list/" class="tile">
        <div class="tile-tittle">Sedes</div>
        <div class="tile-icon">
            <i class="fa-solid fa-building-columns"></i>
            <p><?php echo $total_sedes->rowCount(); ?> Registrados</p>
        </div>
    </a>






    <?php
        require_once "./controladores/cursosControlador.php";
        $ins_cursos = new cursosControlador();
        //cuantos registros hay
        $total_cursos = $ins_cursos->datos_cursos_controlador("Conteo", 0); // 0 porque no se utiliza ese parámetro

    ?>
    
    <a href="<?php echo URL;?>cursos-list/" class="tile">
        <div class="tile-tittle">Cursos</div>
        <div class="tile-icon">
            <i class="fa-solid fa-book-open-reader"></i>
            <p><?php echo $total_cursos->rowCount(); ?> Registrados</p>
        </div>
    </a>


    <?php
        require_once "./controladores/inscripcionesControlador.php";
        $ins_inscripciones = new inscripcionesControlador();
        //cuantos registros hay
        $total_inscripciones = $ins_inscripciones->datos_inscripciones_controlador("Conteo", 0); // 0 porque no se utiliza ese parámetro

    ?>
    <a href="<?php echo URL;?>inscripciones-list/" class="tile">
        <div class="tile-tittle">Inscripciones</div>
        <div class="tile-icon">
            <i class="fa-regular fa-address-card"></i>
            <p><?php echo $total_inscripciones->rowCount(); ?> Registrados</p>
        </div>
    </a>

    <?php
        require_once "./controladores/pagosControlador.php";
        $ins_pagos = new pagosControlador();
        //cuantos registros hay
        $total_pagos = $ins_pagos->datos_pagos_controlador("Conteo", 0); // 0 porque no se utiliza ese parámetro

    ?>
    <a href="<?php echo URL;?>pagos-list/" class="tile">
        <div class="tile-tittle">Pagos</div>
        <div class="tile-icon">
            <i class="fas fa-hand-holding-usd fa-fw"></i>
            <p><?php echo $total_pagos->rowCount(); ?> Registrados</p>
        </div>
    </a>








    <!-- <a href="<?php #echo URL;?>reservation-reservation/" class="tile">
        <div class="tile-tittle">Reservaciones</div>
        <div class="tile-icon">
            <i class="far fa-calendar-alt fa-fw"></i>
            <p>30 Registradas</p>
        </div>
    </a>

    <a href="<?php #echo URL;?>reservation-pending/" class="tile">
        <div class="tile-tittle">Prestamos</div>
        <div class="tile-icon">
            <i class="fas fa-hand-holding-usd fa-fw"></i>
            <p>200 Registrados</p>
        </div>
    </a>

    <a href="<?php #echo URL;?>reservation-list/" class="tile">
        <div class="tile-tittle">Finalizados</div>
        <div class="tile-icon">
            <i class="fas fa-clipboard-list fa-fw"></i>
            <p>700 Registrados</p>
        </div>
    </a> -->


    <?php
        /* privilegios  */
    if ($_SESSION['privilegio_instituto'] == 1) {
        # se va a mostrar solo a los que tengan privilegios nivel 1
        require_once "./controladores/usuarioControlador.php";
        $ins_usuario = new usuarioControlador();
        //cuantos registros hay sin contar el admin principal
        $total_usuarios = $ins_usuario->datos_usuario_controlador("Conteo", 0) // 0 porque no se utiliza ese parámetro

    ?>
    <a href="<?php echo URL;?>user-list/" class="tile">
        <div class="tile-tittle">Usuarios</div>
        <div class="tile-icon">
            <i class="fas fa-user-secret fa-fw"></i>
            <p><?php echo $total_usuarios->rowCount(); ?> Registrados</p>
        </div>
    </a>
    <?php }?>
    
</div>