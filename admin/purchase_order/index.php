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
        <!-- Filtro por estado -->
        <div class="container-fluid mb-3">
            <div class="row justify-content-end">
                <div class="col-md-3">
                    <select id="filterEstado" class="form-control form-control-sm">
                        <option value="">🔍 Filtrar por estado...</option>
                        <option value="0">Por autorizar</option>
                        <option value="1">Autorizado</option>
                        <option value="2">En proceso</option>
                        <option value="3">Finalizado</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <table class="table table-bordered table-striped" id="cotTable">
                <colgroup>
                    <col width="5%">
                    <col width="12%">
                    <col width="12%">
                    <col width="12%">
                    <col width="25%">
                    <col width="5%">
                    <col width="10%">
                    <col width="11%">
                </colgroup>
                <thead class="bg-navy text-light">
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Fecha de Entrega</th>
                        <th>Código</th>
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
                        SELECT 
                            p.*, 
                            c.name AS company,
                            cl.name AS cliente_nombre,
                            cl.rfc AS cliente_rfc
                        FROM purchase_order_list p
                        INNER JOIN company_list c ON c.id = p.id_company
                        LEFT JOIN customer_list cl ON cl.id = p.customer_id
                        WHERE c.id = {$company_id}
                        ORDER BY p.date_created DESC
                    ");

                    if ($qry && $qry->num_rows > 0):
                        while ($row = $qry->fetch_assoc()):
                            $row['items'] = $conn->query("SELECT COUNT(item_id) AS items FROM po_items WHERE po_id = '{$row['id']}'")->fetch_assoc()['items'];
                            $estado = (int)($row['status'] ?? 0);
                            $estadoTexto = ['Por autorizar','Autorizado','En proceso','Finalizado'][$estado] ?? 'Por autorizar';
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                        <td><?php echo !empty($row['fecha_entrega']) ? date("Y-m-d", strtotime($row['fecha_entrega'])) : '—'; ?></td>
                        <td><?php echo htmlspecialchars($row['po_code']) ?></td>
                        <td>
                            <?php 
                                echo htmlspecialchars($row['cliente_nombre'] ?? $row['cliente_cotizacion'] ?? '—'); 
                                if (!empty($row['cliente_rfc'])) {
                                    echo "<br><small class='text-muted'>RFC: " . htmlspecialchars($row['cliente_rfc']) . "</small>";
                                }
                            ?>
                            </td>
                        <td class="text-center"><?php echo number_format($row['items']) ?></td>
                        <td class="text-center">
                            <span class="estado-text d-none"><?php echo $estadoTexto; ?></span>
                            <select class="form-control form-control-sm estado-select" data-id="<?php echo $row['id']; ?>">
                                <option value="0" <?php echo $estado===0?'selected':''; ?>>Por autorizar</option>
                                <option value="1" <?php echo $estado===1?'selected':''; ?>>Autorizado</option>
                                <option value="2" <?php echo $estado===2?'selected':''; ?>>En proceso</option>
                                <option value="3" <?php echo $estado===3?'selected':''; ?>>Finalizado</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="<?php echo base_url.'admin?page=purchase_order/view_po&id='.$row['id'] ?>"
                                   class="btn btn-default btn-sm" title="Ver Detalle"><i class="fa fa-eye text-primary"></i></a>
                                <a href="<?php echo base_url.'admin?page=purchase_order/manage_po&id='.$row['id'].'&company_id='.$company_id ?>"
                                   class="btn btn-default btn-sm" title="Editar"><i class="fa fa-edit text-warning"></i></a>
                                <button type="button" class="btn btn-default btn-sm delete_data"
                                        data-id="<?php echo $row['id'] ?>" title="Eliminar">
                                    <i class="fa fa-trash text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="8" class="text-center text-muted">No hay cotizaciones registradas para esta empresa.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(function() {
    const $tabla = $('#cotTable');
    const COL_ESTADO = 6; // Índice de la columna Estado

    // === Inicializar DataTable ===
    const table = $tabla.DataTable({
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ cotizaciones",
            infoEmpty: "Sin cotizaciones registradas",
            zeroRecords: "No se encontraron resultados",
            paginate: { next: "Siguiente", previous: "Anterior" }
        },
        columnDefs: [{
            targets: COL_ESTADO,
            render: function(data, type) {
                // El filtro usa el texto oculto del estado
                if (type === 'filter' || type === 'sort') {
                    const div = document.createElement('div');
                    div.innerHTML = data;
                    const span = div.querySelector('.estado-text');
                    return span ? span.textContent.trim() : '';
                }
                return data;
            }
        }],
        drawCallback: function() {
            // Asegurar colores y textos correctos tras cualquier redibujo
            $tabla.find('.estado-select').each(function() {
                colorEstado($(this));
                syncEstadoText($(this));
            });
        }
    });

    // === FILTRO SUPERIOR POR ESTADO ===
    $('#filterEstado').on('change', function() {
        const val = $(this).val();

        if (val === "") {
            // Quitar filtro
            table.column(COL_ESTADO).search('', true, false).draw();
        } else {
            // Obtener texto del estado seleccionado
            const texto = $("#filterEstado option:selected").text().trim();

            // Filtrado exacto (usa regex escapado)
            const regex = '^' + texto.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '$';
            table.column(COL_ESTADO).search(regex, true, false).draw();
        }
    });

    // === CAMBIO DE ESTADO (AJAX + COLOR INSTANTÁNEO) ===
    $tabla.on('change', '.estado-select', function() {
        const $select = $(this);
        const id = $select.data('id');
        const nuevo = $select.val();

        // Actualiza visualmente sin recargar
        colorEstado($select);
        syncEstadoText($select);

        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=update_po_status",
            method: "POST",
            data: { id: id, status: nuevo },
            dataType: "json",
            success: function(resp) {
                end_loader();
                if (resp.status === 'success') {
                    alert_toast("Estado actualizado correctamente", 'success');
                } else {
                    alert_toast("Error al actualizar", 'error');
                }
            },
            error: function() {
                end_loader();
                alert_toast("Error de conexión con el servidor", 'error');
            }
        });
    });

    // === COLORES Y TEXTOS INICIALES ===
    $tabla.find('.estado-select').each(function() {
        colorEstado($(this));
        syncEstadoText($(this));
    });

    // === ELIMINAR COTIZACIÓN ===
    $('.delete_data').on('click', function() {
        _conf("¿Deseas eliminar esta cotización de forma permanente?", "delete_po", [$(this).attr('data-id')]);
    });

    // === FUNCIONES AUXILIARES ===
    function syncEstadoText($select) {
        const $celda = $select.closest('td');
        let $span = $celda.find('.estado-text');
        if ($span.length === 0) {
            $span = $('<span class="estado-text d-none"></span>');
            $celda.prepend($span);
        }
        const texto = {
            0: 'Por autorizar',
            1: 'Autorizado',
            2: 'En proceso',
            3: 'Finalizado'
        }[$select.val()] || 'Por autorizar';
        $span.text(texto);
    }

    function colorEstado($select) {
        $select.removeClass('bg-secondary bg-primary bg-warning bg-success text-white text-dark');
        const val = String($select.val());
        if (val === '0') $select.addClass('bg-secondary text-white'); // Por autorizar
        if (val === '1') $select.addClass('bg-primary text-white');   // Autorizado
        if (val === '2') $select.addClass('bg-warning text-dark');    // En proceso
        if (val === '3') $select.addClass('bg-success text-white');   // Finalizado
    }
});

// === GLOBAL delete_po ===
function delete_po(id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_po",
        method: "POST",
        data: { id: id },
        dataType: "json",
        success: function(resp) {
            end_loader();
            if (resp.status == 'success') location.reload();
            else alert_toast("Error al eliminar", 'error');
        },
        error: function() {
            end_loader();
            alert_toast("Error al eliminar", 'error');
        }
    });
}
</script>
