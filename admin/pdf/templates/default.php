<?php
// ==================================================
// 🔹 TEMPLATE PDF DEFAULT – ORBYX TECHNOLOGIES
// Con columna de imagen, estilo corporativo formal
// Ahora incluye Marca, Modelo y Talla
// ==================================================

$data      = $data ?? [];
$items     = isset($items) && is_array($items) ? $items : [];
$subtotal  = isset($subtotal) ? floatval($subtotal) : 0.0;

// ==================================================
// 🧮 Cálculo de subtotal seguro
// ==================================================
if ($subtotal <= 0 && !empty($items)) {
    $subtotal = 0.0;
    foreach ($items as $it) {
        $price = floatval($it['price'] ?? 0);
        $qty   = floatval($it['quantity'] ?? 0);
        $disc  = floatval($it['discount'] ?? 0);
        $subtotal += ($price - ($price * $disc / 100)) * $qty;
    }
}

// ==================================================
// 💰 Cálculo de descuento, IVA y total
// ==================================================
$discount_perc  = floatval($data['discount_perc'] ?? 0);
$discount_monto = floatval($data['discount'] ?? 0);
$tax_perc       = floatval($data['tax_perc'] ?? 16);
$tax            = floatval($data['tax'] ?? 0);
$amount         = floatval($data['amount'] ?? 0);

if ($discount_monto <= 0 && $discount_perc > 0 && $subtotal > 0)
    $discount_monto = round($subtotal * $discount_perc / 100, 2);

if ($discount_perc <= 0 && $discount_monto > 0 && $subtotal > 0)
    $discount_perc = round(($discount_monto / $subtotal) * 100, 2);

$base = max($subtotal - $discount_monto, 0);
if ($tax <= 0 && $tax_perc > 0)
    $tax = round($base * ($tax_perc / 100), 2);
if ($amount <= 0)
    $amount = round($base + $tax, 2);

// ==================================================
// 🔧 Helpers
// ==================================================
$fmt = fn($n) => number_format((float)$n, 2);
$e   = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

// Campos principales
$po_code   = $data['po_code'] ?? '—';
$cliente   = $data['cliente_nombre'] ?? '—';
$email_cli = $data['cliente_email'] ?? '';
$fecha     = !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—';
$empresa   = $data['name_empresa'] ?? '—';
$direccion = $data['address'] ?? '';
$email_emp = $data['email'] ?? '';
$contacto  = $data['contact'] ?? '';
$remarks   = $data['remarks'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
/* ======================================
   ESTILO DEFAULT PDF – ORBYX TECHNOLOGIES
   ====================================== */

@page {
  margin: 20mm 15mm;
}

body {
  font-family: "DejaVu Sans", Arial, sans-serif;
  font-size: 11.5px;
  color: #222;
  margin: 0;
  padding: 0;
}

/* --- LOGO Y ENCABEZADO --- */
.company-block {
  text-align: center;
  margin-bottom: 10px;
  width: 100%;
}
.logo {
  display: block;
  margin: 0 auto;
  max-width: 180px;
  height: auto;
}
.company-data {
  text-align: center;
  margin-top: 5px;
  line-height: 1.4;
}
.company-data h2 {
  margin: 0;
  color: #003366;
  font-size: 18px;
  text-transform: uppercase;
}
.company-data p {
  margin: 4px 0 0 0;
  font-size: 11px;
  color: #444;
}

/* --- TÍTULO PRINCIPAL --- */
.title {
  text-align: center;
  color: #003366;
  margin: 20px 0 10px 0;
  font-size: 14px;
  letter-spacing: 0.3px;
  text-transform: uppercase;
  border-top: 2px solid #003366;
  padding-top: 6px;
}

/* --- CLIENTE Y FECHA --- */
.cliente-fecha {
  width: 100%;
  margin: 10px 0;
}
.col-cliente,
.col-fecha {
  width: 48%;
  display: inline-block;
  vertical-align: top;
  font-size: 11px;
  line-height: 1.5;
}
.col-cliente strong,
.col-fecha strong {
  color: #003366;
}

/* --- TABLA DE PRODUCTOS --- */
table.items {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
  font-size: 11px;
}
.items th,
.items td {
  border: 1px solid #bbb;
  padding: 6px;
}
.items th {
  background: #003366;
  color: #fff;
  text-align: center;
  font-weight: 600;
  font-size: 10.8px;
}
.items td {
  font-size: 10.6px;
  vertical-align: middle;
}
.items .num { text-align: right; }
.items .center { text-align: center; }

/* --- Imagen de producto --- */
.items .img-cell img {
  width: 60px;
  height: auto;
  border-radius: 4px;
}

/* --- TOTALES --- */
tfoot td {
  border-top: 1px solid #003366;
  font-weight: bold;
  font-size: 11.5px;
  color: #003366;
  padding: 6px 8px;
}
tfoot .total-label { text-align: right; }
tfoot .total-value { text-align: right; }
tfoot tr.total td {
  border-top: 2px solid #003366;
  font-size: 12px;
  text-transform: uppercase;
  background: #f2f6fb;
}

/* --- OBSERVACIONES --- */
.remarks {
  margin-top: 15px;
  font-size: 11px;
  color: #333;
}

/* --- PIE --- */
.footer {
  margin-top: 25px;
  text-align: center;
  font-size: 11px;
  color: #003366;
  font-weight: bold;
}

/* --- EVITAR CORTES DE TABLA --- */
.items, .items tr, .items td, .items th {
  page-break-inside: avoid !important;
}
</style>
</head>

<body>

<!-- 🔹 ENCABEZADO -->
<div class="company-block">
  <?php if(!empty($logo_path)): ?>
    <img src="<?= $logo_path ?>" class="logo" alt="Logo">
  <?php endif; ?>
  <div class="company-data">
    <h2><?= $e($empresa) ?></h2>
    <p><?= $e($direccion) ?><br><?= $e($email_emp) ?> | <?= $e($contacto) ?></p>
  </div>
</div>

<!-- 🔹 TÍTULO -->
<div class="title">
  <h1>COTIZACIÓN</h1>
</div>

<!-- 🔹 CLIENTE Y FECHA -->
<div class="cliente-fecha">
  <div class="col-cliente">
    <strong>Cliente:</strong> <?= $e($cliente) ?><br>
    <strong>Email:</strong> <?= $e($email_cli) ?>
  </div>
  <div class="col-fecha">
    <strong>Folio:</strong> <?= $e($po_code) ?><br>
    <strong>Fecha:</strong> <?= $e($fecha) ?>
  </div>
</div>

<!-- 🔹 TABLA DE PRODUCTOS -->
<table class="items">
  <thead>
    <tr>
      <th>SKU</th>
      <th>DESCRIPCIÓN</th>
      <th>MARCA</th>
      <th>MODELO</th>
      <th>TALLA</th>
      <th>IMAGEN</th>
      <th>CANTIDAD</th>
      <th>P. UNITARIO</th>
      <th>DESC. (%)</th>
      <th>IMPORTE</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($items as $it): 
    $sku    = $it['name'] ?? '';
    $desc   = $it['description'] ?? '';
    $marca  = $it['marca'] ?? '';
    $modelo = $it['modelo'] ?? '';
    $talla  = $it['talla'] ?? '';
    $qty    = floatval($it['quantity'] ?? 0);
    $price  = floatval($it['price'] ?? 0);
    $disc   = floatval($it['discount'] ?? 0);
    $lt     = isset($it['line_total']) ? floatval($it['line_total']) : (($price - ($price * $disc / 100)) * $qty);
    $foto   = $it['foto_producto'] ?? '';
  ?>
    <tr>
      <td class="center"><?= $e($sku) ?></td>
      <td><?= nl2br($e($desc)) ?></td>
      <td class="center"><?= $e($marca) ?></td>
      <td class="center"><?= $e($modelo) ?></td>
      <td class="center"><?= $e($talla) ?></td>
      <td class="center img-cell">
        <?php
          if (!empty($foto)) {
              $path = __DIR__ . '/../../uploads/items/' . basename($foto);
              if (file_exists($path)) {
                  echo '<img src="../../uploads/items/' . basename($foto) . '" alt="Producto">';
              } else echo '—';
          } else echo '—';
        ?>
      </td>
      <td class="center"><?= $fmt($qty) ?></td>
      <td class="num">$<?= $fmt($price) ?></td>
      <td class="num"><?= $fmt($disc) ?>%</td>
      <td class="num">$<?= $fmt($lt) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>

  <tfoot>
    <tr><td colspan="9" class="total-label">Subtotal</td><td class="total-value">$<?= $fmt($subtotal) ?></td></tr>
    <?php if ($discount_monto > 0): ?>
      <tr><td colspan="9" class="total-label">Descuento (<?= $fmt($discount_perc) ?>%)</td><td class="total-value">-$<?= $fmt($discount_monto) ?></td></tr>
    <?php endif; ?>
    <tr><td colspan="9" class="total-label">I.V.A. (<?= $fmt($tax_perc) ?>%)</td><td class="total-value">$<?= $fmt($tax) ?></td></tr>
    <tr class="total"><td colspan="9" class="total-label">TOTAL</td><td class="total-value">$<?= $fmt($amount) ?></td></tr>
  </tfoot>
</table>

<!-- 🔹 OBSERVACIONES -->
<?php if(!empty($remarks)): ?>
<div class="remarks">
  <strong>Observaciones:</strong><br>
  <?= nl2br($e($remarks)) ?>
</div>
<?php endif; ?>

</body>
</html>
