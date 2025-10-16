<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title"><i class="fa fa-boxes me-2"></i> Lista de Productos</h3>
    <div class="card-tools">
      <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary">
        <span class="fas fa-plus"></span> Nuevo Producto
      </a>
    </div>
  </div>

  <div class="card-body">
    <div class="container-fluid">
      <div class="container-fluid">
        <table class="table table-bordered table-hover align-middle" id="itemTable">
          <colgroup>
            <col width="10%">
            <col width="15%">
            <col width="25%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="15%">
            <col width="10%">
            <col width="10%">
          </colgroup>
          <thead class="bg-light">
            <tr class="text-center text-secondary">
              <th>OC</th>
              <th>SKU</th>
              <th>Descripción</th>
              <th>Stock</th>
              <th>Fecha Compra</th>
              <th>Precio Venta</th>
              <th>Precio Compra</th>
              <th>Proveedor</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $qry = $conn->query("
              SELECT i.*, s.name AS supplier
              FROM `item_list` i
              INNER JOIN supplier_list s ON i.supplier_id = s.id
              ORDER BY i.name ASC, s.name ASC
            ");
            while ($row = $qry->fetch_assoc()):
            ?>
            <tr>
              <td class="fw-bold text-dark text-start"><?= htmlspecialchars($row['oc']) ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td class="text-center fw-bold text-primary"><?= (int)$row['stock'] ?></td>
              <td><?= date("Y-m-d", strtotime($row['date_purchase'])) ?></td>
              <td>$<?= number_format($row['cost'], 2) ?></td>
              <td>$<?= number_format($row['product_cost'], 2) ?></td>
              <td><?= htmlspecialchars($row['supplier']) ?></td>
              <td class="text-center">
                <?php if ($row['status'] == 1): ?>
                  <span class="badge bg-success px-3 py-2">Activo</span>
                <?php else: ?>
                  <span class="badge bg-danger px-3 py-2">Inactivo</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <div class="btn-group btn-group-sm">
                  <button class="btn btn-outline-secondary view_data" data-id="<?= $row['id'] ?>" title="Ver">
                    <i class="fa fa-eye"></i>
                  </button>
                  <button class="btn btn-outline-primary edit_data" data-id="<?= $row['id'] ?>" title="Editar">
                    <i class="fa fa-edit"></i>
                  </button>
                  <button class="btn btn-outline-danger delete_data" data-id="<?= $row['id'] ?>" title="Eliminar">
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- 🎨 Estilos iguales al módulo de Empresas -->
<style>
  .table thead th {
    font-weight: 600;
    background-color: #f8f9fa;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
  }
  .table tbody tr:hover {
    background-color: #f1f5ff !important;
  }
  .table td, .table th {
    vertical-align: middle !important;
    padding: 10px 12px !important;
  }
  .btn-group .btn {
    border-radius: 6px !important;
  }
  .text-dark {
    color: #000 !important;
  }
  .card-header {
    background-color: #fff !important;
    border-bottom: 2px solid #dee2e6 !important;
  }
  .card-title {
    font-weight: 600;
    color: #004080;
    margin-bottom: 0;
    font-size: 1.1rem;
  }
  .btn-flat {
    border-radius: 6px;
    padding: 6px 14px;
    font-weight: 500;
  }
</style>

<!-- ⚙️ Script -->
<script>
$(document).ready(function() {
  // Activar DataTable
  $('#itemTable').DataTable({
    language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json' },
    pageLength: 10,
    responsive: true
  });

  // Botones
  $('.delete_data').click(function() {
    _conf("¿Deseas eliminar este producto permanentemente?", "delete_item", [$(this).attr('data-id')]);
  });
  $('#create_new').click(function() {
    uni_modal("<i class='fa fa-plus'></i> Agregar Producto", "maintenance/manage_item.php", "mid-large");
  });
  $('.edit_data').click(function() {
    uni_modal("<i class='fa fa-edit'></i> Editar Producto", "maintenance/manage_item.php?id=" + $(this).attr('data-id'), "mid-large");
  });
  $('.view_data').click(function() {
    uni_modal("<i class='fa fa-box'></i> Información del Producto", "maintenance/view_item.php?id=" + $(this).attr('data-id'), "extra-large");
  });
});

// 🗑️ Eliminar producto
function delete_item(id) {
  start_loader();
  $.ajax({
    url: _base_url_ + "classes/Master.php?f=delete_item",
    method: "POST",
    data: { id: id },
    dataType: "json",
    error: err => {
      console.log(err);
      alert_toast("Ocurrió un error", 'error');
      end_loader();
    },
    success: function(resp) {
      if (typeof resp == 'object' && resp.status == 'success') {
        location.reload();
      } else {
        alert_toast("Ocurrió un error", 'error');
        end_loader();
      }
    }
  });
}
</script>
