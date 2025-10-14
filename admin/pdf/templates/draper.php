<?php
// ==================================================
// 🔹 TEMPLATE PDF – DRAPER
// Mismo formato original, con descuento por producto y global
// ==================================================
$data      = $data      ?? [];
$items     = isset($items) && is_array($items) ? $items : [];
$subtotal  = isset($subtotal) ? floatval($subtotal) : 0;
$tax       = floatval($data['tax'] ?? 0);
$tax_perc  = floatval($data['tax_perc'] ?? 16);
$amount    = floatval($data['amount'] ?? 0);
$discount  = floatval($data['discount'] ?? 0);
$discount_perc = floatval($data['discount_perc'] ?? 0);

// 🔹 Calcular subtotal si no vino
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

<!-- ENCABEZADO -->
<?php if ($logo_path): ?>
  <div style="text-align:center; margin-bottom:10px;">
    <img src="<?= $logo_path ?>" alt="Logo Draper" style="max-height:90px;">
  </div>
<?php endif; ?>

<!-- FECHA, CLIENTE Y FOLIO -->
<table style="width:100%; font-size:12px; margin-bottom:10px;">
  <tr>
    <td style="width:40%;"><strong>Fecha:</strong></td>
    <td style="width:30%;"><strong>Cliente:</strong></td>
    <td style="width:30%; text-align:right;"><strong>Folio:</strong></td>
  </tr>
  <tr>
    <td><?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?></td>
    <td><?= htmlspecialchars($data['cliente_cotizacion'] ?? '—') ?></td>
    <td style="text-align:right;"><?= htmlspecialchars($data['po_code'] ?? '') ?></td>
  </tr>
</table>

<!-- TABLA PRINCIPAL -->
<table class="productos" style="width:100%; border-collapse:collapse; font-size:12px; margin-top:10px;">
  <thead>
    <tr style="background:#f2f2f2;">
      <th style="border:1px solid #ccc; padding:6px;">No.</th>
      <th style="border:1px solid #ccc; padding:6px;">Cod.</th>
      <th style="border:1px solid #ccc; padding:6px;">Descripción</th>
      <th style="border:1px solid #ccc; padding:6px;">Cantidad</th>
      <th style="border:1px solid #ccc; padding:6px;">Unidad</th>
      <th style="border:1px solid #ccc; padding:6px;">Desc. %</th>
      <th style="border:1px solid #ccc; padding:6px;">Valor Unitario</th>
      <th style="border:1px solid #ccc; padding:6px;">Valor Total</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach($items as $it): 
      $q = floatval($it['quantity'] ?? 0);
      $p = floatval($it['price'] ?? 0);
      $d = floatval($it['discount'] ?? 0);
      $lt = isset($it['line_total']) ? floatval($it['line_total']) : (($p - ($p * $d / 100)) * $q);
    ?>
    <tr>
      <td style="border:1px solid #ccc; padding:6px; text-align:center;"><?= $i++ ?></td>
      <td style="border:1px solid #ccc; padding:6px;"><?= htmlspecialchars($it['item_code'] ?? '') ?></td>
      <td style="border:1px solid #ccc; padding:6px;"><?= nl2br(htmlspecialchars($it['description'])) ?></td>
      <td style="border:1px solid #ccc; padding:6px; text-align:right;"><?= number_format($q, 2) ?></td>
      <td style="border:1px solid #ccc; padding:6px; text-align:center;"><?= htmlspecialchars($it['unit']) ?></td>
      <td style="border:1px solid #ccc; padding:6px; text-align:right;"><?= number_format($d, 2) ?>%</td>
      <td style="border:1px solid #ccc; padding:6px; text-align:right;">$<?= number_format($p, 2) ?></td>
      <td style="border:1px solid #ccc; padding:6px; text-align:right;">$<?= number_format($lt, 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- TOTALES -->
<table style="width:40%; float:right; font-size:12px; margin-top:15px;">
  <tr>
    <td style="text-align:right; padding:4px;"><strong>Subtotal:</strong></td>
    <td style="text-align:right; padding:4px;">$<?= number_format($subtotal, 2) ?></td>
  </tr>

  <?php if ($discount > 0 || $discount_perc > 0): ?>
  <tr>
    <td style="text-align:right; padding:4px;">Descuento <?= $discount_perc > 0 ? '(' . number_format($discount_perc, 2) . '%)' : '' ?>:</td>
    <td style="text-align:right; padding:4px;">$<?= number_format($discount, 2) ?></td>
  </tr>
  <?php endif; ?>

  <tr>
    <td style="text-align:right; padding:4px;">I.V.A. (<?= number_format($tax_perc, 2) ?>%):</td>
    <td style="text-align:right; padding:4px;">$<?= number_format($tax, 2) ?></td>
  </tr>
  <tr>
    <td style="text-align:right; padding:4px; font-weight:bold;">Total:</td>
    <td style="text-align:right; padding:4px; font-weight:bold;">$<?= number_format($amount, 2) ?></td>
  </tr>
</table>

<div style="clear:both;"></div>

<!-- DATOS INFERIORES -->
<div class="footer-block" style="margin-top:40px; font-size:12px;">
  <p><strong><?= strtoupper(htmlspecialchars($data['name_empresa'] ?? 'DRAPER')) ?></strong></p>
  <p>Atención: <?= htmlspecialchars($data['cperson'] ?? '—') ?></p>
  <p><?= strtoupper(htmlspecialchars($data['address'] ?? '')) ?></p>
  <p>Teléfono: <?= htmlspecialchars($data['contact'] ?? '') ?> &nbsp;&nbsp;|&nbsp;&nbsp; Correo: <?= htmlspecialchars($data['email'] ?? '') ?></p>
</div>

</body>
</html>
