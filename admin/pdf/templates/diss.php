<?php
// Template DISS - encabezado con logo marca de agua y método de pago dentro del bloque de totales
?>
<html>
<head>
<meta charset="UTF-8">
<style><?= $style ?></style>
</head>
<body>

<!-- LOGO COMO MARCA DE AGUA SUPERIOR DERECHA -->
<?php if ($logo_path): ?>
  <div style="
    position: absolute;
    top: 15px;
    right: 30px;
    opacity: 0.15;
    z-index: 0;
  ">
    <img src="<?= $logo_path ?>" alt="Logo DISS" style="height:150px;">
  </div>
<?php endif; ?>

<!-- NOMBRE DE LA EMPRESA -->
<div style="
  text-align:center;
  margin-top:80px;
  z-index:1;
  position: relative;
  font-size:22px;
  font-weight:800;
  text-transform:uppercase;
  color:#000;
  letter-spacing:0.5px;
  line-height:1.3;
">
  DESARROLLO <span style="color:#004aad;">IMPLEMENTACION</span> Y SUMINISTRO DE SISTEMAS S.A. DE C.V.
</div>

<!-- LÍNEA INFORMATIVA (PRESUPUESTO Y FECHA) -->
<div style="margin-top:30px; margin-left:40px; font-size:13px; color:#000;">
  <?php if (!empty($data['po_code'])): ?>
    <p><strong>PRESUPUESTO N.º:</strong> <?= htmlspecialchars($data['po_code']) ?></p>
  <?php endif; ?>
  <p><strong>FECHA:</strong> <?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : date("d/m/Y") ?></p>
</div>

<div class="content">

  <!-- INFO PRINCIPAL -->
  <div class="info-grid" style="margin-top:15px;">
    <div class="info-box">
      <p><strong>Cliente:</strong><br><?= htmlspecialchars($data['cliente_cotizacion'] ?? '—') ?></p>
    </div>
  </div>

  <!-- TABLA DE PRODUCTOS -->
  <h3 class="section-title">Detalle de productos</h3>
  <table class="items-table">
    <thead>
      <tr>
        <th>Cant.</th>
        <th>Unidad</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Desc %</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($items as $it): ?>
        <tr>
          <td><?= number_format($it['quantity'], 2) ?></td>
          <td><?= htmlspecialchars($it['unit']) ?></td>
          <td><?= htmlspecialchars($it['description']) ?></td>
          <td class="num">$<?= number_format($it['price'], 2) ?></td>
          <td class="num"><?= number_format($it['discount'], 2) ?>%</td>
          <td class="num">$<?= number_format($it['line_total'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- TOTALES + MÉTODO DE PAGO -->
  <div class="totals-box" style="margin-top:20px;">
    <table>
      <tr><td class="label">Subtotal:</td><td class="value">$<?= number_format($subtotal, 2) ?></td></tr>
      <tr><td class="label">Descuento (<?= $data['discount_perc'] ?? 0 ?>%):</td><td class="value">$<?= number_format($data['discount'] ?? 0, 2) ?></td></tr>
      <tr><td class="label">Impuesto (<?= $data['tax_perc'] ?? 0 ?>%):</td><td class="value">$<?= number_format($data['tax'] ?? 0, 2) ?></td></tr>
      <tr><td colspan="2"><hr></td></tr>
      <tr>
        <td class="label"><strong>Total:</strong></td>
        <td class="value total">$<?= number_format($data['amount'], 2) ?></td>
      </tr>
      <!-- MÉTODO DE PAGO DEBAJO DEL TOTAL -->
      <tr>
        <td colspan="2" style="padding-top:8px; font-size:12px;">
          <strong>Método de pago:</strong>
          <?= htmlspecialchars($data['metodo_pago'] ?? '—') ?>
        </td>
      </tr>
    </table>
  </div>

  <div style="clear:both;"></div>

  <!-- OBSERVACIONES -->
  <?php if (!empty($data['remarks'])): ?>
    <div class="remarks">
      <strong>Observaciones:</strong><br>
      <?= nl2br(htmlspecialchars($data['remarks'])) ?>
    </div>
  <?php endif; ?>

  <!-- PIE DE PÁGINA -->
  <div class="footer" style="
    margin-top:60px;
    text-align:center;
    font-size:11px;
    color:#333;
    border-top:1px solid #ccc;
    padding-top:10px;
    line-height:1.5;
  ">
    <?php if (!empty($data['address'])): ?>
      <div><?= htmlspecialchars($data['address']) ?></div>
    <?php endif; ?>
    <?php if (!empty($data['contact']) || !empty($data['email'])): ?>
      <div>
        <?php if (!empty($data['contact'])): ?>Tel: <?= htmlspecialchars($data['contact']) ?><?php endif; ?>
        <?php if (!empty($data['email'])): ?> | Email: <?= htmlspecialchars($data['email']) ?><?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

</div>
</body>
</html>
