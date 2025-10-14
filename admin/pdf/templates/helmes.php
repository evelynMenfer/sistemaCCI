<?php
// ==================================================
// 🔹 TEMPLATE PDF – PROVEEDORA COMERCIAL HELMES S.A. DE C.V.
// Mantiene el formato original, se agrega columna de descuento y totales actualizados
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
<div class="header">
  <?php if ($logo_path): ?>
    <img src="<?= $logo_path ?>" alt="Logo Helmes" class="logo">
  <?php endif; ?>
  <div class="header-info">
    <h1>PROVEEDORA COMERCIAL HELMES S.A. DE C.V.</h1>
    <p>Emilio Carranza 811 Int. 4, Reforma, Oaxaca de Juárez, Oaxaca C.P. 68050</p>
    <p><strong>Correo:</strong> carolina.ortega@helmes.mx</p>
  </div>
</div>

<hr class="divider">

<!-- DATOS SUPERIORES -->
<table class="top-info">
  <tr>
    <td><strong>FECHA:</strong> <?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?></td>
    <td><strong>COTIZACIÓN:</strong> <?= htmlspecialchars($data['po_code'] ?? '—') ?></td>
  </tr>
  <tr>
    <td colspan="2"><strong>ATENCIÓN:</strong> <?= htmlspecialchars($data['cliente_cotizacion'] ?? 'ING. CAROLINA ORTEGA') ?></td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 TABLA PRINCIPAL (se agrega columna DESC. %) -->
<!-- ======================================= -->
<table class="productos">
  <thead>
    <tr>
      <th>#</th>
      <th>FECHA DE ENTREGA</th>
      <th>DESCRIPCIÓN, MARCA Y MODELO</th>
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
      <td><?= htmlspecialchars($it['fecha_entrega'] ?? '') ?></td>
      <td class="desc"><?= nl2br(htmlspecialchars($it['description'])) ?></td>
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
<!-- 🔹 TOTALES (se agrega línea de descuento global) -->
<!-- ======================================= -->
<table class="totals">
  <tr>
    <td>FORMA DE PAGO:</td>
    <td class="right">A CONVENIR CON EL CLIENTE</td>
  </tr>
  <tr>
    <td>CUENTA DE BANCO:</td>
    <td class="right">BANAMEX 9889602164</td>
  </tr>
  <tr>
    <td>CLABE INTERBANCARIA:</td>
    <td class="right">002610701764615091</td>
  </tr>
  <tr>
    <td>SUBTOTAL:</td>
    <td class="right">$<?= number_format($subtotal, 2) ?></td>
  </tr>

  <?php if ($discount_perc > 0 || $discount > 0): ?>
  <tr>
    <td>DESCUENTO <?= $discount_perc > 0 ? "(" . number_format($discount_perc, 2) . "%)" : "" ?>:</td>
    <td class="right">$<?= number_format($discount, 2) ?></td>
  </tr>
  <?php endif; ?>

  <tr>
    <td>I.V.A. (<?= number_format($tax_perc, 2) ?>%):</td>
    <td class="right">$<?= number_format($tax, 2) ?></td>
  </tr>
  <tr class="total">
    <td>TOTAL:</td>
    <td class="right">$<?= number_format($amount, 2) ?></td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 PIE DE PÁGINA -->
<!-- ======================================= -->
<div class="footer">
  <p><strong>TIEMPO DE ENTREGA:</strong> SEGÚN DISPONIBILIDAD AL MOMENTO DE FINCAR LA COMPRA</p>
  <p><strong>NOTA:</strong></p>
  <p>HORARIO DE ATENCIÓN A CLIENTES: LUNES - SÁBADO 08:00 A 16:00</p>
  <p><strong>OAXACA DE JUÁREZ, OAXACA</strong></p>
</div>

</body>
</html>
