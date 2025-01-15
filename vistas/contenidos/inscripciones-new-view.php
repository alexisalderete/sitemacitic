<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA INSCRIPCIÓN
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium quod harum vitae, fugit quo soluta. Molestias officiis voluptatum delectus doloribus at tempore, iste optio quam recusandae numquam non inventore dolor.
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo URL;?>inscripciones-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; NUEVA INSCRIPCIÓN</a>
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
    <div class="container-fluid form-neon">
        <!-- <p class="text-center"> -->

            <!-- si NO se agregó los datos de los estudintes -->
            <?php //if(empty($_SESSION['datos_estudiantes'])){ ?>
                <!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#Modalestudiantes"><i class="fas fa-user-plus"></i> &nbsp; Agregar estudiante</button> -->
            <?php //} ?>

            <?php //if(empty($_SESSION['datos_cursos'])){ ?>
                <!-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#Modalcursos"><i class="fa-solid fa-book-open-reader"></i> &nbsp; Agregar curso</button> -->
            <?php //} ?>
        <!-- </p> -->

        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información del estudiante</legend>
            

            <!-- si NO se agregó los datos de los estudintes -->
            <?php if(empty($_SESSION['datos_estudiantes'])){ ?>
                <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un estudiante</span>
                <br>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#Modalestudiantes"><i class="fas fa-user-plus"></i> &nbsp; Agregar estudiante</button>

                <br>
                <br>

            <?php } else{ ?>
                

                <!-- si ya se agregó los datos de los estudintes -->
            <span class="roboto-medium">ESTUDIANTE:</span>
            <form class="FormularioAjax" action="<?php echo URL;?>ajax/inscripcionesAjax.php" method="POST" data-form="inscripciones" style="">
                <input type="hidden" name="id_eliminar_estudiantes" value="<?php echo $_SESSION['datos_estudiantes']['ID']; ?> ">
                <?php echo $_SESSION['datos_estudiantes']['Nombre']." ".$_SESSION['datos_estudiantes']['Apellido']. " (".$_SESSION['datos_estudiantes']['DNI'].")"; ?>
                
                
                <br>
                <!-- button de tipo submit para envia los formularios -->
                <!-- <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-times"></i></button> -->
                <button type="submit" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i> Remover estudiante</button>
            </form>

            <br>
            <br>

            <?php }?>
        </fieldset>


        <!-- si NO se agregó los datos de un curso -->
        <?php if(empty($_SESSION['datos_cursos'])){ ?>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información del curso</legend>
            <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione un curso</span>
            <br>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#Modalcursos"><i class="fa-solid fa-book-open-reader"></i> &nbsp; Agregar curso</button>

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

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_duracion" class="bmd-label-floating">Duración en meses</label>
                                <input type="number" class="form-control" name="cursos_duracion_up" id="cursos_duracion" max="99" min="1"
                                value="<?php echo $_SESSION['datos_cursos']['Duracion']; ?>" readonly>
                            </div>
                        </div>

                        

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_cupos" class="bmd-label-floating">Precio de inscripción</label>
                                <input type="number" class="form-control" name="cursos_precio_up" id="cursos_cupos" max="100000000" min="1"
                                value="<?php echo $_SESSION['datos_cursos']['Precio']; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_cupos" class="bmd-label-floating">Precio de mensualidad</label>
                                <input type="number" class="form-control" name="cursos_mensualidad_up" id="cursos_cupos" max="100000000" min="1"
                                value="<?php echo $_SESSION['datos_cursos']['Mensualidad']; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_cupos" class="bmd-label-floating">Cupos</label>
                                <input type="number" class="form-control" name="cursos_cupos_up" id="cursos_cupos" max="20000" min="1"
                                value="<?php echo $_SESSION['datos_cursos']['Cupos']; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_detalle" class="bmd-label-floating">Detalle</label>
                                <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="cursos_detalle_up" id="cursos_detalle" maxlength="190"
                                value="<?php echo $_SESSION['datos_cursos']['Detalle']; ?>" readonly>
                            </div>
                        </div>


                    </div>
                </div>
            </fieldset>
            <!-- button de tipo submit para envia los formularios -->
            <button type="submit" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i> Remover curso</button>

        </form>
        <?php }?>
        <br>
        <br>
























        <!-- <legend><i class="far fa-plus-square"></i> &nbsp; Información de productos</legend>
        <span class="text-danger">&nbsp; <i class="fas fa-exclamation-triangle"></i> Seleccione los productos</span>
        <br>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#Modalproductos"><i class="fa-solid fa-book-open-reader"></i> &nbsp; Agregar productos</button> -->
        <!-- si NO se agregó los datos de un productos -->
        <?php //if(!empty($_SESSION['datos_productos'])){ ?>

        <!-- <form class="FormularioAjax" action="<?php //echo URL;?>ajax/inscripcionesAjax.php" method="POST" data-form="inscripciones">
            <input type="hidden" name="id_eliminar_productos" value="<?php //echo $_SESSION['datos_productos']['ID']; ?> ">

            <fieldset>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="productos_codigo" class="bmd-label-floating">Códido</label>
                                <input type="text" pattern="[a-zA-Z0-9-]{1,45}" class="form-control" name="productos_codigo_up" id="productos_codigo" maxlength="45"
                                value="<?php //echo $_SESSION['datos_productos']['Codigo']; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="productos_nombre" class="bmd-label-floating">Nombre</label>
                                <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" name="productos_nombre_up" id="productos_nombre" maxlength="140"
                                value="<?php //echo $_SESSION['datos_productos']['Nombre']; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="productos_precio" class="bmd-label-floating">Precio</label>
                                <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="productos_precio_up" id="productos_precio" maxlength="9"
                                value="<?php //echo $_SESSION['datos_productos']['Precio']; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="productos_cantidad" class="bmd-label-floating">Cantidad</label>
                                <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="productos_cantidad_up" id="productos_cantidad" maxlength="190"
                                value="<?php //echo $_SESSION['datos_productos']['Cantidad']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset> -->
            <!-- button de tipo submit para envia los formularios -->
            <!-- <button type="submit" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i> Remover producto</button> -->

        <!-- </form> -->
        <?php //}?>
        <!-- <br>
        <br> -->



























        <form class="FormularioAjax" action="<?php echo URL;?>ajax/inscripcionesAjax.php" method="POST" data-form="guardar">
            <fieldset>
                <legend><i class="fas fa-cubes"></i> &nbsp; Detalles de la inscripción</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inscripciones_fecha_inicio">Fecha de la inscripción</label>
                                <input type="date" class="form-control" name="inscripciones_fecha_inicio_reg" 
                                value="<?php echo date("Y-m-d");?>" id="inscripciones_fecha_inicio"> 
                                <!-- con date("Y-m-d") se pone la fecha actual -->
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inscripciones_hora_inicio">Hora de la inscripción</label>
                                <input type="time" class="form-control" name="inscripciones_hora_inicio_reg"
                                value="<?php echo date("H:i");?>" id="inscripciones_hora_inicio">
                                <!--con date("H:i") para la hora acutal -->
                            </div>
                        </div>
                    </div>
                </div>


                <div class="container-fluid">
                    <div class="row">
                        <!-- <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="inscripciones_estado" class="bmd-label-floating">Estado</label>
                                <select class="form-control" name="inscripciones_estado_reg" id="inscripciones_estado">
                                    <option value="" selected="">Seleccione una opción</option>
                                    <option value="Reservacion">Reservación</option>
                                    <option value="Prestamo">inscripciones</option>
                                    <option value="Finalizado">Finalizado</option>
                                </select>
                            </div>
                        </div> -->
                        <!-- <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inscripciones_total" class="bmd-label-floating">Costo de la inscripción en <?php echo MONEDA; ?></label>
                                <input type="text" pattern="[0-9.]{1,10}" class="form-control" name="inscripciones_costo_reg" id="inscripciones_costo_reg" maxlength="10">
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="inscripciones_pagado" class="bmd-label-floating">Mensualidad del curso en <?php echo MONEDA; ?></label>
                                <input type="text" pattern="[0-9.]{1,10}" class="form-control" name="inscripciones_mensualidad_reg" id="inscripciones_mensualidad_reg" maxlength="10">
                            </div>
                        </div> -->
                    </div>
                </div>
            </fieldset>
            <p class="text-center">
                <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
                &nbsp; &nbsp;
                <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; Guardar</button>
            </p>
        </form>
    </div>
</div>


<!-- MODAL estudiantes -->
<div class="modal fade" id="Modalestudiantes" tabindex="-1" role="dialog" aria-labelledby="Modalestudiantes" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalestudiantes">Agregar estudiantes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_estudiantes" class="bmd-label-floating">DNI, Nombre, Apellido, Telefono</label>
                        <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_estudiantes" id="input_estudiantes" maxlength="30">
                    </div>
                </div>
                <br>
                <!-- los datos de esta tabla esta en el controlador de inscripcionesControlador en la funcion buscar_estudiantes_inscripciones_controlador() -->
                <div class="container-fluid" id="tabla_estudiantes"></div>
                
            </div>
            <!-- onclick="buscar_estudiantes()" para que ejecute la funcion del archivo inc/inscripciones.php -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" onclick="buscar_estudiantes()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
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







<!-- MODAL productos -->
<!-- <div class="modal fade" id="Modalproductos" tabindex="-1" role="dialog" aria-labelledby="Modalproductos" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalproductos">Agregar productos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_productos" class="bmd-label-floating">Código, Nombre</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_productos" id="input_productos" maxlength="30">
                    </div>
                </div>
                <br>
                <div class="container-fluid" id="tabla_productos"></div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" onclick="buscar_productos()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div> -->






<!-- MODAL AGREGAR cursos -->
<!-- 
<div class="modal fade" id="ModalAgregarcursos" tabindex="-1" role="dialog" aria-labelledby="ModalAgregarcursos" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content FormularioAjax" action="<?php #echo URL?>ajax/inscripcionesAjax.php" method="POST" data-form="default" autocomplete="off">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalAgregarcursos">Selecciona el formato, cantidad de cursoss, tiempo y costo del inscripciones del cursos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_agregar_cursos" id="id_agregar_cursos">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="detalle_formato" class="bmd-label-floating">Formato de inscripciones</label>
                                <select class="form-control" name="detalle_formato" id="detalle_formato">
                                    <option value="Horas" selected="" >Horas</option>
                                    <option value="Dias">Días</option>
                                    <option value="Evento">Evento</option>
                                    <option value="Mes">Mes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="detalle_cantidad" class="bmd-label-floating">Cantidad de cursoss</label>
                                <input type="num" pattern="[0-9]{1,7}" class="form-control" name="detalle_cantidad" id="detalle_cantidad" maxlength="7" required="" >
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="detalle_tiempo" class="bmd-label-floating">Tiempo (según formato)</label>
                                <input type="num" pattern="[0-9]{1,7}" class="form-control" name="detalle_tiempo" id="detalle_tiempo" maxlength="7" required="" >
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="detalle_costo_tiempo" class="bmd-label-floating">Costo por unidad de tiempo</label>
                                <input type="text" pattern="[0-9.]{1,15}" class="form-control" name="detalle_costo_tiempo" id="detalle_costo_tiempo" maxlength="15" required="" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" >Agregar</button>
                &nbsp; &nbsp;
                <button type="button" class="btn btn-secondary" onclick="modal_buscar_cursos()">Cancelar</button>
            </div>
        </form>
    </div>
</div>
-->

<?php
    //incluimos codigos de Js
    include_once "./vistas/inc/inscripciones.php";
?>