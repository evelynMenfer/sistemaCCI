<?php
require_once('../../config.php');

if (isset($_GET['id']) && $_GET['id'] > 0) {
	$stmt = $conn->prepare("SELECT * FROM `item_list` WHERE id = ?");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$qry = $stmt->get_result();
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
	$stmt->close();
}
?>
<div class="container-fluid">
	<form action="" id="item-form">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		
		<div class="form-group">
			<label for="supplier_id" class="control-label">Proveedor</label>
			<select name="supplier_id" id="supplier_id" class="custom-select select2" required>
				<option disabled selected>Selecciona aquí</option>
				<?php
				$supplier = $conn->query("SELECT * FROM `supplier_list` WHERE status = 1 ORDER BY `name` ASC");
				while ($row = $supplier->fetch_assoc()):
				?>
					<option value="<?php echo $row['id'] ?>" 
						<?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?>>
						<?php echo htmlspecialchars($row['name']) ?>
					</option>
				<?php endwhile; ?>
			</select>
		</div>

		<div class="form-group">
			<label for="company_id" class="control-label">Empresa</label>
			<select name="company_id" id="company_id" class="custom-select select2" required>
				<option disabled selected>Selecciona aquí</option>
				<?php
				$company = $conn->query("SELECT * FROM `company_list` WHERE status = 1 ORDER BY `name` ASC");
				while ($row = $company->fetch_assoc()):
				?>
					<option value="<?php echo $row['id'] ?>" 
						<?php echo isset($company_id) && $company_id == $row['id'] ? "selected" : "" ?>>
						<?php echo htmlspecialchars($row['name']) ?>
					</option>
				<?php endwhile; ?>
			</select>
		</div>

		<div class="form-group">
			<label for="name" class="control-label">SKU</label>
			<input type="text" name="name" id="name" class="form-control rounded-0"
				value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
		</div>

		<div class="form-group">
			<label for="description" class="control-label">Descripción</label>
			<textarea name="description" id="description" cols="30" rows="2"
				class="form-control no-resize" required><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
		</div>

		<div class="form-group">
			<label for="stock" class="control-label">Stock</label>
			<input type="number" name="stock" id="stock" min="0" step="1" class="form-control text-end"
				value="<?php echo isset($stock) ? $stock : 0; ?>" required>
		</div>

		<div class="form-group">
			<label for="product_cost" class="control-label">Precio Compra</label>
			<input type="number" name="product_cost" id="product_cost" min="0" step="0.01"
				class="form-control text-end"
				value="<?php echo isset($product_cost) ? $product_cost : 0; ?>" required>
		</div>

		<div class="form-group">
			<label for="cost" class="control-label">Precio Venta</label>
			<input type="number" name="cost" id="cost" min="0" step="0.01" class="form-control text-end"
				value="<?php echo isset($cost) ? $cost : 0; ?>" required>
		</div>

		<div class="form-group">
			<label for="shipping_or_extras" class="control-label">Extras</label>
			<input type="number" name="shipping_or_extras" id="shipping_or_extras" min="0" step="0.01"
				class="form-control text-end"
				value="<?php echo isset($shipping_or_extras) ? $shipping_or_extras : 0; ?>">
		</div>

		<div class="form-group">
			<label for="date_purchase" class="control-label">Fecha de Compra</label>
			<input type="date" name="date_purchase" id="date_purchase"
				class="form-control text-end"
				value="<?php echo isset($date_purchase) ? $date_purchase : ''; ?>" required>
		</div>

		<div class="form-group">
			<label for="oc" class="control-label">OC</label>
			<input type="text" name="oc" id="oc" class="form-control text-end"
				value="<?php echo isset($oc) ? htmlspecialchars($oc) : ''; ?>">
		</div>

		<div class="form-group">
			<label for="status" class="control-label">Estado</label>
			<select name="status" id="status" class="custom-select" required>
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Activo</option>
				<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactivo</option>
			</select>
		</div>
	</form>
</div>

<script>
$(document).ready(function () {
	$('.select2').select2({
		placeholder: "Selecciona aquí",
		width: "100%"
	});

	$('#item-form').submit(function (e) {
		e.preventDefault();

		// Validación en cliente antes de enviar
		let stock = parseFloat($('#stock').val()) || 0;
		let cost = parseFloat($('#cost').val()) || 0;
		let product_cost = parseFloat($('#product_cost').val()) || 0;

		if (stock < 0 || cost < 0 || product_cost < 0) {
			alert("Stock y precios no pueden ser negativos");
			return;
		}

		if (cost < product_cost) {
			if (!confirm("⚠ El precio de venta es menor al de compra. ¿Deseas continuar?")) {
				return;
			}
		}

		var _this = $(this);
		$('.err-msg').remove();
		start_loader();

		$.ajax({
			url: _base_url_ + "classes/Master.php?f=save_item",
			data: new FormData(this),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			dataType: 'json',
			error: err => {
				console.log(err);
				alert_toast("Ocurrió un error en la conexión", 'error');
				end_loader();
			},
			success: function (resp) {
				if (resp.status === 'success') {
					location.reload();
				} else if (resp.status === 'failed' && resp.msg) {
					var el = $('<div>')
					el.addClass("alert alert-danger err-msg").text(resp.msg);
					_this.prepend(el);
					el.show('slow');
					end_loader();
				} else {
					alert_toast("Ocurrió un error inesperado", 'error');
					console.log(resp);
					end_loader();
				}
			}
		});
	});
});
</script>
