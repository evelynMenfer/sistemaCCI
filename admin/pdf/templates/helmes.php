<?php
// ==================================================
// 🔹 TEMPLATE PDF – PROVEEDORA COMERCIAL HELMES S.A. DE C.V.
// Muestra NOTAS (remarks) y elimina el símbolo % del descuento
// ==================================================
$data      = $data      ?? [];
$items     = isset($items) && is_array($items) ? $items : [];
$subtotal  = isset($subtotal) ? floatval($subtotal) : 0;
$tax       = floatval($data['tax'] ?? 0);
$tax_perc  = floatval($data['tax_perc'] ?? 16);
$amount    = floatval($data['amount'] ?? 0);
$discount  = floatval($data['discount'] ?? 0);
$discount_perc = floatval($data['discount_perc'] ?? 0);
$remarks   = trim($data['remarks'] ?? ''); // ← NOTAS dinámicas
$cliente_email = trim($data['cliente_email'] ?? ''); // ← correo cliente

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
    <h1><?= htmlspecialchars($data['name_empresa'] ?? '') ?></h1>
    <strong>Dirección:</strong> <?= htmlspecialchars($data['address'] ?? '') ?><br>
    <strong>Correo:</strong> <?= htmlspecialchars($data['email'] ?? '') ?>
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
    <td colspan="2">
      <strong>ATENCIÓN:</strong> <?= htmlspecialchars($data['cliente_nombre'] ?? '—') ?><br>
      <strong>E-MAIL:</strong> <?= htmlspecialchars($cliente_email ?: '—') ?>
    </td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 TABLA PRINCIPAL (con Marca y Modelo visibles) -->
<!-- ======================================= -->
<table class="productos" width="100%" cellspacing="0" cellpadding="4" border="1" style="border-collapse: collapse; font-size: 10pt;">
  <thead style="background-color: #f2f2f2;">
    <tr>
      <th width="5%">#</th>
      <th width="14%">FECHA DE ENTREGA</th>
      <th width="26%">DESCRIPCIÓN</th>
      <th width="12%">MARCA</th>
      <th width="12%">MODELO</th>
      <th width="7%">UNIDAD</th>
      <th width="7%">CANT.</th>
      <th width="7%">DESC.</th>
      <th width="10%">P.U.</th>
      <th width="10%">IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $i = 1; 
    foreach($items as $it): 
        $qty     = floatval($it['quantity'] ?? 0);
        $price   = floatval($it['price'] ?? 0);
        $disc    = floatval($it['discount'] ?? 0);
        $lt      = isset($it['line_total']) ? floatval($it['line_total']) : (($price - ($price * $disc / 100)) * $qty);
        $marca   = trim($it['marca'] ?? '');
        $modelo  = trim($it['modelo'] ?? '');
        $unidad  = trim($it['unit'] ?? '');
        $desc    = trim($it['description'] ?? '');
    ?>
    <tr>
      <td align="center"><?= $i++ ?></td>
      <td align="center"><?= htmlspecialchars($it['fecha_entrega'] ?? '') ?></td>
      <td><?= nl2br(htmlspecialchars($desc)) ?></td>
      <td align="center"><?= htmlspecialchars($marca ?: '—') ?></td>
      <td align="center"><?= htmlspecialchars($modelo ?: '—') ?></td>
      <td align="center"><?= htmlspecialchars($unidad ?: '—') ?></td>
      <td align="right"><?= number_format($qty, 2) ?></td>
      <td align="right"><?= number_format($disc, 2) ?></td>
      <td align="right">$<?= number_format($price, 2) ?></td>
      <td align="right">$<?= number_format($lt, 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ======================================= -->
<!-- 🔹 TOTALES -->
<!-- ======================================= -->
<table class="totals">
  <tr>
    <td>FORMA DE PAGO:</td>
    <td class="right"><?= htmlspecialchars($data['metodo_pago'] ?? '') ?></td>
  </tr>
  <tr>
    <td>CUENTA DE BANCO:</td>
    <td class="right"><?= htmlspecialchars($data['ncuenta'] ?? '') ?> &nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td>CLABE INTERBANCARIA:</td>
    <td class="right"><?= htmlspecialchars($data['cuenta_clabe'] ?? '') ?></td>
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
    <td><strong>TOTAL:</strong></td>
    <td class="right"><strong>$<?= number_format($amount, 2) ?></strong></td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 PIE DE PÁGINA -->
<!-- ======================================= -->
<div class="footer">
  <?php if (!empty($data['nota']) || !empty($nota)): ?>
  <div class="note" style="margin-top:10px; padding:8px; border-top:1px solid #aaa; font-size:11.5px;">
    <?= nl2br(htmlspecialchars($data['nota'] ?? $nota)) ?>
  </div>
  <?php endif; ?> 
  
  <?php if (!empty($remarks)): ?>
    <p><strong>NOTAS:</strong><br><?= nl2br(htmlspecialchars($remarks)) ?></p>
  <?php endif; ?>
  <p><strong>HORARIO DE ATENCIÓN A CLIENTES:</strong> LUNES - SÁBADO 08:00 A 16:00</p>
  <p><strong>OAXACA DE JUÁREZ, OAXACA</strong></p>
</div>

</body>
</html>
