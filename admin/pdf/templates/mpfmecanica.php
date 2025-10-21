<?php
// ==================================================
// 🔹 TEMPLATE PDF – MPF MECÁNICA
// Estructura original conservada + columnas Marca / Modelo / Talla
// ==================================================

// Entradas y fallbacks sin alterar la vista
$data      = $data      ?? [];
$items     = (isset($items) && is_array($items)) ? $items : [];
$logo_path = $logo_path ?? '';
$style     = $style     ?? '';

// Helpers
$hasKey = function(array $a, $k){ return array_key_exists($k, $a); };
$toNum  = function($v, $fallback = null){
  if ($v === 0 || $v === '0' || $v === '0.0' || $v === '0.00') return 0.0;
  if ($v === null || $v === '') return $fallback;
  return (float)$v;
};

// ===== Subtotal =====
if (!isset($subtotal)) {
  $subtotal = $toNum($data['subtotal'] ?? null, null);
}
if ($subtotal === null) {
  $st = 0.0;
  foreach ($items as $it) {
    if ($hasKey($it,'line_total')) {
      $st += (float)$it['line_total'];
    } else {
      $q  = (float)($it['quantity'] ?? 0);
      $p  = (float)($it['price'] ?? 0);
      $dl = (float)($it['discount'] ?? 0);
      $st += ($p - $p*$dl/100) * $q;
    }
  }
  $subtotal = round($st, 2);
} else {
  $subtotal = (float)$subtotal;
}

// ===== Descuento global =====
if (!isset($discount_perc)) {
  $discount_perc = $toNum($data['discount_perc'] ?? null, 0.0);
} else {
  $discount_perc = (float)$discount_perc;
}

if ($hasKey($data, 'discount')) {
  $discount = $toNum($data['discount'], 0.0);
} elseif (isset($discount)) {
  $discount = $toNum($discount, 0.0);
} else {
  $discount = $discount_perc > 0 ? round($subtotal * ($discount_perc/100), 2) : 0.0;
}
$discount = max($discount, 0.0);

// ===== IVA =====
if (!isset($tax_perc)) {
  $tax_perc = $toNum($data['tax_perc'] ?? null, 16.0);
} else {
  $tax_perc = (float)$tax_perc;
}

$base = max($subtotal - $discount, 0.0);

if ($hasKey($data, 'tax')) {
  $tax = $toNum($data['tax'], 0.0);
} elseif (isset($tax)) {
  $tax = $toNum($tax, 0.0);
} else {
  $tax = round($base * ($tax_perc/100), 2);
}

// ===== Total =====
if ($hasKey($data, 'amount')) {
  $amount = $toNum($data['amount'], null);
} elseif (isset($amount)) {
  $amount = $toNum($amount, null);
} else {
  $amount = null;
}
if ($amount === null) {
  $amount = round($base + $tax, 2);
}

$remarks = $remarks ?? ($data['remarks'] ?? '');
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
    <h2><?= htmlspecialchars($data['name_empresa'] ?? 'MPF MECÁNICA') ?></h2>
    <p><?= htmlspecialchars($data['address'] ?? '') ?><br>
    <?= htmlspecialchars($data['email'] ?? '') ?> | <?= htmlspecialchars($data['contact'] ?? '') ?></p>
  </div>
  <div class="cotizacion">
    <h1>Cotización</h1>
    <p>
      <strong>Código:</strong> <?= htmlspecialchars($data['po_code'] ?? '') ?><br>
      <strong>Fecha:</strong> <?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?>
    </p>
  </div>
</header>

<hr>

<section>
  <p>
    <strong>Cliente:</strong> <?= htmlspecialchars($data['cliente_nombre'] ?? '—') ?><br>
    <?= htmlspecialchars($data['cliente_email'] ?? '') ?>
  </p>
</section>

<!-- ======================================================= -->
<!-- 🔹 TABLA DE PRODUCTOS (ahora con Marca, Modelo, Talla) -->
<!-- ======================================================= -->
<table class="items">
  <thead>
    <tr>
      <th>Cantidad</th>
      <th>Unidad</th>
      <th>Marca</th>
      <th>Modelo</th>
      <th>Descripción</th>
      <th>Precio</th>
      <th>Desc %</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($items as $it):
    $q   = (float)($it['quantity'] ?? 0);
    $p   = (float)($it['price'] ?? 0);
    $d   = (float)($it['discount'] ?? 0);
    $lt  = $hasKey($it,'line_total') ? (float)$it['line_total'] : (($p - ($p * $d / 100)) * $q);
    $marca  = htmlspecialchars($it['marca'] ?? '');
    $modelo = htmlspecialchars($it['modelo'] ?? '');
  ?>
    <tr>
      <td class="center"><?= number_format($q, 2) ?></td>
      <td class="center"><?= htmlspecialchars($it['unit'] ?? '') ?></td>
      <td class="center"><?= $marca ?></td>
      <td class="center"><?= $modelo ?></td>
      <td><?= htmlspecialchars($it['description'] ?? '') ?></td>
      <td class="right">$<?= number_format($p, 2) ?></td>
      <td class="right"><?= number_format($d, 2) ?>%</td>
      <td class="right">$<?= number_format($lt, 2) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>

  <tfoot>
    <tr>
      <th colspan="7" class="right">Subtotal</th>
      <th class="right">$<?= number_format($subtotal, 2) ?></th>
    </tr>
    <?php if ($discount > 0 || $discount_perc > 0): ?>
    <tr>
      <th colspan="7" class="right">
        Descuento <?= $discount_perc > 0 ? '(' . number_format($discount_perc, 2) . '%)' : '' ?>
      </th>
      <th class="right">-$<?= number_format($discount, 2) ?></th>
    </tr>
    <?php endif; ?>
    <tr>
      <th colspan="7" class="right">Impuesto (<?= number_format($tax_perc, 2) ?>%)</th>
      <th class="right">$<?= number_format($tax, 2) ?></th>
    </tr>
    <tr>
      <th colspan="7" class="right total">Total</th>
      <th class="right total">$<?= number_format($amount, 2) ?></th>
    </tr>
  </tfoot>
</table>

<?php if(!empty($remarks)): ?>
  <section class="obs">
    <h3>Observaciones</h3>
    <p><?= nl2br(htmlspecialchars($remarks)) ?></p>
  </section>
<?php endif; ?>

<footer>
  <p>Generado por MPF Mecánica Estrada de México</p>
</footer>

</body>
</html>
