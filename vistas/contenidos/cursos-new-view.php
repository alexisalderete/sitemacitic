<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CURSOS
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque laudantium necessitatibus eius iure adipisci modi distinctio. Earum repellat iste et aut, ullam, animi similique sed soluta tempore cum quis corporis!
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo URL;?>cursos-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR CURSO</a>
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
    <form class="form-neon FormularioAjax" action="<?php echo URL?>ajax/cursosAjax.php" method="POST" data-form="guardar" autocomplete="off">
        <fieldset>
            <legend><i class="far fa-plus-square"></i> &nbsp; Información del cursos</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_codigo" class="bmd-label-floating">Códido</label>
                            <input type="text" pattern="[a-zA-Z0-9-]{1,45}" class="form-control" name="cursos_codigo_reg" id="cursos_codigo" maxlength="45">
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" pattern="[a-zA-záéíóúÁÉÍÓÚñÑ0-9 ]{1,140}" class="form-control" name="cursos_nombre_reg" id="cursos_nombre" maxlength="140">
                        </div>
                    </div>







                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_sedes">Sedes</label>
                            <select name="cursos_sedes_reg" id="cursos_sedes" class="form-control">
                                <option value="0">Seleccione una opción</option>
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
                            <input type="number" class="form-control" name="cursos_duracion_reg" id="cursos_duracion" maxlength="2" max="99" min="1">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_cupos" class="bmd-label-floating">Precio de inscripción</label>
                            <input type="number" class="form-control" name="cursos_precio_reg" id="cursos_cupos" max="100000000" min="1">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_cupos" class="bmd-label-floating">Precio de mensualidad</label>
                            <input type="number" class="form-control" name="cursos_mensualidad_reg" id="cursos_cupos" max="100000000" min="1">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_cupos" class="bmd-label-floating">Cupos</label>
                            <input type="number" class="form-control" name="cursos_cupos_reg" id="cursos_cupos" max="20000" min="1">
                        </div>
                    </div>
                    

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_detalle" class="bmd-label-floating">Detalles (opcional)</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,190}" class="form-control" name="cursos_detalle_reg" id="cursos_detalle" maxlength="190">
                        </div>
                    </div>

                    
                    <!-- <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="cursos_estado" class="bmd-label-floating">Estado</label>
                            <select class="form-control" name="cursos_estado_reg" id="cursos_estado">
                                <option value="" selected="">Seleccione una opción</option>
                                <option value="Habilitado">Habilitado</option>
                                <option value="Deshabilitado">Deshabilitado</option>
                            </select>
                        </div>
                    </div> -->

                </div>
            </div>
        </fieldset>
        <br><br><br>
        <p class="text-center">
            <button type="reset" class="btn btn-raised btn-secondary btn-sm"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
            &nbsp; &nbsp;
            <button type="submit" class="btn btn-raised btn-info btn-sm"><i class="far fa-save"></i> &nbsp; Guardar</button>
        </p>
    </form>
</div>