<div class="card card-outline card-primary">
  <div class="card-header">
    <h3 class="card-title">Lista de Clientes</h3>
    <div class="card-tools">
      <a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary">
        <span class="fas fa-plus"></span> Nuevo cliente
      </a>
    </div>
  </div>

  <div class="card-body">
    <div class="container-fluid">
      <table class="table table-bordered table-hover align-middle" id="customerTable">
        <colgroup>
          <col width="25%">
          <col width="25%">
          <col width="20%">
          <col width="20%">
          <col width="10%">
        </colgroup>
        <thead class="bg-light">
          <tr class="text-center text-secondary">
            <th>Nombre</th>
            <th>Email</th>
            <th>Contacto</th>
            <th>Dirección</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $qry = $conn->query("SELECT * FROM `customer_list` ORDER BY `name` ASC");
          while ($row = $qry->fetch_assoc()):
          ?>
          <tr>
            <td class="fw-semibold text-dark text-start"><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
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

<!-- 🎨 Estilos -->
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
  .card {
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  }
</style>

<!-- ⚙️ Script -->
<script>
$(document).ready(function() {
  // Activar DataTable
  $('#customerTable').DataTable({
    language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json' },
    pageLength: 10,
    responsive: true,
    columnDefs: [
      { orderable: false, targets: 4 }
    ]
  });

  // Botones
  $('.delete_data').click(function() {
    _conf("¿Deseas eliminar este cliente permanentemente?", "delete_customer", [$(this).attr('data-id')]);
  });
  $('#create_new').click(function() {
    uni_modal("<i class='fa fa-plus'></i> Agregar Cliente", "maintenance/manage_customer.php", "mid-large");
  });
  $('.edit_data').click(function() {
    uni_modal("<i class='fa fa-edit'></i> Editar Cliente", "maintenance/manage_customer.php?id=" + $(this).attr('data-id'), "mid-large");
  });
  $('.view_data').click(function() {
    uni_modal("<i class='fa fa-user'></i> Información del Cliente", "maintenance/view_customer.php?id=" + $(this).attr('data-id'), "");
  });
});

// 🗑️ Eliminar cliente
function delete_customer(id) {
  start_loader();
  $.ajax({
    url: _base_url_ + "classes/Master.php?f=delete_customer",
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
