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
        <i class="fas fa-sync-alt fa-fw"></i> &nbsp; ACTUALIZAR CURSOS
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque laudantium necessitatibus eius iure adipisci modi distinctio. Earum repellat iste et aut, ullam, animi similique sed soluta tempore cum quis corporis!
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
            <a href="<?php echo URL;?>cursos-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR CURSOS</a>
        </li>
    </ul>
</div>

<!--CONTENT-->
<div class="container-fluid">
    <?php
        require_once "./controladores/cursosControlador.php";
        $ins_cursos = new cursosControlador();

        /*$pagina se definió en plantilla
            $pagina[1] posicion 1 porque en la url, la id que esta encriptado está en la posicion 1
            OBS: la posicion 0 es la vista (user-update) 
        */
        $datos_cursos = $ins_cursos -> datos_cursos_controlador("Unico", $pagina[1]);

        if ($datos_cursos->rowCount() == 1) {// si el cursos existe 
            $campos = $datos_cursos->fetch();//para obtener los datos de los cursos.
    ?>

    <form class="form-neon FormularioAjax" action="<?php echo URL;?>ajax/cursosAjax.php" 
    method="POST" data-form="actualizar" autocomplete="off">
    <input type="hidden" name="cursos_id_up" value="<?php echo $pagina[1]?>"> 
    <!-- $pagina[1] es el id encryptado -->
    
        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información del cursos</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_codigo" class="bmd-label-floating">Códido</label>
                            <input type="text" pattern="[a-zA-Z0-9-]{1,45}" class="form-control" name="cursos_codigo_up" id="cursos_codigo" maxlength="45"
                            value="<?php echo $campos['cursos_codigo']; ?>">
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" name="cursos_nombre_up" id="cursos_nombre" maxlength="140"
                            value="<?php echo $campos['cursos_nombre']; ?>">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_sedes">Sedes</label>
                            <select name="cursos_sedes_up" id="cursos_sedes" class="form-control">
                                <option value="<?php echo $campos['sedes_id']; ?>"><?php echo $campos['sedes_nombre']; ?></option>
                                <?php
                                    require_once "./controladores/cursosControlador.php";
                                    $ins_cursos = new cursosControlador();
                                    echo $ins_cursos->lista_sedes_cursos_controlador();
                                ?>
                                
                            </select>
                        </div>
                    </div>



                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_duracion" class="bmd-label-floating">Duración en meses</label>
                            <input type="number" class="form-control" name="cursos_duracion_up" id="cursos_duracion" max="99" min="1"
                            value="<?php echo $campos['cursos_duracion']; ?>">
                        </div>
                    </div>


                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_cupos" class="bmd-label-floating">Precio de inscripción</label>
                            <input type="number" class="form-control" name="cursos_precio_up" id="cursos_cupos" max="100000000" min="1"
                            value="<?php echo $campos['cursos_precio']; ?>">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_cupos" class="bmd-label-floating">Precio de mensualidad</label>
                            <input type="number" class="form-control" name="cursos_mensualidad_up" id="cursos_cupos" max="100000000" min="1"
                            value="<?php echo $campos['cursos_mensualidad']; ?>">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_cupos" class="bmd-label-floating">Cupos</label>
                            <input type="number" class="form-control" name="cursos_cupos_up" id="cursos_cupos" max="20000" min="1"
                            value="<?php echo $campos['cursos_cupos']; ?>">
                        </div>
                    </div>


                    <div class="col-12 col-md-4">
                        <div class="form-group">
                        <label for="cursos_duracion" class="bmd-label-floating">Estado</label>
                            <select class="form-control" name="cursos_estado_up">
                                <option value="Habilitado"<?php if ($campos['cursos_estado'] =="Habilitado") {
                                    echo 'selected=""';
                                } ?> >Habilitado <?php if($campos['cursos_estado'] == "Habilitado"){
                                    echo '(Actual)';} ?></option>
                                <option value="Deshabilitado" <?php if ($campos['cursos_estado'] =="Deshabilitado") {
                                    echo 'selected=""';
                                } ?>>Deshabilitado <?php if($campos['cursos_estado'] == "Deshabilitado"){
                                    echo '(Actual)';} ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_detalle" class="bmd-label-floating">Detalle</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="cursos_detalle_up" id="cursos_detalle" maxlength="190"
                            value="<?php echo $campos['cursos_detalle']; ?>">
                        </div>
                    </div>
                </div>

                
            </div>
        </fieldset>
        <br><br><br>
        <p class="text-center" >
            <button type="submit" class="btn btn-raised btn-success btn-sm"><i class="fas fa-sync-alt"></i> &nbsp; ACTUALIZAR</button>
        </p>
    </form>
    <?php
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