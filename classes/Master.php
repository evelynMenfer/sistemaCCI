<?php
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

    /** ======================
     *        SAVE / DELETE
     * ======================= */

    /* -------- Proveedor -------- */
    function save_supplier(){ 
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if ($k=='id') continue;
            if (!empty($data)) $data .= ",";
            $data .= " `{$k}`='{$v}' ";
        }
        $check = $this->conn->query("SELECT * FROM `supplier_list` where `name` = '{$name}' ".(!empty($id)?" and id != {$id} ":""))->num_rows;
        if ($this->capture_err()) return $this->capture_err();
        if ($check > 0) return json_encode(['status'=>'failed','msg'=>'Nombre de proveedor ya exite.']);

        if (empty($id)) $sql = "INSERT INTO `supplier_list` set {$data}";
        else $sql = "UPDATE `supplier_list` set {$data} where id = '{$id}'";
        $save = $this->conn->query($sql);
        if ($save){
            $this->settings->set_flashdata('success', empty($id)?"Proveedor guardado con éxito.":"Proveedor actualizado con éxito.");
            return json_encode(['status'=>'success']);
        }else{
            return json_encode(['status'=>'failed','err'=>$this->conn->error." [{$sql}]"]);
        }
    }
    function delete_supplier(){ 
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `supplier_list` where id = '{$id}'");
        if ($del){
            $this->settings->set_flashdata('success', "Proveedor eliminado correctamente.");
            return json_encode(['status'=>'success']);
        }else return json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

    /* -------- Empresa -------- */
    function save_company(){ 
        extract($_POST);
        $data = "";
        foreach ($_POST as $k => $v) {
            if ($k=='id') continue;
            if (!empty($data)) $data .= ",";
            $data .= " `{$k}`='{$v}' ";
        }
        $check = $this->conn->query("SELECT * FROM `company_list` where `name` = '{$name}' ".(!empty($id)?" and id != {$id} ":""))->num_rows;
        if ($this->capture_err()) return $this->capture_err();
        if ($check > 0) return json_encode(['status'=>'failed','msg'=>"company Name already exist."]);

        if (empty($id)) $sql = "INSERT INTO `company_list` set {$data}";
        else $sql = "UPDATE `company_list` set {$data} where id = '{$id}'";
        $save = $this->conn->query($sql);
        if ($save){
            $this->settings->set_flashdata('success', empty($id)?"Empresa guardada con éxito.":"Empresa actualizada con éxito.");
            return json_encode(['status'=>'success']);
        }else{
            return json_encode(['status'=>'failed','err'=>$this->conn->error." [{$sql}]"]);
        }
    }
    function delete_company(){ 
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `company_list` where id = '{$id}'");
        if ($del){
            $this->settings->set_flashdata('success', "Empresa eliminada correctamente.");
            return json_encode(['status'=>'success']);
        }else return json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

    /* -------- Producto -------- */
    function save_item() {
        extract($_POST);
        $fields = []; $types=''; $values=[];
        foreach($_POST as $k=>$v){
            if ($k==='id') continue;
            $fields[] = "`$k` = ?";
            if (is_int($v)) $types.='i';
            elseif (is_double($v) || is_float($v)) $types.='d';
            else $types.='s';
            $values[] = $v;
        }
        if (empty($id)) $sql = "INSERT INTO `item_list` SET ".implode(", ",$fields);
        else { $sql = "UPDATE `item_list` SET ".implode(", ",$fields)." WHERE id = ?"; $types.='i'; $values[]=$id; }
        $stmt = $this->conn->prepare($sql);
        if(!$stmt) return json_encode(['status'=>'failed','err'=>$this->conn->error." [$sql]"]);
        $stmt->bind_param($types, ...$values);
        if($stmt->execute()){
            $this->settings->set_flashdata('success', empty($id)?"Nuevo producto guardado correctamente.":"Producto actualizado correctamente.");
            $stmt->close();
            return json_encode(['status'=>'success']);
        }else{
            $err = $stmt->error; $stmt->close();
            return json_encode(['status'=>'failed','err'=>$err." [$sql]"]);
        }
    }
    function delete_item(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `item_list` where id = '{$id}'");
        if ($del){
            $this->settings->set_flashdata('success', "Producto eliminado correctamente.");
            return json_encode(['status'=>'success']);
        }else return json_encode(['status'=>'failed','error'=>$this->conn->error]);
    }

    /* -------- Cotización / Purchase Order -------- */
    function save_po(){
        // Generar po_code si es nuevo
        if (empty($_POST['id'])) {
            $prefix = "C";
            $code = sprintf("%'.04d", 1);
            while (true) {
                $exists = $this->conn->query("SELECT 1 FROM purchase_order_list WHERE po_code = '{$prefix}{$code}'")->num_rows;
                if ($exists) $code = sprintf("%'.04d", $code + 1);
                else break;
            }
            $_POST['po_code'] = $prefix.$code;
        }

        // helper numérico
        $toFloat = function($v){
            if ($v === null) return 0.0;
            if (is_array($v)) return 0.0;
            $v = trim((string)$v);
            $v = str_replace([',',' '],['',''],$v);
            return is_numeric($v) ? (float)$v : 0.0;
        };

        // campos de cabecera (excluir arrays detalle y totales que recalcularemos)
        $exclude = ['id','item_id','qty','price','unit','discount','descuento','total','amount','discount_perc','discount','tax'];
        $set = [];
        foreach($_POST as $k=>$v){
            if (in_array($k,$exclude) || is_array($v)) continue;
            $val = is_numeric($v) ? $v : $this->conn->real_escape_string($v);
            $set[] = "`$k` = '{$val}'";
        }
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        $this->conn->begin_transaction();
        try{
            if ($id==0){
                $sql = "INSERT INTO purchase_order_list SET ".implode(", ",$set);
            }else{
                $sql = "UPDATE purchase_order_list SET ".implode(", ",$set)." WHERE id='{$id}'";
            }
            if (!$this->conn->query($sql)) throw new Exception("Error cabecera: ".$this->conn->error);

            $po_id = $id==0 ? $this->conn->insert_id : $id;

            // limpiar items anteriores
            if (!$this->conn->query("DELETE FROM po_items WHERE po_id={$po_id}"))
                throw new Exception("Error limpiando items: ".$this->conn->error);

            // arrays detalle
            $item_ids = $_POST['item_id'] ?? [];
            $qtys     = $_POST['qty'] ?? [];
            $prices   = $_POST['price'] ?? [];
            $units    = $_POST['unit'] ?? [];
            $discounts= $_POST['discount'] ?? ($_POST['descuento'] ?? []); // % por línea

            // insertar items
            $stmt = $this->conn->prepare("INSERT INTO po_items (po_id,item_id,quantity,price,unit,discount,total) VALUES (?,?,?,?,?,?,?)");
            if(!$stmt) throw new Exception("Prepare items: ".$this->conn->error);
            $stmt->bind_param("iiddsdd", $b_po_id,$b_item,$b_qty,$b_price,$b_unit,$b_disc,$b_total);
            $b_po_id = $po_id;

            $subtotal = 0.0;
            for($k=0; $k<count($item_ids); $k++){
                $iid = isset($item_ids[$k]) ? intval($item_ids[$k]) : 0;
                if ($iid<=0) continue;
                $q   = $toFloat($qtys[$k]   ?? 0);
                $p   = $toFloat($prices[$k] ?? 0);
                $u   = (string)($units[$k]  ?? '');
                $d   = $toFloat($discounts[$k] ?? 0); // porcentaje
                // total de línea con %
                $line = round( ( $p - ($p * $d / 100) ) * $q, 2 );

                $b_item = $iid;
                $b_qty  = $q;
                $b_price= $p;
                $b_unit = $u;
                $b_disc = $d;     // guardamos el %
                $b_total= $line;

                if (!$stmt->execute()){
                    $err = $stmt->error; $stmt->close();
                    throw new Exception("Insert item: ".$err);
                }
                $subtotal += $line;
            }
            $stmt->close();

            // totales cabecera (recalcular en servidor)
            $tax_perc = $toFloat($_POST['tax_perc'] ?? 0);
            $tax = round($subtotal * $tax_perc / 100, 2);
            $amount = round($subtotal + $tax, 2);

            // actualizar totales (discount a 0 porque ya es por línea)
            $sqlTotals = "UPDATE purchase_order_list 
                          SET discount_perc='0', discount='0', tax_perc='{$tax_perc}', tax='{$tax}', amount='{$amount}'
                          WHERE id='{$po_id}'";
            if (!$this->conn->query($sqlTotals)) throw new Exception("Actualizar totales: ".$this->conn->error);

            $this->conn->commit();
            $this->settings->set_flashdata('success', $id==0 ? "Nueva Cotización guardada correctamente." : "Cotización de producto actualizada correctamente.");
            return json_encode(['status'=>'success','id'=>$po_id]);
        }catch(Exception $e){
            $this->conn->rollback();
            return json_encode(['status'=>'failed','msg'=>$e->getMessage()]);
        }
    }

    /* -------- Eliminar PO -------- */
    function delete_po(){
        $id = intval($_POST['id'] ?? 0);
        if ($id<=0) return json_encode(['status'=>'failed','msg'=>'ID inválido']);
        // Limpieza relacionada con BO/Stocks si aplica...
        $del = $this->conn->query("DELETE FROM purchase_order_list WHERE id={$id}");
        if ($del) return json_encode(['status'=>'success','msg'=>'Cotización eliminada correctamente']);
        return json_encode(['status'=>'failed','msg'=>$this->conn->error]);
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
}

/** ======================
 *       ROUTER
 * ======================= */
$Master = new Master();
$action = strtolower($_GET['f'] ?? 'none');
$sysset = new SystemSettings();

switch ($action) {
  case 'save_supplier': echo $Master->save_supplier(); break;
  case 'delete_supplier': echo $Master->delete_supplier(); break;
  case 'save_company': echo $Master->save_company(); break;
  case 'delete_company': echo $Master->delete_company(); break;
  case 'save_item': echo $Master->save_item(); break;
  case 'delete_item': echo $Master->delete_item(); break;
  case 'save_po': echo $Master->save_po(); break;
  case 'delete_po': echo $Master->delete_po(); break;
  case 'save_receiving': echo $Master->save_receiving(); break;
  case 'delete_receiving': echo $Master->delete_receiving(); break;
  case 'save_return': echo $Master->save_return(); break;
  case 'delete_return': echo $Master->delete_return(); break;
  case 'save_sale': echo $Master->save_sale(); break;
  case 'delete_sale': echo $Master->delete_sale(); break;
  default: break;
}
