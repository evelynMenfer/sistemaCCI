<?php
// =============================
// VALIDAR EMPRESA
// =============================
$company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : 0;

if ($company_id <= 0) {
    echo "<div class='alert alert-warning m-3'>⚠️ No se ha seleccionado una empresa. Por favor elige una desde el menú lateral.</div>";
    exit;
}
?>

<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Cotizaciones Aceptadas</h3>
        <a href="<?php echo base_url ?>admin/?page=purchase_order&company_id=<?php echo $company_id; ?>" class="btn btn-flat btn-secondary">
            <i class="fa fa-arrow-left"></i> Regresar
        </a>
    </div>

    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-bordered table-striped">
                <colgroup>
                    <col width="5%">
                    <col width="25%">
                    <col width="25%">
                    <col width="25%">
                    <col width="20%">
                </colgroup>
                <thead class="bg-navy text-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha Creación</th>
                        <th>Cotización</th>
                        <th>Productos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    // 🔹 Filtrar por empresa
                    $qry = $conn->query("SELECT * FROM `receiving_list` WHERE company_id = '{$company_id}' ORDER BY `date_created` DESC");

                    if ($qry && $qry->num_rows > 0):
                        while ($row = $qry->fetch_assoc()):
                            $row['items'] = explode(',', $row['stock_ids']);
                            if ($row['from_order'] == 1) {
                                $code = $conn->query("SELECT po_code FROM `purchase_order_list` WHERE id='{$row['form_id']}'")->fetch_assoc()['po_code'];
                            } else {
                                $code = $conn->query("SELECT bo_code FROM `back_order_list` WHERE id='{$row['form_id']}'")->fetch_assoc()['bo_code'];
                            }
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td><?php echo $code ?></td>
                            <td class="text-center"><?php echo number_format(count($row['items'])) ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        Acción
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=receiving/view_receiving&id=' . $row['id'] . '&company_id=' . $company_id; ?>">
                                            <span class="fa fa-eye text-dark"></span> Ver
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?php echo base_url . 'admin?page=receiving/manage_receiving&id=' . $row['id'] . '&company_id=' . $company_id; ?>">
                                            <span class="fa fa-edit text-primary"></span> Editar
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id']; ?>">
                                            <span class="fa fa-trash text-danger"></span> Eliminar
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay cotizaciones aceptadas registradas para esta empresa.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.delete_data').click(function() {
        _conf("¿Deseas eliminar esta cotización permanentemente?", "delete_receiving", [$(this).attr('data-id')])
    });

    $('.table td, .table th').addClass('py-1 px-2 align-middle');
    $('.table').DataTable({
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ cotizaciones",
            infoEmpty: "Sin cotizaciones registradas",
            zeroRecords: "No se encontraron resultados",
            paginate: {
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });
});

// === FUNCIÓN ELIMINAR ===
function delete_receiving(id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_receiving",
        method: "POST",
        data: { id: id },
        dataType: "json",
        error: err => {
            console.error(err);
            alert_toast("Ocurrió un error", 'error');
            end_loader();
        },
        success: function(resp) {
            if (resp.status == 'success') {
                location.replace(_base_url_ + "admin/?page=receiving&company_id=<?php echo $company_id ?>");
            } else {
                alert_toast("No se pudo eliminar la cotización", 'error');
                end_loader();
            }
        }
    });
}
</script>
