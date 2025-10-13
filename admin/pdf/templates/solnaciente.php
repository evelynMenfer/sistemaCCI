<?php
// ==================================================
// 🔹 TEMPLATE PDF – OPERADORA COMERCIAL EL GRAN SURTIDOR DEL SOL NACIENTE S.A. DE C.V.
// Diseño formal con tonos verdes como el PDF original
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
<table class="company-header">
  <tr>
    <td colspan="2" class="company-title">
      OPERADORA COMERCIAL EL GRAN SURTIDOR DEL SOL NACIENTE SA DE CV
    </td>
  </tr>
  <tr>
    <td colspan="2" class="company-sub">
      Dirección: Sabinos #900 C, Reforma, Oaxaca de Juárez. <br>
      RFC: OCG171215C97 <br>
      Operadora de Infraestructura de Oaxaca
    </td>
  </tr>
  <tr>
    <td class="client-labels">
      <strong>Cliente</strong><br>
      <strong>Dirección</strong><br>
      <strong>RFC</strong>
    </td>
    <td class="client-values">
      <?= htmlspecialchars($data['cliente'] ?? '—') ?><br>
      <?= htmlspecialchars($data['address'] ?? '—') ?><br>
      <?= htmlspecialchars($data['rfc'] ?? '—') ?>
    </td>
  </tr>
  <tr>
    <td class="empty"></td>
    <td class="folio-block">
      <strong>FECHA:</strong> <?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?><br>
      <strong>FOLIO:</strong> <?= htmlspecialchars($data['po_code'] ?? '') ?>
    </td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 TABLA PRINCIPAL -->
<!-- ======================================= -->
<table class="productos">
  <thead>
    <tr>
      <th>SKU</th>
      <th>DESCRIPCIÓN</th>
      <th>UNIDAD</th>
      <th>CANTIDAD</th>
      <th>PRECIO POR UNIDAD</th>
      <th>IMPORTE</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach($items as $it): ?>
    <tr>
      <td><?= htmlspecialchars($it['sku'] ?? $i++) ?></td>
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
    <td class="label">SUBTOTAL</td>
    <td class="amount">$<?= number_format($subtotal, 2) ?></td>
  </tr>
  <tr>
    <td class="label">I.V.A 16 %</td>
    <td class="amount">$<?= number_format($data['tax'] ?? 0, 2) ?></td>
  </tr>
  <tr class="total">
    <td class="label">TOTAL</td>
    <td class="amount">$<?= number_format($data['amount'], 2) ?></td>
  </tr>
</table>

<!-- ======================================= -->
<!-- 🔹 PIE DE PÁGINA -->
<!-- ======================================= -->
<div class="footer">
  <p><strong>Nota:</strong><br>
  <?= nl2br(htmlspecialchars($data['nota'] ?? 'El tiempo de entrega 2-5 días hábiles partida 1 y 3, partida 2 de 2-3 semanas. La forma de pago es según el contrato del cliente.')) ?>
  </p>
</div>

</body>
</html>
