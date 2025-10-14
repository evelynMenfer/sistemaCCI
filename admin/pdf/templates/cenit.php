<?php
// ==================================================
// 🔹 TEMPLATE PDF – INGENIERÍA Y SERVICIOS CENIT S.A. DE C.V.
// Versión final – descuento global fijo y formato idéntico a Draper
// ==================================================

$data      = $data ?? [];
$items     = isset($items) && is_array($items) ? $items : [];
$subtotal  = isset($subtotal) ? floatval($subtotal) : 0.0;

// ==================================================
// 🧮 Cálculo seguro de subtotal
// ==================================================
if ($subtotal <= 0 && !empty($items)) {
    $subtotal = 0.0;
    foreach ($items as $it) {
        $price = floatval($it['price'] ?? 0);
        $qty   = floatval($it['quantity'] ?? 0);
        $disc  = floatval($it['discount'] ?? 0);
        $subtotal += ($price - ($price * $disc / 100)) * $qty;
    }
}

// ==================================================
// 💰 Descuento, Impuesto y Total – lógica corregida
// ==================================================
$discount_perc  = floatval($data['discount_perc'] ?? 0);
$discount_monto = floatval($data['discount'] ?? 0);
$tax_perc       = floatval($data['tax_perc'] ?? 16);
$tax            = floatval($data['tax'] ?? 0);
$amount         = floatval($data['amount'] ?? 0);

// 🔹 Si hay porcentaje pero no monto → calcularlo
if ($discount_monto <= 0 && $discount_perc > 0 && $subtotal > 0) {
    $discount_monto = round($subtotal * $discount_perc / 100, 2);
}

// 🔹 Si hay monto pero no porcentaje → calcularlo inversamente
if ($discount_perc <= 0 && $discount_monto > 0 && $subtotal > 0) {
    $discount_perc = round(($discount_monto / $subtotal) * 100, 2);
}

// 🔹 Calcular IVA y total si faltan
$base = max($subtotal - $discount_monto, 0);
if ($tax <= 0 && $tax_perc > 0) {
    $tax = round($base * ($tax_perc / 100), 2);
}
if ($amount <= 0) {
    $amount = round($base + $tax, 2);
}

// ==================================================
// 🔧 Helpers
// ==================================================
$fmt = fn($n) => number_format((float)$n, 2);
$e   = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

// Campos principales
$po_code   = $data['po_code'] ?? '—';
$cliente   = $data['cliente_cotizacion'] ?? '—';
$email_cli = $data['cliente_email'] ?? '';
$fecha     = !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—';
$remarks   = $data['remarks'] ?? '';
?>
<html>
<head>
<meta charset="UTF-8">
<style>
<?= $style ?>

body { font-family: Arial, sans-serif; color: #333; font-size: 12px; }
h2 { color: #003366; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #dddddd; padding: 6px; }
th { background: #f1f1f1; font-weight: bold; }
.header td { border: none; vertical-align: middle; }
.logo img { width: 70%; height: auto; }
.company-info { text-align: left; margin-top: 10px; }
.line { border: 0; border-top: 1px solid #ccc; margin: 10px 0; }
.totals td { border: none; padding: 4px 6px; }
.totals .total td { font-weight: bold; border-top: 1px solid #ccc; }
.footer { margin-top: 30px; }
.num { text-align: right; }
.center { text-align: center; }
.desc { text-align: left; }
.thanks { margin-top: 15px; text-align: center; font-style: italic; color: #003366; }
</style>
</head>
<body>

<!-- 🔹 ENCABEZADO -->
<table class="header">
  <tr>
    <td class="logo">
      <?php if (!empty($logo_path)): ?>
        <img src="<?= $logo_path ?>" alt="Logo CENIT">
      <?php endif; ?>
    </td>
    <td class="title" style="text-align:right;">
      <h2>COTIZACIÓN</h2>
    </td>
  </tr>
</table>

<!-- 🔹 INFORMACIÓN EMPRESA -->
<div class="company-info">
  <h3>INGENIERÍA Y SERVICIOS CENIT S.A. DE C.V.</h3>
  <p>Guadalupe Victoria #606, Col. Presidentes de México, Oaxaca de Juárez, Oax. C.P. 68274</p>
  <p>Tel: (951) 202 0060 | Email: ingenieriayservicioscenit13@gmail.com</p>
</div>

<hr class="line">

<!-- 🔹 DATOS GENERALES -->
<table style="margin-top:10px; border:none;">
  <tr>
    <td><strong>Cliente:</strong> <?= $e($cliente) ?></td>
    <td><strong>Email:</strong> <?= $e($email_cli) ?></td>
  </tr>
  <tr>
    <td><strong>Fecha:</strong> <?= $e($fecha) ?></td>
    <td><strong>Folio:</strong> <?= $e($po_code) ?></td>
  </tr>
  <tr>
    <td><strong>Método de Pago:</strong></td>
    <td></td>
  </tr>
</table>

<!-- 🔹 TABLA DE PRODUCTOS -->
<table class="productos" style="margin-top:15px;">
  <thead>
    <tr>
      <th>SKU</th>
      <th>DESCRIPCIÓN</th>
      <th>IMAGEN</th>
      <th>CANTIDAD</th>
      <th>P. UNITARIO</th>
      <th>DESC.</th>
      <th>IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($items as $it): 
      $name   = trim($it['name'] ?? '');
      $desc   = trim($it['description'] ?? '');
      $qty    = floatval($it['quantity'] ?? 0);
      $price  = floatval($it['price'] ?? 0);
      $disc   = floatval($it['discount'] ?? 0);
      $lt     = isset($it['line_total']) ? floatval($it['line_total']) : (($price - ($price * $disc / 100)) * $qty);
      $foto   = $it['foto_producto'] ?? '';
    ?>
    <tr>
      <td class="center"><?= $e($name) ?></td>
      <td class="desc"><?= nl2br($e($desc)) ?></td>
      <td class="center">
        <?php 
          if (!empty($foto)) {
              $foto_path = __DIR__ . '/../../uploads/items/' . basename($foto);
              if (file_exists($foto_path)) {
                  echo '<img src="../../uploads/items/' . basename($foto) . '" style="width:65px; height:auto;">';
              } else {
                  echo '—';
              }
          } else {
              echo '—';
          }
        ?>
      </td>
      <td class="num"><?= $fmt($qty) ?></td>
      <td class="num">$<?= $fmt($price) ?></td>
      <td class="num"><?= $fmt($disc) ?>%</td>
      <td class="num">$<?= $fmt($lt) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- 🔹 TOTALES -->
<table class="totals" style="width:40%; float:right; margin-top:20px;">
  <tr>
    <td>SUBTOTAL:</td>
    <td class="num">$<?= $fmt($subtotal) ?></td>
  </tr>
  <?php if ($discount_perc > 0 || $discount_monto > 0): ?>
  <tr>
    <td>DESCUENTO <?= $discount_perc > 0 ? '(' . $fmt($discount_perc) . '%)' : '' ?>:</td>
    <td class="num">$<?= $fmt($discount_monto) ?></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td>I.V.A. (<?= $fmt($tax_perc) ?>%):</td>
    <td class="num">$<?= $fmt($tax) ?></td>
  </tr>
  <tr class="total">
    <td><strong>TOTAL:</strong></td>
    <td class="num"><strong>$<?= $fmt($amount) ?></strong></td>
  </tr>
</table>

<div style="clear:both;"></div>

<!-- 🔹 OBSERVACIONES -->
<div class="footer">
  <p><strong>Observaciones:</strong><br><?= nl2br($e($remarks)) ?></p>
  <p class="thanks">Gracias por su preferencia — INGENIERÍA Y SERVICIOS CENIT S.A. DE C.V.</p>
</div>

</body>
</html>
