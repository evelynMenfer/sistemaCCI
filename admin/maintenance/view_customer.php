<?php require_once('./../../config.php') ?>
<?php
$qry = $conn->query("SELECT * FROM `customer_list` WHERE id = '{$_GET['id']}' ");
if ($qry->num_rows > 0) {
	foreach ($qry->fetch_assoc() as $k => $v) {
		$$k = $v;
	}
}
?>

<style>
	/* ===== Modal ancho ===== */
	#uni_modal .modal-dialog {
		max-width: 900px !important;
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

	/* ===== Contenedor general ===== */
	.customer-container {
		display: flex;
		flex-direction: column;
		background: #fff;
		border-radius: 12px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
		color: #2c3e50;
		padding: 25px;
	}

	/* ===== Encabezado ===== */
	.customer-header {
		text-align: center;
		margin-bottom: 25px;
	}
	.customer-header h3 {
		font-weight: 700;
		color: #004080;
		margin-bottom: 8px;
	}
	.customer-header p {
		color: #6c757d;
		margin: 0;
	}

	/* ===== Secciones ===== */
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
		flex: 1 1 50%;
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

	.description-box {
		background: #f8f9fa;
		border-radius: 8px;
		padding: 10px 15px;
		border: 1px solid #e0e0e0;
		font-size: 14px;
		color: #333;
		min-height: 60px;
	}

	/* ===== Botón cerrar ===== */
	.custom-close {
		text-align: right;
		margin-top: 25px;
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
		.info-col {
			flex: 1 1 100%;
		}
	}
</style>

<div class="customer-container">

	<!-- 🧾 Encabezado -->
	<div class="customer-header">
		<h3><?= htmlspecialchars($name ?? 'Cliente sin nombre') ?></h3>
	</div>

	<!-- 🟦 Datos generales -->
	<div class="section-header">Datos Generales</div>
	<div class="info-row">
		<div class="info-col">
			<span class="info-label">RFC:</span><br>
			<span class="info-value"><?= !empty($rfc) ? htmlspecialchars($rfc) : '—' ?></span>
		</div>
		<div class="info-col">
			<span class="info-label">Estado:</span><br>
			<?php
				$estado = isset($status) ? intval($status) : 0;
				$estadoTexto = $estado === 1 ? 'Activo' : 'Inactivo';
				$estadoColor = $estado === 1 ? '#28a745' : '#dc3545';
			?>
			<span class="info-value" style="color: <?= $estadoColor ?>; font-weight:600;">
				<?= $estadoTexto ?>
			</span>
		</div>
	</div>

	<!-- 🟦 Información de contacto -->
	<div class="section-header">Datos de Contacto</div>
	<div class="info-row">
		<div class="info-col">
			<span class="info-label">E-mail:</span><br>
			<span class="info-value"><?= !empty($email) ? htmlspecialchars($email) : '—' ?></span>
		</div>
		<div class="info-col">
			<span class="info-label">Teléfono o celular:</span><br>
			<span class="info-value"><?= !empty($contact) ? htmlspecialchars($contact) : '—' ?></span>
		</div>
	</div>

	<!-- 🟦 Dirección -->
	<div class="section-header">Dirección</div>
	<div class="description-box">
		<?= !empty($address) ? nl2br(htmlspecialchars($address)) : '<span class="text-muted">Sin dirección registrada</span>' ?>
	</div>

</div>

<!-- 🔵 Botón de cierre -->
<div class="custom-close">
	<button type="button" class="btn-close-modal" data-dismiss="modal">
		<i class="fa fa-times"></i> Cerrar
	</button>
</div>
