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
        <i class="fas fa-plus fa-fw"></i> &nbsp; NUEVO PAGO
    </h3>
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
            <a href="<?php echo URL;?>pagos-search/"><i class="fas fa-search-dollar fa-fw"></i> &nbsp; BUSCAR POR FECHA</a>
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


        require_once "./controladores/pagosControlador.php";
        $ins_pagos = new pagosControlador();
        /*$pagina se definió en plantilla
            $pagina[1] posicion 1 porque en la url, la id que esta encriptado está en la posicion 1
            OBS: la posicion 0 es la vista (user-update) 
        */
        $datos_pagos = $ins_pagos -> datos_pagos_controlador("Unico", $pagina[1]);


        //|| $datos_inscripciones->rowCount() == 1 para pagar desde la la tabla de incripcion 
        if ($datos_inscripciones->rowCount() == 1) {// si la inscripcion se selecciono
            
            $campos = $datos_inscripciones->fetch();//para obtener los datos de los PAGO.

            //if ($campos['pagos_estado']) {
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


    <?php
        require_once "./controladores/pagosControlador.php";
		$ins_inscripciones_2 = new inscripcionesControlador(); #se instancia al pagos controlador

		$datos_inscripciones_2 = $ins_inscripciones_2 -> datos_inscripciones_controlador("Pagos", $pagina[1]);
		$datos_inscripciones_2 = $datos_inscripciones_2 -> fetch();

    ?>

        <form class="FormularioAjax" action="<?php echo URL;?>ajax/pagosAjax.php" method="POST" data-form="pagos">
            <!-- <input type="hidden" name="id_eliminar_inscripciones" value="<?php #echo $datos_inscripciones_2['pagos_id']; ?> "> -->

            <fieldset>
                <legend><i class="far fa-plus-square"></i> &nbsp; Información de la inscripción</legend>
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_nombre" class="bmd-label-floating">Código de inscripcion</label>
                                <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" maxlength="140"
                                value="<?php echo $datos_inscripciones_2['inscripciones_codigo']; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_nombre" class="bmd-label-floating">Estudiante</label>
                                <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" maxlength="140"
                                value="<?php echo $datos_inscripciones_2['estudiantes_nombre'].' '. $datos_inscripciones_2['estudiantes_apellido'];  ?>" readonly>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="cursos_nombre" class="bmd-label-floating">Curso</label>
                                <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" maxlength="140"
                                value="<?php echo $datos_inscripciones_2['cursos_nombre']; ?>" readonly>
                            </div>
                        </div>

                        <!-- <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="pagos_total" class="bmd-label-floating">Costo de la inscripción en <?php #echo MONEDA; ?></label>
                                <input type="text" 
                                value="<?php #echo $datos_inscripciones_2['inscripciones_costo'];?>" pattern="[0-9.]{1,10}" class="form-control" maxlength="10" readonly>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="pagos_pagado" class="bmd-label-floating">Costo de la mensualidad en <?php #echo MONEDA; ?></label>
                                <input type="text" 
                                value="<?php #echo $datos_inscripciones_2['inscripciones_mensualidad'];?>" pattern="[0-9.]{1,10}" class="form-control" maxlength="10" readonly>
                            </div>
                        </div> -->
                        
                    </div>
                </div>
            </fieldset>


            <!-- button de tipo submit para envia los formularios -->
            <!-- <button type="submit" class="btn btn-danger"><i class="far fa-trash-alt"></i> Remover inscripción</button> -->
        </form>

        <br>

        <button type="button" class="btn btn-primary btn-sm" onclick="agregar_conceptos_incripciones(<?php echo $campos['inscripciones_id']?>)"><i class="fa-solid fa-plus"></i> Agregar inscripción</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#Modalcuotas"><i class="fa-solid fa-file-invoice-dollar"></i> &nbsp;Agregar cuotas</button>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#Modalconceptos"><i class="fa-solid fa-file-invoice-dollar"></i> &nbsp;Más conceptos</button>
        
        <br>
        <br>
        <div class="table-responsive-1">
            <table class="table table-sm">
                <thead>
                    <tr class="text-center">
                        <th>CONCEPTO</th>
                        <th>CANTIDAD</th>
                        <th>COSTO</th>
                        <th>SUBTOTAL</th>
                        <!-- <th>DETALLE</th> -->
                        <th>ELIMINAR</th>
                    </tr>
                </thead>
                <tbody>
                
                    <?php 
                    if (isset($_SESSION['datos_conceptos_inscripciones']) && count($_SESSION['datos_conceptos_inscripciones']) >=1 ) {
                    
                        $_SESSION['pagos_total'] = 0;
                        $_SESSION['pagos_cantidad'] = 0;

                        foreach($_SESSION['datos_conceptos_inscripciones'] as $items){
                            $subtotal = $items['Cantidad'] * $items['Costo'];

                            //$subtotal = $costo = $subtotal;
                            
                    ?>
                        <tr class="text-center" >

                            <td><?php echo $items['Concepto'];?></td>
                            <td><?php echo $items['Cantidad'];?></td>
                            <td><?php echo MONEDA.' '.number_format($items['Costo'], 0, '', '.');?></td> 
                            <td><?php echo MONEDA.' '.number_format($subtotal, 0, '', '.');?></td> 
                            <!-- <td>
                                <button type="button" class="btn btn-info" data-toggle="popover" data-trigger="hover" title="Nombre del item" data-content="Detalle completo del item">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td> -->
                            <td>
                                <form class="FormularioAjax" action="<?php echo URL;?>ajax/pagosAjax.php" method="POST" data-form="pagos">
                                    <input type="hidden" name="id_eliminar_conceptos" value="<?php echo $items['ID'];?>">
                                    <input type="hidden" name="tipo_eliminar_conceptos" value="<?php echo $items['tipo'];?>"> <!-- Tipo del concepto -->
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>

                    <?php

                        $_SESSION['pagos_total'] += $subtotal;
                        $_SESSION['pagos_cantidad'] += $items['Cantidad'];
                    }

                    
                    ?>

                    
                    <tr class="text-center bg-light">
                        <td><strong>TOTAL</strong></td>
                        <td><strong><?php echo $_SESSION['pagos_cantidad'];?></strong></td>
                        <td colspan="1"></td>
                        <td><strong><?php echo MONEDA.' '.number_format($_SESSION['pagos_total'], 0, '', '.');?></strong></td>
                        <td colspan="2"></td>
                    </tr>

                    <?php 
                    }
                    else{
                        $_SESSION['pagos_total'] = 0;
                        $_SESSION['pagos_cantidad'] = 0;


                    ?>

                    

                    <tr class="text-center" >
                        <td colspan="6">Concepto no seleccionado.</td>
                    </tr>


                    <?php } ?>


                </tbody>
            </table>
        </div>



        <form class="FormularioAjax" action="<?php echo URL;?>ajax/pagosAjax.php" method="POST" data-form="guardar">
            
            <input type="hidden" class="form-control" name="pagos_agregar_desde_inscripciones">

            <input type="hidden" name="codigo_agregar_pagos" value="<?php echo $datos_inscripciones_2['inscripciones_codigo']; ?>">
            <fieldset>
                <legend><i class="fas fa-cubes"></i> &nbsp; Detalles del pago</legend>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="pagos_fecha_inicio">Fecha del pago</label>
                                <input type="date" class="form-control" name="pagos_fecha_reg"
                                value="<?php echo date("Y-m-d");?>" id="pagos_fecha_inicio" readonly> 
                                <!-- con date("Y-m-d") se pone la fecha actual -->
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="pagos_hora_inicio">Hora del pago</label>
                                <input type="time" class="form-control" name="pagos_hora_inicio_reg"
                                value="<?php echo date("H:i");?>" id="pagos_hora_inicio" readonly>
                                <!--con date("H:i") para la hora acutal -->
                            </div>
                        </div>

                        <!-- campo para el monto total a pagar -->
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label for="pagos_total" class="bmd-label-floating">Monto a pagar en <?php echo MONEDA; ?></label>
                                <input type="hidden" pattern="[0-9.]{1,10}" class="form-control" name="pagos_monto_reg"
                                value="<?php echo $_SESSION['pagos_total']; ?>" maxlength="20" readonly>

                                <input type="text" pattern="[0-9.]{1,10}" class="form-control"
                                value="<?php echo number_format($_SESSION['pagos_total'], 0, '', '.'); ?>" maxlength="20" readonly>

                            </div> 
                        </div>

                    </div>



                </div>

            </fieldset>

            
            <p class="text-center">
                <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; LIMPIAR</button>
                &nbsp; &nbsp;
                <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; GUARDAR</button>
            </p>
        </form>
    </div>


    <?php
            //}
        }
        else{  //si el cursos no el pagos
            // si no existe ningun pagos con el ID de pagos (encriptado) en la URL
    ?>
    <div class="alert alert-danger text-center" role="alert">
        <p><i class="fas fa-exclamation-triangle fa-5x"></i></p>
        <h4 class="alert-heading">¡Ocurrió un error inesperado!</h4>
        <p class="mb-0">Lo sentimos, no podemos mostrar la información solicitada debido a un error.</p>
    </div>

    <?php } ?>
</div>



<!-- MODAL concepto -->
<div class="modal fade" id="Modalconceptos" tabindex="-1" role="dialog" aria-labelledby="Modalconceptos" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalconceptos">Buscar productos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_conceptos" class="bmd-label-floating">Código, Nombre</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_conceptos" id="input_conceptos" maxlength="30">
                    </div>
                </div>
                <br> -->

                <!-- <div class="container-fluid" id="tabla_conceptos"></div> -->

                <!-- <div class="container-fluid" id="tabla_conceptos">
                    <div class="table-responsive-1">
                        <table class="table table-hover table-sm">
                            <tbody>
                                <tr class="text-center">
                                    <td>Pago de inscripción</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="agregar_conceptos_incripciones(<?php //echo $campos['inscripciones_id']?>)"><i class="fa-solid fa-plus"></i> Agregar incripción</button>
                                    </td>
                                </tr> -->


                                <!-- <tr class="text-center">
                                    <td>Pago de materiales</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="agregar_conceptos(<?php //echo $campos['inscripciones_id']?>)"><i class="fa-solid fa-plus"></i></button>
                                    </td> <?php #echo 'Materiales' ?>
                                </tr>

                                <tr class="text-center">
                                    <td>Pago de mensualidad</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" onclick="agregar_conceptos(<?php //echo $campos['inscripciones_id']?>)"><i class="fa-solid fa-plus"></i></button>
                                    </td>
                                </tr> 
                            </tbody>
                        </table>

                    </div>
                </div> -->


                
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="input_productos" class="bmd-label-floating">Código, Nombre</label>
                        <input type="text" pattern="[a-zA-z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" class="form-control" name="input_productos" id="input_productos" maxlength="30">
                    </div>
                </div>
                <div class="container-fluid" id="tabla_productos"></div>
                &nbsp; &nbsp;
                
                
                
                
                
                
                
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" onclick="buscar_conceptos()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button> -->
                &nbsp; &nbsp;
                <button type="button" class="btn btn-primary btn-sm" onclick="buscar_productos()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



<?php 
    /* ---------- instancia a pagos ----------- */ 
    // $datos_cuotas = $ins_pagos -> datos_pagos_controlador("Cuotas", $campos['inscripciones_id']);
    // $datos_cuotas = $datos_cuotas -> fetchAll();
?>


<!-- MODAL CUOTAS -->
<div class="modal fade" id="Modalcuotas" tabindex="-1" role="dialog" aria-labelledby="Modalcuotas" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modalcuotas">Seleccina el mes a pagar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">



                <div class="container-fluid" id="tabla_conceptos">
                    <div class="table-responsive-1">
                        <table class="table table-hover table-sm">
                            
                        
                            <?php
                                echo $ins_pagos -> datos_cuotas_controlador($campos['inscripciones_id']);
                            ?>

                        </table>

                    </div>
                </div>


                
                
                
                
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary" onclick="buscar_conceptos()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button> -->
                &nbsp; &nbsp;
                <!-- <button type="button" class="btn btn-primary btn-sm" onclick="buscar_productos()"><i class="fas fa-search fa-fw"></i> &nbsp; Buscar</button> -->
                <button type="button" class="btn btn-primary btn-sm" onclick="">Aceptar</button>
                
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>




<?php
    //incluimos codigos de JS
    include_once "./vistas/inc/pagos.php";
?>