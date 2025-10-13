<?php
require_once(__DIR__ . '/../../config.php');

// =============================
// 🔹 VALIDACIÓN DE PARÁMETROS
// =============================
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : 0;

if ($id <= 0) {
    echo "<div class='alert alert-danger mt-4 text-center'>ID de cotización inválido o no especificado.</div>";
    exit;
}

if (!isset($conn) || !$conn) {
    echo "<div class='alert alert-danger text-center mt-5'>
          ❌ Error de conexión a la base de datos. Verifica config.php.
          </div>";
    exit;
}

// =============================
// 🔹 CONSULTA PRINCIPAL
// =============================
$qry = $conn->query("
  SELECT p.*, s.name AS supplier, 
         c.logo AS logo_empresa, 
         c.name AS name_empresa, 
         c.email, c.contact, c.address, 
         c.id AS id_company
  FROM purchase_order_list p
  LEFT JOIN supplier_list s ON p.supplier_id = s.id
  LEFT JOIN company_list c ON p.id_company = c.id
  WHERE p.id = {$id}
");

if (!$qry || $qry->num_rows === 0) {
    echo "<div class='alert alert-warning text-center mt-5'>⚠️ Cotización no encontrada.</div>";
    exit;
}

foreach ($qry->fetch_array() as $k => $v) $$k = $v;

if (empty($company_id) && isset($id_company)) {
    $company_id = intval($id_company);
}

// =============================
// 🔹 OBTENER LOGO EMPRESA
// =============================
$logo_path = '';
if (!empty($logo_empresa)) {
    $relative_logo = ltrim($logo_empresa, '/');
    $absolute_logo_path = realpath(__DIR__ . '/../../' . $relative_logo);
    if ($absolute_logo_path && file_exists($absolute_logo_path)) {
        $logo_path = base_url . $relative_logo;
    }
}
?>
<style>
  .card-title { font-weight:600; }
  th, td { vertical-align: middle !important; }
  thead th { background-color:#001f3f; color:white; text-align:center; }
  tfoot th { background:#f6f6f6; font-weight:600; }
  .text-end { text-align: right !important; }
  .text-center { text-align: center !important; }
  .text-start { text-align: left !important; }
  .border-top-2 { border-top: 2px solid #001f3f !important; }
</style>

<div class="card card-outline card-primary">
  <div class="card-header d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
      <?php if (!empty($logo_path)): ?>
        <img src="<?php echo $logo_path; ?>" alt="Logo" style="width:50px; height:50px; object-fit:contain; margin-right:10px;">
      <?php endif; ?>
      <div>
        <h4 class="card-title mb-0">
          Cotización: <?php echo htmlspecialchars($po_code ?? ''); ?>
        </h4>
        <small class="text-muted text-uppercase"><?php echo htmlspecialchars($name_empresa ?? ''); ?></small>
      </div>
    </div>
  </div>

  <div class="card-body">
    <!-- ================= DATOS GENERALES ================= -->
    <div class="row mb-3">
      <div class="col-md-4"><label class="text-info">Proveedor</label><div><?php echo htmlspecialchars($supplier ?? '—'); ?></div></div>
      <div class="col-md-4"><label class="text-info">OC</label><div><?php echo htmlspecialchars($oc ?? '—'); ?></div></div>
      <div class="col-md-4"><label class="text-info">No. Factura</label><div><?php echo htmlspecialchars($num_factura ?? '—'); ?></div></div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4"><label class="text-info">Carga al Portal</label><div><?php echo htmlspecialchars($date_carga_portal ?? '—'); ?></div></div>
      <div class="col-md-4"><label class="text-info">Fecha de Pago</label><div><?php echo htmlspecialchars($date_pago ?? '—'); ?></div></div>
      <div class="col-md-4"><label class="text-info">Folio Fiscal</label><div><?php echo htmlspecialchars($folio_fiscal ?? '—'); ?></div></div>
    </div>

    <div class="row mb-3">
      <div class="col-md-4"><label class="text-info">Comprobante de Pago</label><div><?php echo htmlspecialchars($folio_comprobante_pago ?? '—'); ?></div></div>
      <div class="col-md-4"><label class="text-info">Pago en Efectivo</label><div><?php echo htmlspecialchars($pago_efectivo ?? '—'); ?></div></div>
      <div class="col-md-4"><label class="text-info">Método de Pago</label><div><?php echo htmlspecialchars($metodo_pago ?? '—'); ?></div></div>
    </div>

    <!-- ================= ESTADO ================= -->
    <?php
    $estado_txt = 'Pendiente';
    if (isset($status)) {
        switch (intval($status)) {
            case 1: $estado_txt = 'En proceso'; break;
            case 2: $estado_txt = 'Aceptado'; break;
        }
    }
    ?>
    <div class="col-md-3 mb-3">
      <label class="control-label text-info">Estado de la cotización</label>
      <input type="text" class="form-control rounded-0" value="<?php echo $estado_txt; ?>" readonly>
    </div>

    <hr>

    <!-- ================= CLIENTE ================= -->
    <div class="row mb-3">
      <div class="col-md-8 text-start">
        <p>
          <strong>Cliente:</strong> <?php echo htmlspecialchars($cliente_cotizacion ?? '—'); ?><br>
          <strong>Email:</strong> <?php echo htmlspecialchars($cliente_email ?? '—'); ?><br>
          <strong>Trabajo:</strong> <?php echo htmlspecialchars($trabajo ?? '—'); ?>
        </p>
      </div>
      <div class="col-md-4 text-end">
        <p>
          <strong>Fecha:</strong> <?php echo !empty($date_exp) ? date("d/m/Y", strtotime($date_exp)) : '—'; ?><br>
          <strong>OC:</strong> <?php echo htmlspecialchars($oc ?? '—'); ?>
        </p>
      </div>
    </div>

    <!-- ================= TABLA DE PRODUCTOS ================= -->
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th style="width:8%">Cant.</th>
          <th style="width:10%">Unidad</th>
          <th style="width:40%">Descripción</th>
          <th style="width:12%">Precio Unitario</th>
          <th style="width:10%">Desc %</th>
          <th style="width:15%">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $subtotal = 0;
        $qry_items = $conn->query("
          SELECT p.*, i.description 
          FROM po_items p 
          INNER JOIN item_list i ON p.item_id = i.id 
          WHERE p.po_id = {$id}
        ");
        while ($row = $qry_items->fetch_assoc()):
          $line_total = ($row['price'] - ($row['price']*$row['discount']/100)) * $row['quantity'];
          $subtotal += $line_total;
        ?>
        <tr>
          <td class="text-end"><?php echo number_format($row['quantity'],2) ?></td>
          <td class="text-center"><?php echo htmlspecialchars($row['unit']); ?></td>
          <td class="text-start"><?php echo htmlspecialchars($row['description']); ?></td>
          <td class="text-end">$<?php echo number_format($row['price'],2) ?></td>
          <td class="text-end"><?php echo number_format($row['discount'],2) ?>%</td>
          <td class="text-end">$<?php echo number_format($line_total,2) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>

      <?php
      // =======================
      // 🔹 CÁLCULOS FINALES
      // =======================
      $discount_perc = floatval($discount_perc ?? 0);
      $tax_perc      = floatval($tax_perc ?? 0);
      $discount_total = $subtotal * ($discount_perc / 100);
      $base_imponible = $subtotal - $discount_total;
      $tax_total = $base_imponible * ($tax_perc / 100);
      $total_final = $base_imponible + $tax_total;
      ?>
      <tfoot>
        <tr>
          <th colspan="5" class="text-end">Sub Total</th>
          <th class="text-end">$<?php echo number_format($subtotal,2) ?></th>
        </tr>
        <tr>
          <th colspan="5" class="text-end">Descuento (<?php echo $discount_perc ?>%)</th>
          <th class="text-end">$<?php echo number_format($discount_total,2) ?></th>
        </tr>
        <tr>
          <th colspan="5" class="text-end">Impuesto (<?php echo $tax_perc ?>%)</th>
          <th class="text-end">$<?php echo number_format($tax_total,2) ?></th>
        </tr>
        <tr class="border-top-2">
          <th colspan="5" class="text-end">Total</th>
          <th class="text-end fw-bold">$<?php echo number_format($total_final,2) ?></th>
        </tr>
      </tfoot>
    </table>

    <?php if(!empty($remarks)): ?>
      <p class="mt-3"><strong>Observaciones:</strong><br><?php echo nl2br(htmlspecialchars($remarks)); ?></p>
    <?php endif; ?>
  </div>

  <div class="card-footer text-center">
    <a class="btn btn-primary" href="<?php echo base_url . 'admin/?page=purchase_order/manage_po&id=' . $id . '&company_id=' . $company_id; ?>">Editar</a>
    <a class="btn btn-danger" href="<?php echo base_url . 'admin/?page=purchase_order/index&company_id=' . $company_id; ?>">Volver</a>
    <a class="btn btn-success" href="<?php echo base_url; ?>admin/pdf/generate_po.php?id=<?php echo $id; ?>" target="_blank">
      Generar PDF
    </a>
  </div>
</div>
