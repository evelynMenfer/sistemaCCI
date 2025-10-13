<?php
// =====================================
// CONFIGURACIÓN DE DEPURACIÓN
// =====================================
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include __DIR__ . '/../../config.php';

// =====================================
// VALIDAR ID DE COTIZACIÓN
// =====================================
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("<h3 style='text-align:center;color:red;margin-top:30px;'>ID inválido o no especificado.</h3>");
}

// =====================================
// CONSULTA PRINCIPAL
// =====================================
$qry = $conn->query("
  SELECT p.*, s.name AS supplier, 
         c.name AS name_empresa, 
         c.logo AS logo, 
         c.address, c.email, c.contact, c.id AS company_id
  FROM purchase_order_list p
  LEFT JOIN supplier_list s ON p.supplier_id = s.id
  LEFT JOIN company_list c ON p.id_company = c.id
  WHERE p.id = {$id}
");

if (!$qry || $qry->num_rows === 0) {
    die("<h3 style='text-align:center;color:red;margin-top:30px;'>❌ No se encontró la cotización.</h3>");
}

$data = $qry->fetch_assoc();
$company_id = intval($data['company_id'] ?? 0);

// =====================================
// CARGAR CSS BASE
// =====================================
$style = "
body { font-family: DejaVu Sans, sans-serif; font-size:12px; color:#333; margin:30px; }
h2,h3 { color:#001f3f; margin:0; }
table { width:100%; border-collapse:collapse; margin-top:10px; }
th, td { border:1px solid #ccc; padding:6px; vertical-align:middle; }
th { background:#001f3f; color:white; text-align:center; }
tfoot th { background:#f6f6f6; }
.text-right { text-align:right; }
.text-center { text-align:center; }
.text-left { text-align:left; }
.company-header { display:flex; align-items:center; border-bottom:3px solid #001f3f; margin-bottom:15px; padding-bottom:8px; }
.company-header img { width:70px; height:70px; object-fit:contain; margin-right:15px; }
.footer { text-align:center; font-size:11px; margin-top:30px; color:#555; }
";

// =====================================
// CONSULTAR ITEMS
// =====================================
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

// =====================================
// RUTA DEL LOGO (definitiva)
// =====================================
$logo_path = '';
if (!empty($data['logo'])) {
    $relative_logo = ltrim($data['logo'], '/');
    $absolute_logo_path = realpath(__DIR__ . '/../../' . $relative_logo);
    if ($absolute_logo_path && file_exists($absolute_logo_path)) {
        $logo_path = 'file://' . $absolute_logo_path;
    } else {
        $base_url = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
        $logo_path = $base_url . '/sisinventarios/' . $relative_logo;
    }
}

// =====================================
// GENERAR HTML DEL PDF
// =====================================
ob_start();
?>
<html>
<head>
  <meta charset="UTF-8">
  <style><?= $style ?></style>
</head>
<body>

<div class="company-header">
  <?php if (!empty($logo_path)): ?>
    <img src="<?= $logo_path ?>" alt="Logo">
  <?php endif; ?>
  <div>
    <h2><?= htmlspecialchars($data['name_empresa']) ?></h2>
    <p style="font-size:11px; margin:3px 0;">
      <?= htmlspecialchars($data['address']) ?><br>
      Tel: <?= htmlspecialchars($data['contact']) ?> |
      Email: <?= htmlspecialchars($data['email']) ?>
    </p>
  </div>
</div>

<h3 style="text-align:right;">Cotización: <?= htmlspecialchars($data['po_code']) ?></h3>

<table>
  <tr>
    <th style="width:40%">Cliente</th>
    <th style="width:30%">Fecha</th>
    <th style="width:30%">Método de Pago</th>
  </tr>
  <tr>
    <td><?= htmlspecialchars($data['cliente_cotizacion'] ?? '') ?></td>
    <td><?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?></td>
    <td><?= htmlspecialchars($data['metodo_pago'] ?? '') ?></td>
  </tr>
</table>

<table>
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
      <td class="text-right"><?= number_format($it['quantity'], 2) ?></td>
      <td class="text-center"><?= htmlspecialchars($it['unit']) ?></td>
      <td class="text-left"><?= htmlspecialchars($it['description']) ?></td>
      <td class="text-right">$<?= number_format($it['price'], 2) ?></td>
      <td class="text-right"><?= number_format($it['discount'], 2) ?>%</td>
      <td class="text-right">$<?= number_format($it['line_total'], 2) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="5" class="text-right">Subtotal</th>
      <th class="text-right">$<?= number_format($subtotal, 2) ?></th>
    </tr>
    <tr>
      <th colspan="5" class="text-right">Descuento (<?= $data['discount_perc'] ?? 0 ?>%)</th>
      <th class="text-right">$<?= number_format($data['discount'] ?? 0, 2) ?></th>
    </tr>
    <tr>
      <th colspan="5" class="text-right">Impuesto (<?= $data['tax_perc'] ?? 0 ?>%)</th>
      <th class="text-right">$<?= number_format($data['tax'] ?? 0, 2) ?></th>
    </tr>
    <tr>
      <th colspan="5" class="text-right">Total</th>
      <th class="text-right"><strong>$<?= number_format($data['amount'], 2) ?></strong></th>
    </tr>
  </tfoot>
</table>

<?php if (!empty($data['remarks'])): ?>
  <p style="margin-top:15px;"><strong>Observaciones:</strong><br><?= nl2br(htmlspecialchars($data['remarks'])) ?></p>
<?php endif; ?>

<div class="footer">
  <p>Gracias por su preferencia. — <?= htmlspecialchars($data['name_empresa']) ?></p>
</div>

</body>
</html>
<?php
$html = ob_get_clean();

// =====================================
// CONFIGURAR Y GENERAR PDF
// =====================================
$options = new Options();
$options->set('isRemoteEnabled', true);

// Carpeta temporal segura
$tempDir = __DIR__ . '/../../storage/tmp';
if (!is_dir($tempDir)) {
    @mkdir($tempDir, 0777, true);
}
$options->set('tempDir', realpath($tempDir));
$options->set('fontCache', realpath($tempDir));

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Cotizacion_{$data['po_code']}.pdf", ["Attachment" => false]);
?>
