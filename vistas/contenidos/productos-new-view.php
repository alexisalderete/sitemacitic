<!-- Page header -->
<div class="full-box page-header">
    <h3 class="text-left">
        <i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PRODUCTO
    </h3>
    <!-- <p class="text-justify">
        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem odit amet asperiores quis minus, dolorem repellendus optio doloremque error a omnis soluta quae magnam dignissimos, ipsam, temporibus sequi, commodi accusantium!
    </p> -->
</div>

<div class="container-fluid">
    <ul class="full-box list-unstyled page-nav-tabs">
        <li>
            <a class="active" href="<?php echo URL;?>productos-new/"><i class="fas fa-plus fa-fw"></i> &nbsp; AGREGAR PRODUCTO</a>
        </li>
        <li>
            <a href="<?php echo URL;?>productos-list/"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; LISTA DE PRODUCTOS</a>
        </li>
        <li>
            <a href="<?php echo URL;?>productos-search/"><i class="fas fa-search fa-fw"></i> &nbsp; BUSCAR PRODUCTO</a>
        </li>
    </ul>	
</div>

<!-- Content here-->
<div class="container-fluid">
    <form class="form-neon FormularioAjax" action="<?php echo URL?>ajax/productosAjax.php" method="POST" data-form="guardar" autocomplete="off">
        <fieldset>
            <legend><i class="fas fa-user"></i> &nbsp; Información básica</legend>
            <div class="container-fluid">
                <div class="row">

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_dni" class="bmd-label-floating">Codigo</label>
                            <input type="text" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{1,20}" class="form-control" name="productos_codigo_reg" id="productos_codigo" maxlength="27">
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_nombre" class="bmd-label-floating">Nombre</label>
                            <input type="text" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{1,40}" class="form-control" name="productos_nombre_reg" id="productos_nombre" maxlength="40">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_precio" class="bmd-label-floating">Precio</label>
                            <input type="number" class="form-control" name="productos_precio_reg" id="productos_precio" max="100000000" min="1">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_cantidad" class="bmd-label-floating">Stock</label>
                            <input type="text" class="form-control" name="productos_cantidad_reg" id="productos_cantidad" max="100000000" min="1">
                        </div>
                    </div>




                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="productos_iva" class="bmd-label-floating">Iva</label>
                            <select class="form-control" name="productos_iva_reg">
                                <option value="">Seleccione una opción</option>
                                <option value="0">0%</option>
                                <option value="5">5%</option>
                                <option value="10">10%</option>
                            </select>
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