<?php
// ==================================================
// 🔹 TEMPLATE PDF – OPERADORA COMERCIAL EL GRAN SURTIDOR DEL SOL NACIENTE S.A. DE C.V.
// Actualizado con descuento por producto y descuento global
// ==================================================
$data      = $data      ?? [];
$items     = isset($items) && is_array($items) ? $items : [];
$subtotal  = isset($subtotal) ? floatval($subtotal) : 0;
$tax       = floatval($data['tax'] ?? 0);
$tax_perc  = floatval($data['tax_perc'] ?? 16);
$amount    = floatval($data['amount'] ?? 0);
$discount  = floatval($data['discount'] ?? 0);
$discount_perc = floatval($data['discount_perc'] ?? 0);

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
<style><?= $style ?></style>
</head>
<body>

<!-- ======================================= -->
<!-- 🔹 ENCABEZADO -->
<!-- ======================================= -->
<table class="company-header">
  <tr>
    <td colspan="2" class="company-title" style="color:#006400; font-weight:bold;">
      OPERADORA COMERCIAL EL GRAN SURTIDOR DEL SOL NACIENTE S.A. DE C.V.
    </td>
  </tr>
  <tr>
    <td colspan="2" class="company-sub" style="font-size:12px;">
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
      <?= htmlspecialchars($data['cliente'] ?? '—') ?><br>
      <?= htmlspecialchars($data['address'] ?? '—') ?><br>
      <?= htmlspecialchars($data['rfc'] ?? '—') ?>
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
<table class="productos" style="width:100%; border-collapse:collapse; margin-top:15px; font-size:12px;">
  <thead>
    <tr style="background:#d9ead3;">
      <th style="border:1px solid #ccc; padding:5px;">SKU</th>
      <th style="border:1px solid #ccc; padding:5px;">DESCRIPCIÓN</th>
      <th style="border:1px solid #ccc; padding:5px;">UNIDAD</th>
      <th style="border:1px solid #ccc; padding:5px;">CANTIDAD</th>
      <th style="border:1px solid #ccc; padding:5px;">PRECIO UNITARIO</th>
      <th style="border:1px solid #ccc; padding:5px;">DESC. %</th>
      <th style="border:1px solid #ccc; padding:5px;">IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach($items as $it): 
        $sku  = htmlspecialchars($it['sku'] ?? $i++);
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
      <td style="border:1px solid #ccc; padding:5px; text-align:center;"><?= $sku ?></td>
      <td style="border:1px solid #ccc; padding:5px;"><?= $desc ?></td>
      <td style="border:1px solid #ccc; padding:5px; text-align:center;"><?= $unit ?></td>
      <td style="border:1px solid #ccc; padding:5px; text-align:right;"><?= number_format($qty, 2) ?></td>
      <td style="border:1px solid #ccc; padding:5px; text-align:right;">$<?= number_format($price, 2) ?></td>
      <td style="border:1px solid #ccc; padding:5px; text-align:right;"><?= number_format($disc, 2) ?>%</td>
      <td style="border:1px solid #ccc; padding:5px; text-align:right;">$<?= number_format($line_total, 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ======================================= -->
<!-- 🔹 TOTALES -->
<!-- ======================================= -->
<table class="totals" style="width:40%; float:right; margin-top:20px; font-size:12px;">
  <tr>
    <td class="label" style="text-align:right; padding:4px;">SUBTOTAL:</td>
    <td class="amount" style="text-align:right; padding:4px;">$<?= number_format($subtotal, 2) ?></td>
  </tr>
  <?php if ($discount_perc > 0 || $discount > 0): ?>
  <tr>
    <td class="label" style="text-align:right; padding:4px;">
      DESCUENTO <?= $discount_perc > 0 ? "(" . number_format($discount_perc, 2) . "%)" : "" ?>:
    </td>
    <td class="amount" style="text-align:right; padding:4px;">$<?= number_format($discount, 2) ?></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="label" style="text-align:right; padding:4px;">I.V.A. (<?= number_format($tax_perc, 2) ?>%):</td>
    <td class="amount" style="text-align:right; padding:4px;">$<?= number_format($tax, 2) ?></td>
  </tr>
  <tr class="total" style="background:#e2f0d9;">
    <td class="label" style="text-align:right; font-weight:bold; padding:6px;">TOTAL:</td>
    <td class="amount" style="text-align:right; font-weight:bold; padding:6px;">$<?= number_format($amount, 2) ?></td>
  </tr>
</table>

<div style="clear:both;"></div>

<!-- ======================================= -->
<!-- 🔹 PIE DE PÁGINA -->
<!-- ======================================= -->
<div class="footer" style="margin-top:50px; font-size:12px;">
  <p><strong>Nota:</strong><br>
    <?= nl2br(htmlspecialchars($data['nota'] ?? 'El tiempo de entrega 2-5 días hábiles partida 1 y 3, partida 2 de 2-3 semanas. La forma de pago es según el contrato del cliente.')) ?>
  </p>
</div>

</body>
</html>
