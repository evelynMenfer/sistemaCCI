<?php
// ==================================================
// 🔹 TEMPLATE PDF – KALAHARI DISTRIBUIDORA COMERCIAL
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

// 🔹 Calcular subtotal desde los ítems si no viene
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
<!-- 🔹 ENCABEZADO CON LOGO A TODO EL ANCHO -->
<!-- ======================================= -->
<div class="header">
  <?php if ($logo_path): ?>
    <img src="<?= $logo_path ?>" alt="Logo Kalahari">
  <?php endif; ?>
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
<!-- 🔹 TABLA PRINCIPAL DE PRODUCTOS -->
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
        $brand = htmlspecialchars($it['brand'] ?? '');
        $model = htmlspecialchars($it['model'] ?? '');
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
  <tr>
    <td>SUBTOTAL:</td>
    <td>$<?= number_format($subtotal, 2) ?></td>
  </tr>
  <?php if ($discount_perc > 0 || $discount > 0): ?>
  <tr>
    <td>DESCUENTO <?= $discount_perc > 0 ? "(" . number_format($discount_perc, 2) . "%)" : "" ?>:</td>
    <td>$<?= number_format($discount, 2) ?></td>
  </tr>
  <?php endif; ?>
  <tr>
    <td>I.V.A. (<?= number_format($tax_perc, 2) ?>%):</td>
    <td>$<?= number_format($tax, 2) ?></td>
  </tr>
  <tr class="total">
    <td><strong>TOTAL:</strong></td>
    <td><strong>$<?= number_format($amount, 2) ?></strong></td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 PIE DE PÁGINA -->
<!-- ======================================= -->
<div class="footer">
  <p class="bank-info">
    <strong>BANCO:</strong> HSBC &nbsp;&nbsp;
    <strong>No. DE CUENTA:</strong> 4065384711 &nbsp;&nbsp;
    <strong>CUENTA CLAVE:</strong> 4065384711
  </p>

  <p class="contact"><strong>ATENCIÓN A CLIENTES</strong><br>
  OAXACA DE JUÁREZ, OAXACA</p>

  <p class="terms">
    LA DISPONIBILIDAD DE LOS PRODUCTOS ES SALVO PREVIA VENTA,<br>
    PRECIOS SUJETOS A CAMBIO SIN PREVIO AVISO POR PARTE DEL FABRICANTE,<br>
    VIGENCIA DE COTIZACIÓN 15 DÍAS
  </p>
</div>

</body>
</html>
