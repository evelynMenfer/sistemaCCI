<?php
// Template DRAPER - réplica exacta del formato en PDF compartido
// Variables disponibles: $data, $items, $subtotal, $logo_path, $style
?>
<html>
<head>
<meta charset="UTF-8">
<style><?= $style ?></style>
</head>
<body>

<!-- ENCABEZADO -->
<?php if ($logo_path): ?>
  <div style="text-align:center; margin-bottom:10px;">
    <img src="<?= $logo_path ?>" alt="Logo Draper" style="max-height:90px;">
  </div>
<?php endif; ?>

<!-- FECHA Y FOLIO -->
<table style="width:100%; font-size:12px; margin-bottom:10px;">
  <tr>
    <td style="width:70%;"><strong>Fecha:</strong> <?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?></td>
    <td style="text-align:right;"><strong>Folio:</strong></td>
  </tr>
  <tr>
    <td style="width:70%;"><?= htmlspecialchars($data['date_exp'] ?? '') ?></td>
    <td style="text-align:right;"><?= htmlspecialchars($data['po_code']) ?></td>
  </tr>
</table>

<!-- TABLA PRINCIPAL -->
<table class="productos" style="width:100%; border-collapse:collapse; font-size:12px; margin-top:10px;">
  <thead>
    <tr style="background:#f2f2f2;">
      <th style="border:1px solid #ccc; padding:6px;">No.</th>
      <th style="border:1px solid #ccc; padding:6px;">Cod.</th>
      <th style="border:1px solid #ccc; padding:6px;">Descripción</th>
      <th style="border:1px solid #ccc; padding:6px;">Cantidad</th>
      <th style="border:1px solid #ccc; padding:6px;">Unidad</th>
      <th style="border:1px solid #ccc; padding:6px;">Valor Unitario</th>
      <th style="border:1px solid #ccc; padding:6px;">Valor Total</th>
    </tr>
  </thead>
  <tbody>
    <?php $i=1; foreach($items as $it): ?>
    <tr>
      <td style="border:1px solid #ccc; padding:6px; text-align:center;"><?= $i++ ?></td>
      <td style="border:1px solid #ccc; padding:6px;"><?= htmlspecialchars($it['item_code'] ?? '') ?></td>
      <td style="border:1px solid #ccc; padding:6px;">
        <?= nl2br(htmlspecialchars($it['description'])) ?>
      </td>
      <td style="border:1px solid #ccc; padding:6px; text-align:right;"><?= number_format($it['quantity'], 2) ?></td>
      <td style="border:1px solid #ccc; padding:6px; text-align:center;"><?= htmlspecialchars($it['unit']) ?></td>
      <td style="border:1px solid #ccc; padding:6px; text-align:right;">$<?= number_format($it['price'], 2) ?></td>
      <td style="border:1px solid #ccc; padding:6px; text-align:right;">$<?= number_format($it['line_total'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- TOTALES -->
<table style="width:40%; float:right; font-size:12px; margin-top:15px;">
  <tr>
    <td style="text-align:right; padding:4px;"><strong>Subtotal:</strong></td>
    <td style="text-align:right; padding:4px;">$<?= number_format($subtotal, 2) ?></td>
  </tr>
  <tr>
    <td style="text-align:right; padding:4px;">I.V.A.:</td>
    <td style="text-align:right; padding:4px;">$<?= number_format($data['tax'] ?? 0, 2) ?></td>
  </tr>
  <tr>
    <td style="text-align:right; padding:4px; font-weight:bold;">Total:</td>
    <td style="text-align:right; padding:4px; font-weight:bold;">$<?= number_format($data['amount'], 2) ?></td>
  </tr>
</table>

<div style="clear:both;"></div>

<!-- DATOS INFERIORES -->
<div class="footer-block" style="margin-top:40px; font-size:12px;">
  <p><strong><?= strtoupper(htmlspecialchars($data['name_empresa'])) ?></strong></p>
  <p>Atención: <?= htmlspecialchars($data['cliente_cotizacion'] ?? '—') ?></p>
  <p><?= strtoupper(htmlspecialchars($data['address'])) ?></p>
  <p>Teléfono: <?= htmlspecialchars($data['contact']) ?> &nbsp;&nbsp;|&nbsp;&nbsp; Correo: <?= htmlspecialchars($data['email']) ?></p>
</div>

</body>
</html>
