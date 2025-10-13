<?php
// ==================================================
// 🔹 TEMPLATE PDF – MB CÓMPUTO
// Estilo tecnológico, formal y profesional
// ==================================================
// Variables disponibles: $data, $items, $subtotal, $logo_path, $style
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
      <?= htmlspecialchars($data['po_code']) ?>
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
      <th>IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach($items as $it): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($it['brand'] ?? '') ?></td>
      <td class="desc"><?= nl2br(htmlspecialchars($it['description'])) ?></td>
      <td><?= htmlspecialchars($it['unit']) ?></td>
      <td class="num"><?= number_format($it['quantity'], 2) ?></td>
      <td class="num">$<?= number_format($it['price'], 2) ?></td>
      <td class="num">$<?= number_format($it['line_total'], 2) ?></td>
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
  <tr>
    <td class="label">I.V.A. 16%:</td>
    <td class="amount">$<?= number_format($data['tax'] ?? 0, 2) ?></td>
  </tr>
  <tr class="divider">
    <td colspan="2"></td>
  </tr>
  <tr class="total">
    <td class="label"><strong>TOTAL:</strong></td>
    <td class="amount"><strong>$<?= number_format($data['amount'], 2) ?></strong></td>
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
