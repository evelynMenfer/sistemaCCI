<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * FROM `customer_list` WHERE id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
?>

<style>
	/* === Estilo general === */
	#customer-form label {
		font-weight: 600;
		color: #2c3e50;
	}
	#customer-form .form-control {
		border-radius: 6px;
		border: 1px solid #ccc;
		font-size: 14px;
	}
	#customer-form .form-group {
		margin-bottom: 1rem;
	}
	.section-header {
		font-weight: 700;
		color: #004080;
		margin-bottom: .5rem;
		font-size: 16px;
		border-left: 4px solid #007bff;
		padding-left: 10px;
	}
	.btn-save {
		background: #007bff;
		color: #fff;
		font-weight: 600;
		border: none;
		padding: 8px 20px;
		border-radius: 6px;
		transition: 0.2s ease;
	}
	.btn-save:hover {
		background: #0056b3;
	}
	.btn-cancel {
		border: 1px solid #ccc;
		background: #fff;
		color: #333;
		border-radius: 6px;
		padding: 8px 20px;
		transition: 0.2s ease;
	}
	.btn-cancel:hover {
		background: #f1f1f1;
	}
</style>

<div class="container-fluid">
	<form id="customer-form" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">

		<!-- 🟦 Fila 1: Nombre, RFC y Email -->
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label for="name">Nombre del Cliente</label>
					<input type="text" name="name" id="name" class="form-control"
						value="<?= isset($name) ? htmlspecialchars($name) : ''; ?>"
						placeholder="Ej. Juan Pérez o Empresa XYZ" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="rfc">RFC</label>
					<input type="text" name="rfc" id="rfc" class="form-control"
						value="<?= isset($rfc) ? htmlspecialchars($rfc) : ''; ?>"
						placeholder="Ej. ABC123456789" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="email">Correo Electrónico</label>
					<input type="email" name="email" id="email" class="form-control"
						value="<?= isset($email) ? htmlspecialchars($email) : ''; ?>"
						placeholder="cliente@correo.com" required>
				</div>
			</div>
		</div>

		<!-- 🟦 Fila 2: Contacto y Dirección -->
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="contact">Teléfono o Celular</label>
					<input type="text" name="contact" id="contact" class="form-control"
						value="<?= isset($contact) ? htmlspecialchars($contact) : ''; ?>"
						placeholder="Ej. 555-123-4567">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="address">Dirección</label>
					<textarea name="address" id="address" cols="30" rows="2" class="form-control"
						placeholder="Ej. Calle, número, colonia, ciudad"><?= isset($address) ? htmlspecialchars($address) : ''; ?></textarea>
				</div>
			</div>
		</div>

		<!-- 🟦 Fila 3: Estado -->
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="status">Estado</label>
					<select name="status" id="status" class="custom-select">
						<option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Activo</option>
						<option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Inactivo</option>
					</select>
				</div>
			</div>
		</div>

	</form>
</div>

<script>
$(document).on('submit', '#customer-form', function(e){
	e.preventDefault();
	start_loader();

	$.ajax({
		url: _base_url_ + "classes/Master.php?f=save_customer",
		method: "POST",
		data: new FormData(this),
		cache: false,
		contentType: false,
		processData: false,
		dataType: "json",
		error: err => {
			console.log(err);
			alert_toast("Ocurrió un error en el servidor", "error");
			end_loader();
		},
		success: function(resp) {
			if (resp && resp.status === "success") {
				alert_toast("Cliente guardado correctamente", "success");
				$('.modal').modal('hide');
				setTimeout(() => location.reload(), 500);
			} else {
				alert_toast(resp.msg || "Error al guardar el cliente", "error");
				end_loader();
			}
		}
	});
});
</script>
