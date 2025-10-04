<div class="card shadow-sm border-0 rounded-3">
	<div class="card-header d-flex align-items-center bg-white" style="border-top: 3px solid #007bff;">
		<!-- Título con icono -->
		<h5 class="mb-0">
			<i class="fas fa-boxes"></i> Lista de Productos
		</h5>

		<!-- Botón azul centrado verticalmente a la derecha -->
		<button id="create_new" class="btn btn-primary btn-sm ml-auto">
			<i class="fas fa-plus"></i> Nuevo producto
		</button>
	</div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="productTable" class="table table-striped table-hover align-middle">
        <thead class="">
          <tr>
            <th>#</th>
            <th>SKU</th>
            <th>Empresa</th>
            <th>Proveedor</th>
            <th>Descripción</th>
            <th>Stock</th>
            <th>Fecha Compra</th>
            <th>$ Venta</th>
            <th>$ Compra</th>
            <th>Extras</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          $qry = $conn->query("SELECT i.*,s.name as supplier, c.name as company 
                               FROM `item_list` i 
                               INNER JOIN supplier_list s ON i.supplier_id = s.id 
                               INNER JOIN company_list c ON i.company_id = c.id 
                               ORDER BY i.name ASC, s.name ASC, c.name ASC");
          while ($row = $qry->fetch_assoc()) :
          ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= $row['name'] ?></td>
              <td><?= $row['company'] ?></td>
              <td><?= $row['supplier'] ?></td>
              <td><?= $row['description'] ?></td>
              <td><?= $row['stock'] ?></td>
              <td><?= date("Y-m-d", strtotime($row['date_purchase'])) ?></td>
              <td>$<?= number_format($row['cost'], 2) ?></td>
              <td>$<?= number_format($row['product_cost'], 2) ?></td>
              <td><?= $row['shipping_or_extras'] ?></td>
              <td class="text-center">
                <?php if ($row['status'] == 1): ?>
                  <span class="badge bg-success">Activo</span>
                <?php else: ?>
                  <span class="badge bg-danger">Inactivo</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="btn-group">
                  <button class="btn btn-sm btn-outline-secondary view_data" data-id="<?= $row['id'] ?>"><i class="fa fa-eye"></i></button>
                  <button class="btn btn-sm btn-outline-primary edit_data" data-id="<?= $row['id'] ?>"><i class="fa fa-edit"></i></button>
                  <button class="btn btn-sm btn-outline-danger delete_data" data-id="<?= $row['id'] ?>"><i class="fa fa-trash"></i></button>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
  // DataTable
  $('#productTable').DataTable({
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
    }
  });

  // Nuevo producto
  $('#create_new').click(function () {
    uni_modal("<i class='fa fa-plus'></i> Agregar nuevo Producto", "maintenance/manage_item.php", "mid-large");
  });

  // Ver producto
  $('.view_data').click(function () {
    uni_modal("<i class='fa fa-box'></i> Información de Producto", "maintenance/view_item.php?id=" + $(this).data('id'), "large");
  });

  // Editar producto
  $('.edit_data').click(function () {
    uni_modal("<i class='fa fa-edit'></i> Editar información de Producto", "maintenance/manage_item.php?id=" + $(this).data('id'), "mid-large");
  });

  // Eliminar producto
  $('.delete_data').click(function () {
    let id = $(this).data('id');
    _conf("¿Deseas eliminar este producto permanentemente?", "delete_product", [id]);
  });
});

// Función eliminar
function delete_product(id) {
  start_loader();
  $.ajax({
    url: _base_url_ + "classes/Master.php?f=delete_item",
    method: "POST",
    data: { id },
    dataType: "json",
    error: err => {
      console.error(err);
      alert_toast("Ocurrió un error", 'error');
      end_loader();
    },
    success: function (resp) {
      if (resp.status === 'success') {
        location.reload();
      } else {
        alert_toast("Ocurrió un error", 'error');
        end_loader();
      }
    }
  });
}
</script>
