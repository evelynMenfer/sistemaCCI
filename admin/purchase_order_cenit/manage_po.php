<?php
// CENIT COT - Captura / Edición con descuento total %
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
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

$company_id = 3;
?>
<style>
select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events:none; touch-action:none; background:#eee; box-shadow:none;
}
select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
  background:#eee; box-shadow:none;
}
.inline-edit { width: 100px; text-align: right; }
.table td, .table th { vertical-align: middle !important; }
tfoot tr th { background:#f6f6f6; }
</style>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h4 class="card-title">
      <?php echo $id ? "Información Cotización : ".$po_code : 'Crear Nueva Cotización' ?>
    </h4>
  </div>

  <div class="card-body">
    <form action="" id="po-form">
      <input type="hidden" name="id" value="<?php echo $id ?>">

      <div class="container-fluid">
        <!-- CABECERA -->
        <div class="row">
          <div class="col-md-3">
            <label class="control-label text-info">Cotización</label>
            <input type="text" class="form-control rounded-0" value="<?php echo $po_code ?? '' ?>" readonly>
          </div>
          <div class="col-md-5">
          <label for="id_company" class="control-label text-info">Empresa</label>
                            <select name="id_company" id="id_company" class="custom-select select2">
                                <option <?php echo !isset($company_id) ? 'selected' : '' ?> disabled></option>
                                <?php
                                $supplier = $conn->query("SELECT * FROM `company_list` where status = 1 and id = $company_id order by `name` asc");
                                while ($row = $supplier->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($id_company) && $id_company == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
          </div>
          <div class="col-md-4">
            <label class="control-label text-info">Fecha de Expedición</label>
            <input type="date" name="date_exp" class="form-control rounded-0 text-end" value="<?php echo $date_exp ?? '' ?>">
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-5">
            <label class="text-info control-label">Cliente</label>
            <textarea name="cliente_cotizacion" rows="1" class="form-control rounded-0"><?php echo $cliente_cotizacion ?? '' ?></textarea>
          </div>
          <div class="col-md-3">
            <label class="control-label text-info">Email cliente</label>
            <input name="cliente_email" class="form-control rounded-0" value="<?php echo $cliente_email ?? '' ?>">
          </div>
          <div class="col-md-4">
            <label class="text-info control-label">Trabajo</label>
            <textarea name="trabajo" rows="1" class="form-control rounded-0"><?php echo $trabajo ?? '' ?></textarea>
          </div>
        </div>

        <hr>

        <!-- FACTURACIÓN -->
        <div class="row">
          <?php
          $fields = [
            ['metodo_pago','Método de pago'], ['date_pago','Fecha de pago','date'],
            ['pago_efectivo','Pago en efectivo','date'], ['oc','OC'],
            ['num_factura','No. Factura'], ['date_carga_portal','Fecha de carga al portal','date'],
            ['folio_fiscal','Folio Fiscal'], ['folio_comprobante_pago','Folio Comprobante de pago'],
            ['num_cheque','No. de cheque']
          ];
          foreach ($fields as $f):
            $name=$f[0]; $label=$f[1]; $type=$f[2]??'text';
          ?>
          <div class="col-md-4">
            <label class="control-label text-info"><?php echo $label ?></label>
            <input type="<?php echo $type ?>" name="<?php echo $name ?>" class="form-control rounded-0" 
              value="<?php echo $$name ?? '' ?>">
          </div>
          <?php endforeach; ?>
        </div>

        <hr>
        <legend class="text-info">Productos</legend>

        <!-- ================= BUSCADOR DE PRODUCTOS ACTIVOS ================= -->
        <div class="card mb-3 border-primary">
          <div class="card-header bg-primary text-white">
            <strong>Buscar productos activos</strong>
          </div>
          <div class="card-body">
            <div class="form-group mb-3">
              <input type="text" id="searchProduct" class="form-control" placeholder="Escribe descripción o proveedor...">
            </div>

            <table class="table table-bordered table-hover" id="productSearchTable">
              <thead class="table-light">
                <tr>
                  <th>Descripción</th>
                  <th>Proveedor</th>
                  <th>Stock</th>
                  <th>Precio Compra</th>
                  <th>Precio Venta</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr><td colspan="6" class="text-center text-muted">Escribe para buscar...</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <hr>

        <!-- TABLA PRODUCTOS -->
        <table class="table table-striped table-bordered" id="list">
          <thead class="bg-navy text-light text-center">
            <tr>
              <th></th><th>Cant</th><th>Unidad</th><th>Descripción</th>
              <th>Precio</th><th>Desc. %</th><th>Total</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($id):
            $qry = $conn->query("SELECT p.*, i.description FROM po_items p INNER JOIN item_list i ON p.item_id=i.id WHERE p.po_id={$id}");
            while ($row=$qry->fetch_assoc()):
              $line_total = ($row['price'] - ($row['price']*$row['discount']/100)) * $row['quantity'];
          ?>
            <tr data-id="<?php echo $row['item_id']; ?>">
              <td class="text-center"><button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button></td>
              <td><input type="number" step="0.01" class="form-control inline-edit qty-input" name="qty[]" value="<?php echo $row['quantity']; ?>"><input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>"></td>
              <td class="text-center"><input type="text" class="form-control inline-edit text-center unit-input" name="unit[]" value="<?php echo $row['unit']; ?>"></td>
              <td class="item"><?php echo $row['description']; ?></td>
              <td><input type="number" step="0.01" class="form-control inline-edit price-input" name="price[]" value="<?php echo $row['price']; ?>"></td>
              <td><input type="number" step="0.01" class="form-control inline-edit discount-input" name="discount[]" value="<?php echo $row['discount']; ?>"></td>
              <td class="text-end total  text-right"><?php echo number_format($line_total,2); ?><input type="hidden" name="total[]" value="<?php echo $line_total; ?>"></td>
            </tr>
          <?php endwhile; endif; ?>
          </tbody>
          <tfoot>
  <tr>
    <th colspan="6" class="text-end  text-right">Sub Total</th>
    <th class="text-end sub-total">0.00</th>
  </tr>

  <tr>
    <th colspan="6" class="text-end  text-right">
      Descuento 
      <input style="width:60px" name="discount_perc" type="number" min="0" max="100" 
             value="<?php echo $discount_perc ?? 0 ?>"> %
    </th>
    <th class="text-end discount  text-right">
      $<?php echo number_format($discount ?? 0,2) ?>
      <!-- 👇 Campo oculto necesario para que el valor en $ se guarde -->
      <input type="hidden" name="discount" value="<?php echo $discount ?? 0 ?>">
    </th>
  </tr>

  <tr>
    <th colspan="6" class="text-end text-right">
      Impuesto 
      <input style="width:60px" name="tax_perc" type="number" min="0" max="100" 
             value="<?php echo $tax_perc ?? 16 ?>"> %
    </th>
    <th class="text-end tax">$<?php echo number_format($tax ?? 0,2) ?></th>
  </tr>

  <tr>
    <th colspan="6" class="text-end text-right">Total</th>
    <th class="text-end grand-total">
      0.00
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
    <a class="btn btn-flat btn-danger" href="<?php echo base_url . '/admin?page=purchase_order_cenit' ?>">Cancelar</a>
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
$(function(){
  $('.select2').select2({ placeholder:"Selecciona aquí", width:'resolve' });

  $(document).on('click','.rem_row',function(){ $(this).closest('tr').remove(); calc(); });
  $(document).on('input','.qty-input,.price-input,.discount-input,.unit-input,[name="tax_perc"],[name="discount_perc"]',calc);

  $('#po-form').on('submit',function(e){
    e.preventDefault();
    $.ajax({
      url:_base_url_+"classes/Master.php?f=save_po",
      data:new FormData(this), method:'POST', cache:false, contentType:false, processData:false, dataType:'json',
      success:function(resp){
        if(resp.status=='success') location.replace(_base_url_+"admin/?page=purchase_order_cenit/view_po&id="+resp.id);
        else alert(resp.msg||'Error al guardar');
      }, error:function(err){ alert('Error de conexión'); console.log(err); }
    });
  });

  calc();
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
});

$(function(){
  $('#searchProduct').on('keyup', function(){
    let q = $(this).val().trim();
    if(q.length < 2){
      $('#productSearchTable tbody').html('<tr><td colspan="6" class="text-center text-muted">Escribe para buscar...</td></tr>');
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
                <td>${p.descripcion}</td>
                <td>${p.proveedor}</td>
                <td class="text-center">${p.stock}</td>
                <td class="text-end">${parseFloat(p.precio_compra).toFixed(2)}</td>
                <td class="text-end">${parseFloat(p.precio_venta).toFixed(2)}</td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-success addFromSearch"
                          data-id="${p.id}"
                          data-supplier="${p.supplier_id}"
                          data-name="${p.descripcion}"
                          data-price="${p.precio_venta || 0}">
                    Agregar
                  </button>
                </td>
              </tr>`;
          });
        } else {
          rows = `<tr><td colspan="6" class="text-center text-muted">Sin resultados</td></tr>`;
        }
        $('#productSearchTable tbody').html(rows);
      }
    });
  });

  // Evento: Agregar desde la tabla de búsqueda
  $(document).on('click', '.addFromSearch', function(){
    const $btn = $(this);
    const productID = $btn.data('id');
    const supplierID = $btn.data('supplier'); // 🔥 capturamos proveedor real
    const safeSupplier = supplierID > 0 ? supplierID : 1; // usa genérico si es null o 0

    const name = $btn.data('name');
    const price = parseFloat($btn.data('price')) || 0;

    // Crear campo oculto supplier_id si no existe
    if ($('[name="supplier_id"]').length === 0) {
      $('<input>').attr({
        type: 'hidden',
        name: 'supplier_id',
        value: supplierID
      }).appendTo('#po-form');
    }

    // Si ya hay supplier_id distinto, bloqueamos mezcla de proveedores
    const currentSupplier = $('[name="supplier_id"]').val();
    if (currentSupplier && currentSupplier != supplierID) {
      alert('⚠️ Esta cotización pertenece a otro proveedor. No se pueden mezclar.');
      return;
    }

    if($('#list tbody tr[data-id="'+productID+'"]').length){
      alert('El producto ya está en la lista.');
      return;
    }

    $btn.prop('disabled', true)
        .removeClass('btn-success')
        .addClass('btn-secondary')
        .html('<i class="fa fa-check"></i> Añadido');

    const qty = 1;
    const unit = '';
    const disc = 0;
    const total = price * qty;

    const tr = `
      <tr data-id="${productID}">
        <td class="text-center">
          <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td><input type="number" step="0.01" class="form-control inline-edit qty-input" name="qty[]" value="${qty}">
            <input type="hidden" name="item_id[]" value="${productID}"></td>
        <td class="text-center"><input type="text" class="form-control inline-edit text-center unit-input" name="unit[]" value="${unit}"></td>
        <td class="item text-start">${name}</td>
        <td class="text-end"><input type="number" step="0.01" class="form-control inline-edit text-end price-input" name="price[]" value="${price.toFixed(2)}"></td>
        <td class="text-end"><input type="number" step="0.01" class="form-control inline-edit text-end discount-input" name="discount[]" value="${disc}"></td>
        <td class="text-end total">${total.toFixed(2)}<input type="hidden" name="total[]" value="${total.toFixed(2)}"></td>
      </tr>
    `;

    $('#list tbody').append(tr);
    calc();

    $('#searchProduct').val('');
    $('#productSearchTable tbody').html('<tr><td colspan="6" class="text-center text-muted">Escribe para buscar...</td></tr>');

    setTimeout(() => {
      $btn.prop('disabled', false)
          .removeClass('btn-secondary')
          .addClass('btn-success')
          .html('Agregar');
    }, 1500);
  });
});
</script>
