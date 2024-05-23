<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * from `item_list` where id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
?>
<div class="container-fluid">
	<form action="" id="item-form">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="oc" class="control-label">OC</label>
			<input type="text" name="oc" id="oc" step="any" class="form-control rounded-0 text-end" value="<?php echo isset($oc) ? $oc : ''; ?>">
		</div>
		<div class="form-group">
			<label for="supplier_id" class="control-label">Proveedor</label>
			<select name="supplier_id" id="supplier_id" class="custom-select select2">
				<option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
				<?php
				$supplier = $conn->query("SELECT * FROM `supplier_list` where status = 1 order by `name` asc");
				while ($row = $supplier->fetch_assoc()) :
				?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?></option>
				<?php endwhile; ?>
			</select>
		</div>

		<div class="form-group">
			<label for="company_id" class="control-label">Empresa</label>
			<select name="company_id" id="company_id" class="custom-select select2">
				<option <?php echo !isset($company_id) ? 'selected' : '' ?> disabled></option>
				<?php
				$company = $conn->query("SELECT * FROM `company_list` where status = 1 order by `name` asc");
				while ($row = $company->fetch_assoc()) :
				?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($company_id) && $company_id == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="name" class="control-label">SKU</label>
			<input type="text" name="name" id="name" class="form-control rounded-0" value="<?php echo isset($name) ? $name : ''; ?>">
		</div>
		<div class="form-group">
			<label for="description" class="control-label">Descripción</label>
			<textarea name="description" id="description" cols="30" rows="2" class="form-control form no-resize"><?php echo isset($description) ? $description : ''; ?></textarea>
		</div>
		<div class="form-group">
			<label for="stock" class="control-label">Stock</label>
			<input type="number" name="stock" id="stock" step="any" class="form-control rounded-0 text-end" value="<?php echo isset($stock) ? $stock : ''; ?>">
		</div>
		<div class="form-group">
			<label for="date_purchase" class="control-label">Fecha de Compra</label>
			<input type="date" name="date_purchase" id="date_purchase" step="any" class="form-control rounded-0 text-end" value="<?php echo isset($date_purchase) ? $date_purchase : ''; ?>">
		</div>
		<div class="form-group">
			<label for="cost" class="control-label">Precio Venta</label>
			<input type="number" name="cost" id="cost" step="any" class="form-control rounded-0 text-end" value="<?php echo isset($cost) ? $cost : ''; ?>">
		</div>
		<div class="form-group">
			<label for="product_cost" class="control-label">Precio Compra</label>
			<input type="number" name="product_cost" id="product_cost" step="any" class="form-control rounded-0 text-end" value="<?php echo isset($product_cost) ? $product_cost : ''; ?>">
		</div>
		<div class="form-group">
			<label for="shipping_or_extras" class="control-label">Extras</label>
			<input type="number" name="shipping_or_extras" id="shipping_or_extras" step="any" class="form-control rounded-0 text-end" value="<?php echo isset($shipping_or_extras) ? $shipping_or_extras : ''; ?>">
		</div>

		<div class="form-group">
			<label for="status" class="control-label">Estado</label>
			<select name="status" id="status" class="custom-select selevt">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Activo</option>
				<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactivo</option>
			</select>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: "Selecciona aquí",
			width: "relative"
		})
		$('#item-form').submit(function(e) {
			e.preventDefault();
			var _this = $(this)
			$('.err-msg').remove();
			start_loader();
			$.ajax({
				url: _base_url_ + "classes/Master.php?f=save_item",
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				dataType: 'json',
				error: err => {
					console.log(err)
					alert_toast("Ocurrió un error", 'error');
					end_loader();
				},
				success: function(resp) {
					if (typeof resp == 'object' && resp.status == 'success') {
						location.reload();
					} else if (resp.status == 'failed' && !!resp.msg) {
						var el = $('<div>')
						el.addClass("alert alert-danger err-msg").text(resp.msg)
						_this.prepend(el)
						el.show('slow')
						end_loader()
					} else {
						alert_toast("Ocurrió un error", 'error');
						end_loader();
						console.log(resp)
					}
				}
			})
		})
	})
</script>