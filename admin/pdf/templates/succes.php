<?php
// ==================================================
// 🔹 TEMPLATE PDF – COMERCIALIZADORA SUCCES
// Estructura fiel al PDF original, rediseñado elegante
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
<table class="header">
  <tr>
    <td class="company-name" colspan="5">
      COMERCIALIZADORA SUCCES
    </td>
    <td class="header-right" colspan="2">
      OAXACA DE JUÁREZ, OAXACA<br>
      <span class="link">COTIZACIÓN: <?= htmlspecialchars($data['po_code'] ?? '—') ?></span>
    </td>
  </tr>
  <tr>
    <td colspan="5" class="client">
      <strong>Atención:</strong> <?= htmlspecialchars($data['cliente_cotizacion'] ?? '—') ?><br>
      <strong>e-mail:</strong> <?= htmlspecialchars($data['email'] ?? '') ?>
    </td>
    <td colspan="2" class="fecha-box">
      <div class="fecha-title">FECHA</div>
      <div class="fecha-value"><?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?></div>
    </td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 TABLA PRINCIPAL -->
<!-- ======================================= -->
<table class="productos">
  <thead>
    <tr>
      <th>PARTIDA</th>
      <th>DESCRIPCIÓN</th>
      <th>IMAGEN</th>
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
      <td class="desc"><?= nl2br(htmlspecialchars($it['description'])) ?></td>
      <td class="center">—</td>
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
    <td colspan="5" rowspan="6" class="payment-info">
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
  <tr>
    <td class="label">I.V.A. 16%</td>
    <td class="amount">$<?= number_format($data['tax'] ?? 0, 2) ?></td>
  </tr>
  <tr class="total">
    <td class="label"><strong>TOTAL</strong></td>
    <td class="amount"><strong>$<?= number_format($data['amount'], 2) ?></strong></td>
  </tr>
</table>

</body>
</html>
