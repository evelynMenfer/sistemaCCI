<?php
// CENIT COT - Captura / Edición
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT p.*, s.name as supplier 
                         FROM purchase_order_list p 
                         INNER JOIN supplier_list s ON p.supplier_id = s.id  
                         WHERE p.id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) $$k = $v;
    }
}
$company_id = 3;
?>
<style>
select[readonly].select2-hidden-accessible + .select2-container { pointer-events:none; touch-action:none; background:#eee; box-shadow:none; }
select[readonly].select2-hidden-accessible + .select2-container .select2-selection { background:#eee; box-shadow:none; }
.inline-edit { width: 100px; text-align: right; }
.table td, .table th { vertical-align: middle !important; }
</style>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h4 class="card-title"><?php echo isset($id) ? "Información Cotización : ".$po_code : 'Crear Nueva Cotización' ?></h4>
  </div>

  <div class="card-body">
    <form action="" id="po-form">
      <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

      <div class="container-fluid">
        <!-- ===== CABECERA ===== -->
        <div class="row">
          <div class="col-md-3">
            <label class="control-label text-info">Cotización</label>
            <input type="text" class="form-control rounded-0" value="<?php echo isset($po_code)?$po_code:'' ?>" readonly>
          </div>
          <div class="col-md-5">
            <div class="form-group">
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
          </div>
          <div class="col-md-4">
            <label class="control-label text-info">Fecha de Expedición</label>
            <input type="date" name="date_exp" class="form-control rounded-0 text-end" value="<?php echo $date_exp ?? '' ?>">
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-5">
            <div class="form-group">
              <label for="cliente_cotizacion" class="text-info control-label">Cliente</label>
              <textarea name="cliente_cotizacion" id="cliente_cotizacion" rows="1" class="form-control rounded-0"><?php echo $cliente_cotizacion ?? '' ?></textarea>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="cliente_email" class="control-label text-info">Email cliente</label>
              <input name="cliente_email" id="cliente_email" class="form-control rounded-0" value="<?php echo $cliente_email ?? '' ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="trabajo" class="text-info control-label">Trabajo</label>
              <textarea name="trabajo" id="trabajo" rows="1" class="form-control rounded-0"><?php echo $trabajo ?? '' ?></textarea>
            </div>
          </div>
        </div>

        <hr>

        <!-- ===== PAGOS / FACTURACIÓN ===== -->
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="metodo_pago" class="control-label text-info">Método de pago</label>
              <input name="metodo_pago" id="metodo_pago" class="form-control rounded-0" value="<?php echo $metodo_pago ?? '' ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="date_pago" class="control-label text-info">Fecha de pago</label>
              <input type="date" name="date_pago" id="date_pago" class="form-control rounded-0 text-end" value="<?php echo $date_pago ?? '' ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="pago_efectivo" class="control-label text-info">Pago en efectivo</label>
              <input type="date" name="pago_efectivo" id="pago_efectivo" class="form-control rounded-0 text-end" value="<?php echo $pago_efectivo ?? '' ?>">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="oc" class="control-label text-info">OC</label>
              <input name="oc" id="oc" class="form-control rounded-0" value="<?php echo $oc ?? '' ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="num_factura" class="control-label text-info">No. Factura</label>
              <input name="num_factura" id="num_factura" class="form-control rounded-0" value="<?php echo $num_factura ?? '' ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="date_carga_portal" class="control-label text-info">Fecha de carga al portal</label>
              <input type="date" name="date_carga_portal" id="date_carga_portal" class="form-control rounded-0 text-end" value="<?php echo $date_carga_portal ?? '' ?>">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label for="folio_fiscal" class="control-label text-info">Folio Fiscal</label>
              <input name="folio_fiscal" id="folio_fiscal" class="form-control rounded-0" value="<?php echo $folio_fiscal ?? '' ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="folio_comprobante_pago" class="control-label text-info">Folio Comprobante de pago</label>
              <input name="folio_comprobante_pago" id="folio_comprobante_pago" class="form-control rounded-0" value="<?php echo $folio_comprobante_pago ?? '' ?>">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="num_cheque" class="control-label text-info">No. de cheque</label>
              <input name="num_cheque" id="num_cheque" class="form-control rounded-0" value="<?php echo $num_cheque ?? '' ?>">
            </div>
          </div>
        </div>

        <hr>

        <!-- ===== PRODUCTOS ===== -->
        <fieldset>
          <legend class="text-info">Producto</legend>
          <?php
          $item_arr = [];
          $items = $conn->query("SELECT * FROM `item_list` WHERE status = 1 ORDER BY `description` ASC");
          while ($row = $items->fetch_assoc()):
              $item_arr[$row['supplier_id']][$row['id']] = $row;
          endwhile;
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
              <select id="item_id" class="custom-select select2">
                <option disabled selected></option>
              </select>
            </div>
          </div>

          <div class="row justify-content-start mt-2">
            <div class="col-md-2">
              <label>Unidad</label>
              <input type="text" class="form-control rounded-0" id="unit">
            </div>
            <div class="col-md-2">
              <label>Precio x Unidad</label>
              <input type="number" step="0.01" class="form-control rounded-0" id="price">
            </div>
            <div class="col-md-2">
              <label>Cantidad</label>
              <input type="number" step="0.01" class="form-control rounded-0" id="qty">
            </div>
            <div class="col-md-2">
              <label>Descuento %</label>
              <input type="number" step="0.01" class="form-control rounded-0" id="discount_item" value="0">
            </div>
            <div class="col-md-2 align-self-end">
              <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Agregar</button>
            </div>
          </div>
        </fieldset>

        <hr>

        <!-- ===== TABLA DE PRODUCTOS ===== -->
        <table class="table table-striped table-bordered" id="list">
          <colgroup>
            <col width="5%"><col width="8%"><col width="10%"><col width="35%"><col width="12%"><col width="10%"><col width="20%">
          </colgroup>
          <thead>
            <tr class="text-light bg-navy">
              <th class="text-center"></th>
              <th class="text-center">Cant</th>
              <th class="text-center">Unidad</th>
              <th class="text-center">Descripción</th>
              <th class="text-center">Precio por unidad</th>
              <th class="text-center">Desc. %</th>
              <th class="text-center">Total de línea</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($id)):
              $qry = $conn->query("SELECT p.*, i.description 
                                   FROM `po_items` p 
                                   INNER JOIN item_list i ON p.item_id = i.id 
                                   WHERE p.po_id = '{$id}'");
              while ($row = $qry->fetch_assoc()):
                $line_total = ($row['price'] - ($row['price']*$row['discount']/100)) * $row['quantity'];
            ?>
            <tr data-id="<?php echo $row['item_id']; ?>">
              <td class="text-center">
                <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
              </td>
              <td class="text-center">
                <input type="number" step="0.01" class="form-control inline-edit qty-input" name="qty[]" value="<?php echo $row['quantity']; ?>">
                <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
              </td>
              <td class="text-center unit"><?php echo $row['unit']; ?></td>
              <td class="item"><?php echo $row['description']; ?></td>
              <td class="text-center">
                <input type="number" step="0.01" class="form-control inline-edit price-input" name="price[]" value="<?php echo $row['price']; ?>">
              </td>
              <td class="text-center">
                <input type="number" step="0.01" class="form-control inline-edit discount-input" name="discount[]" value="<?php echo $row['discount']; ?>">
              </td>
              <td class="text-right total">
                <?php echo number_format($line_total,2); ?>
                <input type="hidden" name="total[]" value="<?php echo $line_total; ?>">
              </td>
            </tr>
            <?php endwhile; endif; ?>
          </tbody>
          <tfoot>
            <tr><th class="text-right" colspan="6">Sub Total</th><th class="text-right sub-total">0.00</th></tr>
            <tr>
              <th class="text-right" colspan="6">
                Impuesto %
                <input style="width:60px" name="tax_perc" type="number" min="0" max="100" value="<?php echo isset($tax_perc) ? $tax_perc : 16 ?>">
              </th>
              <th class="text-right tax">0.00</th>
            </tr>
            <tr>
              <th class="text-right" colspan="6">Total
                <input type="hidden" name="amount" value="<?php echo isset($amount) ? $amount : 0 ?>">
              </th>
              <th class="text-right grand-total">0.00</th>
            </tr>
          </tfoot>
        </table>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="remarks" class="text-info control-label">Observaciones</label>
              <textarea name="remarks" id="remarks" rows="3" class="form-control rounded-0"><?php echo $remarks ?? '' ?></textarea>
            </div>
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

<!-- FILA CLON PARA NUEVOS PRODUCTOS -->
<table id="clone_list" class="d-none">
  <tr>
    <td class="text-center">
      <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
    </td>
    <td class="text-center">
      <input type="number" step="0.01" class="form-control inline-edit qty-input" name="qty[]">
      <input type="hidden" name="item_id[]">
      <input type="hidden" name="unit[]">
    </td>
    <td class="text-center unit"></td>
    <td class="item"></td>
    <td class="text-center">
      <input type="number" step="0.01" class="form-control inline-edit price-input" name="price[]">
    </td>
    <td class="text-center">
      <input type="number" step="0.01" class="form-control inline-edit discount-input" name="discount[]" value="0">
    </td>
    <td class="text-right total">0.00
      <input type="hidden" name="total[]">
    </td>
  </tr>
</table>

<script>
var items = <?php echo json_encode($item_arr) ?>;

$(function () {
  // Init select2
  $('.select2').select2({ placeholder: "Selecciona aquí", width: 'resolve' });
  $('#item_id').select2({ placeholder: "Selecciona el proveedor primero", width: 'resolve' });

  // Al cambiar proveedor, cargar productos
  function populateProducts(){
    var supplier_id = $('#supplier_id').val();
    $('#item_id').select2('destroy').html('');
    if (items && items[supplier_id]) {
      $.each(items[supplier_id], function(k,row){
        $('#item_id').append($('<option>', {value: row.id, text: row.description}));
      });
      $('#item_id').select2({ placeholder: "Selecciona producto aquí", width: 'resolve' });
    } else {
      $('#item_id').select2({ placeholder: "Sin productos disponibles", width: 'resolve' });
    }
  }
  $('#supplier_id').on('change', populateProducts);
  // Si viene seleccionado (edición), poblar productos
  if($('#supplier_id').val()) populateProducts();

  // Agregar producto
  $('#add_to_list').on('click', function () {
    var supplier = $('#supplier_id').val();
    var item = $('#item_id').val();
    var qty = parseFloat($('#qty').val()) || 0;
    var unit = $('#unit').val();
    var price = parseFloat($('#price').val()) || 0;
    var discount = parseFloat($('#discount_item').val()) || 0; // porcentaje

    if (!supplier || !item || !unit || !price || !qty) {
      alert('Los campos del producto son obligatorios.');
      return false;
    }
    if ($('#list tbody').find('tr[data-id="'+item+'"]').length > 0){
      alert('Producto ya existe en el listado');
      return false;
    }

    var total = (price - (price*discount/100)) * qty;
    var item_description = (items[supplier] && items[supplier][item] && items[supplier][item].description) ? items[supplier][item].description : 'N/A';

    var tr = $('#clone_list tr').clone();
    tr.attr('data-id', item);
    tr.find('[name="item_id[]"]').val(item);
    tr.find('[name="unit[]"]').val(unit);
    tr.find('[name="qty[]"]').val(qty);
    tr.find('[name="price[]"]').val(price);
    tr.find('[name="discount[]"]').val(discount);
    tr.find('[name="total[]"]').val(total);

    tr.find('.unit').text(unit);
    tr.find('.item').text(item_description);
    tr.find('.total').text(total.toFixed(2));

    $('#list tbody').append(tr);

    // limpiar inputs de captura
    $('#item_id').val('').trigger('change');
    $('#qty').val(''); $('#unit').val(''); $('#price').val(''); $('#discount_item').val('0');

    calc();
  });

  // Eliminar fila (delegado)
  $(document).on('click', '.rem_row', function (e) {
    e.preventDefault();
    $(this).closest('tr').remove();
    calc();
  });

  // Recalcular al editar celdas (delegado)
  $(document).on('input', '.qty-input, .price-input, .discount-input', function () {
    calc();
  });

  // Recalcular al cambiar % impuesto
  $('[name="tax_perc"]').on('input', calc);

  // Enviar formulario
  $('#po-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: _base_url_ + "classes/Master.php?f=save_po",
      data: new FormData(this),
      cache: false, contentType: false, processData: false,
      method: 'POST', dataType: 'json',
      success: function(resp){
        if(resp && resp.status=='success'){
          location.replace(_base_url_+"admin/?page=purchase_order_cenit/view_po&id="+resp.id);
        }else{
          alert(resp && resp.msg ? resp.msg : 'Ocurrió un error');
          console.log(resp);
        }
      },
      error: function(err){ alert('Ocurrió un error'); console.log(err); }
    });
  });

  // Inicializar totales
  calc();

  function calc(){
    var sub_total=0;
    $('#list tbody tr').each(function(){
      var $tr = $(this);
      var qty = parseFloat($tr.find('.qty-input').val()) || 0;
      var price = parseFloat($tr.find('.price-input').val()) || 0;
      var discount = parseFloat($tr.find('.discount-input').val()) || 0; // %

      var total = (price - (price*discount/100)) * qty;
      $tr.find('[name="total[]"]').val(total);
      $tr.find('.total').text(total.toFixed(2));
      sub_total += total;
    });

    // Subtotal
    $('.sub-total').text(sub_total.toFixed(2));

    // Impuesto %
    var tax_perc = parseFloat($('[name="tax_perc"]').val()) || 0;
    var tax = (sub_total * tax_perc)/100;
    $('.tax').text(tax.toFixed(2));

    // Total
    var grand_total = sub_total + tax;
    $('.grand-total').text(grand_total.toFixed(2));
    $('[name="amount"]').val(grand_total.toFixed(2));
  }
});
</script>
