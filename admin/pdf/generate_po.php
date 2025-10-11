<?php
// =============================
// CONFIGURACIÓN DE DEPURACIÓN
// =============================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include __DIR__ . '/../../config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) die("ID inválido.");

// =============================
// CONSULTA PRINCIPAL
// =============================
$qry = $conn->query("
  SELECT p.*, s.name AS supplier, c.name AS name_empresa, c.logo AS logo_empresa,
         c.address, c.email, c.contact
  FROM purchase_order_list p
  LEFT JOIN supplier_list s ON p.supplier_id = s.id
  LEFT JOIN company_list c ON p.id_company = c.id
  WHERE p.id = {$id}
");
if (!$qry || $qry->num_rows === 0) die("No se encontró la cotización.");
$data = $qry->fetch_assoc();

// =============================
// DETECTAR ESTILO
// =============================
$style_name = strtolower(preg_replace('/[^a-z0-9]/', '', $data['name_empresa']));
$style_file = __DIR__ . "/styles/ingenieriayservicioscenitsadecvcv.css";
if (!file_exists($style_file)) {
  $style_file = __DIR__ . "/styles/default.css";
}
$style = file_get_contents($style_file);

// =============================
// CONSULTAR ITEMS
// =============================
$items = [];
$subtotal = 0;
$qry_items = $conn->query("
  SELECT p.*, i.description
  FROM po_items p
  INNER JOIN item_list i ON p.item_id = i.id
  WHERE p.po_id = {$id}
");
while ($row = $qry_items->fetch_assoc()) {
  $price = floatval($row['price']);
  $discount = floatval($row['discount']);
  $quantity = floatval($row['quantity']);
  $line_total = ($price - ($price * $discount / 100)) * $quantity;
  $subtotal += $line_total;
  $items[] = $row + ['line_total' => $line_total];
}

// =============================
// GENERAR HTML
// =============================
ob_start();
?>
<html>
<head>
  <meta charset="UTF-8">
  <style><?= $style ?></style>
</head>
<body>

<div class="company-block">
<?php
// =========================
// RESOLVER RUTA DEL LOGO
// =========================
$logo_path = '';
if (!empty($data['logo_empresa'])) {
    $relative_logo = ltrim($data['logo_empresa'], '/');
    $absolute_logo_path = realpath($_SERVER['DOCUMENT_ROOT'] . '/sisinventarios/' . $relative_logo);
    if ($absolute_logo_path && file_exists($absolute_logo_path)) {
        $logo_path = 'file://' . $absolute_logo_path;
    } else {
        $logo_path = 'http://localhost/sisinventarios/' . $relative_logo;
    }
}
?>

<?php if (!empty($logo_path)): ?>
  <img src="<?= $logo_path ?>" class="logo">
<?php endif; ?>

  <div class="company-data">
    <h2><?= htmlspecialchars($data['name_empresa']) ?></h2>
    <p><?= htmlspecialchars($data['address']) ?><br>
    Tel: <?= htmlspecialchars($data['contact']) ?> |
    Email: <?= htmlspecialchars($data['email']) ?></p>
  </div>
</div>

<!-- BLOQUE DE CLIENTE Y FECHA -->
<div class="cliente-fecha">
    <!-- Columna izquierda: Vendido a -->
    <div class="col-cliente">
    <p class="cliente-linea">
    <strong>Vendido a:</strong>
    <?= htmlspecialchars(preg_replace('/\s+/', ' ', str_replace(array("\r", "\n"), ' ', trim($data['cliente_cotizacion'] ?? '')))) ?>
  </p>
  <br>
  </div>

  <!-- Columna derecha: Fecha y Factura -->
  <div class="col-fecha">
    <?php if (!empty($data['date_exp'])): ?>
      <p><strong>Fecha:</strong> <?= date("d/m/Y", strtotime($data['date_exp'])) ?></p>
    <?php endif; ?>
    <?php if (!empty($data['num_factura'])): ?>
      <p><strong>No. Factura:</strong> <?= htmlspecialchars($data['num_factura']) ?></p>
    <?php endif; ?>
  </div>
</div>


<!-- TABLA DE INFORMACIÓN DE PAGO -->
<div class="tabla-pago-wrap">
      <table class="tabla-pago">
        <thead>
          <tr>
            <th>Método de Pago</th>
            <th>No. de Cheque</th>
            <th>Trabajo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?= htmlspecialchars($data['metodo_pago'] ?? '') ?></td>
            <td><?= htmlspecialchars($data['num_cheque'] ?? '') ?></td>
            <td><?= htmlspecialchars($data['trabajo'] ?? '') ?></td>
          </tr>
        </tbody>
      </table><br>
    </div>



<!-- TABLA DE PRODUCTOS -->
<table class="items">
  <thead>
    <tr>
      <th>Cant.</th>
      <th>Unidad</th>
      <th>Descripción</th>
      <th>Precio Unit.</th>
      <th>Desc %</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($items as $it): ?>
    <tr>
      <td class="num"><?= number_format($it['quantity'], 2) ?></td>
      <td><?= htmlspecialchars($it['unit']) ?></td>
      <td><?= htmlspecialchars($it['description']) ?></td>
      <td class="num">$<?= number_format($it['price'], 2) ?></td>
      <td class="num"><?= number_format($it['discount'], 2) ?>%</td>
      <td class="num">$<?= number_format($it['line_total'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="5" class="total-label">Subtotal</td>
      <td class="total-value">$<?= number_format($subtotal, 2) ?></td>
    </tr>
    <tr>
      <td colspan="5" class="total-label">Descuento (<?= $data['discount_perc'] ?? 0 ?>%)</td>
      <td class="total-value">$<?= number_format($data['discount'] ?? 0, 2) ?></td>
    </tr>
    <tr>
      <td colspan="5" class="total-label">Impuesto (<?= $data['tax_perc'] ?? 0 ?>%)</td>
      <td class="total-value">$<?= number_format($data['tax'] ?? 0, 2) ?></td>
    </tr>
    <tr class="total">
      <td colspan="5" class="total-label">TOTAL</td>
      <td class="total-value">$<?= number_format($data['amount'], 2) ?></td>
    </tr>
  </tfoot>

</table>

<?php if (!empty($data['remarks'])): ?>
  <p class="remarks"><strong>Observaciones:</strong><br><?= nl2br(htmlspecialchars($data['remarks'])) ?></p>
<?php endif; ?>

<!-- SOLO LA FRASE FINAL CENTRADA -->
<div class="footer">
  <p>Gracias por su confianza.</p>
</div>

</body>
</html>
<?php
$html = ob_get_clean();

// =============================
// CONFIGURAR Y GENERAR PDF
// =============================
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('chroot', realpath($_SERVER['DOCUMENT_ROOT'] . '/sisinventarios/'));
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Cotizacion_{$data['po_code']}.pdf", ["Attachment" => false]);
?>
