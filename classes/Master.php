<?php
// =========================
// CONFIGURACIÓN DE ERRORES
// =========================
ini_set('log_errors', 1);
ini_set('display_errors', 0);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_once('../config.php');

class Master extends DBConnection {
    private $settings;

    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }
    public function __destruct(){ parent::__destruct(); }

    private function capture_err(){
        if (!$this->conn->error) return false;
        return json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

    /* =======================================================
     *   SECCIÓN GENERAL: GUARDAR / ELIMINAR ENTIDADES
     * ======================================================= */

    // --- GUARDAR PROVEEDOR ---
    function save_supplier(){ 
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if ($k=='id') continue;
            if (!empty($data)) $data .= ",";
            $data .= " `{$k}`='{$v}' ";
        }
        $check = $this->conn->query("SELECT * FROM `supplier_list` WHERE `name` = '{$name}' ".(!empty($id)?" AND id != {$id} ":""))->num_rows;
        if ($this->capture_err()) return $this->capture_err();
        if ($check > 0) return json_encode(['status'=>'failed','msg'=>'Nombre de proveedor ya existe.']);

        $sql = empty($id) ? "INSERT INTO `supplier_list` SET {$data}" : "UPDATE `supplier_list` SET {$data} WHERE id='{$id}'";
        $save = $this->conn->query($sql);
        if ($save){
            $this->settings->set_flashdata('success', empty($id)?"Proveedor guardado.":"Proveedor actualizado.");
            return json_encode(['status'=>'success']);
        }
        return json_encode(['status'=>'failed','err'=>$this->conn->error." [{$sql}]"]);
    }
    function delete_supplier(){
        $id = intval($_POST['id'] ?? 0);
        if ($id<=0) return json_encode(['status'=>'failed','msg'=>'ID inválido']);
        $del = $this->conn->query("DELETE FROM `supplier_list` WHERE id='{$id}'");
        return $del ? json_encode(['status'=>'success']) : json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

    // --- GUARDAR EMPRESA ---
	function save_company() { 
		extract($_POST);
		$id = intval($_POST['id'] ?? 0);
		$identificador = trim($_POST['identificador'] ?? '');
		$name = trim($_POST['name'] ?? '');
	
		// ===== Validación mínima =====
		if ($identificador === '' || $name === '') {
			return json_encode([
				'status'=>'failed',
				'msg'=>'Por favor completa los campos obligatorios: Identificador y Nombre de la empresa.'
			]);
		}
	
		// ===== Validar duplicados de nombre o identificador =====
		$check = $this->conn->query("
			SELECT id FROM company_list 
			WHERE (name = '{$this->conn->real_escape_string($name)}' 
				   OR identificador = '{$this->conn->real_escape_string($identificador)}')
			" . (!empty($id) ? "AND id != {$id}" : "") . "
		")->num_rows;
	
		if ($check > 0) {
			return json_encode(['status'=>'failed','msg'=>'Ya existe una empresa con ese identificador o nombre.']);
		}
	
		// ===== Manejo del LOGO =====
		if (isset($_FILES['logo']) && $_FILES['logo']['tmp_name'] != '') {
			$upload_dir = "uploads/empresas/";
			if (!is_dir(base_app . $upload_dir)) mkdir(base_app . $upload_dir, 0777, true);
	
			// 🔹 Normalizar a minúsculas y limpiar caracteres
			$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
			$clean_identificador = strtolower(preg_replace('/[^A-Za-z0-9_\-]/', '_', $identificador));
			$filename = $clean_identificador . '.' . strtolower($ext);
			$filepath = $upload_dir . $filename;
	
			// 🔹 Si ya existe un logo con ese nombre, eliminarlo
			if (file_exists(base_app . $filepath)) {
				@unlink(base_app . $filepath);
			}
	
			// 🔹 Guardar el nuevo archivo
			if (move_uploaded_file($_FILES['logo']['tmp_name'], base_app . $filepath)) {
				$_POST['logo'] = $filepath;
			}
		}
	
		// ===== Preparar datos para SQL =====
		$data = "";
		foreach ($_POST as $k => $v) {
			if ($k == 'id') continue;
			if ($v === null) $v = '';
			if (!empty($data)) $data .= ", ";
			$data .= " `{$k}` = '{$this->conn->real_escape_string($v)}' ";
		}
	
		$sql = empty($id)
			? "INSERT INTO `company_list` SET {$data}"
			: "UPDATE `company_list` SET {$data} WHERE id = '{$id}'";
	
		// ===== Guardar =====
		$save = $this->conn->query($sql);
	
		if ($save) {
			$this->settings->set_flashdata('success', empty($id)
				? "Empresa registrada correctamente."
				: "Empresa actualizada correctamente."
			);
			return json_encode(['status'=>'success']);
		} else {
			return json_encode([
				'status'=>'failed',
				'msg'=>'Error al guardar empresa: '.$this->conn->error
			]);
		}
	}
	
	
    function delete_company(){
        $id = intval($_POST['id'] ?? 0);
        if ($id<=0) return json_encode(['status'=>'failed','msg'=>'ID inválido']);
        $del = $this->conn->query("DELETE FROM `company_list` WHERE id='{$id}'");
        return $del ? json_encode(['status'=>'success']) : json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

    // --- GUARDAR PRODUCTO ---
	function save_item() {
		extract($_POST);
	
		// 🔹 CAMPOS OBLIGATORIOS
		$required = [
			'date_purchase' => 'Fecha de compra',
			'supplier_id' => 'Proveedor',
			'company_id' => 'Empresa',
			'cost' => 'Precio de venta',
			'product_cost' => 'Precio de compra'
		];
		foreach ($required as $field => $label) {
			if (!isset($_POST[$field]) || trim($_POST[$field]) === '' || $_POST[$field] === '0') {
				return json_encode([
					'status' => 'failed',
					'msg' => "El campo \"{$label}\" es obligatorio. Por favor complétalo antes de guardar."
				]);
			}
		}
	
		// 🔹 Si se está actualizando, obtener datos previos
		$old = [];
		if (!empty($id)) {
			$get = $this->conn->query("SELECT * FROM item_list WHERE id = '{$id}'");
			if ($get->num_rows > 0) $old = $get->fetch_assoc();
		}
	
		// 🔹 Manejo de imagen
		if (isset($_FILES['foto_producto']) && $_FILES['foto_producto']['tmp_name'] != '') {
			$upload_dir = "uploads/productos/";
			if (!is_dir(base_app . $upload_dir)) mkdir(base_app . $upload_dir, 0777, true);
			$filename = time() . '_' . basename($_FILES['foto_producto']['name']);
			$filepath = $upload_dir . $filename;
	
			// Mover archivo
			if (move_uploaded_file($_FILES['foto_producto']['tmp_name'], base_app . $filepath)) {
				$_POST['foto_producto'] = $filepath;
	
				// 🔹 Eliminar foto anterior si existe y es distinta
				if (!empty($old['foto_producto']) && file_exists(base_app . $old['foto_producto'])) {
					@unlink(base_app . $old['foto_producto']);
				}
			}
		} else {
			// Si no sube nueva foto, conservar la anterior
			if (!empty($old['foto_producto'])) {
				$_POST['foto_producto'] = $old['foto_producto'];
			}
		}
	
		// --- Manejo de Ficha Técnica (PDF) ---
if (isset($_FILES['pdf_path']) && $_FILES['pdf_path']['tmp_name'] != '') {
    $upload_dir = "uploads/pdf/";
    if (!is_dir(base_app . $upload_dir)) mkdir(base_app . $upload_dir, 0777, true);
    $filename = time() . '_' . basename($_FILES['pdf_path']['name']);
    $filepath = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['pdf_path']['tmp_name'], base_app . $filepath)) {
        $_POST['pdf_path'] = $filepath;

        // Eliminar PDF anterior si existe
        if (!empty($old['pdf_path']) && file_exists(base_app . $old['pdf_path'])) {
            @unlink(base_app . $old['pdf_path']);
        }
    }
} else {
    // Mantener PDF anterior si no se carga uno nuevo
    if (!empty($old['pdf_path'])) {
        $_POST['pdf_path'] = $old['pdf_path'];
    }
}


		// 🔹 Preparar campos
		$fields = []; 
		$types = ''; 
		$values = [];
	
		foreach ($_POST as $k => $v) {
			if ($k === 'id') continue;
	
			if ($v === '' || $v === null) $v = null;
	
			$fields[] = "`$k` = ?";
			if (is_int($v)) $types .= 'i';
			elseif (is_double($v) || is_float($v)) $types .= 'd';
			else $types .= 's';
			$values[] = $v;
		}
	
		// 🔹 SQL dinámico
		$sql = empty($id)
			? "INSERT INTO `item_list` SET " . implode(", ", $fields)
			: "UPDATE `item_list` SET " . implode(", ", $fields) . " WHERE id = ?";
		if (!empty($id)) {
			$types .= 'i';
			$values[] = $id;
		}
	
		$stmt = $this->conn->prepare($sql);
		if (!$stmt)
			return json_encode(['status' => 'failed', 'msg' => $this->conn->error]);
	
		$stmt->bind_param($types, ...$values);
	
		try {
			$ok = $stmt->execute();
		} catch (mysqli_sql_exception $e) {
			return json_encode([
				'status' => 'failed',
				'msg' => 'Error al guardar producto: ' . $e->getMessage()
			]);
		}
	
		$stmt->close();
	
		if ($ok) {
			$this->settings->set_flashdata('success', empty($id) ? "Producto guardado correctamente." : "Producto actualizado correctamente.");
			return json_encode(['status' => 'success']);
		}
	
		return json_encode(['status' => 'failed', 'msg' => 'No se pudo guardar el producto.']);
	}
	

    function delete_item(){
        $id = intval($_POST['id'] ?? 0);
        if ($id<=0) return json_encode(['status'=>'failed','msg'=>'ID inválido']);
        $del = $this->conn->query("DELETE FROM `item_list` WHERE id='{$id}'");
        return $del ? json_encode(['status'=>'success']) : json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

/* =======================================================
 *   COTIZACIONES / PURCHASE ORDER
 * ======================================================= */
public function save_po() {
	// ===========================
	// 🔍 VALIDACIONES BÁSICAS
	// ===========================
	$id         = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$id_company = isset($_POST['id_company']) ? intval($_POST['id_company']) : 0;
	$customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;

	if ($id_company <= 0)
		return json_encode(['status' => 'failed', 'msg' => 'No se ha especificado la empresa.']);
	if ($customer_id <= 0)
		return json_encode(['status' => 'failed', 'msg' => 'Debe seleccionar un cliente.']);

	$item_id = $_POST['item_id'] ?? [];
	$qtys    = $_POST['qty'] ?? [];
	$units   = $_POST['unit'] ?? [];
	$prices  = $_POST['price'] ?? [];
	$dlines  = $_POST['discount'] ?? [];

	if (!is_array($item_id) || count($item_id) === 0)
		return json_encode(['status' => 'failed', 'msg' => 'Debe agregar al menos un producto.']);

	// ===========================
	// 🔹 DETERMINAR ESTADO
	// ===========================
	$status_actual = 0;
	if ($id > 0) {
		$res = $this->conn->query("SELECT status FROM purchase_order_list WHERE id = {$id}");
		if ($res && $res->num_rows > 0) $status_actual = intval($res->fetch_assoc()['status']);
	}
	$status_final = isset($_POST['status']) && $_POST['status'] !== '' ? intval($_POST['status']) : $status_actual;
	if (!in_array($status_final, [0,1,2,3])) $status_final = 0;

	// ===========================
	// 🧮 CALCULAR SUBTOTAL, DESCUENTO Y TOTAL
	// ===========================
	$subtotal = 0.0;
	foreach ($item_id as $k => $iid) {
		$q  = isset($qtys[$k])   ? floatval($qtys[$k])   : 0.0;
		$p  = isset($prices[$k]) ? floatval($prices[$k]) : 0.0;
		$dl = isset($dlines[$k]) ? floatval($dlines[$k]) : 0.0;
		$subtotal += ($p - ($p * $dl / 100.0)) * $q;
	}

	$discount_perc = floatval($_POST['discount_perc'] ?? 0);
	$tax_perc      = floatval($_POST['tax_perc'] ?? 0);

	$discount_calc = round($subtotal * $discount_perc / 100.0, 2);
	$base = max(0, $subtotal - $discount_calc);
	$tax_calc = round($base * $tax_perc / 100.0, 2);
	$amount_calc = round($base + $tax_calc, 2);

	// ===========================
	// 🧩 NORMALIZAR FECHA_ENTREGA
	// ===========================
	$fecha_entrega_val = null;
	if (!empty($_POST['fecha_entrega'])) {
		$raw = trim($_POST['fecha_entrega']);
		$dt = DateTime::createFromFormat('Y-m-d', $raw) ?: DateTime::createFromFormat('d/m/Y', $raw);
		if ($dt) $fecha_entrega_val = $dt->format('Y-m-d');
	}

	// ===========================
	// 🧩 CAMPOS PRINCIPALES
	// ===========================
	$fields = [
		'id_company'             => $id_company,
		'customer_id'            => $customer_id,
		'supplier_id'            => isset($_POST['supplier_id']) && $_POST['supplier_id'] !== '' ? intval($_POST['supplier_id']) : 'NULL',
		'date_exp'               => $_POST['date_exp'] ?? date('Y-m-d'),
		'fecha_entrega'          => $fecha_entrega_val,
		'rq'                     => trim($_POST['rq'] ?? ''),
		'metodo_pago'            => trim($_POST['metodo_pago'] ?? ''),
		'date_pago'              => !empty($_POST['date_pago']) ? $_POST['date_pago'] : null,
		'pago_efectivo'          => !empty($_POST['pago_efectivo']) ? $_POST['pago_efectivo'] : null,
		'oc'                     => trim($_POST['oc'] ?? ''),
		'num_factura'            => trim($_POST['num_factura'] ?? ''),
		'date_carga_portal'      => !empty($_POST['date_carga_portal']) ? $_POST['date_carga_portal'] : null,
		'folio_fiscal'           => trim($_POST['folio_fiscal'] ?? ''),
		'folio_comprobante_pago' => trim($_POST['folio_comprobante_pago'] ?? ''),
		'num_cheque'             => trim($_POST['num_cheque'] ?? ''),
		'discount_perc'          => $discount_perc,
		'discount'               => $discount_calc,
		'tax_perc'               => $tax_perc,
		'tax'                    => $tax_calc,
		'amount'                 => $amount_calc,
		'remarks'                => trim($_POST['remarks'] ?? ''),
		'status'                 => $status_final
	];

	// ===========================
	// ⚙️ CONVERSIÓN A SQL
	// ===========================
	$data = "";
	foreach ($fields as $k => $v) {
		if ($v === null || $v === 'NULL') $data .= " `{$k}`=NULL, ";
		else $data .= " `{$k}`='" . $this->conn->real_escape_string($v) . "', ";
	}
	$data = rtrim($data, ', ');

	// ===========================
	// 💾 TRANSACCIÓN
	// ===========================
	$this->conn->begin_transaction();
	try {
		// ==============================
		// DATOS DE EMPRESA Y PREFIJO
		// ==============================
		$empresa = $this->conn->query("SELECT name, identificador FROM company_list WHERE id = {$id_company}")->fetch_assoc();
		$prefijo = $empresa ? strtoupper(preg_replace('/\s+/', '', substr($empresa['name'], 0, 5))) : 'COT';
		$identificador = $empresa ? strtoupper(preg_replace('/\s+/', '', substr($empresa['identificador'], 0, 5))) : 'EMP';

		$this->conn->query("LOCK TABLES purchase_order_list WRITE");

		$res = $this->conn->query("SELECT COUNT(*) as total FROM purchase_order_list WHERE id_company = {$id_company}");
		$num = $res ? ($res->fetch_assoc()['total'] + 1) : 1;

		$anio = date("y");
		if ($id_company % 2 === 0) {
			$po_code = "{$prefijo}-" . str_pad($num, 3, '0', STR_PAD_LEFT) . $anio . "C";
		} else {
			$po_code = "C" . str_pad($num, 3, '0', STR_PAD_LEFT) . $identificador;
		}
		$po_code = preg_replace('/[^A-Z0-9\-]/', '', strtoupper($po_code));

		if ($id > 0) {
			$this->conn->query("DELETE FROM po_items WHERE po_id = {$id}");
			$sql = "UPDATE purchase_order_list SET {$data}, date_updated = NOW() WHERE id = {$id}";
			if (!$this->conn->query($sql)) throw new Exception("Error al actualizar: " . $this->conn->error);
		} else {
			$sql = "INSERT INTO purchase_order_list SET {$data}, po_code='{$po_code}', date_created=NOW()";
			if (!$this->conn->query($sql)) throw new Exception("Error al crear: " . $this->conn->error);
			$id = $this->conn->insert_id;
		}

		$this->conn->commit();
		$this->conn->query("UNLOCK TABLES");

		// ==============================
		// GUARDAR PRODUCTOS
		// ==============================
		$stmt = $this->conn->prepare("
			INSERT INTO po_items (po_id, item_id, quantity, unit, price, discount)
			VALUES (?, ?, ?, ?, ?, ?)
		");
		if (!$stmt) throw new Exception("Error al preparar statement: " . $this->conn->error);

		foreach ($item_id as $k => $iid) {
			$iid       = intval($iid);
			$cantidad  = isset($qtys[$k])   ? floatval($qtys[$k])   : 0.0;
			$unidad    = isset($units[$k])  ? trim($units[$k])      : '';
			$precio    = isset($prices[$k]) ? floatval($prices[$k]) : 0.0;
			$desc_line = isset($dlines[$k]) ? floatval($dlines[$k]) : 0.0;
			$stmt->bind_param('iidsdd', $id, $iid, $cantidad, $unidad, $precio, $desc_line);
			$stmt->execute();
		}
		$stmt->close();

		$this->conn->commit();
		return json_encode([
			'status' => 'success',
			'id'     => $id,
			'msg'    => ($id > 0 ? 'Cotización actualizada correctamente.' : 'Cotización guardada correctamente.')
		]);
	} catch (Exception $e) {
		$this->conn->rollback();
		return json_encode(['status' => 'failed', 'msg' => 'Error al guardar: ' . $e->getMessage()]);
	}
}

		
	public function delete_po() {
		// Validar ID recibido
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		if ($id <= 0) {
			return json_encode(['status' => 'failed', 'msg' => 'ID inválido o no especificado.']);
		}
	
		// Verificar existencia del registro
		$chk = $this->conn->query("SELECT id FROM purchase_order_list WHERE id = {$id}");
		if (!$chk || $chk->num_rows == 0) {
			return json_encode(['status' => 'failed', 'msg' => 'No se encontró la cotización a eliminar.']);
		}
	
		// Iniciar transacción para asegurar consistencia
		$this->conn->begin_transaction();
	
		try {
			// Eliminar ítems asociados primero
			$this->conn->query("DELETE FROM po_items WHERE po_id = {$id}");
	
			// Eliminar cotización principal
			$delete_main = $this->conn->query("DELETE FROM purchase_order_list WHERE id = {$id}");
	
			if (!$delete_main) {
				throw new Exception('Error al eliminar la cotización principal: ' . $this->conn->error);
			}
	
			// Confirmar cambios
			$this->conn->commit();
	
			return json_encode(['status' => 'success']);
		} catch (Exception $e) {
			// Revertir en caso de error
			$this->conn->rollback();
			return json_encode(['status' => 'failed', 'msg' => 'Error al eliminar: ' . $e->getMessage()]);
		}
	}

// =======================================================
// 🔄 ACTUALIZAR ESTADO DE COTIZACIÓN
// =======================================================
public function update_po_status() {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;

    if ($id <= 0) {
        return json_encode(['status' => 'failed', 'error' => 'ID inválido']);
    }

    try {
        $stmt = $this->conn->prepare("UPDATE `purchase_order_list` SET `status` = ? WHERE `id` = ?");
        if (!$stmt) {
            return json_encode(['status' => 'failed', 'error' => 'Error al preparar: ' . $this->conn->error]);
        }

        $stmt->bind_param("ii", $status, $id);
        $stmt->execute();
        $stmt->close();

        return json_encode(['status' => 'success']);
    } catch (mysqli_sql_exception $e) {
        return json_encode(['status' => 'failed', 'error' => $e->getMessage()]);
    }
}

	
// --- Guardar y eliminar Recepción (con company_id) ---
function save_receiving()
{
    extract($_POST);
    $resp = ['status' => 'failed'];

    // =============================
    // 🔍 Validar que venga empresa
    // =============================
    $company_id = intval($_POST['company_id'] ?? 0);
    if ($company_id <= 0) {
        return json_encode(['status' => 'failed', 'msg' => 'No se ha especificado la empresa.']);
    }

    // =============================
    // 📦 Generar código de backorder si aplica
    // =============================
    if (empty($_POST['id'])) {
        $prefix = "BO";
        $code = sprintf("%'.04d", 1);
        while (true) {
            $check_code = $this->conn->query("SELECT * FROM `back_order_list` WHERE bo_code ='{$prefix}-{$code}'")->num_rows;
            if ($check_code > 0) $code = sprintf("%'.04d", $code + 1);
            else break;
        }
        $_POST['bo_code'] = "{$prefix}-{$code}";
    }

    // =============================
    // 🧩 Guardar encabezado
    // =============================
    $data = "";
    foreach ($_POST as $k => $v) {
        if (in_array($k, ['id', 'bo_code', 'supplier_id', 'po_id'])) continue;
        if (is_array($v)) continue;
        if (!is_numeric($v)) $v = $this->conn->real_escape_string($v);
        if (!empty($data)) $data .= ", ";
        $data .= " `{$k}` = '{$v}' ";
    }
    $data .= ", `company_id` = '{$company_id}'"; // 🔹 Agregar empresa

    if (empty($id)) {
        $sql = "INSERT INTO `receiving_list` SET {$data}";
    } else {
        $sql = "UPDATE `receiving_list` SET {$data} WHERE id = '{$id}'";
    }

    $save = $this->conn->query($sql);
    if (!$save) {
        return json_encode(['status' => 'failed', 'msg' => $this->conn->error]);
    }

    $r_id = empty($id) ? $this->conn->insert_id : $id;

    // =============================
    // 🧾 Registrar los productos recibidos
    // =============================
    if (!empty($id)) {
        $old_stocks = $this->conn->query("SELECT stock_ids FROM `receiving_list` WHERE id='{$id}'")->fetch_assoc()['stock_ids'] ?? '';
        if (!empty($old_stocks)) {
            $this->conn->query("DELETE FROM `stock_list` WHERE id IN ({$old_stocks})");
        }
    }

    $stock_ids = [];
    foreach ($item_id as $k => $v) {
        $sql = "INSERT INTO stock_list (`item_id`,`quantity`,`price`,`unit`,`total`,`type`) 
                VALUES ('{$v}','{$qty[$k]}','{$price[$k]}','{$unit[$k]}','{$total[$k]}','1')";
        $this->conn->query($sql);
        $stock_ids[] = $this->conn->insert_id;
    }

    if (count($stock_ids) > 0) {
        $stock_ids_str = implode(',', $stock_ids);
        $this->conn->query("UPDATE `receiving_list` SET stock_ids='{$stock_ids_str}' WHERE id='{$r_id}'");
    }

    // =============================
    // 🧾 Actualizar estado de cotización
    // =============================
    $this->conn->query("UPDATE `purchase_order_list` SET status = 2 WHERE id = '{$po_id}'");

    // =============================
    // ✅ Respuesta final
    // =============================
    $this->settings->set_flashdata('success', empty($id)
        ? "Recepción registrada correctamente."
        : "Recepción actualizada correctamente."
    );

    return json_encode([
        'status' => 'success',
        'id' => $r_id,
        'company_id' => $company_id
    ]);
}
function delete_receiving()
{
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0)
        return json_encode(['status' => 'failed', 'msg' => 'ID inválido']);

    // 🔹 Obtener datos de la recepción
    $res = $this->conn->query("SELECT stock_ids, form_id, from_order, company_id FROM receiving_list WHERE id={$id}")->fetch_assoc();
    if (!$res)
        return json_encode(['status' => 'failed', 'msg' => 'No se encontró la recepción']);

    // 🔹 Eliminar registros de stock
    if (!empty($res['stock_ids']))
        $this->conn->query("DELETE FROM stock_list WHERE id IN ({$res['stock_ids']})");

    // 🔹 Eliminar recepción
    $this->conn->query("DELETE FROM receiving_list WHERE id={$id}");

    // 🔹 Si era de cotización, revertir su estado
    if ($res['from_order'] == 1)
        $this->conn->query("UPDATE purchase_order_list SET status=0 WHERE id={$res['form_id']}");

    $this->settings->set_flashdata('success', "Recepción eliminada correctamente.");

    // 🔹 Devolver también company_id
    return json_encode([
        'status' => 'success',
        'company_id' => intval($res['company_id'])
    ]);
}


// --- Guardar y eliminar Devolución ---
function save_return() { 
	if (empty($_POST['id'])) {
		$prefix = "R";
		$code = sprintf("%'.04d", 1);
		while (true) {
			$check_code = $this->conn->query("SELECT * FROM `return_list` where return_code ='" . $prefix . '-' . $code . "' ")->num_rows;
			if ($check_code > 0) {
				$code = sprintf("%'.04d", $code + 1);
			} else {
				break;
			}
		}
		$_POST['return_code'] = $prefix . "-" . $code;
	}
	extract($_POST);
	$data = "";
	foreach ($_POST as $k => $v) {
		if (!in_array($k, array('id')) && !is_array($_POST[$k])) {
			if (!is_numeric($v))
				$v = $this->conn->real_escape_string($v);
			if (!empty($data)) $data .= ", ";
			$data .= " `{$k}` = '{$v}' ";
		}
	}
	if (empty($id)) {
		$sql = "INSERT INTO `return_list` set {$data}";
	} else {
		$sql = "UPDATE `return_list` set {$data} where id = '{$id}'";
	}
	$save = $this->conn->query($sql);
	if ($save) {
		$resp['status'] = 'success';
		if (empty($id))
			$return_id = $this->conn->insert_id;
		else
			$return_id = $id;
		$resp['id'] = $return_id;
		$data = "";
		$sids = array();
		$get = $this->conn->query("SELECT * FROM `return_list` where id = '{$return_id}'");
		if ($get->num_rows > 0) {
			$res = $get->fetch_array();
			if (!empty($res['stock_ids'])) {
				$this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
			}
		}
		foreach ($item_id as $k => $v) {
			$sql = "INSERT INTO `stock_list` set item_id='{$v}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}', `price` = '{$price[$k]}', `total` = '{$total[$k]}', `type` = 2 ";
			$save = $this->conn->query($sql);
			if ($save) {
				$sids[] = $this->conn->insert_id;
			}
		}
		$sids = implode(',', $sids);
		$this->conn->query("UPDATE `return_list` set stock_ids = '{$sids}' where id = '{$return_id}'");
	} else {
		$resp['status'] = 'failed';
		$resp['msg'] = 'An error occured. Error: ' . $this->conn->error;
	}
	if ($resp['status'] == 'success') {
		if (empty($id)) {
			$this->settings->set_flashdata('success', " El nuevo registro de artículo devuelto se creó con éxito.");
		} else {
			$this->settings->set_flashdata('success', " Registro de artículos devueltos actualizado con éxito.");
		}
	}

	return json_encode($resp);
 }
function delete_return()
{
	$id = intval($_POST['id'] ?? 0);
	if ($id <= 0) return json_encode(['status'=>'failed','msg'=>'ID inválido']);

	$res = $this->conn->query("SELECT stock_ids FROM return_list WHERE id={$id}")->fetch_assoc();
	if (!empty($res['stock_ids'])) $this->conn->query("DELETE FROM stock_list WHERE id IN ({$res['stock_ids']})");
	$this->conn->query("DELETE FROM return_list WHERE id={$id}");
	return json_encode(['status'=>'success','msg'=>'Devolución eliminada correctamente']);
}

// --- Guardar y eliminar Venta ---
function save_sale() { 

	if (empty($_POST['id'])) {
		echo '<pre>';
		$prefix = "Venta";
		$code = sprintf("%'.04d", 1);
		while (true) {
			$check_code = $this->conn->query("SELECT * FROM `sales_list` where sales_code ='" . $prefix . '-' . $code . "' ")->num_rows;			
			if ($check_code > 0) {
				$code = sprintf("%'.04d", $code + 1);
			} else {
				break;
			}
		}
		$_POST['sales_code'] = $prefix . "-" . $code;
	}
	extract($_POST);
	$data = "";
	foreach ($_POST as $k => $v) {
		if (!in_array($k, array('id')) && !is_array($_POST[$k])) {
			if (!is_numeric($v))
				$v = $this->conn->real_escape_string($v);
			if (!empty($data)) $data .= ", ";
			$data .= " `{$k}` = '{$v}' ";
		}
	}
	if (empty($id)) {
		$sql = "INSERT INTO `sales_list` set {$data}";
		//echo $sql;
	} else {
		$sql = "UPDATE `sales_list` set {$data} where id = '{$id}'";
	}
	$save = $this->conn->query($sql);
	if ($save) {
		$resp['status'] = 'success';
		if (empty($id))
			$sale_id = $this->conn->insert_id;
		else
			$sale_id = $id;
		$resp['id'] = $sale_id;
		$data = "";
		$sids = array();
		$get = $this->conn->query("SELECT * FROM `sales_list` where id = '{$sale_id}'");
		if ($get->num_rows > 0) {
			$res = $get->fetch_array();
			if (!empty($res['stock_ids'])) {
				$this->conn->query("DELETE FROM `stock_list` where id in ({$res['stock_ids']}) ");
			}
		}
		foreach ($item_id as $k => $v) {
			$sql = "INSERT INTO `stock_list` set item_id='{$v}', `quantity` = '{$qty[$k]}', `unit` = '{$unit[$k]}', `price` = '{$price[$k]}', `total` = '{$total[$k]}', `type` = 2 ";
			$save = $this->conn->query($sql);
			if ($save) {
				$sids[] = $this->conn->insert_id;
			}
		}
		$sids = implode(',', $sids);
		$this->conn->query("UPDATE `sales_list` set stock_ids = '{$sids}' where id = '{$sale_id}'");
	} else {
		$resp['status'] = 'failed';
		$resp['msg'] = 'An error occured. Error: ' . $this->conn->error;
	}
	if ($resp['status'] == 'success') {
		if (empty($id)) {
			$this->settings->set_flashdata('success', " Nuevo registro de ventas fue creado con éxito.");
		} else {
			$this->settings->set_flashdata('success', " Registro de ventas actualizado con éxito.");
		}
	}

	return json_encode($resp);
 }
function delete_sale()
{
	$id = intval($_POST['id'] ?? 0);
	if ($id <= 0) return json_encode(['status'=>'failed','msg'=>'ID inválido']);

	$res = $this->conn->query("SELECT stock_ids FROM sales_list WHERE id={$id}")->fetch_assoc();
	if (!empty($res['stock_ids'])) $this->conn->query("DELETE FROM stock_list WHERE id IN ({$res['stock_ids']})");
	$this->conn->query("DELETE FROM sales_list WHERE id={$id}");
	return json_encode(['status'=>'success','msg'=>'Venta eliminada correctamente']);
}
    // --- BUSCAR PRODUCTOS ---
	function search_products(){
		$q = trim($_GET['q'] ?? '');
		if(strlen($q) < 2){
			return json_encode([]); // mínimo 2 caracteres
		}
	
		// ===============================
		// BUSCAR PRODUCTOS ACTIVOS POR SKU (name) O DESCRIPCIÓN
		// ===============================
		$sql = "
			SELECT 
				i.id,
				i.name AS name,              -- SKU
				i.description AS description, -- Descripción
				i.date_purchase,             -- Fecha de compra
				i.stock,
				i.product_cost AS precio_compra,
				i.cost AS precio_venta
			FROM item_list i
			WHERE i.status = 1
			  AND (
					i.name LIKE CONCAT('%', ?, '%') 
				 OR i.description LIKE CONCAT('%', ?, '%')
			  )
			ORDER BY i.description ASC
			LIMIT 50
		";
	
		$stmt = $this->conn->prepare($sql);
		if(!$stmt){
			return json_encode(['status'=>'failed','msg'=>$this->conn->error]);
		}
	
		$stmt->bind_param('ss', $q, $q);
		$stmt->execute();
		$res = $stmt->get_result();
	
		$data = [];
		while($row = $res->fetch_assoc()){
			$data[] = [
				'id' => (int)$row['id'],
				'sku' => $row['name'] ?: '—',                       // SKU
				'descripcion' => $row['description'] ?: '',         // Descripción
				'fecha_compra' => $row['date_purchase'] ?: '—',     // Fecha de compra
				'stock' => (float)$row['stock'],
				'precio_compra' => (float)$row['precio_compra'],
				'precio_venta' => (float)$row['precio_venta']
			];
		}
	
		$stmt->close();
		header('Content-Type: application/json; charset=utf-8');
		return json_encode($data);
	}
	
	// --- CLIENTES ---
	function save_customer(){
		try {
			// === Validaciones y sanitización ===
			$id      = isset($_POST['id']) ? (int)$_POST['id'] : 0;
			$name    = trim($_POST['name'] ?? '');
			$rfc     = trim($_POST['rfc'] ?? '');
			$email   = trim($_POST['email'] ?? '');
			$contact = trim($_POST['contact'] ?? '');
			$address = trim($_POST['address'] ?? '');
			$status  = isset($_POST['status']) ? (int)$_POST['status'] : 1;
	
			if ($name === '') {
				return json_encode(['status'=>'failed','msg'=>'El nombre es obligatorio']);
			}
			
	
			// === INSERT o UPDATE ===
			if ($id > 0) {
				// 🟦 UPDATE
				$stmt = $this->conn->prepare("UPDATE `customer_list`
					SET `name`=?, `rfc`=?, `email`=?, `contact`=?, `address`=?, `status`=? 
					WHERE `id`=?");
				$stmt->bind_param("ssssssi", $name, $rfc, $email, $contact, $address, $status, $id);
			} else {
				// 🟩 INSERT
				$stmt = $this->conn->prepare("INSERT INTO `customer_list`
					(`name`,`rfc`,`email`,`contact`,`address`,`status`)
					VALUES (?,?,?,?,?,?)");
				$stmt->bind_param("sssssi", $name, $rfc, $email, $contact, $address, $status);
			}
	
			if (!$stmt->execute()) {
				return json_encode(['status'=>'failed','msg'=>'Error al guardar el cliente']);
			}
	
			return json_encode([
				'status' => 'success',
				'msg' => $id > 0 ? 'Cliente actualizado correctamente' : 'Cliente agregado correctamente'
			]);
	
		} catch (Throwable $e) {
			return json_encode(['status'=>'failed','msg'=>'Error interno: '.$e->getMessage()]);
		}
	}
	
	
	function delete_customer(){
		try {
			$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
			if ($id <= 0) {
				return json_encode(['status'=>'failed','msg'=>'ID inválido']);
			}
			$stmt = $this->conn->prepare("DELETE FROM `customer_list` WHERE `id`=?");
			$stmt->bind_param("i", $id);
			if (!$stmt->execute()) {
				return json_encode(['status'=>'failed','msg'=>'No se pudo eliminar']);
			}
			return json_encode(['status'=>'success']);
		} catch (Throwable $e) {
			return json_encode(['status'=>'failed','msg'=>$e->getMessage()]);
		}
	}

/* =======================================================
 *   BUSCADOR DE CLIENTES ACTIVOS
 * ======================================================= */
function search_customers(){
    $q = trim($_GET['q'] ?? '');
    if(strlen($q) < 2){
        return json_encode([]); // mínimo 2 caracteres
    }

    // ===============================
    // BUSCAR CLIENTES ACTIVOS POR NOMBRE, EMAIL, CONTACTO O DIRECCIÓN
    // ===============================
    $sql = "
        SELECT 
            c.id,
            c.name,
            c.email,
            c.contact,
            c.address,
			c.rfc
        FROM customer_list c
        WHERE c.status = 1
          AND (
                c.name LIKE CONCAT('%', ?, '%') 
			 OR c.email LIKE CONCAT('%', ?, '%')
             OR c.contact LIKE CONCAT('%', ?, '%')
             OR c.address LIKE CONCAT('%', ?, '%')
			 OR c.rfc LIKE CONCAT('%', ?, '%')
          )
        ORDER BY c.name ASC
        LIMIT 50
    ";

    $stmt = $this->conn->prepare($sql);
    if(!$stmt){
        return json_encode(['status'=>'failed','msg'=>$this->conn->error]);
    }

    // Se pasan cuatro parámetros porque hay cuatro condiciones LIKE
    $stmt->bind_param('sssss', $q, $q, $q, $q, $q);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = [];
    while($row = $res->fetch_assoc()){
        $data[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'] ?: '',
			'email' => $row['email'] ?: '',
            'contact' => $row['contact'] ?: '',
            'address' => $row['address'] ?: '',
			'rfc' => $row['rfc'] ?: ''
        ];
    }

    $stmt->close();
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($data);
}


	
	
}

/* ======================
 *        ROUTER
 * ======================= */
$Master  = new Master();
$action  = strtolower($_GET['f'] ?? 'none');
$sysset  = new SystemSettings();

try {
  switch ($action) {
    case 'save_supplier':   $out = $Master->save_supplier(); break;
    case 'delete_supplier': $out = $Master->delete_supplier(); break;
    case 'save_company':    $out = $Master->save_company(); break;
    case 'delete_company':  $out = $Master->delete_company(); break;
    case 'save_item':       $out = $Master->save_item(); break;
    case 'delete_item':     $out = $Master->delete_item(); break;
    case 'save_po':         $out = $Master->save_po(); break;
    case 'delete_po':       $out = $Master->delete_po(); break;
	case 'update_po_status':$out = $Master->update_po_status(); break;
    case 'save_receiving':  $out = $Master->save_receiving(); break;
    case 'delete_receiving':$out = $Master->delete_receiving(); break;
    case 'save_return':     $out = $Master->save_return(); break;
    case 'delete_return':   $out = $Master->delete_return(); break;
    case 'save_sale':       $out = $Master->save_sale(); break;
    case 'delete_sale':     $out = $Master->delete_sale(); break;
    case 'search_products': $out = $Master->search_products(); break;
	case 'save_customer':	$out = $Master->save_customer(); break;
	case 'delete_customer':	$out = $Master->delete_customer(); break;
	case 'search_customers': $out = $Master->search_customers(); break;
    default:                $out = json_encode(['status'=>'failed','msg'=>'Acción no válida']);
  }
} catch (Throwable $e) {
	header('Content-Type: text/plain; charset=utf-8');
	echo "⚠️ Excepción detectada:\n\n";
	echo $e->getMessage() . "\n\n";
	echo $e->getTraceAsString();
	exit;
  }
  

while (ob_get_level() > 0) { ob_end_clean(); }

header('Content-Type: application/json; charset=utf-8');
if (!is_string($out)) { $out = json_encode($out); }
echo $out;
exit;
?>
