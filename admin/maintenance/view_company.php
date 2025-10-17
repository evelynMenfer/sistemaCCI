<?php require_once('./../../config.php') ?>
<?php
$qry = $conn->query("SELECT * FROM `company_list` WHERE id = '{$_GET['id']}' ");
if ($qry->num_rows > 0) {
	foreach ($qry->fetch_assoc() as $k => $v) {
		$$k = $v;
	}
}
?>

<style>
	/* ===== Modal ancho ===== */
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

	/* ===== Contenedor general ===== */
	.company-container {
		display: flex;
		flex-wrap: wrap;
		background: #fff;
		border-radius: 12px;
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
		color: #2c3e50;
		padding: 25px;
		gap: 25px;
	}

	/* ===== Columna izquierda (logo) ===== */
	.logo-side {
		flex: 0 0 280px;
		display: flex;
		flex-direction: column;
		align-items: center;
		text-align: center;
	}

	.logo-box {
		background: #f8f9fa;
		border: 1px solid #e0e0e0;
		border-radius: 10px;
		height: 250px;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		margin-bottom: 15px;
	}

	.logo-box img {
		max-width: 100%;
		max-height: 100%;
		object-fit: contain;
		border-radius: 10px;
	}

	.company-name {
		font-weight: 600;
		color: #004080;
		margin-top: 8px;
		font-size: 15px;
	}

	.status-badge {
		display: inline-block;
		padding: 7px 18px;
		border-radius: 30px;
		font-weight: 600;
		font-size: 13px;
		margin-top: 10px;
	}
	.status-activo {
		background: #d4edda;
		color: #155724;
	}
	.status-inactivo {
		background: #f8d7da;
		color: #721c24;
	}

	/* ===== Columna derecha (información) ===== */
	.info-side {
		flex: 1;
	}

	.view-header h3 {
		font-weight: 700;
		color: #004080;
		margin-bottom: 1rem;
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
		margin-top: 15px;
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
		.company-container {
			flex-direction: column;
		}
		.logo-side {
			width: 100%;
			align-items: center;
		}
		.logo-box {
			height: 200px;
			width: 100%;
		}
	}
</style>

<div class="company-container">

	<!-- 🖼️ Columna izquierda -->
	<div class="logo-side">
		<div class="logo-box">
			<?php if (!empty($logo) && file_exists(base_app . $logo)): ?>
				<img src="<?= base_url . $logo ?>" alt="Logo Empresa">
			<?php else: ?>
				<span class="text-muted">Sin logo</span>
			<?php endif; ?>
		</div>
		<div class="company-name"><?= $name ?? '—' ?></div>

		<?php if (isset($status) && $status == 1): ?>
			<span class="status-badge status-activo">Activo</span>
		<?php else: ?>
			<span class="status-badge status-inactivo">Inactivo</span>
		<?php endif; ?>
	</div>

	<!-- 📋 Columna derecha -->
	<div class="info-side">

		<!-- 🟦 Datos generales -->
		<div class="section-header">Datos Generales</div>
		<div class="info-row">
			<div class="info-col">
				<span class="info-label">Identificador:</span><br>
				<span class="info-value"><?= $identificador ?? '—' ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">RFC:</span><br>
				<span class="info-value"><?= $rfc ?? '—' ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">Email:</span><br>
				<span class="info-value"><?= $email ?? '—' ?></span>
			</div>
		</div>

		<!-- 🟦 Razón Social -->
		<div class="section-header">Nombre o Razón Social</div>
		<div class="description-box">
			<?= !empty($name) ? htmlspecialchars($name) : '<span class="text-muted">Sin nombre</span>' ?>
		</div>

		<!-- 🟦 Dirección / Descripción -->
		<div class="section-header">Descripción / Dirección</div>
		<div class="description-box">
			<?= !empty($address) ? nl2br(htmlspecialchars($address)) : '<span class="text-muted">Sin descripción</span>' ?>
		</div>

		<!-- 🟦 Nota al pie -->
<?php if (!empty($nota)): ?>
	<div class="section-header">Nota al pie</div>
	<div class="description-box">
		<?= nl2br(htmlspecialchars($nota)) ?>
	</div>
<?php else: ?>
	<div class="section-header">Nota al pie</div>
	<div class="description-box">
		<span class="text-muted">Sin nota</span>
	</div>
<?php endif; ?>


		<!-- 🟦 Contacto -->
		<div class="section-header">Contacto</div>
		<div class="info-row">
			<div class="info-col">
				<span class="info-label">Persona de contacto:</span><br>
				<span class="info-value"><?= $cperson ?? '—' ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">Teléfono / Contacto:</span><br>
				<span class="info-value"><?= $contact ?? '—' ?></span>
			</div>
		</div>

		<!-- 🟦 Datos Bancarios -->
		<div class="section-header">Datos Bancarios</div>
		<div class="info-row">
			<div class="info-col">
				<span class="info-label">Banco:</span><br>
				<span class="info-value"><?= $banco ?? '—' ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">CLABE Interbancaria:</span><br>
				<span class="info-value"><?= $cuenta_clabe ?? '—' ?></span>
			</div>
			<div class="info-col">
				<span class="info-label">Número de Cuenta:</span><br>
				<span class="info-value"><?= $ncuenta ?? '—' ?></span>
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
