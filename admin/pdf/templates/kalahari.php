<?php
// ==================================================
// 🔹 TEMPLATE PDF – KALAHARI DISTRIBUIDORA COMERCIAL
// Inspirado en su logo y estructura PDF original
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
      <?= htmlspecialchars($data['po_code']) ?>
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
      <th>IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach($items as $it): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td><?= htmlspecialchars($it['brand'] ?? '') ?></td>
      <td><?= htmlspecialchars($it['model'] ?? '') ?></td>
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
<!-- 🔹 TOTALES -->
<!-- ======================================= -->
<table class="totals">
  <tr>
    <td>SUBTOTAL:</td>
    <td>$<?= number_format($subtotal, 2) ?></td>
  </tr>
  <tr>
    <td>I.V.A. 16%:</td>
    <td>$<?= number_format($data['tax'] ?? 0, 2) ?></td>
  </tr>
  <tr class="total">
    <td><strong>TOTAL:</strong></td>
    <td><strong>$<?= number_format($data['amount'], 2) ?></strong></td>
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
