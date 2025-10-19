<?php
// ==================================================
// 🔹 TEMPLATE PDF – INGENIERÍA Y SERVICIOS CENIT S.A. DE C.V.
// Replica exacta del formato mostrado (2025)
// ==================================================

// --- Compatibilidad y datos seguros ---
$data      = $data ?? [];
$items     = isset($items) && is_array($items) ? $items : [];
$subtotal  = isset($subtotal) ? floatval($subtotal) : 0.0;

// ===============================
// 🧮 Subtotal seguro
// ===============================
if ($subtotal <= 0 && !empty($items)) {
    $subtotal = 0.0;
    foreach ($items as $it) {
        $price = isset($it['price']) ? floatval($it['price']) : 0.0;
        $qty   = isset($it['quantity']) ? floatval($it['quantity']) : 0.0;
        $disc  = isset($it['discount']) ? floatval($it['discount']) : 0.0;
        $subtotal += ($price - ($price * $disc / 100)) * $qty;
    }
}

// ===============================
// 🧾 Descuento, Impuesto y Total
// ===============================
$discount_perc  = isset($data['discount_perc']) ? floatval($data['discount_perc']) : 0.0;
$discount_monto = isset($data['discount']) ? floatval($data['discount']) : 0.0;
$tax_perc       = isset($data['tax_perc']) ? floatval($data['tax_perc']) : 16.0;
$tax            = isset($data['tax']) ? floatval($data['tax']) : 0.0;
$amount         = isset($data['amount']) ? floatval($data['amount']) : 0.0;

if ($discount_monto <= 0 && $discount_perc > 0 && $subtotal > 0)
    $discount_monto = round(($subtotal * $discount_perc) / 100, 2);

$base = max($subtotal - $discount_monto, 0);

if ($tax <= 0 && $tax_perc > 0)
    $tax = round($base * ($tax_perc / 100), 2);

if ($amount <= 0)
    $amount = round($base + $tax, 2);

// ===============================
// 🔧 Helpers de salida
// ===============================
$fmt = fn($n) => number_format((float)$n, 2);
$e   = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

// Campos de cabecera esperados por tu schema
$po_code       = $data['po_code']            ?? '—';
$cliente       = $data['cliente_nombre'] ?? '—';
$supplier_name = $data['supplier']           ?? '—';
$fecha_exp     = !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—';
$metodo_pago   = $data['metodo_pago']        ?? '—';
$remarks       = $data['remarks']            ?? '';
?>
<html>
<head>
<meta charset="UTF-8">
<style><?= $style ?></style>
</head>
<body>

<!-- ========================================================= -->
<!-- 🔹 ENCABEZADO -->
<!-- ========================================================= -->
<table class="header">
  <tr>
    <td class="logo">
      <?php if (!empty($logo_path)): ?>
        <img src="<?= $e($logo_path) ?>" alt="Logo <?= $e($data['name_empresa'] ?? '') ?>">
      <?php endif; ?>
    </td>
    <td class="title">
      <h2>COTIZACIÓN</h2>
    </td>
  </tr>
</table>
<br>
<!-- ========================================================= -->
<!-- 🔹 INFORMACIÓN EMPRESA -->
<!-- ========================================================= -->
<div class="company-info">
  <?php if (!empty($data['name_empresa'])): ?>
    <h3><?= $e($data['name_empresa']) ?></h3>
  <?php endif; ?>

  <?php if (!empty($data['address'])): ?>
    <p><?= $e($data['address']) ?></p>
  <?php endif; ?>

  <?php if (!empty($data['contact']) || !empty($data['email'])): ?>
    <p>
      <?php if (!empty($data['contact'])): ?>
        Tel: <?= $e($data['contact']) ?>
      <?php endif; ?>
      <?php if (!empty($data['contact']) && !empty($data['email'])): ?> | <?php endif; ?>
      <?php if (!empty($data['email'])): ?>
        Email: <?= $e($data['email']) ?>
      <?php endif; ?>
    </p>
  <?php endif; ?>
</div>

<hr class="line">
<br>
<!-- ========================================================= -->
<!-- 🔹 DATOS GENERALES -->
<!-- ========================================================= -->
<div class="quote-info">
  <h4>COTIZACIÓN: <?= $e($po_code) ?></h4>
  <p><strong>Cliente:</strong> <?= $e($cliente) ?></p>
  <p><strong>Fecha:</strong> <?= $e($fecha_exp) ?></p>
  <p><strong>Método de Pago:</strong> <?= $e($metodo_pago) ?></p>
</div>

<!-- ========================================================= -->
<!-- 🔹 TABLA PRINCIPAL -->
<!-- ========================================================= -->
<table class="productos">
  <thead>
    <tr>
      <th>Cant.</th>
      <th>Unidad</th>
      <th>Descripción</th>
      <th>Precio Unit.</th>
      <th>Desc %</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($items as $it):
      $qty   = isset($it['quantity']) ? floatval($it['quantity']) : 0.0;
      $unit  = $it['unit']        ?? '';
      $descL = $it['description'] ?? '';
      $price = isset($it['price']) ? floatval($it['price']) : 0.0;
      $dLine = isset($it['discount']) ? floatval($it['discount']) : 0.0;
      $line_total = isset($it['line_total']) ? floatval($it['line_total']) : (($price - ($price * $dLine / 100)) * $qty);
    ?>
    <tr>
      <td class="num"><?= $fmt($qty) ?></td>
      <td class="center"><?= $e($unit) ?></td>
      <td class="desc"><?= nl2br($e($descL)) ?></td>
      <td class="num">$<?= $fmt($price) ?></td>
      <td class="num"><?= $fmt($dLine) ?>%</td>
      <td class="num">$<?= $fmt($line_total) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ========================================================= -->
<!-- 🔹 TOTALES -->
<!-- ========================================================= -->
<table class="totals">
  <tr>
    <td>Subtotal</td>
    <td>$<?= $fmt($subtotal) ?></td>
  </tr>

  <?php if ($discount_perc > 0 || $discount_monto > 0): ?>
  <tr>
    <td>Descuento (<?= number_format($discount_perc, 0) ?>%)</td>
    <td>$<?= $fmt($discount_monto) ?></td>
  </tr>
  <?php endif; ?>

  <tr>
    <td>Impuesto (<?= number_format($tax_perc, 0) ?>%)</td>
    <td>$<?= $fmt($tax) ?></td>
  </tr>
  <tr class="total">
    <td><strong>TOTAL</strong></td>
    <td><strong>$<?= $fmt($amount) ?></strong></td>
  </tr>
</table>

<!-- ========================================================= -->
<!-- 🔹 OBSERVACIONES Y FIRMA -->
<!-- ========================================================= -->
<div class="footer">
  <p><strong>Observaciones:</strong> <?= nl2br($e($remarks)) ?></p>
  <p class="thanks">Gracias por su preferencia. — <?= $e($data['name_empresa'] ?? '') ?></p>
</div>

</body>
</html>
