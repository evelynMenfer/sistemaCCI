<?php
// ==================================================
// Template DISS - Cotización PDF (robusto con fallbacks)
// ==================================================

// ---------- Compat Layer / Fallbacks ----------
$data           = $data           ?? [];
$items          = isset($items) && is_array($items) ? $items : [];
$style          = $style          ?? '';
$logo_path      = $logo_path      ?? '';

$cliente        = trim($data['cliente_nombre'] ?? '');
$cliente_email  = trim($data['cliente_email'] ?? '');
$date_exp       = $date_exp       ?? ($data['date_exp'] ?? date('Y-m-d'));
$po_code        = $data['po_code'] ?? '';

// 🔹 Corrección: siempre priorizar valores reales de BD
$discount_perc  = isset($data['discount_perc']) ? floatval($data['discount_perc']) : (isset($discount_perc) ? floatval($discount_perc) : 0);
$tax_perc       = isset($data['tax_perc']) ? floatval($data['tax_perc']) : (isset($tax_perc) ? floatval($tax_perc) : 0);
$discount       = isset($data['discount']) ? floatval($data['discount']) : (isset($discount) ? floatval($discount) : 0);
$tax            = isset($data['tax']) ? floatval($data['tax']) : (isset($tax) ? floatval($tax) : 0);
$amount         = isset($data['amount']) ? floatval($data['amount']) : (isset($amount) ? floatval($amount) : 0);

$remarks        = isset($remarks) ? $remarks : ($data['remarks'] ?? '');
$metodo_pago    = isset($metodo_pago) ? $metodo_pago : ($data['metodo_pago'] ?? '');
$address        = $data['address'] ?? '';
$contact        = $data['contact'] ?? '';
$email_empresa  = $data['email']   ?? '';

// 🔹 Subtotal: si no vino, lo calculamos desde los ítems
$subtotal = isset($subtotal) ? floatval($subtotal) : 0.0;
if ($subtotal <= 0 && !empty($items)) {
    $tmp_sub = 0.0;
    foreach ($items as $it) {
        if (isset($it['line_total'])) {
            $tmp_sub += floatval($it['line_total']);
            continue;
        }
        $q = floatval($it['quantity'] ?? 0);
        $p = floatval($it['price'] ?? 0);
        $d = floatval($it['discount'] ?? 0);
        $tmp_sub += ($p - ($p * $d / 100.0)) * $q;
    }
    $subtotal = $tmp_sub;
}

// 🔹 Solo reconstruimos si algo está completamente vacío
if ($discount === 0 && $discount_perc > 0) {
    $discount = round($subtotal * ($discount_perc / 100.0), 2);
}
$base_para_impuesto = max($subtotal - $discount, 0);
if ($tax === 0 && $tax_perc > 0) {
    $tax = round($base_para_impuesto * ($tax_perc / 100.0), 2);
}
if ($amount === 0) {
    $amount = round($base_para_impuesto + $tax, 2);
}

// 🔹 Sanitizador
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style><?= $style ?></style>
</head>
<body>

<!-- LOGO COMO MARCA DE AGUA SUPERIOR DERECHA -->
<?php if (!empty($logo_path)): ?>
  <div style="
    position: absolute;
    top: 15px;
    right: 30px;
    opacity: 0.15;
    z-index: 0;
  ">
    <img src="<?= $logo_path ?>" alt="Logo DISS" style="height:150px;">
  </div>
<?php endif; ?>

<!-- NOMBRE DE LA EMPRESA -->
<div style="
  text-align:center;
  margin-top:80px;
  z-index:1;
  position: relative;
  font-size:22px;
  font-weight:800;
  text-transform:uppercase;
  color:#000;
  letter-spacing:0.5px;
  line-height:1.3;
">
  DESARROLLO <span style="color:#004aad;">IMPLEMENTACIÓN</span> Y SUMINISTRO DE SISTEMAS S.A. DE C.V.
</div>

<!-- LÍNEA INFORMATIVA (PRESUPUESTO Y FECHA) -->
<div style="margin-top:30px; margin-left:40px; font-size:13px; color:#000;">
  <?php if (!empty($po_code)): ?>
    <p><strong>PRESUPUESTO N.º:</strong> <?= e($po_code) ?></p>
  <?php endif; ?>
  <p><strong>FECHA:</strong> <?= !empty($date_exp) ? date("d/m/Y", strtotime($date_exp)) : date("d/m/Y") ?></p>
</div>

<div class="content">

  <!-- INFO PRINCIPAL -->
  <div class="info-grid" style="margin-top:15px;">
    <div class="info-box">
      <p><strong>Cliente:</strong><br><?= e($cliente ?: '—') ?></p>
      <?php if (!empty($cliente_email)): ?>
        <p style="margin-top:4px;"><strong>Email:</strong> <?= e($cliente_email) ?></p>
      <?php endif; ?>
    </div>
  </div>

  <!-- TABLA DE PRODUCTOS -->
  <h3 class="section-title" style="margin-top:30px;">Detalle de productos</h3>
  <table class="items-table" style="width:100%; border-collapse:collapse; font-size:12px;">
    <thead style="background:#004aad; color:#fff;">
      <tr>
        <th style="padding:6px; text-align:center;">Cant.</th>
        <th style="padding:6px; text-align:center;">Unidad</th>
        <th style="padding:6px;">Descripción</th>
        <!-- 🔹 Agregadas sin quitar nada -->
        <th style="padding:6px; text-align:center;">Marca</th>
        <th style="padding:6px; text-align:center;">Modelo</th>
        <!-- 🔹 Fin agregado -->
        <th style="padding:6px; text-align:right;">Precio</th>
        <th style="padding:6px; text-align:right;">Desc %</th>
        <th style="padding:6px; text-align:right;">Total</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($items)): ?>
        <?php foreach($items as $it): 
          $q  = floatval($it['quantity'] ?? 0);
          $u  = (string)($it['unit'] ?? '');
          $ds = (string)($it['description'] ?? '');
          $ma = (string)($it['marca'] ?? '');   // 🔹 nuevo uso
          $mo = (string)($it['modelo'] ?? '');  // 🔹 nuevo uso
          $p  = floatval($it['price'] ?? 0);
          $d  = floatval($it['discount'] ?? 0);
          $lt = isset($it['line_total']) ? floatval($it['line_total']) : (($p - ($p * $d / 100.0)) * $q);
        ?>
        <tr style="border-bottom:1px solid #ddd;">
          <td style="padding:5px; text-align:center;"><?= number_format($q, 2) ?></td>
          <td style="padding:5px; text-align:center;"><?= e($u) ?></td>
          <td style="padding:5px;"><?= e($ds) ?></td>
          <!-- 🔹 celdas nuevas -->
          <td style="padding:5px; text-align:center;"><?= e($ma) ?></td>
          <td style="padding:5px; text-align:center;"><?= e($mo) ?></td>
          <!-- 🔹 fin agregado -->
          <td style="padding:5px; text-align:right;">$<?= number_format($p, 2) ?></td>
          <td style="padding:5px; text-align:right;"><?= number_format($d, 2) ?>%</td>
          <td style="padding:5px; text-align:right;">$<?= number_format($lt, 2) ?></td>
        </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="9" style="padding:8px; text-align:center; color:#666;">No hay productos en esta cotización.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- TOTALES + MÉTODO DE PAGO -->
  <div class="totals-box" style="margin-top:25px; width:40%; float:right;">
    <table style="width:100%; font-size:12px;">
      <tr>
        <td class="label">Subtotal:</td>
        <td class="value" style="text-align:right;">$<?= number_format($subtotal, 2) ?></td>
      </tr>

      <tr>
        <td class="label">
          Descuento<?= $discount_perc > 0 ? " (" . number_format($discount_perc, 2) . "%)" : "" ?>:
        </td>
        <td class="value" style="text-align:right;">$<?= number_format($discount, 2) ?></td>
      </tr>

      <tr>
        <td class="label">Impuesto (<?= number_format($tax_perc, 2) ?>%):</td>
        <td class="value" style="text-align:right;">$<?= number_format($tax, 2) ?></td>
      </tr>

      <tr><td colspan="2"><hr></td></tr>

      <tr>
        <td class="label" style="font-weight:bold;">Total:</td>
        <td class="value total" style="text-align:right; font-weight:bold;">$<?= number_format($amount, 2) ?></td>
      </tr>

      <tr>
        <td colspan="2" style="padding-top:8px; font-size:12px;">
          <strong>Método de pago:</strong> <?= e($metodo_pago ?: '—') ?>
        </td>
      </tr>
    </table>
  </div>

  <div style="clear:both;"></div>

  <!-- OBSERVACIONES -->
  <?php if (!empty(trim($remarks))): ?>
    <div class="remarks" style="margin-top:30px; font-size:12px;">
      <strong>Observaciones:</strong><br>
      <?= nl2br(e($remarks)) ?>
    </div>
  <?php endif; ?>

  <!-- PIE DE PÁGINA -->
  <div class="footer" style="
    margin-top:60px;
    text-align:center;
    font-size:11px;
    color:#333;
    border-top:1px solid #ccc;
    padding-top:10px;
    line-height:1.5;
  ">
    <?php if (!empty($address)): ?>
      <div><?= e($address) ?></div>
    <?php endif; ?>
    <?php if (!empty($contact) || !empty($email_empresa)): ?>
      <div>
        <?php if (!empty($contact)): ?>Tel: <?= e($contact) ?><?php endif; ?>
        <?php if (!empty($email_empresa)): ?><?= !empty($contact) ? ' | ' : '' ?>Email: <?= e($email_empresa) ?><?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

</div>
</body>
</html>
