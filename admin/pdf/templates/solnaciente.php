<?php
// ==================================================
// 🔹 TEMPLATE PDF – OPERADORA COMERCIAL EL GRAN SURTIDOR DEL SOL NACIENTE S.A. DE C.V.
// Final con cliente_cotizacion, nota dinámica, descuento con %, bordes suaves y SKU = name del ítem
// ==================================================
$data      = $data      ?? [];
$items     = isset($items) && is_array($items) ? $items : [];
$subtotal  = isset($subtotal) ? floatval($subtotal) : 0;
$tax       = floatval($data['tax'] ?? 0);
$tax_perc  = floatval($data['tax_perc'] ?? 16);
$amount    = floatval($data['amount'] ?? 0);
$discount  = floatval($data['discount'] ?? 0);
$discount_perc = floatval($data['discount_perc'] ?? 0);
$remarks   = trim($data['remarks'] ?? '');
$cliente   = trim($data['cliente_cotizacion'] ?? '—');

// 🔹 Calcular subtotal si no viene
if ($subtotal <= 0 && !empty($items)) {
    $subtotal = 0;
    foreach ($items as $it) {
        $price = floatval($it['price'] ?? 0);
        $qty   = floatval($it['quantity'] ?? 0);
        $disc  = floatval($it['discount'] ?? 0);
        $subtotal += ($price - ($price * $disc / 100)) * $qty;
    }
}

// 🔹 Recalcular totales si faltan
if ($amount <= 0) {
    $discount = ($discount_perc > 0) ? $subtotal * $discount_perc / 100 : $discount;
    $tax = ($tax_perc > 0) ? (($subtotal - $discount) * $tax_perc / 100) : $tax;
    $amount = ($subtotal - $discount) + $tax;
}
?>
<html>
<head>
<meta charset="UTF-8">
<style>
<?= $style ?>

/* ==== Ajustes de Sol Naciente ==== */
body {
  font-family: Arial, sans-serif;
  font-size: 12px;
  color: #333;
}
table {
  border-collapse: collapse;
  width: 100%;
}
th, td {
  border: 1px solid #dddddd;
  padding: 5px;
}
th {
  background: #f4f9f4;
  color: #006400;
}
tr:nth-child(even) {
  background: #fafafa;
}
.company-title {
  font-size: 14px;
  color: #006400;
  font-weight: bold;
}
.company-sub {
  color: #555;
}
.footer {
  margin-top: 40px;
  font-size: 12px;
  color: #333;
}
</style>
</head>
<body>

<!-- ======================================= -->
<!-- 🔹 ENCABEZADO -->
<!-- ======================================= -->
<table class="company-header">
  <tr>
    <td colspan="2" class="company-title">
      OPERADORA COMERCIAL EL GRAN SURTIDOR DEL SOL NACIENTE S.A. DE C.V.
    </td>
  </tr>
  <tr>
    <td colspan="2" class="company-sub">
      Dirección: Sabinos #900 C, Reforma, Oaxaca de Juárez.<br>
      RFC: OCG171215C97<br>
      Operadora de Infraestructura de Oaxaca
    </td>
  </tr>
  <tr>
    <td class="client-labels" style="width:30%; vertical-align:top;">
      <strong>Cliente</strong><br>
      <strong>Dirección</strong><br>
      <strong>RFC</strong>
    </td>
    <td class="client-values" style="width:70%; vertical-align:top;">
      <?= htmlspecialchars($cliente) ?><br>
      <br> <!-- Dirección vacía -->
      <br> <!-- RFC vacío -->
    </td>
  </tr>
  <tr>
    <td class="empty"></td>
    <td class="folio-block" style="text-align:right;">
      <strong>FECHA:</strong> <?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?><br>
      <strong>FOLIO:</strong> <?= htmlspecialchars($data['po_code'] ?? '') ?>
    </td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 TABLA PRINCIPAL -->
<!-- ======================================= -->
<table class="productos" style="margin-top:15px;">
  <thead>
    <tr>
      <th>SKU</th>
      <th>DESCRIPCIÓN</th>
      <th>UNIDAD</th>
      <th>CANTIDAD</th>
      <th>PRECIO UNITARIO</th>
      <th>DESC. %</th>
      <th>IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($items as $it): 
        $sku  = htmlspecialchars($it['name'] ?? '—'); // ← ahora muestra el campo name
        $desc = nl2br(htmlspecialchars($it['description'] ?? ''));
        $unit = htmlspecialchars($it['unit'] ?? '');
        $qty  = floatval($it['quantity'] ?? 0);
        $price = floatval($it['price'] ?? 0);
        $disc  = floatval($it['discount'] ?? 0);
        $line_total = isset($it['line_total']) 
            ? floatval($it['line_total']) 
            : (($price - ($price * $disc / 100)) * $qty);
    ?>
    <tr>
      <td style="text-align:center;"><?= $sku ?></td>
      <td><?= $desc ?></td>
      <td style="text-align:center;"><?= $unit ?></td>
      <td style="text-align:right;"><?= number_format($qty, 2) ?></td>
      <td style="text-align:right;">$<?= number_format($price, 2) ?></td>
      <td style="text-align:right;"><?= number_format($disc, 2) ?>%</td>
      <td style="text-align:right;">$<?= number_format($line_total, 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ======================================= -->
<!-- 🔹 TOTALES -->
<!-- ======================================= -->
<table class="totals" style="width:40%; float:right; margin-top:20px;">
  <tr>
    <td class="label" style="text-align:right;">SUBTOTAL:</td>
    <td class="amount" style="text-align:right;">$<?= number_format($subtotal, 2) ?></td>
  </tr>
  <?php if ($discount_perc > 0 || $discount > 0): ?>
  <tr>
    <td class="label" style="text-align:right;">
      DESCUENTO <?= $discount_perc > 0 ? "(" . number_format($discount_perc, 2) . "%)" : "" ?>:
    </td>
    <td class="amount" style="text-align:right;">$<?= number_format($discount, 2) ?></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="label" style="text-align:right;">I.V.A. (<?= number_format($tax_perc, 2) ?>%):</td>
    <td class="amount" style="text-align:right;">$<?= number_format($tax, 2) ?></td>
  </tr>
  <tr class="total" style="background:#f1f8f1;">
    <td class="label" style="text-align:right; font-weight:bold;">TOTAL:</td>
    <td class="amount" style="text-align:right; font-weight:bold;">$<?= number_format($amount, 2) ?></td>
  </tr>
</table>

<div style="clear:both;"></div>

<!-- ======================================= -->
<!-- 🔹 PIE DE PÁGINA -->
<!-- ======================================= -->
<div class="footer">
  <?php if (!empty($remarks)): ?>
    <p><strong>NOTA:</strong><br><?= nl2br(htmlspecialchars($remarks)) ?></p>
  <?php endif; ?>
  <p><strong>HORARIO DE ATENCIÓN A CLIENTES:</strong> Lunes a Viernes de 09:00 a 18:00</p>
  <p><strong>OAXACA DE JUÁREZ, OAXACA</strong></p>
</div>

</body>
</html>
