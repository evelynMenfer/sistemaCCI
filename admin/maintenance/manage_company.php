<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * FROM `company_list` WHERE id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
?>

<style>
	/* === Estilo general === */
	#company-form label {
		font-weight: 600;
		color: #2c3e50;
	}

	#company-form .form-control {
		border-radius: 6px;
		border: 1px solid #ccc;
		font-size: 14px;
	}

	#company-form .form-group {
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

	.preview-container {
		position: relative;
		text-align: center;
		background: #f8f9fa;
		padding: 15px;
		border-radius: 10px;
		border: 1px solid #e0e0e0;
		height: 180px;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
	}

	.preview-container img#cimg {
		max-width: 100%;
		max-height: 100%;
		object-fit: contain;
		object-position: center;
		border-radius: 10px;
		background: #fff;
		border: 1px solid #ccc;
		padding: 4px;
		display: none;
	}

	.preview-container .no-logo {
		color: #999;
		font-size: 15px;
		font-weight: 500;
	}
</style>

<div class="container-fluid">
	<form action="" id="company-form" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

		<!-- 🟦 Fila 1: Identificador / RFC / Email -->
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label for="identificador">Identificador</label>
					<input type="text" name="identificador" id="identificador"
						class="form-control" value="<?php echo isset($identificador) ? $identificador : ''; ?>" placeholder="Ej. ORBYX01" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="rfc">RFC</label>
					<input type="text" name="rfc" id="rfc" class="form-control"
						value="<?php echo isset($rfc) ? $rfc : ''; ?>" required>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" name="email" id="email" class="form-control"
						value="<?php echo isset($email) ? $email : ''; ?>" required>
				</div>
			</div>
		</div>

		<!-- 🟦 Fila 2: Nombre -->
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="name">Nombre o Razón Social</label>
					<input type="text" name="name" id="name" class="form-control"
						value="<?php echo isset($name) ? $name : ''; ?>" required>
				</div>
			</div>
		</div>

		<!-- 🟦 Fila 3: Descripción / Dirección -->
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for="address">Descripción o Dirección</label>
					<textarea name="address" id="address" cols="30" rows="2" class="form-control"
						placeholder="Breve descripción o dirección de la empresa"><?php echo isset($address) ? $address : ''; ?></textarea>
				</div>
			</div>
		</div>

		<!-- 🟦 Nueva Fila 3.1: Nota al pie -->
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label for="nota">Nota al pie</label>
			<textarea name="nota" id="nota" cols="30" rows="2" class="form-control"
				placeholder="Ej. Información adicional o mensaje breve al pie de documentos"><?php echo isset($nota) ? $nota : ''; ?></textarea>
		</div>
	</div>
</div>

		<!-- 🟦 Fila 4: Contacto -->
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="cperson">Persona de Contacto</label>
					<input type="text" name="cperson" id="cperson" class="form-control"
						value="<?php echo isset($cperson) ? $cperson : ''; ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="contact"># de Contacto</label>
					<input type="text" name="contact" id="contact" class="form-control"
						value="<?php echo isset($contact) ? $contact : ''; ?>">
				</div>
			</div>
		</div>

		<!-- 🟦 Fila 5: Datos bancarios -->
		<div class="section-header mt-3">Datos Bancarios</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label for="banco">Banco</label>
					<input type="text" name="banco" id="banco" class="form-control"
						value="<?php echo isset($banco) ? $banco : ''; ?>">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="cuenta_clabe">CLABE Interbancaria</label>
					<input type="text" name="cuenta_clabe" id="cuenta_clabe" class="form-control"
						value="<?php echo isset($cuenta_clabe) ? $cuenta_clabe : ''; ?>">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label for="ncuenta">Número de Cuenta</label>
					<input type="text" name="ncuenta" id="ncuenta" class="form-control"
						value="<?php echo isset($ncuenta) ? $ncuenta : ''; ?>">
				</div>
			</div>
		</div>

		<!-- 🟦 Fila 6: Logo -->
		<div class="section-header mt-3">Logo Corporativo</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group preview-container">
					<label for="logo" class="d-block mb-2">Seleccionar Logo</label>
					<input type="file" name="logo" id="logo" accept="image/*" class="form-control mb-2">
					<?php if (isset($logo) && !empty($logo)): ?>
						<img id="cimg" src="<?php echo base_url . $logo; ?>" alt="Logo" style="display:block;">
					<?php else: ?>
						<span class="no-logo" id="noLogoText">Sin logo</span>
						<img id="cimg" src="" alt="Logo" style="display:none;">
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Estado -->
		<div class="form-group mt-3">
			<label for="status">Estado</label>
			<select name="status" id="status" class="custom-select">
				<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Activo</option>
				<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactivo</option>
			</select>
		</div>

	</form>
</div>

<script>
	// 🖼️ Vista previa del logo
	$('#logo').change(function() {
		const file = this.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = e => {
				$('#cimg').attr('src', e.target.result).show();
				$('#noLogoText').hide();
			};
			reader.readAsDataURL(file);
		} else {
			$('#cimg').hide();
			$('#noLogoText').show();
		}
	});

	// 🚀 Envío AJAX
	$('#company-form').submit(function(e) {
		e.preventDefault();
		start_loader();

		$.ajax({
			url: _base_url_ + "classes/Master.php?f=save_company",
			method: "POST",
			data: new FormData(this),
			cache: false,
			contentType: false,
			processData: false,
			dataType: "json",
			error: err => {
				console.log(err);
				alert_toast("Ocurrió un error", 'error');
				end_loader();
			},
			success: function(resp) {
				if (resp.status === 'success') {
					location.reload();
				} else {
					alert_toast(resp.msg || 'Error al guardar', 'error');
					end_loader();
				}
			}
		});
	});
</script>
