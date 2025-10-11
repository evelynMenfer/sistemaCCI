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
    function save_company(){ 
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if ($k=='id') continue;
            if (!empty($data)) $data .= ",";
            $data .= " `{$k}`='{$v}' ";
        }
        $check = $this->conn->query("SELECT * FROM `company_list` WHERE `name` = '{$name}' ".(!empty($id)?" AND id != {$id} ":""))->num_rows;
        if ($this->capture_err()) return $this->capture_err();
        if ($check > 0) return json_encode(['status'=>'failed','msg'=>'Nombre de empresa ya existe.']);

        $sql = empty($id) ? "INSERT INTO `company_list` SET {$data}" : "UPDATE `company_list` SET {$data} WHERE id='{$id}'";
        $save = $this->conn->query($sql);
        if ($save){
            $this->settings->set_flashdata('success', empty($id)?"Empresa guardada.":"Empresa actualizada.");
            return json_encode(['status'=>'success']);
        }
        return json_encode(['status'=>'failed','err'=>$this->conn->error." [{$sql}]"]);
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
        $fields=[]; $types=''; $values=[];
        foreach($_POST as $k=>$v){
            if ($k==='id') continue;
            $fields[]="`$k`=?";
            if (is_int($v)) $types.='i';
            elseif (is_double($v)||is_float($v)) $types.='d';
            else $types.='s';
            $values[]=$v;
        }
        $sql = empty($id) ? "INSERT INTO `item_list` SET ".implode(", ",$fields)
                          : "UPDATE `item_list` SET ".implode(", ",$fields)." WHERE id=?";
        if(!empty($id)){ $types.='i'; $values[]=$id; }

        $stmt = $this->conn->prepare($sql);
        if(!$stmt) return json_encode(['status'=>'failed','err'=>$this->conn->error." [$sql]"]);
        $stmt->bind_param($types,...$values);
        $ok = $stmt->execute();
        $stmt->close();

        if($ok){
            $this->settings->set_flashdata('success', empty($id)?"Producto guardado.":"Producto actualizado.");
            return json_encode(['status'=>'success']);
        }
        return json_encode(['status'=>'failed','err'=>$this->conn->error]);
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
	function save_po(){
		// 🔹 Asegurar proveedor válido o genérico
		$defaultSupplierId = 1;
		$checkSupplier = $this->conn->query("SELECT id FROM supplier_list WHERE id = {$defaultSupplierId}");
		if(!$checkSupplier || $checkSupplier->num_rows == 0){
			$this->conn->query("INSERT INTO supplier_list (id, name, address, status) VALUES (1, 'Proveedor Genérico', '', 1)");
		}
	
		// 🔹 Si no viene supplier_id o es inválido, usar el genérico
		$supplier_id = intval($_POST['supplier_id'] ?? 0);
		if ($supplier_id <= 0) {
			$_POST['supplier_id'] = $defaultSupplierId;
		}
	
		// 🔹 Validar productos
		if (empty($_POST['item_id']) || count($_POST['item_id']) == 0) {
			return json_encode(['status'=>'failed','msg'=>'Debes agregar al menos un producto antes de guardar.']);
		}
	
		// 🔹 Generar código si es nuevo
		if (empty($_POST['id'])) {
			$prefix = "C";
			$num = 1;
			do {
				$code = sprintf("%'.04d", $num);
				$exists = $this->conn->query("SELECT 1 FROM purchase_order_list WHERE po_code='{$prefix}{$code}'")->num_rows;
				$num++;
			} while($exists);
			$_POST['po_code'] = $prefix.$code;
		}
	
		// 🔹 Conversor seguro a float (convierte vacío o NULL a 0.0)
		$toFloat = function($v){
			if ($v === null || $v === '' || is_bool($v)) return 0.0;
			$v = trim((string)$v);
			$v = str_replace([',',' '], ['',''], $v);
			return is_numeric($v) ? (float)$v : 0.0;
		};
	
		// 🔹 Campos que no deben ir en la cabecera
		$exclude = ['id','item_id','qty','price','unit','discount','total','amount','tax'];
		$set = [];
		foreach($_POST as $k=>$v){
			if (in_array($k,$exclude) || is_array($v)) continue;
			if (stripos($k, 'perc') !== false || stripos($k, 'amount') !== false || stripos($k, 'tax') !== false || stripos($k, 'discount') !== false) {
				// 🔹 Si es campo numérico, convertir vacío a 0
				$val = $toFloat($v);
			} else {
				$val = $this->conn->real_escape_string((string)$v);
			}
			$set[] = "`$k`='{$val}'";
		}
	
		$id = intval($_POST['id'] ?? 0);
		$this->conn->begin_transaction();
	
		try {
			// 🔹 Guardar cabecera
			$sql = $id==0
				? "INSERT INTO purchase_order_list SET ".implode(", ",$set)
				: "UPDATE purchase_order_list SET ".implode(", ",$set)." WHERE id='{$id}'";
			if(!$this->conn->query($sql)) throw new Exception("Cabecera: ".$this->conn->error);
	
			$po_id = $id==0 ? $this->conn->insert_id : $id;
	
			// 🔹 Limpiar productos antiguos
			$this->conn->query("DELETE FROM po_items WHERE po_id={$po_id}");
	
			// 🔹 Insertar productos nuevos
			$stmt = $this->conn->prepare("
				INSERT INTO po_items (po_id,item_id,quantity,price,unit,discount,total)
				VALUES (?,?,?,?,?,?,?)
			");
			if(!$stmt) throw new Exception("Error prepare: ".$this->conn->error);
	
			$stmt->bind_param("iiddsdd", $b_po,$b_item,$b_qty,$b_price,$b_unit,$b_disc,$b_total);
			$b_po = $po_id;
			$subtotal = 0.0;
	
			$item_ids = $_POST['item_id'] ?? [];
			$qtys = $_POST['qty'] ?? [];
			$prices = $_POST['price'] ?? [];
			$units = $_POST['unit'] ?? [];
			$discounts = $_POST['discount'] ?? [];
	
			foreach($item_ids as $k=>$iid){
				$b_item = intval($iid);
				if ($b_item <= 0) continue;
				$b_qty = $toFloat($qtys[$k] ?? 0);
				$b_price = $toFloat($prices[$k] ?? 0);
				$b_unit = (string)($units[$k] ?? '');
				$b_disc = $toFloat($discounts[$k] ?? 0);
				$b_total = round(($b_price - ($b_price*$b_disc/100))*$b_qty,2);
	
				if(!$stmt->execute()) throw new Exception("Item: ".$stmt->error);
				$subtotal += $b_total;
			}
			$stmt->close();
	
			// 🔹 Calcular totales
			$discount_perc = $toFloat($_POST['discount_perc'] ?? 0);
			$tax_perc = $toFloat($_POST['tax_perc'] ?? 0);
			$discount = round($subtotal * $discount_perc / 100, 2);
			$tax = round(($subtotal - $discount) * $tax_perc / 100, 2);
			$amount = round($subtotal - $discount + $tax, 2);
	
			// 🔹 Actualizar totales
			$sql = "
				UPDATE purchase_order_list SET
					discount='{$discount}',
					discount_perc='{$discount_perc}',
					tax='{$tax}',
					tax_perc='{$tax_perc}',
					amount='{$amount}'
				WHERE id='{$po_id}'
			";
			if(!$this->conn->query($sql)) throw new Exception("Totales: ".$this->conn->error);
	
			$this->conn->commit();
			$this->settings->set_flashdata('success',$id==0 ? "Cotización creada correctamente." : "Cotización actualizada correctamente.");
	
			return json_encode(['status'=>'success','id'=>$po_id]);
	
		} catch (Exception $e) {
			$this->conn->rollback();
			return json_encode(['status'=>'failed','msg'=>'EXCEPCIÓN: '.$e->getMessage()]);
		}
	}
	

	function delete_po(){
		$id = intval($_POST['id'] ?? 0);
		if ($id<=0) return json_encode(['status'=>'failed','msg'=>'ID inválido']);
		$this->conn->query("DELETE FROM po_items WHERE po_id={$id}");
		$del = $this->conn->query("DELETE FROM purchase_order_list WHERE id={$id}");
		return $del ? json_encode(['status'=>'success','msg'=>'Cotización eliminada correctamente'])
					: json_encode(['status'=>'failed','msg'=>$this->conn->error]);
	}

    // --- Guardar y eliminar Recepción ---
function save_receiving() { 
	if (empty($_POST['id'])) {
		$prefix = "BO";
		$code = sprintf("%'.04d", 1);
		while (true) {
			$check_code = $this->conn->query("SELECT * FROM `back_order_list` where bo_code ='" . $prefix . '-' . $code . "' ")->num_rows;
			if ($check_code > 0) {
				$code = sprintf("%'.04d", $code + 1);
			} else {
				break;
			}
		}
		$_POST['bo_code'] = $prefix . "-" . $code;
	} else {
		$get = $this->conn->query("SELECT * FROM back_order_list where receiving_id = '{$_POST['id']}' ");
		if ($get->num_rows > 0) {
			$res = $get->fetch_array();
			$bo_id = $res['id'];
			$_POST['bo_code'] = $res['bo_code'];
		} else {

			$prefix = "BO";
			$code = sprintf("%'.04d", 1);
			while (true) {
				$check_code = $this->conn->query("SELECT * FROM `back_order_list` where bo_code ='" . $prefix . '-' . $code . "' ")->num_rows;
				if ($check_code > 0) {
					$code = sprintf("%'.04d", $code + 1);
				} else {
					break;
				}
			}
			$_POST['bo_code'] = $prefix . "-" . $code;
		}
	}
	extract($_POST);
	$data = "";
	foreach ($_POST as $k => $v) {
		if (!in_array($k, array('id', 'bo_code', 'supplier_id', 'po_id')) && !is_array($_POST[$k])) {
			if (!is_numeric($v))
				$v = $this->conn->real_escape_string($v);
			if (!empty($data)) $data .= ", ";
			$data .= " `{$k}` = '{$v}' ";
		}
	}
	if (empty($id)) {
		$sql = "INSERT INTO `receiving_list` set {$data}";
	} else {
		$sql = "UPDATE `receiving_list` set {$data} where id = '{$id}'";
	}
	$save = $this->conn->query($sql);
	if ($save) {
		$resp['status'] = 'success';
		if (empty($id))
			$r_id = $this->conn->insert_id;
		else
			$r_id = $id;
		$resp['id'] = $r_id;
		if (!empty($id)) {
			$stock_ids = $this->conn->query("SELECT stock_ids FROM `receiving_list` where id = '{$id}'")->fetch_array()['stock_ids'];
			$this->conn->query("DELETE FROM `stock_list` where id in ({$stock_ids})");
		}
		$stock_ids = array();
		foreach ($item_id as $k => $v) {
			if (!empty($data)) $data .= ", ";
			$sql = "INSERT INTO stock_list (`item_id`,`quantity`,`price`,`unit`,`total`,`type`) VALUES ('{$v}','{$qty[$k]}','{$price[$k]}','{$unit[$k]}','{$total[$k]}','1')";
			$this->conn->query($sql);
			$stock_ids[] = $this->conn->insert_id;
			if ($qty[$k] < $oqty[$k]) {
				$bo_ids[] = $k;
			}
		}
		if (count($stock_ids) > 0) {
			$stock_ids = implode(',', $stock_ids);
			$this->conn->query("UPDATE `receiving_list` set stock_ids = '{$stock_ids}' where id = '{$r_id}'");
		}
		if (isset($bo_ids)) {
			$this->conn->query("UPDATE `purchase_order_list` set status = 1 where id = '{$po_id}'");
			if ($from_order == 2) {
				$this->conn->query("UPDATE `back_order_list` set status = 1 where id = '{$form_id}'");
			}
			if (!isset($bo_id)) {
				$sql = "INSERT INTO `back_order_list` set 
						bo_code = '{$bo_code}',	
						receiving_id = '{$r_id}',	
						po_id = '{$po_id}',	
						supplier_id = '{$supplier_id}',	
						discount_perc = '{$discount_perc}',	
						tax_perc = '{$tax_perc}'
					";
			} else {
				$sql = "UPDATE `back_order_list` set 
						receiving_id = '{$r_id}',	
						po_id = '{$form_id}',	
						supplier_id = '{$supplier_id}',	
						discount_perc = '{$discount_perc}',	
						tax_perc = '{$tax_perc}',
						where bo_id = '{$bo_id}'
					";
			}
			$bo_save = $this->conn->query($sql);
			if (!isset($bo_id))
				$bo_id = $this->conn->insert_id;
			$stotal = 0;
			$data = "";
			foreach ($item_id as $k => $v) {
				if (!in_array($k, $bo_ids))
					continue;
				$total = ($oqty[$k] - $qty[$k]) * $price[$k];
				$stotal += $total;
				if (!empty($data)) $data .= ", ";
				$data .= " ('{$bo_id}','{$v}','" . ($oqty[$k] - $qty[$k]) . "','{$price[$k]}','{$unit[$k]}','{$total}') ";
			}
			$this->conn->query("DELETE FROM `bo_items` where bo_id='{$bo_id}'");
			$save_bo_items = $this->conn->query("INSERT INTO `bo_items` (`bo_id`,`item_id`,`quantity`,`price`,`unit`,`total`) VALUES {$data}");
			if ($save_bo_items) {
				$discount = $stotal * ($discount_perc / 100);
				$stotal -= $discount;
				$tax = $stotal * ($tax_perc / 100);
				$stotal += $tax;
				$amount = $stotal;
				$this->conn->query("UPDATE back_order_list set amount = '{$amount}', discount='{$discount}', tax = '{$tax}' where id = '{$bo_id}'");
			}
		} else {
			$this->conn->query("UPDATE `purchase_order_list` set status = 2 where id = '{$po_id}'");
			if ($from_order == 2) {
				$this->conn->query("UPDATE `back_order_list` set status = 2 where id = '{$form_id}'");
			}
		}
	} else {
		$resp['status'] = 'failed';
		$resp['msg'] = 'An error occured. Error: ' . $this->conn->error;
	}
	if ($resp['status'] == 'success') {
		if (empty($id)) {
			$this->settings->set_flashdata('success', " El nuevo stock se recibió con éxito.");
		} else {
			$this->settings->set_flashdata('success', " Detalles del stock recibido Actualizado con éxito.");
		}
	}

	return json_encode($resp);
 }
function delete_receiving()
{
	$id = intval($_POST['id'] ?? 0);
	if ($id <= 0) return json_encode(['status'=>'failed','msg'=>'ID inválido']);

	$res = $this->conn->query("SELECT stock_ids, form_id, from_order FROM receiving_list WHERE id={$id}")->fetch_assoc();
	if (!empty($res['stock_ids'])) $this->conn->query("DELETE FROM stock_list WHERE id IN ({$res['stock_ids']})");
	$this->conn->query("DELETE FROM receiving_list WHERE id={$id}");
	if ($res['from_order']==1) $this->conn->query("UPDATE purchase_order_list SET status=0 WHERE id={$res['form_id']}");
	return json_encode(['status'=>'success','msg'=>'Recepción eliminada correctamente']);
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
        if(strlen($q) < 2) return json_encode([]);

        $sql = "
            SELECT 
                i.id,
                i.description AS descripcion,
                s.name AS proveedor,
                i.supplier_id,
                i.stock,
                i.product_cost AS precio_compra,
                i.cost AS precio_venta
            FROM item_list i
            LEFT JOIN supplier_list s ON s.id = i.supplier_id
            WHERE i.status = 1
              AND (i.description LIKE CONCAT('%', ?, '%')
                OR s.name LIKE CONCAT('%', ?, '%'))
            ORDER BY i.description ASC
            LIMIT 20
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $q, $q);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while($row = $res->fetch_assoc()) $data[] = $row;

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
    case 'save_receiving':  $out = $Master->save_receiving(); break;
    case 'delete_receiving':$out = $Master->delete_receiving(); break;
    case 'save_return':     $out = $Master->save_return(); break;
    case 'delete_return':   $out = $Master->delete_return(); break;
    case 'save_sale':       $out = $Master->save_sale(); break;
    case 'delete_sale':     $out = $Master->delete_sale(); break;
    case 'search_products': $out = $Master->search_products(); break;
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
