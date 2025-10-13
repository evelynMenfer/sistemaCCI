<?php
// ==================================================
// 🔹 TEMPLATE PDF – PROVEEDORA COMERCIAL HELMES S.A. DE C.V.
// Estilo corporativo inspirado en su logotipo (negro, gris, azul, naranja)
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
<!-- 🔹 TABLA PRINCIPAL -->
<!-- ======================================= -->
<table class="productos">
  <thead>
    <tr>
      <th>#</th>
      <th>FECHA DE ENTREGA</th>
      <th>DESCRIPCIÓN, MARCA Y MODELO</th>
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
      <td><?= htmlspecialchars($it['fecha_entrega'] ?? '') ?></td>
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
  <tr>
    <td>I.V.A. 16%:</td>
    <td class="right">$<?= number_format($data['tax'] ?? 0, 2) ?></td>
  </tr>
  <tr class="total">
    <td>TOTAL:</td>
    <td class="right">$<?= number_format($data['amount'], 2) ?></td>
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
