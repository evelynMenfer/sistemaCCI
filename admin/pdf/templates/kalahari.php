<?php
// ==================================================
// 🔹 TEMPLATE PDF – KALAHARI DISTRIBUIDORA COMERCIAL
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
<!-- 🔹 ENCABEZADO CON LOGO Y DATOS DE EMPRESA -->
<!-- ======================================= -->
<div class="header">
  <?php if (!empty($logo_path)): ?>
    <img src="<?= $logo_path ?>" alt="Logo <?= htmlspecialchars($data['name_empresa'] ?? '') ?>">
  <?php endif; ?>
</div>

<div class="company-info">
  <p>
    <strong>RFC:</strong> <?= htmlspecialchars($data['rfc'] ?? '') ?><br>
    <strong>Dirección:</strong> <?= htmlspecialchars($data['address'] ?? '') ?><br>
    <strong>Contacto:</strong> <?= htmlspecialchars($data['contact'] ?? '') ?><br>
    <strong>Atención:</strong> <?= htmlspecialchars($data['cperson'] ?? '') ?><br>
    <strong>Email:</strong> <?= htmlspecialchars($data['email'] ?? '') ?>
  </p>
</div>

<hr class="header-line">

<!-- ======================================= -->
<!-- 🔹 ENCABEZADO DE COTIZACIÓN -->
<!-- ======================================= -->
<table class="header-info">
  <tr>
    <td class="labels"><strong>FECHA:</strong></td>
    <td><?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?></td>
    <td class="labels"><strong>FOLIO:</strong></td>
    <td><?= htmlspecialchars($data['po_code'] ?? '') ?></td>
  </tr>
  <tr>
    <td class="labels"><strong>CLIENTE:</strong></td>
    <td colspan="3"><?= htmlspecialchars($data['cliente_nombre'] ?? '') ?></td>
  </tr>
  <tr>
  <td class="labels"><strong>ENTREGA:</strong></td>
  <td colspan="3">
    <?= !empty($data['fecha_entrega'])
        ? date("d/m/Y", strtotime($data['fecha_entrega']))
        : '—' ?>
  </td>
</tr>

</table>

<!-- ======================================= -->
<!-- 🔹 TABLA DE PRODUCTOS -->
<!-- ======================================= -->
<table class="productos">
  <thead>
    <tr>
      <th>ART.</th>
      <th>MARCA</th>
      <th>MODELO</th>
      <th>DESCRIPCIÓN</th>
      <th>UNIDAD</th>
      <th>CANT.</th>
      <th>P.U.</th>
      <th>DESC. %</th>
      <th>IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach($items as $it): 
        $brand = htmlspecialchars($it['marca'] ?? '');
        $model = htmlspecialchars($it['modelo'] ?? '');
        $desc  = nl2br(htmlspecialchars($it['description'] ?? ''));
        $unit  = htmlspecialchars($it['unit'] ?? '');
        $qty   = floatval($it['quantity'] ?? 0);
        $price = floatval($it['price'] ?? 0);
        $disc  = floatval($it['discount'] ?? 0);
        $line_total = isset($it['line_total']) 
            ? floatval($it['line_total']) 
            : (($price - ($price * $disc / 100)) * $qty);
    ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= $brand ?></td>
      <td><?= $model ?></td>
      <td class="desc"><?= $desc ?></td>
      <td><?= $unit ?></td>
      <td class="num"><?= number_format($qty, 2) ?></td>
      <td class="num">$<?= number_format($price, 2) ?></td>
      <td class="num"><?= number_format($disc, 2) ?>%</td>
      <td class="num">$<?= number_format($line_total, 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ======================================= -->
<!-- 🔹 TOTALES -->
<!-- ======================================= -->
<table class="totals">
  <tr><td>SUBTOTAL:</td><td>$<?= number_format($subtotal, 2) ?></td></tr>
  <?php if ($discount_perc > 0 || $discount > 0): ?>
  <tr><td>DESCUENTO <?= $discount_perc > 0 ? "(" . number_format($discount_perc, 2) . "%)" : "" ?>:</td>
      <td>$<?= number_format($discount, 2) ?></td></tr>
  <?php endif; ?>
  <tr><td>I.V.A. (<?= number_format($tax_perc, 2) ?>%):</td><td>$<?= number_format($tax, 2) ?></td></tr>
  <tr class="total"><td><strong>TOTAL:</strong></td><td><strong>$<?= number_format($amount, 2) ?></strong></td></tr>
</table>

<!-- ======================================= -->
<!-- 🔹 PIE DE PÁGINA CON DATOS Y NOTA -->
<!-- ======================================= -->
<div class="footer" style="margin-top: 25px; font-size: 12px;">

  <p class="bank-info" style="margin-bottom: 8px;">
    <strong>BANCO:</strong> <?= htmlspecialchars($data['banco'] ?? '') ?> &nbsp;&nbsp;
    <strong>No. DE CUENTA:</strong> <?= htmlspecialchars($data['ncuenta'] ?? '') ?> &nbsp;&nbsp;
    <strong>CUENTA CLABE:</strong> <?= htmlspecialchars($data['cuenta_clabe'] ?? '') ?>
  </p>

  <p class="contact" style="margin-bottom: 6px;">
    <strong>DIRECCIÓN:</strong> <?= htmlspecialchars($data['address'] ?? '') ?>
  </p>

  <?php if (!empty($data['nota']) || !empty($nota)): ?>
  <div class="note" style="margin-top:10px; padding:8px; border-top:1px solid #aaa; font-size:11.5px;">
    <?= nl2br(htmlspecialchars($data['nota'] ?? $nota)) ?>
  </div>
  <?php endif; ?>

</div>

</body>
</html>
