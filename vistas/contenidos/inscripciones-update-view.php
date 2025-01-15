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
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR INSCRIPCIÓN
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium quod harum vitae, fugit quo soluta. Molestias officiis voluptatum delectus doloribus at tempore, iste optio quam recusandae numquam non inventore dolor.
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a href="<?php echo URL;?>inscripciones-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA INSCRIPCIÓN</a>
        </li>
        <li>
            <a href="<?php echo URL;?>inscripciones-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE INSCRIPCIONES</a>
        </li>
        <li>
            <a href="<?php echo URL;?>inscripciones-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
        </li>
    </ul>
</div>


<div class="container-fluid">

    <?php

    

        require_once "./controladores/inscripcionesControlador.php";
        $ins_inscripciones = new inscripcionesControlador();

        /*$pagina se definió en plantilla
            $pagina[1] posicion 1 porque en la url, la id que esta encriptado está en la posicion 1
            OBS: la posicion 0 es la vista (user-update) 
        */
        $datos_inscripciones = $ins_inscripciones -> datos_inscripciones_controlador("Unico", $pagina[1]);

        if ($datos_inscripciones->rowCount() == 1) {// si el inscripción existe 
            
            $campos = $datos_inscripciones->fetch();//para obtener los datos de los inscripción.

            //if ($campos['inscripciones_estado']) {
            //para pagos (video 115)
            //if ($campos['pagos_estado'] == "Pagado" && $campos[pagos_pagado] == $campos[pagos_total]) { //si esta pagado
    ?>

        <!-- <div class="alert alert-danger text-center" role="alert">
            <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
            <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
            <p class="mb-0">Lo sentimos, no se pudo realizar el pago porque ya se encuantra pagado.</p>
        </div> -->

    <?php
            /*}
            else{*/
            
    ?>
    <div class="container-fluid form-neon">

        <!-- <div class="container-fluid">
            <p class="text-center roboto-medium">AGREGAR NUEVO PAGO A ESTA INSCRIPCIÓN</p>
            <p class="text-center">Esta INSCRIPCIÓN presenta un pago pendiente por la cantidad de <strong>$50</strong>, puede agregar un pago a esta INSCRIPCIÓN haciendo clic en el siguiente botón.</p>
            <p class="text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalPago"><i class="far fa-money-bill-alt"></i> &nbsp; Agregar pago</button>
            </p>
        </div> -->
        <div class="container-fluid">
            <?php
                //para llamar los datos del estudiante
                require_once "./controladores/estudiantesControlador.php";
                $ins_estudiantes = new estudiantesControlador();
                $datos_estudiantes = $ins_estudiantes -> datos_estudiantes_controlador("Unico", $lc->encryption($campos['estudiantes_id']));
                $datos_estudiantes = $datos_estudiantes -> fetch();
            ?>

            <div>
                <span class="roboto-medium">ESTUDIANTE:</span> 
                &nbsp; <?php echo $datos_estudiantes['estudiantes_nombre']." ".$datos_estudiantes['estudiantes_apellido'] ?>
            </div>

            <!-- <div class="table-responsive">
                <table class="table table-dark table-sm">
                    <thead>
                        <tr class="text-center roboto-medium">
                            <th>ITEM</th>
                            <th>CANTIDAD</th>
                            <th>TIEMPO</th>
                            <th>COSTO</th>
                            <th>TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center" >
                            <td>Silla plastica</td>
                            <td>7</td>
                            <td>Hora</td>
                            <td>$5.00</td>
                            <td>$35.00</td>
                        </tr>
                        <tr class="text-center" >
                            <td>Silla metalica</td>
                            <td>9</td>
                            <td>Día</td>
                            <td>$5.00</td>
                            <td>$45.00</td>
                        </tr>
                        <tr class="text-center" >
                            <td>Mesa plastica</td>
                            <td>5</td>
                            <td>Evento</td>
                            <td>$10.00</td>
                            <td>$50.00</td>
                        </tr>
                    </tbody>
                </table>
            </div> -->

        </div>

        <?php
            //para llamar los datos del curso
            require_once "./controladores/cursosControlador.php";
            $ins_cursos = new cursosControlador();
            $datos_cursos = $ins_cursos -> datos_cursos_controlador("Unico", $lc->encryption($campos['cursos_id']));
            $datos_cursos = $datos_cursos -> fetch();

        ?>















        


            

            <!-- si NO se agregó los datos de un curso -->
        <?php if(empty($_SESSION['datos_cursos'])){ ?>

            <fieldset>
                <legend><i class="far fa-plus-square"></i> &nbsp; Información del curso</legend>
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_codigo" class="bmd-label-floating">Código</label>
                                <input type="text" pattern="[a-zA-Z0-9-]{1,45}" class="form-control" maxlength="45"
                                value="<?php echo $datos_cursos['cursos_codigo']; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_nombre" class="bmd-label-floating">Nombre</label>
                                <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" maxlength="140"
                                value="<?php echo $datos_cursos['cursos_nombre']; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_nombre" class="bmd-label-floating">Sede</label>
                                <input type="text" class="form-control"
                                value="<?php echo $datos_cursos['sedes_nombre']; ?>" readonly>
                            </div>
                        </div>

                    </div>
                </div>
            </fieldset>



            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#Modalcursos"><i class="fa-solid fa-book-open-reader"></i> &nbsp; Cambiar curso</button>

        <?php } else{ ?>

        <form class="FormularioAjax" action="<?php echo URL;?>ajax/inscripcionesAjax.php" method="POST" data-form="inscripciones">
            <input type="hidden" name="id_eliminar_cursos" value="<?php echo $_SESSION['datos_cursos']['ID']; ?> ">

            <fieldset>
                <legend><i class="far fa-plus-square"></i> &nbsp; Información del curso</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_codigo" class="bmd-label-floating">Códido</label>
                                <input type="text" pattern="[a-zA-Z0-9-]{1,45}" class="form-control" name="cursos_codigo_up" id="cursos_codigo" maxlength="45"
                                value="<?php echo $_SESSION['datos_cursos']['Codigo']; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_nombre" class="bmd-label-floating">Nombre</label>
                                <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" name="cursos_nombre_up" id="cursos_nombre" maxlength="140"
                                value="<?php echo $_SESSION['datos_cursos']['Nombre']; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_sedes" class="bmd-label-floating">Sede</label>
                                <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" name="cursos_sedes_up" id="cursos_nombre" maxlength="140"
                                value="<?php echo $_SESSION['datos_cursos']['Sede']; ?>" readonly>
                            </div>
                        </div>

                        <!-- <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_duracion" class="bmd-label-floating">Duración en meses</label>
                                <input type="number" class="form-control" name="cursos_duracion_up" id="cursos_duracion" max="99" min="1"
                                value="<?php //echo $_SESSION['datos_cursos']['Duracion']; ?>" readonly>
                            </div>
                        </div>

                        

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_cupos" class="bmd-label-floating">Precio de inscripción</label>
                                <input type="number" class="form-control" name="cursos_precio_up" id="cursos_cupos" max="100000000" min="1"
                                value="<?php //echo $_SESSION['datos_cursos']['Precio']; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_cupos" class="bmd-label-floating">Precio de mensualidad</label>
                                <input type="number" class="form-control" name="cursos_mensualidad_up" id="cursos_cupos" max="100000000" min="1"
                                value="<?php //echo $_SESSION['datos_cursos']['Mensualidad']; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_cupos" class="bmd-label-floating">Cupos</label>
                                <input type="number" class="form-control" name="cursos_cupos_up" id="cursos_cupos" max="20000" min="1"
                                value="<?php //echo $_SESSION['datos_cursos']['Cupos']; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_detalle" class="bmd-label-floating">Detalle</label>
                                <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="cursos_detalle_up" id="cursos_detalle" maxlength="190"
                                value="<?php //echo $_SESSION['datos_cursos']['Detalle']; ?>" readonly>
                            </div>
                        </div> -->


                    </div>
                </div>
            </fieldset>
            <!-- button de tipo submit para envia los formularios -->
            <button type="submit" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i> Remover curso</button>

        </form>
        <?php }?>

        <br>
        <br>






        <form class="FormularioAjax" action="<?php echo URL;?>ajax/inscripcionesAjax.php" 
        method="POST" data-form="actualizar" autocomplete="off">
            <input type="hidden" name="inscripciones_codigo_up" value="<?php echo $lc->encryption($campos['inscripciones_codigo'])?>"> 
            <input type="hidden" name="estudiantes_id" value="<?php echo $campos['estudiantes_id']?>"> 
        <!-- $pagina[1] es el id encryptado -->

        <!-- para comprobar si se cambio el curso o no -->
        <?php if (empty($_SESSION['datos_cursos'])){?>
            <input type="hidden" name="cursos_id_up" value="<?php echo $campos['cursos_id']?>"> 
        <?php } else{ ?>
            <input type="hidden" name="cursos_id_up" value="<?php echo $_SESSION['datos_cursos']['ID']?>">
        <?php } ?>


            <fieldset>
                <legend><i class="fas fa-cubes"></i> &nbsp; Detalles de la inscripción</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="inscripciones_fecha_inicio">Fecha de inscripción</label>
                                <input type="date" class="form-control" name="inscripciones_fecha_inicio_up"
                                value="<?php echo $campos['inscripciones_fecha'];?>">
                                <!-- con date("Y-m-d") se pone la fecha actual -->
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="inscripciones_hora_inicio">Hora de inscripciones</label>
                                <input type="time" class="form-control" name="inscripciones_hora_inicio_up"
                                value="<?php echo date('H:i', strtotime($campos['inscripciones_hora'])); ?>" id="inscripciones_hora_inicio">
                                <!--con date("H:i") para la hora acutal -->
                            </div>
                        </div>


                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="inscripciones_estado" class="bmd-label-floating">Estado</label>
                                <select class="form-control" name="inscripciones_estado_up">
                                    <option value="Activo"<?php if ($campos['inscripciones_estado'] =="Activo") {
                                        echo 'selected=""';
                                    } ?> >Activo <?php if($campos['inscripciones_estado'] == "Activo"){
                                        echo '(Actual)';} ?></option>
                                    <option value="Inactivo" <?php if ($campos['inscripciones_estado'] =="Inactivo") {
                                        echo 'selected=""';
                                    } ?>>Inactivo <?php if($campos['inscripciones_estado'] == "Inactivo"){
                                        echo '(Actual)';} ?></option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

            </fieldset>
            <br>
            <p class="text-center">
                <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp; ACTUALIZAR</button>
            </p>
        </form>
    </div>

    <!-- MODAL inscripción -->
    <!-- <div class="modal fade" id="ModalPago" tabindex="-1" role="dialog" aria-labelledby="ModalPago" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalPago">Agregar pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive" >
                        <table class="table table-hover table-bordered table-sm">
                            <thead>
                                <tr class="text-center bg-dark">
                                    <th>FECHA</th>
                                    <th>MONTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td>Fecha</td>
                                    <td>Monto</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="container-fluid">
                        <input type="hidden" name="pago_codigo_reg">
                        <div class="form-group">
                            <label for="pago_monto_reg" class="bmd-label-floating">Monto en $</label>
                            <input type="text" pattern="[0-9.]{1,10}" class="form-control" name="pago_monto_reg" id="pago_monto_reg" maxlength="10" required="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-raised btn-info btn-sm" >Agregar pago</button> &nbsp;&nbsp; 
                    <button type="button" class="btn btn-raised btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div> -->

    <?php
            //}
        }
        else{  //si el cursos no existe
            // si no existe ningun cursos con el ID de cursos (encriptado) en la URL
    ?>
    <div class="alert alert-danger text-center" role="alert">
        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>

    <?php } ?>
</div>



<!-- MODAL cursos -->
<div class="modal fade" id="Modalcursos" tabindex="-1" role="dialog" aria-labelledby="Modalcursos" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalcursos">Agregar cursos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_cursos" class="bmd-label-floating">Código, Nombre</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_cursos" id="input_cursos" maxlength="30">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_cursos"></div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" onclick="buscar_cursos()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
    //incluimos codigos de Js
    include_once "./vistas/inc/inscripciones.php";
?>