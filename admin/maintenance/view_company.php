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
	/* ===== MODAL AMPLIADO ===== */
	#uni_modal .modal-dialog {
		max-width: 1000px !important;
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
		display: none;
	}

	/* ===== ESTRUCTURA GENERAL ===== */
	.company-wrapper {
		display: flex;
		flex-wrap: wrap;
		gap: 25px;
	}

	.logo-side {
		flex: 0 0 220px;
		background: #f8f9fa;
		border-radius: 10px;
		padding: 20px;
		text-align: center;
		border: 1px solid #e0e0e0;
	}

	/* 🔹 Ajuste de logo para evitar deformaciones */
	.logo-side img {
		max-width: 100%;
		max-height: 150px;
		width: auto;
		height: auto;
		object-fit: contain; /* 🔥 evita deformaciones y recortes */
		border-radius: 10px;
		background: #fff;
		border: 1px solid #ccc;
		padding: 6px;
		margin-bottom: 10px;
		display: inline-block;
	}

	.logo-side small {
		display: block;
		color: #555;
		margin-top: 5px;
		font-size: 14px;
	}

	.details-side {
		flex: 1;
		min-width: 300px;
	}

	.section-header {
		font-weight: 700;
		color: #004080;
		margin-top: 1rem;
		margin-bottom: .5rem;
		font-size: 16px;
		border-left: 4px solid #007bff;
		padding-left: 10px;
	}

	dl {
		margin-bottom: .8rem;
	}

	dt {
		font-weight: 600;
		color: #2c3e50;
		margin-bottom: 2px;
	}

	dd {
		margin-left: 0;
		margin-bottom: .4rem;
		color: #444;
		word-break: break-word;
	}

	.badge {
		font-size: 13px;
		padding: 6px 10px;
		border-radius: 20px;
	}

	/* ===== SECCIÓN INFERIOR ===== */
	.bottom-section {
		border-top: 1px solid #e0e0e0;
		margin-top: 1.5rem;
		padding-top: 1.5rem;
	}

	/* ===== RESPONSIVE ===== */
	@media (max-width: 768px) {
		#uni_modal .modal-body {
			padding: 1.2rem;
		}
		.company-wrapper {
			flex-direction: column;
		}
		.logo-side {
			flex: 1 1 100%;
			max-width: 100%;
		}
	}
</style>

<div class="container-fluid">
	<div class="company-wrapper">

		<!-- 🖼️ LOGO -->
		<div class="logo-side">
			<img src="<?php echo isset($logo) && !empty($logo) ? base_url.$logo : base_url.'uploads/default-logo.png'; ?>" alt="Logo">
			<small><?php echo isset($name) ? $name : ''; ?></small>
		</div>

		<!-- 📋 DATOS GENERALES -->
		<div class="details-side">
			<div class="row">
				<div class="col-md-4 col-sm-12">
					<dl>
						<dt>Identificador:</dt>
						<dd><?php echo isset($identificador) ? $identificador : '—'; ?></dd>
					</dl>
				</div>
				<div class="col-md-4 col-sm-12">
					<dl>
						<dt>RFC:</dt>
						<dd><?php echo isset($rfc) ? $rfc : '—'; ?></dd>
					</dl>
				</div>
				<div class="col-md-4 col-sm-12">
					<dl>
						<dt>Email:</dt>
						<dd><?php echo isset($email) ? $email : '—'; ?></dd>
					</dl>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<dl>
						<dt>Nombre o Razón Social:</dt>
						<dd><?php echo isset($name) ? $name : '—'; ?></dd>
					</dl>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<dl>
						<dt>Descripción / Dirección:</dt>
						<dd><?php echo isset($address) ? nl2br($address) : '—'; ?></dd>
					</dl>
				</div>
			</div>

			<div class="row align-items-center">
				<div class="col-md-4 col-sm-12">
					<dl>
						<dt>Persona de Contacto:</dt>
						<dd><?php echo isset($cperson) ? $cperson : '—'; ?></dd>
					</dl>
				</div>
				<div class="col-md-4 col-sm-12">
					<dl>
						<dt># de Contacto:</dt>
						<dd><?php echo isset($contact) ? $contact : '—'; ?></dd>
					</dl>
				</div>
				<div class="col-md-4 col-sm-12">
					<dl>
						<dt>Estado:</dt>
						<dd>
							<?php if ($status == 1): ?>
								<span class="badge badge-success">Activo</span>
							<?php else: ?>
								<span class="badge badge-danger">Inactivo</span>
							<?php endif; ?>
						</dd>
					</dl>
				</div>
			</div>
		</div>
	</div>

	<!-- 🔹 SECCIÓN INFERIOR (DATOS BANCARIOS) -->
	<div class="bottom-section">
		<div class="section-header">Datos Bancarios</div>
		<div class="row">
			<div class="col-md-4 col-sm-12">
				<dl>
					<dt>Banco:</dt>
					<dd><?php echo isset($banco) ? $banco : '—'; ?></dd>
				</dl>
			</div>
			<div class="col-md-4 col-sm-12">
				<dl>
					<dt>CLABE Interbancaria:</dt>
					<dd><?php echo isset($cuenta_clabe) ? $cuenta_clabe : '—'; ?></dd>
				</dl>
			</div>
			<div class="col-md-4 col-sm-12">
				<dl>
					<dt>Número de Cuenta:</dt>
					<dd><?php echo isset($ncuenta) ? $ncuenta : '—'; ?></dd>
				</dl>
			</div>
		</div>
	</div>

	<!-- 🔴 Botón de cierre -->
	<div class="text-right mt-4">
		<button class="btn btn-danger btn-flat px-4" type="button" data-dismiss="modal">
			Cerrar
		</button>
	</div>
</div>
