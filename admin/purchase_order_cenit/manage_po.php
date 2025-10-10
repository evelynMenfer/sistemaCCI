<?php
// CENIT COT - Captura / Edición Segura
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id > 0) {
    $qry = $conn->query("
        SELECT p.*, s.name as supplier 
        FROM purchase_order_list p 
        INNER JOIN supplier_list s ON p.supplier_id = s.id  
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
              <option disabled <?php echo !isset($id_company)?'selected':'' ?>></option>
              <?php
              $company = $conn->query("SELECT * FROM `company_list` WHERE status = 1 ORDER BY `name` ASC");
              while ($row = $company->fetch_assoc()):
              ?>
                <option value="<?php echo $row['id'] ?>" <?php echo (isset($id_company) && $id_company==$row['id'])?'selected':'' ?>>
                  <?php echo $row['name'] ?>
                </option>
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

        <!-- PRODUCTOS -->
        <fieldset>
          <legend class="text-info">Producto</legend>
          <?php
          $item_arr = [];
          $items = $conn->query("SELECT * FROM `item_list` WHERE status = 1 ORDER BY `description` ASC");
          while ($row = $items->fetch_assoc()) $item_arr[$row['supplier_id']][$row['id']] = $row;
          ?>
          <div class="row">
            <div class="col-md-6">
              <label class="control-label text-info">Proveedor</label>
              <select name="supplier_id" id="supplier_id" class="custom-select select2">
                <option disabled <?php echo !isset($supplier_id)?'selected':'' ?>></option>
                <?php
                $supplier = $conn->query("SELECT DISTINCT s.id, s.name 
                                          FROM supplier_list s 
                                          INNER JOIN item_list i ON i.supplier_id = s.id 
                                          WHERE s.status = 1 AND i.status = 1 
                                          ORDER BY s.name ASC");
                while ($row = $supplier->fetch_assoc()):
                ?>
                  <option value="<?php echo $row['id'] ?>" <?php echo (isset($supplier_id) && $supplier_id==$row['id'])?'selected':'' ?>>
                    <?php echo $row['name'] ?>
                  </option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="control-label text-info">Producto</label>
              <select id="item_id" class="custom-select select2"><option disabled selected></option></select>
            </div>
          </div>

          <div class="row justify-content-start mt-2">
            <div class="col-md-2"><label>Unidad</label><input type="text" class="form-control rounded-0" id="unit"></div>
            <div class="col-md-2"><label>Precio x Unidad</label><input type="number" step="0.01" class="form-control rounded-0" id="price"></div>
            <div class="col-md-2"><label>Cantidad</label><input type="number" step="0.01" class="form-control rounded-0" id="qty"></div>
            <div class="col-md-2"><label>Descuento %</label><input type="number" step="0.01" class="form-control rounded-0" id="discount_item" value="0"></div>
            <div class="col-md-2 align-self-end"><button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Agregar</button></div>
          </div>
        </fieldset>

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
              <td><input type="number" step="0.01" class="form-control inline-edit qty-input" name="qty[]" value="<?php echo $row['quantity']; ?>"><input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>"><input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>"></td>
              <td class="text-center unit"><?php echo $row['unit']; ?></td>
              <td class="item"><?php echo $row['description']; ?></td>
              <td><input type="number" step="0.01" class="form-control inline-edit price-input" name="price[]" value="<?php echo $row['price']; ?>"></td>
              <td><input type="number" step="0.01" class="form-control inline-edit discount-input" name="discount[]" value="<?php echo $row['discount']; ?>"></td>
              <td class="text-end total"><?php echo number_format($line_total,2); ?><input type="hidden" name="total[]" value="<?php echo $line_total; ?>"></td>
            </tr>
          <?php endwhile; endif; ?>
          </tbody>
          <tfoot>
            <tr><th colspan="6" class="text-end">Sub Total</th><th class="text-end sub-total">0.00</th></tr>
            <tr>
              <th colspan="6" class="text-end">
                Impuesto <input style="width:60px" name="tax_perc" type="number" min="0" max="100" value="<?php echo $tax_perc ?? 16 ?>"> %
              </th>
              <th class="text-end tax">0.00</th>
            </tr>
            <tr><th colspan="6" class="text-end">Total</th><th class="text-end grand-total">0.00<input type="hidden" name="amount" value="<?php echo $amount ?? 0 ?>"></th></tr>
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
    <td><input type="number" step="0.01" class="form-control inline-edit qty-input" name="qty[]"><input type="hidden" name="item_id[]"><input type="hidden" name="unit[]"></td>
    <td class="text-center unit"></td>
    <td class="item"></td>
    <td><input type="number" step="0.01" class="form-control inline-edit price-input" name="price[]"></td>
    <td><input type="number" step="0.01" class="form-control inline-edit discount-input" name="discount[]" value="0"></td>
    <td class="text-end total">0.00<input type="hidden" name="total[]"></td>
  </tr>
</table>

<script>
var items = <?php echo json_encode($item_arr) ?>;
$(function(){
  $('.select2').select2({ placeholder:"Selecciona aquí", width:'resolve' });

  function populateProducts(){
    var sid=$('#supplier_id').val();
    $('#item_id').empty().select2('destroy');
    if(items[sid]){
      $.each(items[sid],function(k,row){
        $('#item_id').append(new Option(row.description,row.id));
      });
    }
    $('#item_id').select2({ placeholder:"Selecciona producto aquí", width:'resolve' });
  }
  $('#supplier_id').on('change',populateProducts);
  if($('#supplier_id').val()) populateProducts();

  $('#add_to_list').on('click',function(){
    var sid=$('#supplier_id').val(), item=$('#item_id').val(),
        qty=parseFloat($('#qty').val())||0, unit=$('#unit').val(),
        price=parseFloat($('#price').val())||0, disc=parseFloat($('#discount_item').val())||0;
    if(!sid||!item||!unit||!price||!qty){ alert('Completa los campos del producto.'); return; }
    if($('#list tbody tr[data-id="'+item+'"]').length){ alert('Producto ya existe.'); return; }
    var total=(price-(price*disc/100))*qty;
    var tr=$('#clone_list tr').clone().attr('data-id',item);
    tr.find('[name="item_id[]"]').val(item);
    tr.find('[name="unit[]"]').val(unit);
    tr.find('[name="qty[]"]').val(qty);
    tr.find('[name="price[]"]').val(price);
    tr.find('[name="discount[]"]').val(disc);
    tr.find('[name="total[]"]').val(total);
    tr.find('.unit').text(unit);
    tr.find('.item').text(items[sid][item].description);
    tr.find('.total').text(total.toFixed(2));
    $('#list tbody').append(tr);
    $('#qty,#unit,#price').val(''); $('#discount_item').val('0'); $('#item_id').val('').trigger('change');
    calc();
  });

  $(document).on('click','.rem_row',function(){ $(this).closest('tr').remove(); calc(); });
  $(document).on('input','.qty-input,.price-input,.discount-input,[name="tax_perc"]',calc);

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
    var tperc=parseFloat($('[name="tax_perc"]').val())||0;
    var tax=sub*tperc/100;
    $('.tax').text(tax.toFixed(2));
    var grand=sub+tax;
    $('.grand-total').text(grand.toFixed(2));
    $('[name="amount"]').val(grand.toFixed(2));
  }
});
</script>
