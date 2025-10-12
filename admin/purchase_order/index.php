<?php
// =============================
// VALIDAR ID DE EMPRESA
// =============================
$company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : 0;

if ($company_id <= 0) {
    echo "<div class='alert alert-warning m-3'>⚠️ No se ha seleccionado una empresa. Por favor elige una desde el menú lateral.</div>";
    exit;
}

// =============================
// CONSULTAR DATOS DE LA EMPRESA
// =============================
$empresa = $conn->query("SELECT * FROM company_list WHERE id = $company_id")->fetch_assoc();
if (!$empresa) {
    echo "<div class='alert alert-danger m-3'>❌ Empresa no encontrada.</div>";
    exit;
}
?>
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            Cotizaciones de <?php echo htmlspecialchars($empresa['name']); ?>
        </h3>
        <a href="<?php echo base_url ?>admin/?page=purchase_order/manage_po&company_id=<?php echo $company_id; ?>"
           class="btn btn-flat btn-primary">
            <span class="fas fa-plus"></span> Nueva Cotización
        </a>
    </div>

    <div class="card-body">
        <div class="container-fluid">
            <table class="table table-bordered table-striped">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="10%">
                    <col width="20%">
                    <col width="25%">
                    <col width="5%">
                    <col width="10%">
                    <col width="10%">
                </colgroup>
                <thead class="bg-navy text-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Código</th>
                        <th>Empresa</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("
                        SELECT p.*, c.name AS company 
                        FROM purchase_order_list p 
                        INNER JOIN company_list c ON c.id = p.id_company 
                        WHERE c.id = {$company_id}
                        ORDER BY p.date_created DESC
                    ");

                    if ($qry && $qry->num_rows > 0):
                        while ($row = $qry->fetch_assoc()):
                            $row['items'] = $conn->query("SELECT COUNT(item_id) AS items FROM po_items WHERE po_id = '{$row['id']}'")->fetch_assoc()['items'];
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                        <td><?php echo $row['po_code'] ?></td>
                        <td><?php echo htmlspecialchars($row['company']) ?></td>
                        <td><?php echo htmlspecialchars($row['cliente_cotizacion'] ?? '—') ?></td>
                        <td class="text-center"><?php echo number_format($row['items']) ?></td>
                        <td class="text-center">
                            <?php if ($row['status'] == 0): ?>
                                <span class="badge badge-primary">Pendiente</span>
                            <?php elseif ($row['status'] == 1): ?>
                                <span class="badge badge-warning">Parcial</span>
                            <?php elseif ($row['status'] == 2): ?>
                                <span class="badge badge-success">Recibido</span>
                            <?php else: ?>
                                <span class="badge badge-danger">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <?php if ($row['status'] == 0): ?>
                                    <a href="<?php echo base_url . 'admin?page=receiving/manage_receiving&po_id=' . $row['id'] ?>"
                                       class="btn btn-default btn-sm"
                                       title="Recibir">
                                       <i class="fa fa-boxes text-dark"></i>
                                    </a>
                                <?php endif; ?>

                                <a href="<?php echo base_url . 'admin?page=purchase_order/view_po&id=' . $row['id'] ?>"
                                   class="btn btn-default btn-sm"
                                   title="Ver Detalle">
                                   <i class="fa fa-eye text-primary"></i>
                                </a>

                                <a href="<?php echo base_url . 'admin?page=purchase_order/manage_po&id=' . $row['id'] . '&company_id=' . $company_id ?>"
                                   class="btn btn-default btn-sm"
                                   title="Editar">
                                   <i class="fa fa-edit text-warning"></i>
                                </a>

                                <button type="button"
                                        class="btn btn-default btn-sm delete_data"
                                        data-id="<?php echo $row['id'] ?>"
                                        title="Eliminar">
                                        <i class="fa fa-trash text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted">No hay cotizaciones registradas para esta empresa.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
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

    $('.delete_data').click(function() {
        _conf("¿Deseas eliminar esta cotización de forma permanente?", "delete_po", [$(this).attr('data-id')]);
    });
});

function delete_po(id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_po",
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
                location.reload();
            } else {
                alert_toast("Ocurrió un error al eliminar", 'error');
                end_loader();
            }
        }
    });
}
</script>
