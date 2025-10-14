<?php
// ==================================================
// 🔹 TEMPLATE PDF – MB CÓMPUTO
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
<div class="header">
  <h1 class="company-name">MB CÓMPUTO</h1>
  <h2 class="company-sub">Soluciones Tecnológicas y Soporte Profesional</h2>
</div>

<div class="header-line"></div>

<!-- DATOS ENCABEZADO DERECHO -->
<table class="header-info">
  <tr>
    <td class="spacer"></td>
    <td class="labels"><strong>FECHA</strong> &nbsp;&nbsp; <strong>FOLIO</strong></td>
  </tr>
  <tr>
    <td></td>
    <td class="values">
      <?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?>
      &nbsp;&nbsp;&nbsp;
      <?= htmlspecialchars($data['po_code'] ?? '') ?>
    </td>
  </tr>
  <tr>
    <td></td>
    <td class="delivery">Tiempo de entrega: 1 semana</td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 TABLA PRINCIPAL -->
<!-- ======================================= -->
<table class="productos">
  <thead>
    <tr>
      <th>NO.</th>
      <th>PRODUCTO / SERVICIO</th>
      <th>DESCRIPCIÓN</th>
      <th>UNIDAD</th>
      <th>CANTIDAD</th>
      <th>P.U.</th>
      <th>DESC. %</th>
      <th>IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach($items as $it): 
        $brand = htmlspecialchars($it['brand'] ?? '');
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
<!-- 🔹 TOTALES (formato integrado en tabla) -->
<!-- ======================================= -->
<table class="totals">
  <tr>
    <td class="label">SUBTOTAL:</td>
    <td class="amount">$<?= number_format($subtotal, 2) ?></td>
  </tr>
  <?php if ($discount_perc > 0 || $discount > 0): ?>
  <tr>
    <td class="label">
      DESCUENTO <?= $discount_perc > 0 ? "(" . number_format($discount_perc, 2) . "%)" : "" ?>:
    </td>
    <td class="amount">$<?= number_format($discount, 2) ?></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td class="label">I.V.A. (<?= number_format($tax_perc, 2) ?>%):</td>
    <td class="amount">$<?= number_format($tax, 2) ?></td>
  </tr>
  <tr class="divider">
    <td colspan="2"></td>
  </tr>
  <tr class="total">
    <td class="label"><strong>TOTAL:</strong></td>
    <td class="amount"><strong>$<?= number_format($amount, 2) ?></strong></td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 PIE DE PÁGINA -->
<!-- ======================================= -->
<div class="footer">
  <p class="bank-info">
    <strong>BANCO:</strong> BBVA &nbsp;&nbsp;
    <strong>No. DE CUENTA:</strong> 0123456789 &nbsp;&nbsp;
    <strong>CUENTA CLABE:</strong> 012180001234567891
  </p>

  <p class="contact">
    <strong>ATENCIÓN A CLIENTES</strong><br>
    soporte@mbcomputo.com.mx &nbsp;|&nbsp; Tel. (951) 000 0000
  </p>

  <p class="terms">
    Todos los precios están sujetos a cambios sin previo aviso.<br>
    Equipos y servicios garantizados conforme a políticas del fabricante.<br>
    Vigencia de cotización: 15 días.
  </p>
</div>

</body>
</html>
