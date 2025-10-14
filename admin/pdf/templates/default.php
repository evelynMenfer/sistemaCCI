<?php
// default.php — Plantilla PDF Cotización
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
<?= $style ?>
</style>
</head>
<body>

<header>
  <div class="empresa">
    <?php if(!empty($logo_path)): ?>
      <img src="<?= $logo_path ?>" class="logo">
    <?php endif; ?>
    <h2><?= htmlspecialchars($data['name_empresa']) ?></h2>
    <p><?= htmlspecialchars($data['address']) ?><br>
    <?= htmlspecialchars($data['email']) ?> | <?= htmlspecialchars($data['contact']) ?></p>
  </div>
  <div class="cotizacion">
    <h1>Cotización</h1>
    <p><strong>Código:</strong> <?= htmlspecialchars($data['po_code']) ?><br>
       <strong>Fecha:</strong> <?= htmlspecialchars($data['date_exp']) ?></p>
  </div>
</header>

<hr>

<section>
  <h3>Cliente</h3>
  <p><strong><?= htmlspecialchars($data['cliente_cotizacion']) ?></strong><br>
     <?= htmlspecialchars($data['cliente_email']) ?></p>
</section>

<table class="items">
  <thead>
    <tr>
      <th>Cantidad</th>
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
      <td class="center"><?= number_format($it['quantity'], 2) ?></td>
      <td class="center"><?= htmlspecialchars($it['unit']) ?></td>
      <td><?= htmlspecialchars($it['description']) ?></td>
      <td class="right">$<?= number_format($it['price'], 2) ?></td>
      <td class="right"><?= number_format($it['discount'], 2) ?></td>
      <td class="right">$<?= number_format($it['line_total'], 2) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr><th colspan="5" class="right">Subtotal</th><th class="right">$<?= number_format($subtotal,2) ?></th></tr>
    <tr><th colspan="5" class="right">Descuento (<?= $discount_perc ?>%)</th><th class="right">-$<?= number_format($discount,2) ?></th></tr>
    <tr><th colspan="5" class="right">Impuesto (<?= $tax_perc ?>%)</th><th class="right">$<?= number_format($tax,2) ?></th></tr>
    <tr><th colspan="5" class="right total">Total</th><th class="right total">$<?= number_format($amount,2) ?></th></tr>
  </tfoot>
</table>

<?php if(!empty($remarks)): ?>
  <section class="obs">
    <h3>Observaciones</h3>
    <p><?= nl2br(htmlspecialchars($remarks)) ?></p>
  </section>
<?php endif; ?>

<footer>
  <p>Generado por Orbyx Technologies</p>
</footer>

</body>
</html>
