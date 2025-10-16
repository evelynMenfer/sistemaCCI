</style>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-light-blue elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="<?php echo base_url ?>admin" class="brand-link bg-blue text-sm">
        <img src="<?php echo validate_image($_settings->info('logo')) ?>" alt="Store Logo"
            class="brand-image img-circle elevation-3 bg-black"
            style="width: 1.8rem; height: 1.8rem; max-height: unset">
        <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar bg-navy os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">

        <div class="os-resize-observer-host observed"><div class="os-resize-observer"></div></div>
        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
        </div>

        <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                <div class="os-content" style="padding: 0 8px; height: 100%; width: 100%;">
                    
                    <!-- Sidebar Menu -->
                    <nav class="mt-4">
                        <ul class="nav nav-pills nav-sidebar flex-column text-md nav-compact nav-flat nav-child-indent nav-collapse-hide-child"
                            data-widget="treeview" role="menu" data-accordion="false">

                            <!-- DASHBOARD -->
                            <li class="nav-item dropdown">
                                <a href="./" class="nav-link nav-home">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>

                            <!-- ==============================
                                EMPRESAS DINÁMICAS (usando identificador)
                                ============================== -->
                                <li class="nav-header text-uppercase text-light">Empresas</li>
                            <?php
                            $companies = $conn->query("SELECT id, name, identificador FROM company_list WHERE status = 1 ORDER BY name ASC");
                            while ($row = $companies->fetch_assoc()):
                                $is_active = (isset($_GET['company_id']) && $_GET['company_id'] == $row['id']);
                            ?>
                            <li class="nav-item">
                                <a href="<?php echo base_url ?>admin/?page=purchase_order/index&company_id=<?php echo $row['id']; ?>"
                                class="nav-link <?php echo $is_active ? 'active bg-blue' : ''; ?>"
                                title="<?php echo htmlspecialchars($row['name']); ?>">
                                    <i class="nav-icon fas fa-th-list"></i>
                                    <p class="text-uppercase"><?php echo htmlspecialchars($row['identificador']); ?></p>
                                </a>
                            </li>
                            <?php endwhile; ?>


                            <!-- ==============================
                                 GESTIÓN GENERAL
                                 ============================== -->
                            <li class="nav-header text-uppercase text-light">Gestión</li>
                            <li class="nav-item">
                                <a href="<?php echo base_url ?>admin/?page=receiving" class="nav-link nav-receiving">
                                    <i class="nav-icon fas fa-boxes"></i>
                                    <p>Aceptadas</p>
                                </a>
                            </li>

                            <!-- ==============================
                                 SECCIÓN MANTENIMIENTO
                                 ============================== -->
                            <?php if ($_settings->userdata('type') == 1) : ?>
                            <li class="nav-header text-uppercase text-light">Mantenimiento</li>

                            <li class="nav-item dropdown">
                                <a href="<?php echo base_url ?>admin/?page=maintenance/item"
                                    class="nav-link nav-maintenance_item">
                                    <i class="nav-icon fas fa-boxes"></i>
                                    <p>Productos</p>
                                </a>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a href="<?php echo base_url ?>admin/?page=maintenance/supplier"
                                    class="nav-link nav-maintenance_supplier">
                                    <i class="nav-icon fas fa-truck-loading"></i>
                                    <p>Proveedores</p>
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a href="<?php echo base_url ?>admin/?page=maintenance/company"
                                    class="nav-link nav-maintenance_company">
                                    <i class="nav-icon fa fa-building"></i>
                                    <p>Empresas</p>
                                </a>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a href="<?php echo base_url ?>admin/?page=user/list" class="nav-link nav-user_list">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Usuarios</p>
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a href="<?php echo base_url ?>admin/?page=system_info"
                                    class="nav-link nav-system_info">
                                    <i class="nav-icon fas fa-cogs"></i>
                                    <p>Configuración</p>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
            </div>
        </div>

        <!-- Scrollbars -->
        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div>
        </div>
        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div></div>
        </div>
        <div class="os-scrollbar-corner"></div>
    </div>
    <!-- /.sidebar -->
</aside>

<script>
var page;
$(document).ready(function() {
    page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
    page = page.replace(/\//gi, '_');

    // Activar la opción correspondiente del menú
    if ($('.nav-link.nav-' + page).length > 0) {
        $('.nav-link.nav-' + page).addClass('active');
        if ($('.nav-link.nav-' + page).hasClass('tree-item') == true) {
            $('.nav-link.nav-' + page).closest('.nav-treeview').siblings('a').addClass('active');
            $('.nav-link.nav-' + page).closest('.nav-treeview').parent().addClass('menu-open');
        }
        if ($('.nav-link.nav-' + page).hasClass('nav-is-tree') == true) {
            $('.nav-link.nav-' + page).parent().addClass('menu-open');
        }
    }

    // Acción del botón de recepción (si aplica)
    $('#receive-nav').click(function() {
        $('#uni_modal').on('shown.bs.modal', function() {
            $('#find-transaction [name="tracking_code"]').focus();
        });
        uni_modal("Enter Tracking Number", "transaction/find_transaction.php");
    });
});
</script>
