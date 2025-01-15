<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SEDES
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem odit amet asperiores quis minus, dolorem repellendus optio doloremque error a omnis soluta quae magnam dignissimos, ipsam, temporibus sequi, commodi accusantium!
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo URL;?>sedes-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR SEDES</a>
        </li>
        <li>
            <a href="<?php echo URL;?>sedes-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE SEDES</a>
        </li>
        <li>
            <a href="<?php echo URL;?>sedes-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR SEDES</a>
        </li>
    </ul>	
</div>

<!-- Content here-->
<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo URL?>ajax/sedesAjax.php" method="POST" data-form="guardar" autocomplete="off">
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="sedes_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="sedes_nombre_reg" id="sedes_nombre" maxlength="40">
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="sedes_descripcion" class="bmd-label-floating">Descripción</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,150}" class="form-control" name="sedes_descripcion_reg" id="sedes_descripcion" maxlength="150">
                        </div>
                    </div>

                    
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