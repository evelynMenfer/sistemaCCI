<?php
// ===============================
// MÓDULO GENERAL DE COTIZACIONES
// ===============================

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : 0;

if ($id > 0) {
  $qry = $conn->query("
      SELECT p.*, s.name as supplier 
      FROM purchase_order_list p 
      LEFT JOIN supplier_list s ON p.supplier_id = s.id  
      WHERE p.id = {$id}
  ");
  if ($qry && $qry->num_rows > 0) {
      foreach ($qry->fetch_array() as $k => $v) $$k = $v;
  }
}

// Si no viene en GET pero el registro ya tiene empresa (edición)
if ($company_id <= 0 && isset($id_company)) $company_id = intval($id_company);

// Obtener nombre de la empresa
$company_name = '';
$emp = $conn->query("SELECT name FROM company_list WHERE id = {$company_id}");
if ($emp && $emp->num_rows > 0) {
  $company_name = $emp->fetch_assoc()['name'];
}
?>
<style>
.inline-edit { width: 100px; text-align: right; }
.table td, .table th { vertical-align: middle !important; }
tfoot tr th { background:#f6f6f6; }
.card-header h4 { display: flex; justify-content: space-between; align-items: center; }
</style>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h4 class="card-title w-100 d-flex justify-content-between align-items-center">
      <span>
        <?php echo $id ? "Editar Cotización: " . htmlspecialchars($po_code ?? '') : "Nueva Cotización"; ?>
      </span>
      <span class="text-secondary" style="font-weight: normal;"><?php echo htmlspecialchars($company_name); ?></span>
    </h4>
  </div>

  <div class="card-body">
    <form action="" id="po-form">
      <input type="hidden" name="id" value="<?php echo $id ?>">
      <input type="hidden" name="id_company" value="<?php echo $company_id ?>">

      <div class="container-fluid">

        <!-- DATOS DE CLIENTE -->
        <div class="row mt-2">
          <div class="col-md-4">
            <label class="text-info control-label">Cliente *</label>
            <textarea name="cliente_cotizacion" rows="1" class="form-control rounded-0" required><?php echo $cliente_cotizacion ?? '' ?></textarea>
          </div>
          <div class="col-md-4">
            <label class="control-label text-info">Email cliente</label>
            <input name="cliente_email" class="form-control rounded-0" value="<?php echo $cliente_email ?? '' ?>">
          </div>
          <div class="col-md-4">
            <label class="text-info control-label">RQ</label>
            <textarea name="rq" rows="1" class="form-control rounded-0"><?php echo $rq ?? '' ?></textarea>
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-4">
            <label class="control-label text-info">Fecha de Expedición *</label>
            <input type="date" name="date_exp" class="form-control rounded-0 text-end"
              value="<?php echo $date_exp ?? date('Y-m-d') ?>" required>
          </div>
          <div class="col-md-4">
            <label class="control-label text-info">Fecha de Entrega</label>
            <input type="date" name="fecha_entrega" class="form-control rounded-0" value="<?php echo $fecha_entrega ?? '' ?>">
          </div>
          <div class="col-md-4">
            <label class="control-label text-info">OC</label>
            <input type="text" name="oc" class="form-control rounded-0" value="<?php echo $oc ?? '' ?>">
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-4">
            <label class="control-label text-info">Método de pago</label>
            <input type="text" name="metodo_pago" class="form-control rounded-0" value="<?php echo $metodo_pago ?? '' ?>">
          </div>
          <div class="col-md-4">
            <label class="control-label text-info">Fecha de pago</label>
            <input type="date" name="date_pago" class="form-control rounded-0" value="<?php echo $date_pago ?? '' ?>">
          </div>
          <div class="col-md-4">
            <label class="control-label text-info">Pago en efectivo</label>
            <input type="date" name="pago_efectivo" class="form-control rounded-0" value="<?php echo $pago_efectivo ?? '' ?>">
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-4">
            <label class="control-label text-info">No. Factura</label>
            <input type="text" name="num_factura" class="form-control rounded-0" value="<?php echo $num_factura ?? '' ?>">
          </div>
          <div class="col-md-4">
            <label class="control-label text-info">Fecha de carga al portal</label>
            <input type="date" name="date_carga_portal" class="form-control rounded-0" value="<?php echo $date_carga_portal ?? '' ?>">
          </div>
          <div class="col-md-4">
            <label class="control-label text-info">Folio Fiscal</label>
            <input type="text" name="folio_fiscal" class="form-control rounded-0" value="<?php echo $folio_fiscal ?? '' ?>">
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-4">
            <label class="control-label text-info">Folio Comprobante de pago</label>
            <input type="text" name="folio_comprobante_pago" class="form-control rounded-0" value="<?php echo $folio_comprobante_pago ?? '' ?>">
          </div>
         
          <div class="col-md-4">
          <label for="status" class="control-label text-info">Estado</label>
            <select name="status" id="status" class="form-control rounded-0">
              <option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>Por autorizar</option>
              <option value="1" <?= isset($status) && $status == 1 ? 'selected' : '' ?>>Autorizado</option>
              <option value="2" <?= isset($status) && $status == 2 ? 'selected' : '' ?>>En proceso</option>
              <option value="3" <?= isset($status) && $status == 3 ? 'selected' : '' ?>>Finalizado</option>
            </select>
          </div>
        </div>

        <hr>

        <legend class="text-info">Productos</legend>

        <!-- BUSCADOR -->
        <div class="card mb-3 border-primary">
          <div class="card-header bg-primary text-white"><strong>Buscar productos activos</strong></div>
          <div class="card-body">
            <div class="form-group mb-3">
              <input type="text" id="searchProduct" class="form-control" placeholder="Escribe descripción o proveedor...">
            </div>
            <table class="table table-bordered table-hover" id="productSearchTable">
              <thead class="table-light">
                <tr>
                  <th>SKU</th>
                  <th>Descripción</th>
                  <th>Fecha Compra</th>
                  <th>Stock</th>
                  <th>Precio Compra</th>
                  <th>Precio Venta</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="7" class="text-center text-muted">Escribe para buscar...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <hr>

        <!-- TABLA PRODUCTOS -->
        <table class="table table-striped table-bordered" id="list">
          <thead class="bg-navy text-light text-center">
            <tr>
              <th></th>
              <th>Cant</th>
              <th>Unidad</th>
              <th>Descripción</th>
              <th>Precio</th>
              <th>Desc. %</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($id):
            $qi = $conn->query("SELECT p.*, i.description FROM po_items p INNER JOIN item_list i ON p.item_id=i.id WHERE p.po_id={$id}");
            while ($row=$qi->fetch_assoc()):
              $line_total = ($row['price'] - ($row['price']*$row['discount']/100)) * $row['quantity'];
          ?>
            <tr data-id="<?php echo $row['item_id']; ?>">
              <td class="text-center"><button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button></td>
              <td>
                <input type="number" step="0.01" class="form-control inline-edit qty-input" name="qty[]" value="<?php echo $row['quantity']; ?>">
                <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
              </td>
              <td class="text-center"><input type="text" class="form-control inline-edit text-center unit-input" name="unit[]" value="<?php echo $row['unit']; ?>"></td>
              <td class="item"><?php echo $row['description']; ?></td>
              <td><input type="number" step="0.01" class="form-control inline-edit price-input" name="price[]" value="<?php echo $row['price']; ?>"></td>
              <td><input type="number" step="0.01" class="form-control inline-edit discount-input" name="discount[]" value="<?php echo $row['discount']; ?>"></td>
              <td class="text-end total"><?php echo number_format($line_total,2); ?><input type="hidden" name="total[]" value="<?php echo $line_total; ?>"></td>
            </tr>
          <?php endwhile; endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="6" class="text-end">Sub Total</th>
              <th class="text-end sub-total">0.00</th>
            </tr>
            <tr>
              <th colspan="6" class="text-end">
                Descuento 
                <input style="width:60px" name="discount_perc" type="number" min="0" max="100" value="<?php echo $discount_perc ?? 0 ?>"> %
              </th>
              <th class="text-end discount">$<?php echo number_format($discount ?? 0,2) ?>
                <input type="hidden" name="discount" value="<?php echo $discount ?? 0 ?>">
              </th>
            </tr>
            <tr>
              <th colspan="6" class="text-end">
                Impuesto 
                <input style="width:60px" name="tax_perc" type="number" min="0" max="100" value="<?php echo $tax_perc ?? 16 ?>"> %
              </th>
              <th class="text-end tax">$<?php echo number_format($tax ?? 0,2) ?></th>
            </tr>
            <tr>
              <th colspan="6" class="text-end">Total</th>
              <th class="text-end grand-total">0.00
                <input type="hidden" name="amount" value="<?php echo $amount ?? 0 ?>">
              </th>
            </tr>
          </tfoot>
        </table>

        <div class="row">
          <div class="col-md-6">
            <label class="text-info control-label">Observaciones</label>
            <textarea name="remarks" rows="3" class="form-control rounded-0"><?php echo $remarks ?? '' ?></textarea>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="card-footer py-1 text-center">
    <button class="btn btn-flat btn-primary" type="submit" form="po-form">Guardar</button>
    <a class="btn btn-flat btn-danger" href="<?php echo base_url . 'admin/?page=purchase_order/index&company_id=' . $company_id ?>">Cancelar</a>
  </div>
</div>

<!-- Fila clon -->
<table id="clone_list" class="d-none">
  <tr>
    <td class="text-center"><button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button></td>
    <td><input type="number" step="0.01" class="form-control inline-edit qty-input" name="qty[]"><input type="hidden" name="item_id[]"></td>
    <td class="text-center"><input type="text" class="form-control inline-edit text-center unit-input" name="unit[]"></td>
    <td class="item"></td>
    <td><input type="number" step="0.01" class="form-control inline-edit price-input" name="price[]"></td>
    <td><input type="number" step="0.01" class="form-control inline-edit discount-input" name="discount[]" value="0"></td>
    <td class="text-end total">0.00<input type="hidden" name="total[]"></td>
  </tr>
</table>

<script>
// ======== CALC GLOBAL ========
function calc(){
  var sub=0;
  $('#list tbody tr').each(function(){
    var q=parseFloat($(this).find('.qty-input').val())||0;
    var p=parseFloat($(this).find('.price-input').val())||0;
    var d=parseFloat($(this).find('.discount-input').val())||0;
    var tot=(p-(p*d/100))*q;
    $(this).find('[name="total[]"]').val(tot);
    $(this).find('.total').text(tot.toFixed(2));
    sub+=tot;
  });
  $('.sub-total').text(sub.toFixed(2));

  var dperc=parseFloat($('[name="discount_perc"]').val())||0;
  var discount=sub*dperc/100;
  $('.discount').text('$'+discount.toFixed(2));
  $('[name="discount"]').val(discount.toFixed(2));

  var base=sub-discount;
  var tperc=parseFloat($('[name="tax_perc"]').val())||0;
  var tax=base*tperc/100;
  $('.tax').text('$'+tax.toFixed(2));
  var grand=base+tax;
  $('.grand-total').text(grand.toFixed(2));
  $('[name="amount"]').val(grand.toFixed(2));
}

$(function(){
  // recalcular en cambios
  $(document).on('input','.qty-input,.price-input,.discount-input,[name="tax_perc"],[name="discount_perc"]',calc);

  // quitar fila
  $(document).on('click','.rem_row',function(){ $(this).closest('tr').remove(); calc(); });

  // guardar
  $('#po-form').on('submit',function(e){
    e.preventDefault();
    $.ajax({
      url:_base_url_ + "classes/Master.php?f=save_po",
      data:new FormData(this), method:'POST', cache:false, contentType:false, processData:false, dataType:'json',
      success:function(resp){
  if (resp.status === 'success') {
    const msg = resp.msg || 'Cotización guardada correctamente.';
    if (typeof alert_toast === 'function') {
      alert_toast(msg, 'success');
    } else {
      alert('✅ ' + msg);
    }
    setTimeout(() => {
      location.replace(_base_url_ + "admin/?page=purchase_order/view_po&id=" + resp.id);
    }, 1000);
  } else {
    alert(resp.msg || '❌ Error al guardar la cotización.');
  }
},
      error:function(err){ alert('Error de conexión'); console.log(err); }
    });
  });

  // inicial
  calc();

  // ======== BUSCADOR ========
  $('#searchProduct').on('keyup', function(){
    let q = $(this).val().trim();
    if(q.length < 2){
      $('#productSearchTable tbody').html('<tr><td colspan="7" class="text-center text-muted">Escribe para buscar...</td></tr>');
      return;
    }
    $.ajax({
      url: _base_url_ + "classes/Master.php?f=search_products",
      data: { q },
      dataType: 'json',
      success: function(data){
        let rows = '';
        if(data && data.length > 0){
            data.forEach(p => {
            rows += `
                <tr>
                <td>${p.sku ?? '—'}</td>
                <td>${p.descripcion ?? ''}</td>
                <td class="text-center">${p.fecha_compra ?? '—'}</td>
                <td class="text-center">${p.stock ?? 0}</td>
                <td class="text-end">${parseFloat(p.precio_compra||0).toFixed(2)}</td>
                <td class="text-end">${parseFloat(p.precio_venta||0).toFixed(2)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-success addFromSearch"
                            data-id="${p.id}"
                            data-name="${p.descripcion}"
                            data-price="${p.precio_venta||0}">
                    Agregar
                    </button>
                </td>
                </tr>`;
            });
        } else {
          rows = `<tr><td colspan="7" class="text-center text-muted">Sin resultados</td></tr>`;
        }
        $('#productSearchTable tbody').html(rows);
      }
    });
  });

  // ======== AGREGAR DESDE BUSCADOR ========
  $(document).on('click', '.addFromSearch', function(){
    const $btn = $(this);
    const productID = $btn.data('id');
    const name = $btn.data('name');
    const price = parseFloat($btn.data('price')) || 0;

    if($('#list tbody tr[data-id="'+productID+'"]').length){
      alert('El producto ya está en la lista.');
      return;
    }

    $btn.prop('disabled', true).removeClass('btn-success').addClass('btn-secondary').html('<i class="fa fa-check"></i> Añadido');

    const qty = 1, unit = '', disc = 0, total = price * qty;

    const tr = `
      <tr data-id="${productID}">
        <td class="text-center">
          <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td>
          <input type="number" step="0.01" class="form-control inline-edit qty-input" name="qty[]" value="${qty}">
          <input type="hidden" name="item_id[]" value="${productID}">
        </td>
        <td class="text-center">
          <input type="text" class="form-control inline-edit text-center unit-input" name="unit[]" value="${unit}">
        </td>
        <td class="item text-start">${name}</td>
        <td class="text-end">
          <input type="number" step="0.01" class="form-control inline-edit text-end price-input" name="price[]" value="${price.toFixed(2)}">
        </td>
        <td class="text-end">
          <input type="number" step="0.01" class="form-control inline-edit text-end discount-input" name="discount[]" value="${disc}">
        </td>
        <td class="text-end total">${total.toFixed(2)}<input type="hidden" name="total[]" value="${total.toFixed(2)}"></td>
      </tr>
    `;
    $('#list tbody').append(tr);
    calc();

    $('#searchProduct').val('');
    $('#productSearchTable tbody').html('<tr><td colspan="7" class="text-center text-muted">Escribe para buscar...</td></tr>');

    setTimeout(() => {
      $btn.prop('disabled', false).removeClass('btn-secondary').addClass('btn-success').text('Agregar');
    }, 1000);
  });
});
</script>
