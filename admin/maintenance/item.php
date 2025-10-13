<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Lista de Productos</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Nuevo</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
			<div class="container-fluid">
				<table class="table table-hovered table-striped">
					<colgroup>
						<col width="10%">
						<col width="25%">
						<col width="25%">
						<col width="10%">
						<col width="15%">
						<col width="10%">
						<col width="10%">
						<col width="10%">
						<col width="15%">
						<col width="15%">
						<col width="20%">
						<col width="20%">
					</colgroup>
					<thead>
						<tr>
							<th>OC</th>
							<th>SKU</th>
							<th>Descripción</th>
							<th>Stock</th>
							<th>Fecha de Compra</th>
							<th>Precio Venta</th>
							<th>Precio Compra</th>
							<th>Extras</th>
							<th>Proveedor</th>
							<th>Empresa</th>
							<th>Estado</th>
							<th>Acción</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$qry = $conn->query("SELECT i.*,s.name as supplier, c.name as company from `item_list` i inner join supplier_list s on i.supplier_id = s.id inner join company_list c on i.company_id = c.id order by i.name asc,s.name asc, c.name asc");
						while ($row = $qry->fetch_assoc()) :
						?>
							<tr>
								<td><?php echo $row['oc'] ?></td>
								<td><?php echo $row['name'] ?></td>
								<td><?php echo $row['description'] ?></td>
								<td><?php echo $row['stock'] ?></td>
								<td><?php echo date("Y-m-d H:i", strtotime($row['date_purchase'])) ?></td>
								<td><?php echo $row['cost'] ?></td>
								<td><?php echo $row['product_cost'] ?></td>
								<td><?php echo $row['shipping_or_extras'] ?></td>
								<td><?php echo $row['supplier'] ?></td>
								<td><?php echo $row['company'] ?></td>
								<td class="text-center">
									<?php if ($row['status'] == 1) : ?>
										<span class="badge badge-success rounded-pill">Activo</span>
									<?php else : ?>
										<span class="badge badge-danger rounded-pill">Inactivo</span>
									<?php endif; ?>
								</td>
								<td align="center">
									<button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
										Acción
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<div class="dropdown-menu" role="menu">
										<a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Ver</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Editar</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Eliminar</a>
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
<script>
	$(document).ready(function() {
		$('.delete_data').click(function() {
			_conf("Deseas eliminar este producto permanentemente?", "delete_category", [$(this).attr('data-id')])
		})
		$('#create_new').click(function() {
			uni_modal("<i class='fa fa-plus'></i> Agregar nuevo Producto", "maintenance/manage_item.php", "mid-large")
		})
		$('.edit_data').click(function() {
			uni_modal("<i class='fa fa-edit'></i> Editar información de Producto", "maintenance/manage_item.php?id=" + $(this).attr('data-id'), "mid-large")
		})
		$('.view_data').click(function() {
			uni_modal("<i class='fa fa-box'></i> Información de Producto", "maintenance/view_item.php?id=" + $(this).attr('data-id'), "")
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})

	function delete_category($id) {
		start_loader();
		$.ajax({
			url: _base_url_ + "classes/Master.php?f=delete_item",
			method: "POST",
			data: {
				id: $id
			},
			dataType: "json",
			error: err => {
				console.log(err)
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
		})
	}
</script>