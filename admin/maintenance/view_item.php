<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT i.*, 
								s.name AS supplier, 
								c.name AS company 
						 FROM item_list i 
						 LEFT JOIN supplier_list s ON i.supplier_id = s.id 
						 LEFT JOIN company_list c ON i.company_id = c.id 
						 WHERE i.id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
?>

<style>
	#uni_modal .modal-dialog {
		max-width: 1100px !important;
	}
	#uni_modal .modal-content {
		border-radius: 12px;
		border: none;
		box-shadow: 0 0 25px rgba(0, 0, 0, 0.15);
	}
	#uni_modal .modal-body {
		padding: 2rem 2.5rem;
	}
	#uni_modal .modal-footer {
		display: none !important;
	}

	.view-container {
		display: flex;
		flex-wrap: wrap;
		background: #fff;
		border-radius: 12px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
		color: #2c3e50;
		padding: 25px;
		gap: 25px;
	}

	.image-side {
		flex: 0 0 300px;
		display: flex;
		flex-direction: column;
		align-items: center;
	}

	.image-box {
		background: #f8f9fa;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		height: 280px;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		margin-bottom: 15px;
	}
	.image-box img {
		max-width: 100%;
		max-height: 100%;
		object-fit: contain;
		border-radius: 10px;
	}

	.side-actions {
		text-align: center;
		width: 100%;
	}
	.pdf-btn {
		display: inline-block;
		margin: 8px 0;
		padding: 6px 14px;
		border: 1px solid #007bff;
		border-radius: 6px;
		color: #007bff;
		font-weight: 500;
		text-decoration: none;
		transition: all 0.2s ease;
		font-size: 13px;
	}
	.pdf-btn:hover {
		background: #007bff;
		color: #fff;
	}

	.status-badge {
		display: inline-block;
		padding: 8px 18px;
		border-radius: 30px;
		font-weight: 600;
		font-size: 13px;
		margin-top: 5px;
		text-align: center;
	}
	.status-activo {
		background: #d4edda;
		color: #155724;
	}
	.status-inactivo {
		background: #f8d7da;
		color: #721c24;
	}

	.info-side {
		flex: 1;
	}
	.section-header {
		font-weight: 700;
		color: #004080;
		font-size: 15px;
		margin-top: 1.2rem;
		border-left: 4px solid #007bff;
		padding-left: 10px;
	}

	.info-row {
		display: flex;
		flex-wrap: wrap;
		margin-top: 8px;
	}
	.info-col {
		flex: 1 1 33%;
		margin-bottom: 10px;
		padding-right: 15px;
	}
	.info-label {
		font-weight: 600;
		color: #555;
	}
	.info-value {
		color: #1a1a1a;
		font-weight: 500;
		word-wrap: break-word;
	}

	.description-stock-row {
		display: flex;
		gap: 15px;
		margin-top: 10px;
	}
	.description-box {
		flex: 1;
		background: #f8f9fa;
		border-radius: 8px;
		padding: 10px 15px;
		border: 1px solid #e0e0e0;
		font-size: 14px;
		color: #333;
		min-height: 80px;
	}
	.stock-box {
		width: 120px;
		text-align: center;
		background: #e9f5ff;
		border: 1px solid #bcdffb;
		border-radius: 8px;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		font-size: 14px;
		font-weight: 600;
		color: #004080;
	}

	.custom-close {
		text-align: right;
		margin-top: 10px;
	}
	.btn-close-modal {
		background: #007bff;
		color: #fff;
		font-weight: 600;
		padding: 8px 24px;
		border-radius: 8px;
		border: none;
		transition: all 0.2s ease;
	}
	.btn-close-modal:hover {
		background: #0056b3;
	}

	@media (max-width: 768px) {
		.view-container {
			flex-direction: column;
		}
		.image-side {
			width: 100%;
			align-items: center;
		}
		.image-box {
			height: 220px;
			width: 100%;
		}
		.description-stock-row {
			flex-direction: column;
		}
		.stock-box {
			width: 100%;
			padding: 10px;
		}
	}
</style>

<div class="view-container">

	<!-- 🖼️ Columna izquierda -->
	<div class="image-side">
		<div class="image-box">
			<?php if (!empty($foto_producto) && file_exists(base_app . $foto_producto)): ?>
				<img src="<?= base_url . $foto_producto ?>" alt="Producto">
			<?php else: ?>
				<span class="text-muted">Sin imagen disponible</span>
			<?php endif; ?>
		</div>

		<div class="side-actions">


			<?php if (isset($status) && $status == 1): ?>
				<span class="status-badge status-activo">Activo</span>
			<?php else: ?>
				<span class="status-badge status-inactivo">Inactivo</span>
			<?php endif; ?>
		</div>
	</div>

	<!-- 🧾 Columna derecha -->
	<div class="info-side">
		<!-- 🟦 Detalles Generales -->
		<div class="section-header">Detalles Generales</div>
		<div class="info-row">
			<div class="info-col">
				<span class="info-label">OC:</span><br>
				<span class="info-value"><?= $oc ?? '—' ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">SKU:</span><br>
				<span class="info-value"><?= $name ?? '—' ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">Fecha de Compra:</span><br>
				<span class="info-value"><?= !empty($date_purchase) ? date("Y-m-d", strtotime($date_purchase)) : '—' ?></span>
			</div>
		</div>

		<!-- 🟦 Descripción y Stock -->
		<div class="section-header">Descripción y Stock</div>
		<div class="description-stock-row">
			<div class="description-box">
				<?= !empty($description) ? nl2br(htmlspecialchars($description)) : '<span class="text-muted">Sin descripción</span>' ?>
			</div>
			<div class="stock-box">
				<div>Stock</div>
				<div style="font-size: 18px;"><?= number_format($stock ?? 0, 0) ?></div>
			</div>
		</div>

		<!-- 🟦 Nueva fila: Marca, Modelo y Ficha Técnica -->
<div class="section-header">Detalles Técnicos</div>
<div class="info-row">
	<div class="info-col">
		<span class="info-label">Marca:</span><br>
		<span class="info-value"><?= !empty($marca) ? htmlspecialchars($marca) : '—' ?></span>
	</div>
	<div class="info-col">
		<span class="info-label">Modelo:</span><br>
		<span class="info-value"><?= !empty($modelo) ? htmlspecialchars($modelo) : '—' ?></span>
	</div>
	
</div>

<div class="info-row">
	<div class="info-col">
		<span class="info-label">Ficha Técnica:</span><br>
		<?php if (!empty($pdf_path) && file_exists(base_app . $pdf_path)): ?>
			<a href="<?= base_url . $pdf_path ?>" target="_blank" class="text-primary fw-bold">
				<i class="fa fa-file-pdf"></i> Ver PDF
			</a>
		<?php else: ?>
			<span class="text-muted">No disponible</span>
		<?php endif; ?>
	</div>
</div>


		<!-- 🟦 Precios -->
		<div class="section-header">Precios</div>
		<div class="info-row">
			<div class="info-col">
				<span class="info-label">Precio de Compra:</span><br>
				<span class="info-value">$<?= number_format($product_cost ?? 0, 2) ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">Precio de Venta:</span><br>
				<span class="info-value">$<?= number_format($cost ?? 0, 2) ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">Extras:</span><br>
				<span class="info-value">$<?= number_format($shipping_or_extras ?? 0, 2) ?></span>
			</div>
		</div>

		<!-- 🟦 Relaciones -->
		<div class="section-header">Relaciones</div>
		<div class="info-row">
			<div class="info-col">
				<span class="info-label">Proveedor:</span><br>
				<span class="info-value"><?= $supplier ?? '—' ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">Empresa:</span><br>
				<span class="info-value"><?= $company ?? '—' ?></span>
			</div>
		</div>
	</div>
</div>

<!-- 🔵 Botón de cierre -->
<div class="custom-close">
	<button type="button" class="btn-close-modal" data-dismiss="modal">
		<i class="fa fa-times"></i> Cerrar
	</button>
</div>
