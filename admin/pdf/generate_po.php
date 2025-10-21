<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include __DIR__ . '/../../config.php';

// ==================================================
// 🔹 VALIDAR ID DE COTIZACIÓN
// ==================================================
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("<h3 style='text-align:center;color:red;margin-top:30px;'>ID inválido o no especificado.</h3>");
}

// ==================================================
// 🔹 FUNCIONES AUXILIARES
// ==================================================
function column_exists(mysqli $conn, string $table, string $column): bool {
    $table = $conn->real_escape_string($table);
    $column = $conn->real_escape_string($column);
    $res = $conn->query("SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
    return $res && $res->num_rows > 0;
}

// ==================================================
// 🔹 CONSULTA PRINCIPAL (COTIZACIÓN + EMPRESA + CLIENTE + PROVEEDOR)
// ==================================================
$qry = $conn->query("
    SELECT 
        p.*, 
        s.name AS supplier, 
        c.name AS name_empresa, 
        c.logo, 
        c.address, 
        c.email, 
        c.contact, 
        c.cperson,
        c.nota,
        c.identificador,
        c.id AS id_company,
        cl.id AS customer_id,
        cl.name AS cliente_nombre,
        cl.email AS cliente_email,
        cl.contact AS cliente_contact,
        cl.address AS cliente_address,
        cl.rfc AS cliente_rfc
    FROM purchase_order_list p
    LEFT JOIN supplier_list s ON p.supplier_id = s.id
    LEFT JOIN company_list c ON p.id_company = c.id
    LEFT JOIN customer_list cl ON cl.id = p.customer_id
    WHERE p.id = {$id}
");

if (!$qry || $qry->num_rows === 0) {
    die("<h3 style='text-align:center;color:red;margin-top:30px;'>❌ No se encontró la cotización.</h3>");
}

$data = $qry->fetch_assoc();

// ==================================================
// 🔹 DATOS DE CLIENTE
// ==================================================
$cliente = [
    'nombre'  => $data['cliente_nombre']  ?? '—',
    'email'   => $data['cliente_email']   ?? '—',
    'contact' => $data['cliente_contact'] ?? '—',
    'address' => $data['cliente_address'] ?? '—',
    'rfc'     => $data['cliente_rfc']     ?? '—'
];

// ==================================================
// 🔹 DATOS DE EMPRESA
// ==================================================
$company_id = intval($data['id_company'] ?? 0);
$data['cperson'] = $data['cperson'] ?? '';
$data['address'] = $data['address'] ?? '';
$data['contact'] = $data['contact'] ?? '';
$data['email']   = $data['email']   ?? '';
$data['nota']    = $data['nota']    ?? '';

// ==================================================
// 🔹 RFC Y DATOS BANCARIOS DE EMPRESA
// ==================================================
$rfc = $bank = $account_no = $clabe = '';
if ($company_id > 0) {
    $cols = [];
    foreach (['rfc', 'banco', 'ncuenta', 'cuenta_clabe'] as $col) {
        if (column_exists($conn, 'company_list', $col)) $cols[] = "`{$col}`";
    }
    if (!empty($cols)) {
        $res = $conn->query("SELECT " . implode(',', $cols) . " FROM company_list WHERE id = {$company_id} LIMIT 1");
        if ($res && $res->num_rows) {
            $row = $res->fetch_assoc();
            $rfc        = $row['rfc'] ?? '';
            $bank       = $row['banco'] ?? '';
            $account_no = $row['ncuenta'] ?? '';
            $clabe      = $row['cuenta_clabe'] ?? '';
        }
    }
}

// ==================================================
// 🔹 TEMPLATE Y ESTILO
// ==================================================
$identificador = strtolower(trim($data['identificador'] ?? ''));
$styles_dir = __DIR__ . '/styles/';
$templates_dir = __DIR__ . '/templates/';

$style_file = $styles_dir . (file_exists($styles_dir . $identificador . '.css') ? $identificador . '.css' : 'default.css');
$style = file_get_contents($style_file);

$template_file = $templates_dir . (file_exists($templates_dir . $identificador . '.php') ? $identificador . '.php' : 'default.php');

// ==================================================
// 🔹 CONSULTA DE ÍTEMS (con marca, modelo, talla)
// ==================================================
$items = [];
$subtotal = 0;

$qry_items = $conn->query("
    SELECT 
        p.*, 
        i.name, 
        i.description, 
        i.foto_producto,
        COALESCE(NULLIF(p.marca, ''),  i.marca)  AS marca,
        COALESCE(NULLIF(p.modelo, ''), i.modelo) AS modelo,
        COALESCE(NULLIF(p.talla, ''),  i.talla)  AS talla
    FROM po_items p
    INNER JOIN item_list i ON p.item_id = i.id
    WHERE p.po_id = {$id}
");

while ($row = $qry_items->fetch_assoc()) {
    $price     = floatval($row['price']);
    $discount  = floatval($row['discount']);
    $quantity  = floatval($row['quantity']);
    $line_total = ($price - ($price * $discount / 100)) * $quantity;
    $subtotal += $line_total;

    // Imagen del producto
    $row['foto_producto_base64'] = '';
    if (!empty($row['foto_producto'])) {
        $filename = basename($row['foto_producto']);
        $foto_path = __DIR__ . '/../../uploads/productos/' . $filename;
        if (is_file($foto_path)) {
            $mime = function_exists('mime_content_type') ? mime_content_type($foto_path) : 'image/jpeg';
            $imgData = base64_encode(file_get_contents($foto_path));
            $row['foto_producto_base64'] = 'data:' . $mime . ';base64,' . $imgData;
        }
    }

    $items[] = $row + ['line_total' => $line_total];
}

// ==================================================
// 🔹 LOGO EMPRESA
// ==================================================
$logo_path = '';
if (!empty($data['logo'])) {
    $logo_file = basename(trim($data['logo']));
    $absolute_logo_path = __DIR__ . '/../../uploads/empresas/' . $logo_file;
    if (!file_exists($absolute_logo_path)) {
        $absolute_logo_path = realpath(__DIR__ . '/../../' . ltrim($data['logo'], '/'));
    }
    if ($absolute_logo_path && file_exists($absolute_logo_path)) {
        $mime = function_exists('mime_content_type') ? mime_content_type($absolute_logo_path) : 'image/png';
        $imgData = base64_encode(file_get_contents($absolute_logo_path));
        $logo_path = 'data:' . $mime . ';base64,' . $imgData;
    }
}

// ==================================================
// 🔹 VARIABLES DISPONIBLES PARA TEMPLATE
// ==================================================
$data['rfc']          = $rfc;
$data['banco']        = $bank;
$data['ncuenta']      = $account_no;
$data['cuenta_clabe'] = $clabe;

// ==================================================
// 🔹 GENERAR HTML FINAL
// ==================================================
ob_start();
include $template_file;
$html = ob_get_clean();

// ==================================================
// 🔹 RENDERIZAR PDF CON DOMPDF
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
// 🔹 SALIDA PDF
// ==================================================
$filename = "Cotizacion_" . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data['po_code']) . ".pdf";
$dompdf->stream($filename, ["Attachment" => false]);
?>
