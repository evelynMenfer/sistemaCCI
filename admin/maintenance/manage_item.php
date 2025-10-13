<?php
require_once('../../config.php');
if (isset($_GET['id']) && $_GET['id'] > 0) {
	$qry = $conn->query("SELECT * from `item_list` where id = '{$_GET['id']}' ");
	if ($qry->num_rows > 0) {
		foreach ($qry->fetch_assoc() as $k => $v) {
			$$k = $v;
		}
	}
}
?>
<div class="container-fluid">
<form action="" id="item-form">
  <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

  <!-- ====== 1️⃣ PRIMERA FILA: OC, FECHA, NOMBRE ====== -->
  <div class="row mb-3">
    <div class="col-md-3">
      <div class="form-group">
        <label for="oc" class="control-label">OC</label>
        <input type="text" name="oc" id="oc" class="form-control rounded-0" 
               value="<?php echo isset($oc) ? htmlspecialchars($oc) : ''; ?>">
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="date_purchase" class="control-label">Fecha de Compra</label>
        <input type="date" name="date_purchase" id="date_purchase" class="form-control rounded-0"
               value="<?php echo isset($date_purchase) ? htmlspecialchars($date_purchase) : ''; ?>">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="name" class="control-label">SKU</label>
        <input type="text" name="name" id="name" class="form-control rounded-0"
               value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
      </div>
    </div>
  </div>

  <!-- ====== 2️⃣ SEGUNDA FILA: DESCRIPCIÓN, STOCK ====== -->
  <div class="row mb-3">
    <div class="col-md-9">
      <div class="form-group">
        <label for="description" class="control-label">Descripción</label>
        <textarea name="description" id="description" rows="2" 
                  class="form-control no-resize rounded-0"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="stock" class="control-label">Stock</label>
        <input type="number" name="stock" id="stock" step="any" 
               class="form-control rounded-0 text-end" 
               value="<?php echo isset($stock) ? htmlspecialchars($stock) : ''; ?>">
      </div>
    </div>
  </div>

  <!-- ====== 3️⃣ TERCERA FILA: PRECIO VENTA, PRECIO COMPRA, EXTRAS ====== -->
  <div class="row mb-3">
    <div class="col-md-4">
      <div class="form-group">
        <label for="product_cost" class="control-label">Precio Compra</label>
        <input type="number" name="product_cost" id="product_cost" step="any" 
               class="form-control rounded-0 text-end" 
               value="<?php echo isset($product_cost) ? htmlspecialchars($product_cost) : ''; ?>">
      </div>
    </div>
	<div class="col-md-4">
      <div class="form-group">
        <label for="cost" class="control-label">Precio Venta</label>
        <input type="number" name="cost" id="cost" step="any" 
               class="form-control rounded-0 text-end" 
               value="<?php echo isset($cost) ? htmlspecialchars($cost) : ''; ?>">
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="form-group">
        <label for="shipping_or_extras" class="control-label">Extras</label>
        <input type="number" name="shipping_or_extras" id="shipping_or_extras" step="any" 
               class="form-control rounded-0 text-end" 
               value="<?php echo isset($shipping_or_extras) ? htmlspecialchars($shipping_or_extras) : ''; ?>">
      </div>
    </div>
  </div>

  <!-- ====== 4️⃣ CUARTA FILA: PROVEEDOR, EMPRESA, ESTADO ====== -->
  <div class="row mb-3">
    <div class="col-md-4">
      <div class="form-group">
        <label for="supplier_id" class="control-label">Proveedor</label>
        <select name="supplier_id" id="supplier_id" class="custom-select select2">
          <option disabled selected>Selecciona proveedor</option>
          <?php
          $supplier = $conn->query("SELECT * FROM `supplier_list` WHERE status = 1 ORDER BY `name` ASC");
          while ($row = $supplier->fetch_assoc()):
          ?>
            <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?>>
              <?php echo htmlspecialchars($row['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>

    <div class="col-md-4">
      <div class="form-group">
        <label for="company_id" class="control-label">Empresa</label>
        <select name="company_id" id="company_id" class="custom-select select2">
          <option disabled selected>Selecciona empresa</option>
          <?php
          $company = $conn->query("SELECT * FROM `company_list` WHERE status = 1 ORDER BY `name` ASC");
          while ($row = $company->fetch_assoc()):
          ?>
            <option value="<?php echo $row['id'] ?>" <?php echo isset($company_id) && $company_id == $row['id'] ? "selected" : "" ?>>
              <?php echo htmlspecialchars($row['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>

    <div class="col-md-4">
      <div class="form-group">
        <label for="status" class="control-label">Estado</label>
        <select name="status" id="status" class="custom-select">
          <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Activo</option>
          <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactivo</option>
        </select>
      </div>
    </div>
  </div>

  <!-- ====== 5️⃣ FOTO DEL PRODUCTO ====== -->
  <div class="form-group text-center">
    <label for="foto_producto" class="control-label">Imagen del Producto</label>
    <input type="file" name="foto_producto" id="foto_producto" accept="image/*" 
           class="form-control rounded-0 mb-2" style="max-width: 400px; margin: 0 auto;">
    
    <div id="preview-container">
      <?php if (!empty($foto_producto)): ?>
        <img id="preview-img" src="<?php echo base_url . $foto_producto ?>" 
             alt="Imagen actual" class="img-fluid border rounded shadow-sm" 
             style="max-height: 160px; margin-top: 5px;">
      <?php else: ?>
        <img id="preview-img" src="" 
             class="img-fluid border rounded shadow-sm d-none" 
             style="max-height: 160px; margin-top: 5px;">
      <?php endif; ?>
    </div>
  </div>
</form>

</div>

<script>
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: "Selecciona aquí",
			width: "relative"
		})
		$('#item-form').submit(function(e) {
			e.preventDefault();
			var _this = $(this)
			$('.err-msg').remove();
			start_loader();
			$.ajax({
				url: _base_url_ + "classes/Master.php?f=save_item",
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				method: 'POST',
				type: 'POST',
				dataType: 'json',
				error: err => {
					console.log(err)
					alert_toast("Ocurrió un error", 'error');
					end_loader();
				},
				success: function(resp) {
					if (typeof resp == 'object' && resp.status == 'success') {
						location.reload();
					} else if (resp.status == 'failed' && !!resp.msg) {
						var el = $('<div>')
						el.addClass("alert alert-danger err-msg").text(resp.msg)
						_this.prepend(el)
						el.show('slow')
						end_loader()
					} else {
						alert_toast("Ocurrió un error", 'error');
						end_loader();
						console.log(resp)
					}
				}
			})
		})
	})
</script>
<script>
$('#foto_producto').on('change', function(){
  const file = this.files[0];
  if (file){
    const reader = new FileReader();
    reader.onload = function(e){
      $('#preview-img').attr('src', e.target.result).removeClass('d-none');
    }
    reader.readAsDataURL(file);
  }
});
</script>
