<h1 class=""><?php echo $_settings->info('name') ?></h1>
<hr>
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-dark shadow">
            <span class="info-box-icon bg-primary elevation-1"><i class="fa fa-file"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Cotizaciones</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `purchase_order_list`")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-dark shadow">
            <span class="info-box-icon bg-teal elevation-1"><i class="fas fa-th-list"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Finalizadas</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `purchase_order_list` WHERE `status` = 3")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>


    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-dark shadow">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-undo"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">En proceso</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `purchase_order_list` WHERE `status` = 2")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-dark shadow">
            <span class="info-box-icon bg-lightblue elevation-1"><i class="fa fa-check-square"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Autorizado</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `purchase_order_list` WHERE `status` = 1")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-dark shadow">
            <span class="info-box-icon bg-light elevation-1 text-dark">
                <i class="fa fa-clock"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Sin autorizar</span>
                <span class="info-box-number text-right">
                    <?php echo $conn->query("SELECT * FROM `purchase_order_list` WHERE `status` = 0")->num_rows; ?>
                </span>
            </div>
        </div>
    </div>


    <!--<div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-dark shadow">
            <span class="info-box-icon bg-navy elevation-1"><i class="fas fa-file-invoice-dollar"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Ventas</span>
                <span class="info-box-number text-right">
                    <php
                    echo $conn->query("SELECT * FROM `sales_list`")->num_rows;
                    ?>
                </span>
            </div>
        </div>
    </div>-->
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-dark shadow">
            <span class="info-box-icon bg-orange elevation-1"><i class="fa fa-building"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Empresas</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `company_list` where `status` = 1")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-dark shadow">
            <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-boxes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Productos</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `item_list` where `status` = 1")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-dark shadow">
            <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-truck-loading"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Proveedores</span>
                <span class="info-box-number text-right">
                    <?php
                    echo $conn->query("SELECT * FROM `supplier_list` where `status` = 1")->num_rows;
                    ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    
    <?php if ($_settings->userdata('type') == 1) : ?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-dark shadow">
                <span class="info-box-icon bg-olive elevation-1"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Clientes</span>
                    <span class="info-box-number text-right">
                        <?php
                        echo $conn->query("SELECT * FROM `customer_list` where `status` = 1")->num_rows;
                        ?>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    <?php endif; ?>

    <?php if ($_settings->userdata('type') == 1) : ?>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box bg-dark shadow">
                <span class="info-box-icon bg-maroon elevation-1"><i class="fas fa-user"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Usuarios</span>
                    <span class="info-box-number text-right">
                        <?php
                        echo $conn->query("SELECT * FROM `users` where `id` != 1")->num_rows;
                        ?>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    <?php endif; ?>
</div>