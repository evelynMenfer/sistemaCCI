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

// Helper: verificar si existe una columna en una tabla
function column_exists(mysqli $conn, string $table, string $column): bool {
    $table = $conn->real_escape_string($table);
    $column = $conn->real_escape_string($column);
    $res = $conn->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
    return $res && $res->num_rows > 0;
}

// ==================================================
// 🔹 CONSULTA PRINCIPAL (COTIZACIÓN + EMPRESA + PROVEEDOR)
//    *Se deja EXACTAMENTE como tu versión funcional*
// ==================================================
$qry = $conn->query("
  SELECT 
      p.*, 
      p.cliente_cotizacion,        -- Nombre del cliente
      s.name AS supplier, 
      c.name AS name_empresa, 
      c.logo AS logo, 
      c.address, 
      c.email, 
      c.contact,
      c.cperson,                   -- Atención (persona de contacto de la empresa)
      c.nota,                   
      c.identificador
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
// 🔹 CAMPOS BASE SEGUROS
// ==================================================
$data['cperson'] = $data['cperson'] ?? '';
$data['address'] = $data['address'] ?? '';
$data['contact'] = $data['contact'] ?? '';
$data['email']   = $data['email']   ?? '';
$data['nota']    = $data['nota']   ?? '';


// ==================================================
// 🔹 EXTRA: CAMPOS OPCIONALES DESDE BD (sin romper si no existen)
// ==================================================
$company_id = $data['id_company'] ?? $data['id_company'] ?? null;

$rfc   = '';
$bank  = '';
$account_no = '';
$clabe = '';
$nota  = '';

// 1) NOTA (purchase_order_list.nota)
if (column_exists($conn, 'purchase_order_list', 'nota')) {
    $resNota = $conn->query("SELECT nota FROM purchase_order_list WHERE id = {$id} LIMIT 1");
    if ($resNota && $resNota->num_rows) {
        $rowN = $resNota->fetch_assoc();
        $nota = $rowN['nota'] ?? '';
    }
}

// 2) Datos bancarios y RFC (company_list: rfc, banco, ncuenta, cuenta_clabe)
$extraCols = [];
if (column_exists($conn, 'company_list', 'rfc'))          $extraCols[] = 'rfc';
if (column_exists($conn, 'company_list', 'banco'))        $extraCols[] = 'banco';
if (column_exists($conn, 'company_list', 'ncuenta'))      $extraCols[] = 'ncuenta';
if (column_exists($conn, 'company_list', 'cuenta_clabe')) $extraCols[] = 'cuenta_clabe';

if (!empty($extraCols) && !empty($company_id)) {
    $cols = implode(',', array_map(fn($c) => "`$c`", $extraCols));
    $resBank = $conn->query("SELECT {$cols} FROM company_list WHERE id = {$company_id} LIMIT 1");
    if ($resBank && $resBank->num_rows) {
        $rowB = $resBank->fetch_assoc();
        $rfc       = $rowB['rfc'] ?? '';
        $bank      = $rowB['banco'] ?? '';
        $account_no= $rowB['ncuenta'] ?? '';
        $clabe     = $rowB['cuenta_clabe'] ?? '';
    }
}

// ==================================================
// 🔹 DETECCIÓN AUTOMÁTICA DE ESTILO Y TEMPLATE (igual que tu versión)
// ==================================================
$identificador = strtolower(trim($data['identificador'] ?? ''));

$styles_dir = __DIR__ . '/styles/';
$style_file = $styles_dir . (file_exists($styles_dir . $identificador . '.css') ? $identificador . '.css' : 'default.css');
$style = file_get_contents($style_file);

$templates_dir = __DIR__ . '/templates/';
$template_file = $templates_dir . (file_exists($templates_dir . $identificador . '.php') ? $identificador . '.php' : 'default.php');

// ==================================================
// 🔹 CONSULTA DE ÍTEMS (agregar marca/modelo si existen EN item_list)
//    Si no existen, no rompemos y devolvemos vacío en esas llaves.
// ==================================================
$items = [];
$subtotal = 0;

$itemCols = "p.*, i.name, i.description, i.foto_producto";
$hasMarca  = column_exists($conn, 'item_list', 'marca');
$hasModelo = column_exists($conn, 'item_list', 'modelo');
if ($hasMarca)  $itemCols .= ", i.marca";
if ($hasModelo) $itemCols .= ", i.modelo";

$qry_items = $conn->query("
  SELECT {$itemCols}
  FROM po_items p
  INNER JOIN item_list i ON p.item_id = i.id
  WHERE p.po_id = {$id}
");

while ($row = $qry_items->fetch_assoc()) {
    if (!isset($row['marca']))  $row['marca']  = '';
    if (!isset($row['modelo'])) $row['modelo'] = '';

    $price = floatval($row['price']);
    $discount = floatval($row['discount']);
    $quantity = floatval($row['quantity']);
    $line_total = ($price - ($price * $discount / 100)) * $quantity;
    $subtotal += $line_total;

    // Embebemos imagen si existe (carpeta correcta: uploads/productos/)
$row['foto_producto_base64'] = '';
if (!empty($row['foto_producto'])) {
    // La BD puede traer 'uploads/productos/archivo.jpg' o solo 'archivo.jpg'
    $filename  = basename($row['foto_producto']);
    $foto_path = __DIR__ . '/../../uploads/productos/' . $filename;

    if (is_file($foto_path)) {
        // mime_content_type puede no existir en algunos hosts, ponemos fallback
        $mime = function_exists('mime_content_type') ? mime_content_type($foto_path) : null;
        if (!$mime || !preg_match('~^image/~', $mime)) {
            $mime = 'image/jpeg';
        }
        $imgData = base64_encode(file_get_contents($foto_path));
        $row['foto_producto_base64'] = 'data:' . $mime . ';base64,' . $imgData;
    }
}

    $items[] = $row + ['line_total' => $line_total];
}

// ==================================================
// 🔹 LOGO EN BASE64 (igual que tu versión)
// ==================================================
$logo_path = '';
if (!empty($data['logo'])) {
    $logo_file = basename(trim($data['logo']));
    $absolute_logo_path = __DIR__ . '/../../uploads/logos/' . $logo_file;

    if (file_exists($absolute_logo_path)) {
        $imgData = base64_encode(file_get_contents($absolute_logo_path));
        $mime = mime_content_type($absolute_logo_path);
        $logo_path = 'data:' . $mime . ';base64,' . $imgData;
    }
}

// ==================================================
// 🔹 PASAR VARIABLES EXTRA AL TEMPLATE (compatibilidad total)
//    - Si tu template usa $data['rfc'] también lo tendrá (abajo)
//    - Y además, exponemos variables sueltas por si tu template las espera
// ==================================================
$data['rfc']          = $rfc;
$data['banco']        = $bank;
$data['ncuenta']      = $account_no;
$data['cuenta_clabe'] = $clabe;

$bank       = $bank;
$account_no = $account_no;
$clabe      = $clabe;
$rfc        = $rfc;

// ==================================================
// 🔹 CARGAR TEMPLATE SEGÚN LA EMPRESA
// ==================================================
ob_start();
include $template_file; // ← Se genera el HTML
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
