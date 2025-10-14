<?php
// ==================================================
// 🔹 TEMPLATE PDF – COMERCIALIZADORA SUCCES
// Mantiene formato original, agrega descuento por producto y global
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

<!-- ======================================= -->
<!-- 🔹 ENCABEZADO -->
<!-- ======================================= -->
<table class="header">
  <tr>
    <td class="company-name" colspan="5">
      COMERCIALIZADORA SUCCES
    </td>
    <td class="header-right" colspan="3">
      OAXACA DE JUÁREZ, OAXACA<br>
      <span class="link">COTIZACIÓN: <?= htmlspecialchars($data['po_code'] ?? '—') ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="5" class="client">
      <strong>Atención:</strong> <?= htmlspecialchars($data['cliente_cotizacion'] ?? '—') ?><br>
      <strong>e-mail:</strong> <?= htmlspecialchars($data['email'] ?? '') ?>
    </td>
    <td colspan="3" class="fecha-box">
      <div class="fecha-title">FECHA</div>
      <div class="fecha-value"><?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?></div>
    </td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 TABLA PRINCIPAL (agregada columna DESC. %) -->
<!-- ======================================= -->
<table class="productos">
  <thead>
    <tr>
      <th>PARTIDA</th>
      <th>DESCRIPCIÓN</th>
      <th>IMAGEN</th>
      <th>UNIDAD</th>
      <th>CANTIDAD</th>
      <th>DESC. %</th>
      <th>P.U.</th>
      <th>IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach($items as $it): 
      $qty  = floatval($it['quantity'] ?? 0);
      $price = floatval($it['price'] ?? 0);
      $disc  = floatval($it['discount'] ?? 0);
      $lt = isset($it['line_total']) ? floatval($it['line_total']) : (($price - ($price * $disc / 100)) * $qty);
    ?>
    <tr>
      <td><?= $i++ ?></td>
      <td class="desc"><?= nl2br(htmlspecialchars($it['description'])) ?></td>
      <td class="center">—</td>
      <td><?= htmlspecialchars($it['unit']) ?></td>
      <td class="num"><?= number_format($qty, 2) ?></td>
      <td class="num"><?= number_format($disc, 2) ?>%</td>
      <td class="num">$<?= number_format($price, 2) ?></td>
      <td class="num">$<?= number_format($lt, 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ======================================= -->
<!-- 🔹 TOTALES (manteniendo estructura original) -->
<!-- ======================================= -->
<table class="totals">
  <tr>
    <td colspan="5" rowspan="7" class="payment-info">
      <p><strong>FORMA DE PAGO:</strong> CRÉDITO</p>
      <p><strong>CUENTA BBVA BANCOMER:</strong> 1549488070</p>
      <p><strong>CLABE INTERBANCARIA:</strong> 012610015494880704</p>
      <p><strong>TIEMPO DE ENTREGA:</strong> a convenir con el proveedor</p>
      <p><strong>NOTA:</strong> vigencia de la cotización 5 días</p>
      <p><strong>HORARIO DE ATENCIÓN A CLIENTES:</strong> Lunes a Sábado 08:00 a 20:00</p>
      <p><strong>TELÉFONO:</strong> (951) 215 2725</p>
      <p>5a Privada de Vicente Guerrero #112 Colonia Candiani, Oaxaca de Juárez, Oax. C.P. 68130</p>
    </td>
    <td class="label">SUBTOTAL</td>
    <td class="amount">$<?= number_format($subtotal, 2) ?></td>
  </tr>

  <?php if ($discount_perc > 0 || $discount > 0): ?>
  <tr>
    <td class="label">DESCUENTO <?= $discount_perc > 0 ? "(" . number_format($discount_perc, 2) . "%)" : "" ?></td>
    <td class="amount">$<?= number_format($discount, 2) ?></td>
  </tr>
  <?php endif; ?>

  <tr>
    <td class="label">I.V.A. (<?= number_format($tax_perc, 2) ?>%)</td>
    <td class="amount">$<?= number_format($tax, 2) ?></td>
  </tr>
  <tr class="total">
    <td class="label"><strong>TOTAL</strong></td>
    <td class="amount"><strong>$<?= number_format($amount, 2) ?></strong></td>
  </tr>
</table>

</body>
</html>
