<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * FROM `item_list` WHERE id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
?>

<style>
	#item-form label {
		font-weight: 600;
		color: #2c3e50;
	}

	#item-form .form-control,
	#item-form .custom-select {
		border-radius: 6px;
		border: 1px solid #ccc;
		font-size: 14px;
	}

	#item-form .form-group {
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
		height: 200px;
		display: flex;
		align-items: center;
		justify-content: center;
		flex-direction: column;
	}

	.preview-container img#pimg {
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

	.preview-container .no-image {
		color: #999;
		font-size: 15px;
		font-weight: 500;
	}
</style>

<div class="container-fluid">
	<form action="" id="item-form" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">

		<!-- 🟦 Fila 1: OC / Fecha / SKU -->
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label for="oc">OC</label>
					<input type="text" name="oc" id="oc" class="form-control"
						value="<?= htmlspecialchars($oc ?? '') ?>">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="date_purchase">Fecha de Compra</label>
					<input type="date" name="date_purchase" id="date_purchase" class="form-control"
						value="<?= htmlspecialchars($date_purchase ?? '') ?>">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="name">SKU</label>
					<input type="text" name="name" id="name" class="form-control"
						value="<?= htmlspecialchars($name ?? '') ?>">
				</div>
			</div>
		</div>

		<!-- 🟦 Fila 2: Descripción -->
<div class="row">
	<div class="col-md-12">
		<div class="form-group position-relative">
			<label for="description">Descripción <span class="text-danger">*</span></label>
			<textarea name="description" id="description" rows="2" class="form-control"
				placeholder="Breve descripción del producto"><?= htmlspecialchars($description ?? '') ?></textarea>
			<small id="desc-error" class="text-danger mt-1 d-none">Por favor ingresa la descripción del producto.</small>
		</div>
	</div>
</div>

		<!-- 🟦 Fila 3: Marca / Modelo / Talla / Ficha técnica -->
<div class="row">
	<div class="col-md-3">
		<div class="form-group">
			<label for="marca">Marca</label>
			<input type="text" name="marca" id="marca" class="form-control"
				value="<?= htmlspecialchars($marca ?? '') ?>"
				placeholder="Ej. Samsung, LG, Bosch...">
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<label for="modelo">Modelo</label>
			<input type="text" name="modelo" id="modelo" class="form-control"
				value="<?= htmlspecialchars($modelo ?? '') ?>"
				placeholder="Ej. XR5000, Pro Max, T-300...">
		</div>
	</div>

	<!-- 🆕 Nuevo campo: Talla -->
	<div class="col-md-3">
		<div class="form-group">
			<label for="talla">Talla</label>
			<input type="text" name="talla" id="talla" class="form-control"
				value="<?= htmlspecialchars($talla ?? '') ?>"
				placeholder="Ej. S, M, L, 42, 7.5...">
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<label for="pdf_path">Ficha Técnica (PDF)</label>
			<input type="file" name="pdf_path" id="pdf_path" accept="application/pdf" class="form-control mb-2">
			<?php if (!empty($pdf_path)): ?>
				<a href="<?= base_url . $pdf_path ?>" target="_blank" class="btn btn-outline-primary btn-sm w-100">
					<i class="fa fa-file-pdf me-1"></i> Ver PDF actual
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>


		<!-- 🟦 Fila 4: Precios y Stock -->
		<div class="section-header mt-3">Precios y Stock</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label for="stock">Stock</label>
					<input type="number" name="stock" id="stock" class="form-control text-end"
						value="<?= htmlspecialchars($stock ?? '') ?>" step="1" min="0">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="product_cost">Precio Compra</label>
					<input type="number" name="product_cost" id="product_cost" class="form-control text-end"
						value="<?= htmlspecialchars($product_cost ?? '') ?>" step="0.01" min="0">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="cost">Precio Venta</label>
					<input type="number" name="cost" id="cost" class="form-control text-end"
						value="<?= htmlspecialchars($cost ?? '') ?>" step="0.01" min="0">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label for="shipping_or_extras">Extras</label>
					<input type="number" name="shipping_or_extras" id="shipping_or_extras" class="form-control text-end"
						value="<?= htmlspecialchars($shipping_or_extras ?? '') ?>" step="0.01" min="0">
				</div>
			</div>
		</div>

		<!-- 🟦 Fila 5: Relaciones -->
		<div class="section-header mt-3">Relaciones</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label for="supplier_id">Proveedor</label>
					<select name="supplier_id" id="supplier_id" class="custom-select select2">
						<option disabled selected>Buscar proveedor...</option>
						<?php
						$supplier = $conn->query("SELECT * FROM supplier_list WHERE status = 1 ORDER BY name ASC");
						while ($row = $supplier->fetch_assoc()):
						?>
							<option value="<?= $row['id'] ?>" <?= isset($supplier_id) && $supplier_id == $row['id'] ? 'selected' : '' ?>>
								<?= htmlspecialchars($row['name']) ?>
							</option>
						<?php endwhile; ?>
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="company_id">Empresa</label>
					<select name="company_id" id="company_id" class="custom-select select2">
						<option disabled selected>Buscar empresa...</option>
						<?php
						$company = $conn->query("SELECT * FROM company_list WHERE status = 1 ORDER BY name ASC");
						while ($row = $company->fetch_assoc()):
						?>
							<option value="<?= $row['id'] ?>" <?= isset($company_id) && $company_id == $row['id'] ? 'selected' : '' ?>>
								<?= htmlspecialchars($row['name']) ?>
							</option>
						<?php endwhile; ?>
					</select>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="status">Estado</label>
					<select name="status" id="status" class="custom-select">
						<option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Activo</option>
						<option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Inactivo</option>
					</select>
				</div>
			</div>
		</div>

		<!-- 🟦 Fila 6: Imagen -->
		<div class="section-header mt-3">Imagen del Producto</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group preview-container">
					<label for="foto_producto" class="d-block mb-2">Seleccionar Imagen</label>
					<input type="file" name="foto_producto" id="foto_producto" accept="image/*" class="form-control mb-2">
					<?php if (!empty($foto_producto)): ?>
						<img id="pimg" src="<?= base_url . $foto_producto ?>" alt="Producto" style="display:block;">
					<?php else: ?>
						<span class="no-image" id="noImageText">Sin imagen</span>
						<img id="pimg" src="" alt="Producto">
					<?php endif; ?>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	// 🖼️ Vista previa de imagen
	$('#foto_producto').change(function() {
		const file = this.files[0];
		if (file) {
			const reader = new FileReader();
			reader.onload = e => {
				$('#pimg').attr('src', e.target.result).show();
				$('#noImageText').hide();
			};
			reader.readAsDataURL(file);
		} else {
			$('#pimg').hide();
			$('#noImageText').show();
		}
	});

	// 🔍 Select2 con búsqueda
	$('#supplier_id').select2({
		placeholder: "Buscar proveedor...",
		width: '100%',
		language: { noResults: () => "No se encontraron resultados" }
	});
	$('#company_id').select2({
		placeholder: "Buscar empresa...",
		width: '100%',
		language: { noResults: () => "No se encontraron resultados" }
	});

	// 🚀 Envío AJAX con validación personalizada
	$('#item-form').submit(function(e) {
		e.preventDefault();

		// 🔹 Validar campo descripción antes de enviar
		const desc = $('#description').val().trim();
		if (desc === '') {
			$('#desc-error').removeClass('d-none');
			$('#description').addClass('is-invalid').focus();
			return; // detener envío
		} else {
			$('#desc-error').addClass('d-none');
			$('#description').removeClass('is-invalid');
		}

		start_loader();

		$.ajax({
			url: _base_url_ + "classes/Master.php?f=save_item",
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

	// 💅 Estilo visual para error de validación
	$(document).on('input', '#description', function() {
		if ($(this).val().trim() !== '') {
			$(this).removeClass('is-invalid');
			$('#desc-error').addClass('d-none');
		}
	});
</script>

<style>
	/* 💅 Borde rojo suave en error */
	.is-invalid {
		border-color: #dc3545 !important;
		box-shadow: 0 0 0 0.1rem rgba(220, 53, 69, 0.25);
	}
</style>

