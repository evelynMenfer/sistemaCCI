<?php
// ==================================================
// 🔹 TEMPLATE PDF – INGENIERÍA Y SERVICIOS CENIT S.A. DE C.V.
// Replica exacta del formato mostrado (2025)
// ==================================================
?>
<html>
<head>
<meta charset="UTF-8">
<style><?= $style ?></style>
</head>
<body>

<!-- ========================================================= -->
<!-- 🔹 ENCABEZADO -->
<!-- ========================================================= -->
<table class="header">
  <tr>
    <td class="logo">
      <?php if (!empty($logo_path)): ?>
        <img src="<?= $logo_path ?>" alt="Logo CENIT">
      <?php endif; ?>
    </td>
    <td class="title">
      <h2>COTIZACIÓN</h2>
    </td>
  </tr>
</table>

<!-- ========================================================= -->
<!-- 🔹 INFORMACIÓN EMPRESA -->
<!-- ========================================================= -->
<div class="company-info">
  <h3>INGENIERÍA Y SERVICIOS CENIT S.A DE C.V.</h3>
  <p>Guadalupe Victoria #606, Colonia Presidentes de México, Oaxaca de Juárez, Oaxaca. C.P. 68274</p>
  <p>Tel: 951 202 0060 | Email: ingenieriayservicioscenit13@gmail.com</p>
</div>

<hr class="line">

<!-- ========================================================= -->
<!-- 🔹 DATOS GENERALES -->
<!-- ========================================================= -->
<div class="quote-info">
  <h4>COTIZACIÓN: <?= htmlspecialchars($data['po_code'] ?? '—') ?></h4>
  <p><strong>Cliente:</strong> <?= htmlspecialchars($data['cliente_cotizacion'] ?? '—') ?></p>
  <p><strong>Proveedor:</strong> <?= htmlspecialchars($data['supplier'] ?? '—') ?></p>
  <p><strong>Fecha:</strong> <?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?></p>
  <p><strong>Método de Pago:</strong> <?= htmlspecialchars($data['payment_method'] ?? '—') ?></p>
</div>

<!-- ========================================================= -->
<!-- 🔹 TABLA PRINCIPAL -->
<!-- ========================================================= -->
<table class="productos">
  <thead>
    <tr>
      <th>Cant.</th>
      <th>Unidad</th>
      <th>Descripción</th>
      <th>Precio Unit.</th>
      <th>Desc %</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($items as $it): ?>
    <tr>
      <td class="num"><?= number_format($it['quantity'], 2) ?></td>
      <td class="center"><?= htmlspecialchars($it['unit']) ?></td>
      <td class="desc"><?= nl2br(htmlspecialchars($it['description'])) ?></td>
      <td class="num">$<?= number_format($it['price'], 2) ?></td>
      <td class="num"><?= number_format($it['discount'] ?? 0, 2) ?>%</td>
      <td class="num">$<?= number_format($it['line_total'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- ========================================================= -->
<!-- 🔹 TOTALES -->
<!-- ========================================================= -->
<table class="totals">
  <tr><td>Subtotal</td><td>$<?= number_format($subtotal, 2) ?></td></tr>
  <tr><td>Descuento (<?= number_format($data['discount'] ?? 0, 0) ?>%)</td><td>$<?= number_format($data['discount_amount'] ?? 0, 2) ?></td></tr>
  <tr><td>Impuesto (16%)</td><td>$<?= number_format($data['tax'] ?? 0, 2) ?></td></tr>
  <tr class="total"><td><strong>TOTAL</strong></td><td><strong>$<?= number_format($data['amount'], 2) ?></strong></td></tr>
</table>

<!-- ========================================================= -->
<!-- 🔹 OBSERVACIONES Y FIRMA -->
<!-- ========================================================= -->
<div class="footer">
  <p><strong>Observaciones:</strong> <?= nl2br(htmlspecialchars($data['nota'] ?? '')) ?></p>
  <p class="thanks">Gracias por su preferencia. — INGENIERÍA Y SERVICIOS CENIT S.A. DE C.V.</p>
</div>

</body>
</html>
