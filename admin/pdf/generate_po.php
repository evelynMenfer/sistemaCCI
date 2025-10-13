<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include __DIR__ . '/../../config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("<h3 style='text-align:center;color:red;margin-top:30px;'>ID inválido o no especificado.</h3>");
}

$qry = $conn->query("
  SELECT p.*, 
         s.name AS supplier, 
         c.name AS name_empresa, 
         c.logo AS logo, 
         c.address, c.email, c.contact,
         c.idname
  FROM purchase_order_list p
  LEFT JOIN supplier_list s ON p.supplier_id = s.id
  LEFT JOIN company_list c ON p.id_company = c.id
  WHERE p.id = {$id}
");

if (!$qry || $qry->num_rows === 0) {
    die("<h3 style='text-align:center;color:red;margin-top:30px;'>❌ No se encontró la cotización.</h3>");
}

$data = $qry->fetch_assoc();

// =====================
// CARGA DE ESTILO
// =====================
$styles_dir = __DIR__ . '/styles/';
$idname = strtolower(trim($data['idname'] ?? ''));
$style_file = $styles_dir . (file_exists($styles_dir . $idname . '.css') ? $idname . '.css' : 'default.css');
$style = file_get_contents($style_file);
error_log("🟦 [PDF] Estilo aplicado: {$style_file}");

// =====================
// ITEMS
// =====================
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

// =====================
// LOGO (versión 100 % funcional con base64 embebido)
// =====================
$logo_path = '';

if (!empty($data['logo'])) {
    $logo_file = basename(trim($data['logo']));
    $absolute_logo_path = __DIR__ . '/../../uploads/logos/' . $logo_file;

    if (file_exists($absolute_logo_path)) {
        $imgData = base64_encode(file_get_contents($absolute_logo_path));
        // detecta automáticamente el tipo MIME
        $mime = mime_content_type($absolute_logo_path);
        $logo_path = 'data:' . $mime . ';base64,' . $imgData;
        error_log("🟢 Logo incrustado en base64: {$absolute_logo_path}");
    } else {
        error_log("🔴 Logo NO encontrado: {$absolute_logo_path}");
    }
} else {
    error_log("⚠️ No hay logo definido en la BD.");
}

// =====================
// HTML PDF
// =====================
ob_start();
?>
<html>
<head>
<meta charset="UTF-8">
<style><?= $style ?></style>
</head>
<body>

<!-- LOGO -->
<div class="company-block">
<?php if ($logo_path): ?>
  <img src="<?= $logo_path ?>" class="logo" alt="Logo" style="max-height:80px; display:block; margin-bottom:10px;">
<?php else: ?>
  <p style="color:red;font-size:12px;">[Logo no disponible]</p>
<?php endif; ?>
  
</div>

<!-- DATOS DE EMPRESA -->
<div class="company-data">
  <h2><?= htmlspecialchars($data['name_empresa']) ?></h2>
  <p><?= htmlspecialchars($data['address']) ?><br>
  Tel: <?= htmlspecialchars($data['contact']) ?> |
  Email: <?= htmlspecialchars($data['email']) ?></p>
</div>

<!-- TÍTULO -->
<h3 class="title">COTIZACIÓN: <?= htmlspecialchars($data['po_code']) ?></h3>

<!-- CLIENTE Y FECHA -->
<div class="cliente-fecha">
  <div class="col-cliente">
    <p><strong>Cliente:</strong> <?= htmlspecialchars($data['cliente_cotizacion'] ?? '—') ?></p>
    <p><strong>Proveedor:</strong> <?= htmlspecialchars($data['supplier'] ?? '—') ?></p>
  </div>
  <div class="col-fecha">
    <p><strong>Fecha:</strong> <?= !empty($data['date_exp']) ? date("d/m/Y", strtotime($data['date_exp'])) : '—' ?></p>
    <p><strong>Método de Pago:</strong> <?= htmlspecialchars($data['metodo_pago'] ?? '—') ?></p>
  </div>
</div>

<!-- TABLA PRODUCTOS -->
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
    <tr><td colspan="5" class="total-label">Subtotal</td><td class="total-value">$<?= number_format($subtotal, 2) ?></td></tr>
    <tr><td colspan="5" class="total-label">Descuento (<?= $data['discount_perc'] ?? 0 ?>%)</td><td class="total-value">$<?= number_format($data['discount'] ?? 0, 2) ?></td></tr>
    <tr><td colspan="5" class="total-label">Impuesto (<?= $data['tax_perc'] ?? 0 ?>%)</td><td class="total-value">$<?= number_format($data['tax'] ?? 0, 2) ?></td></tr>
    <tr class="total"><td colspan="5" class="total-label">Total</td><td class="total-value">$<?= number_format($data['amount'], 2) ?></td></tr>
  </tfoot>
</table>

<!-- OBSERVACIONES -->
<?php if (!empty($data['remarks'])): ?>
  <div class="remarks"><strong>Observaciones:</strong><br><?= nl2br(htmlspecialchars($data['remarks'])) ?></div>
<?php endif; ?>

<!-- PIE -->
<div class="footer">Gracias por su preferencia. — <?= htmlspecialchars($data['name_empresa']) ?></div>

</body>
</html>
<?php
$html = ob_get_clean();

// =====================
// GENERAR PDF
// =====================
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$tempDir = __DIR__ . '/../../storage/tmp';
if (!is_dir($tempDir)) mkdir($tempDir, 0777, true);
$options->set('tempDir', realpath($tempDir));
$options->set('fontCache', realpath($tempDir));

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("Cotizacion_{$data['po_code']}.pdf", ["Attachment" => false]);
?>
