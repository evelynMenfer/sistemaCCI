<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include __DIR__ . '/../../config.php';

// ==================================================
// 🔹 VALIDACIÓN DEL ID
// ==================================================
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("<h3 style='text-align:center;color:red;margin-top:30px;'>ID inválido o no especificado.</h3>");
}

// ==================================================
// 🔹 CONSULTA PRINCIPAL (COTIZACIÓN + EMPRESA + PROVEEDOR)
// ==================================================
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

// ==================================================
// 🔹 DETECCIÓN AUTOMÁTICA DE ESTILO Y TEMPLATE
// ==================================================
$idname = strtolower(trim($data['idname'] ?? ''));

// --- Estilo CSS ---
$styles_dir = __DIR__ . '/styles/';
$style_file = $styles_dir . (file_exists($styles_dir . $idname . '.css') ? $idname . '.css' : 'default.css');
$style = file_get_contents($style_file);

// --- Template HTML ---
$templates_dir = __DIR__ . '/templates/';
$template_file = $templates_dir . (file_exists($templates_dir . $idname . '.php') ? $idname . '.php' : 'default.php');

// ==================================================
// 🔹 CONSULTA DE ÍTEMS
// ==================================================
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

// ==================================================
// 🔹 LOGO EN BASE64 (EMBEBIDO EN EL PDF)
// ==================================================
$logo_path = '';
if (!empty($data['logo'])) {
    $logo_file = basename(trim($data['logo']));
    $absolute_logo_path = __DIR__ . '/../../uploads/logos/' . $logo_file;

    if (file_exists($absolute_logo_path)) {
        $imgData = base64_encode(file_get_contents($absolute_logo_path));
        $mime = mime_content_type($absolute_logo_path);
        $logo_path = 'data:' . $mime . ';base64,' . $imgData;
        error_log("🟢 Logo embebido: {$absolute_logo_path}");
    } else {
        error_log("🔴 Logo no encontrado: {$absolute_logo_path}");
    }
} else {
    error_log("⚠️ No hay logo definido para esta empresa.");
}

// ==================================================
// 🔹 CARGAR TEMPLATE SEGÚN LA EMPRESA
// ==================================================
ob_start();
include $template_file; // ← Aquí se genera el HTML según la empresa
$html = ob_get_clean();

// ==================================================
// 🔹 CONFIGURACIÓN DE DOMPDF
// ==================================================
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

// ==================================================
// 🔹 MOSTRAR PDF EN NAVEGADOR
// ==================================================
$filename = "Cotizacion_" . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['po_code']) . ".pdf";
$dompdf->stream($filename, ["Attachment" => false]);
?>
