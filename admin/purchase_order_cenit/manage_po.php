<?php
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_order_list p inner join supplier_list s on p.supplier_id = s.id  where p.id = '{$_GET['id']}'");
    //$qry = $conn->query("SELECT p.*, c.name as company FROM `purchase_order_list` p inner join company_list c on c.id = p.id_company order by p.`date_created` desc;");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            $$k = $v;
        }
    }
}
?>
<style>
    select[readonly].select2-hidden-accessible+.select2-container {
        pointer-events: none;
        touch-action: none;
        background: #eee;
        box-shadow: none;
    }

    select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
        background: #eee;
        box-shadow: none;
    }
</style>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">
            <?php echo isset($id) ? "Información Cotización : " . $po_code : 'Crear Nueva Cotización' ?>
        </h4>
    </div>
    <div class="card-body">
        <form action="" id="po-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">
                        <label class="control-label text-info">Cotización</label>
                        <input type="text" class="form-control rounded-0"
                            value="<?php echo isset($po_code) ? $po_code : '' ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="oc" class="control-label text-info">OC</label>
                            <input name="oc" id="oc" class="form-control rounded-0"
                                value="<?php echo isset($oc) ? $oc : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_exp" class="control-label text-info">Fecha de Expedición</label>
                            <input type="date" name="date_exp" id="date_exp" step="any"
                                class="form-control rounded-0 text-end"
                                value="<?php echo isset($date_exp) ? $date_exp : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="id_company" class="control-label text-info">Empresa</label>
                            <select name="id_company" id="id_company" class="custom-select select2">
                                <option <?php echo !isset($company_id) ? 'selected' : '' ?> disabled></option>
                                <?php
                                $supplier = $conn->query("SELECT * FROM `company_list` where status = 1 and id = 3 order by `name` asc");
                                while ($row = $supplier->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($id_company) && $id_company == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="num_factura" class="control-label text-info">No. Factura</label>
                            <input name="num_factura" id="num_factura" class="form-control rounded-0"
                                value="<?php echo isset($num_factura) ? $num_factura : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_carga_portal" class="control-label text-info">Fecha de carga al
                                portal</label>
                            <input type="date" name="date_carga_portal" id="date_carga_portal" step="any"
                                class="form-control rounded-0 text-end"
                                value="<?php echo isset($date_carga_portal) ? $date_carga_portal : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date_pago" class="control-label text-info">Fecha de pago</label>
                            <input type="date" name="date_pago" id="date_pago" step="any"
                                class="form-control rounded-0 text-end"
                                value="<?php echo isset($date_pago) ? $date_pago : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="folio_fiscal" class="control-label text-info">Folio Fiscal</label>
                            <input name="folio_fiscal" id="folio_fiscal" class="form-control rounded-0"
                                value="<?php echo isset($folio_fiscal) ? $folio_fiscal : ''; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="folio_comprobante_pago" class="control-label text-info">Folio Comprobante de pago</label>
                            <input name="folio_comprobante_pago" id="folio_comprobante_pago"
                                class="form-control rounded-0"
                                value="<?php echo isset($folio_comprobante_pago) ? $folio_comprobante_pago : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pago_efectivo" class="control-label text-info">Pago en efectivo</label>
                            <input type="date" name="pago_efectivo" id="pago_efectivo" step="any"
                                class="form-control rounded-0 text-end"
                                value="<?php echo isset($pago_efectivo) ? $pago_efectivo : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cliente_cotizacion" class="text-info control-label">Cliente</label>
                            <textarea name="cliente_cotizacion" id="cliente_cotizacion" rows="3"
                                class="form-control rounded-0"><?php echo isset($cliente_cotizacion) ? $cliente_cotizacion : '' ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="metodo_pago" class="control-label text-info">Método de pago</label>
                            <input name="metodo_pago" id="metodo_pago" class="form-control rounded-0"
                                value="<?php echo isset($metodo_pago) ? $metodo_pago : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="num_cheque" class="control-label text-info">No. de cheque</label>
                            <input name="num_cheque" id="num_cheque" step="any" class="form-control rounded-0 text-end"
                                value="<?php echo isset($num_cheque) ? $num_cheque : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="trabajo" class="text-info control-label">Trabajo</label>
                            <textarea name="trabajo" id="trabajo" rows="3"
                                class="form-control rounded-0"><?php echo isset($trabajo) ? $trabajo : '' ?></textarea>
                        </div>
                    </div>
                </div>
                <hr>
                <fieldset>
                    <legend class="text-info">Producto</legend>
                    <div class="row justify-content-center align-items-end">
                        <?php
                        $item_arr = array();
                        $cost_arr = array();
                        $item = $conn->query("SELECT * FROM `item_list` where status = 1 order by `name` asc");
                        while ($row = $item->fetch_assoc()):
                            $item_arr[$row['supplier_id']][$row['id']] = $row;
                            $cost_arr[$row['id']] = $row['cost'];
                        endwhile;
                        ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="supplier_id" class="control-label text-info">Proveedor</label>
                                <select name="supplier_id" id="supplier_id" class="custom-select select2">
                                    <option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
                                    <?php
                                    //$supplier = $conn->query("SELECT * FROM `supplier_list` where status = 1 order by `name` asc");
                                    $supplier = $conn->query("SELECT DISTINCT supplier_list.id, supplier_list.* FROM `supplier_list` inner join `item_list` ON item_list.supplier_id = supplier_list.id where supplier_list.status = 1 order by `name` asc");
                                    while ($row = $supplier->fetch_assoc()):
                                        ?>
                                        <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="item_id" class="control-label">Producto</label>
                                <select id="item_id" class="custom-select ">
                                    <option disabled selected></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center align-items-end">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="unit" class="control-label">Unidad</label>
                                <input type="text" class="form-control rounded-0" id="unit">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="price" class="control-label">Precio x Unidad</label>
                                <input type="text" class="form-control rounded-0" id="price">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="qty" class="control-label">Cantidad</label>
                                <input type="number" step="any" class="form-control rounded-0" id="qty">
                            </div>
                        </div>
                        <div class="col-md-1 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-flat btn-sm btn-primary"
                                    id="add_to_list">Agregar</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <hr>
                <table class="table table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="5%">
                        <col width="10%">
                        <col width="35%">
                        <col width="15%">
                        <col width="10%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr class="text-light bg-navy">
                            <th class="text-center py-1 px-2"></th>
                            <th class="text-center py-1 px-2">Cant</th>
                            <th class="text-center py-1 px-2">Unidad</th>
                            <th class="text-center py-1 px-2">Descripción</th>
                            <th class="text-center py-1 px-2">Precio por unidad</th>
                            <th class="text-center py-1 px-2">Desc.</th>
                            <th class="text-center py-1 px-2">Total de línea</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        if (isset($id)):
                            $qry = $conn->query("SELECT p.*,i.name,i.description FROM `po_items` p inner join item_list i on p.item_id = i.id where p.po_id = '{$id}'");
                            while ($row = $qry->fetch_assoc()):
                                $total += $row['total']
                                    ?>
                                <tr>
                                    <td class="py-1 px-2 text-center">
                                        <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i
                                                class="fa fa-times"></i></button>
                                    </td>
                                    <td class="py-1 px-2 text-center qty">
                                        <span class="visible">
                                            <?php echo number_format($row['quantity']); ?>
                                        </span>
                                        <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                                        <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
                                        <input type="hidden" name="qty[]" value="<?php echo $row['quantity']; ?>">
                                        <input type="hidden" name="price[]" value="<?php echo $row['price']; ?>">
                                        <input type="hidden" name="descuento[]" value="<?php echo $row['descuento']; ?>">
                                        <input type="hidden" name="total[]" value="<?php echo $row['total']; ?>">
                                    </td>
                                    <td class="py-1 px-2 text-center unit">
                                        <?php echo $row['unit']; ?>
                                    </td>
                                    <td class="py-1 px-2 item">
                                        <?php echo $row['name']; ?> <br>
                                        <?php echo $row['description']; ?>
                                    </td>
                                    <td class="py-1 px-2 text-right cost">
                                        <?php echo number_format($row['price']); ?>
                                    </td>
                                    <td class="py-1 px-2 text-right descuento">
                                        <?php echo number_format($row['descuento']); ?>
                                    </td>
                                    <td class="py-1 px-2 text-right total">
                                        <?php echo number_format($row['total']); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="6">Sub Total</th>
                            <th class="text-right py-1 px-2 sub-total">0</th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="6">Descuento <input style="width:40px !important"
                                    name="discount_perc" class='' type="number" min="0" max="100"
                                    value="<?php echo isset($discount_perc) ? $discount_perc : 0 ?>">%
                                <input type="hidden" name="discount"
                                    value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 discount">
                                <?php echo isset($discount) ? number_format($discount) : 0 ?>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="6">Impuesto 
                                <!--<input style="width:40px !important"
                                    name="tax_perc" class='' type="number" min="0" max="100"
                                    value="<php echo isset($tax_perc) ? $tax_perc : 0 ?>">%-->
                                <!--<input type="hidden" name="tax" value="<php echo isset($discount) ? $discount : 0 ?>">-->
                            </th>
                            <th class="text-right py-1 px-2 tax">
                                <?php echo isset($tax) ? number_format($tax) : 0 ?>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="6">Total
                                <input type="hidden" name="amount"
                                    value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 grand-total">0</th>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remarks" class="text-info control-label">Observaciones</label>
                            <textarea name="remarks" id="remarks" rows="3"
                                class="form-control rounded-0"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
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
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-2 text-center">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-2 text-center qty">
            <span class="visible"></span>
            <input type="hidden" name="item_id[]">
            <input type="hidden" name="unit[]">
            <input type="hidden" name="qty[]">
            <input type="hidden" name="price[]">
            <input type="hidden" name="descuento[]">
            <input type="hidden" name="total[]">
        </td>
        <td class="py-1 px-2 text-center unit">
        </td>
        <td class="py-1 px-2 item">
        </td>
        <td class="py-1 px-2 text-right cost">
        </td>
        <td class="py-1 px-2 text-right descuento">
        </td>
        <td class="py-1 px-2 text-right total">
        </td>
    </tr>
</table>
<script>
    var items = $.parseJSON('<?php echo json_encode($item_arr) ?>')
    var costs = $.parseJSON('<?php echo json_encode($cost_arr) ?>')

    $(function () {
        $('.select2').select2({
            placeholder: "Selecciona aquí",
            width: 'resolve',
        })
        $('#item_id').select2({
            placeholder: "Selecciona el proveedor primero",
            width: 'resolve',
        })

        //funcion para cambiar box productos
        $('#supplier_id').change(function () {
            var supplier_id = $(this).val()
            $('#item_id').select2('destroy')
            if (!!items[supplier_id]) {
                $('#item_id').html('')
                var list_item = new Promise(resolve => {
                    Object.keys(items[supplier_id]).map(function (k) {
                        var row = items[supplier_id][k]
                        var opt = $('<option>')
                        opt.attr('value', row.id)
                        opt.text(row.name)
                        $('#item_id').append(opt)
                    })
                    resolve()
                })
                list_item.then(function () {
                    $('#item_id').select2({
                        placeholder: "Selecciona producto aquí",
                        width: 'resolve',
                    })
                })
            } else {
                list_item.then(function () {
                    $('#item_id').select2({
                        placeholder: "Sin productos disponibles",
                        width: 'resolve',
                    })
                })
            }

        })

        $('#add_to_list').click(function () {
            var supplier = $('#supplier_id').val()
            var item = $('#item_id').val()
            var qty = $('#qty').val() > 0 ? $('#qty').val() : 0;
            var unit = $('#unit').val()
            //var price = costs[item] || 0
            var price = $('#price').val()
            //var descuento = parseFloat(price) * 0.16
            var descuento = 0.0

            var total = (parseFloat(price) + parseFloat(descuento)) * parseFloat(qty)
            // console.log(supplier,item)
            var item_name = items[supplier][item].name || 'N/A';
            var item_description = items[supplier][item].description || 'N/A';
            var tr = $('#clone_list tr').clone()
            if (item == '' || qty == '' || unit == '' || price == '') {
                alert_toast('Los campos de texto del elemento de formulario son obligatorios.', 'warning');
                return false;
            }
            if ($('table#list tbody').find('tr[data-id="' + item + '"]').length > 0) {
                alert_toast('Producto ya existe en el listado', 'error');
                return false;
            }
            tr.find('[name="item_id[]"]').val(item)
            tr.find('[name="unit[]"]').val(unit)
            tr.find('[name="qty[]"]').val(qty)
            tr.find('[name="price[]"]').val(price)
            tr.find('[name="descuento[]"]').val(descuento)
            tr.find('[name="total[]"]').val(total)
            tr.attr('data-id', item)
            tr.find('.qty .visible').text(qty)
            tr.find('.unit').text(unit)
            tr.find('.price').text(price)
            tr.find('.item').html(item_name + '<br/>' + item_description)
            tr.find('.cost').text(parseFloat(price).toLocaleString('en-US'))
            tr.find('.descuento').text(parseFloat(descuento).toLocaleString('en-US'))
            tr.find('.total').text(parseFloat(total).toLocaleString('en-US'))
            $('table#list tbody').append(tr)
            calc()
            $('#item_id').val('').trigger('change')
            $('#qty').val('')
            $('#unit').val('')
            $('#price').val('')
            tr.find('.rem_row').click(function () {
                rem($(this))
            })

            $('[name="discount_perc"],[name="tax_perc"]').on('input', function () {
                calc()
            })
            //$('#supplier_id').attr('readonly', 'readonly')
        })

        $('#po-form').submit(function (e) {
            e.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url: _base_url_ + "classes/Master.php?f=save_po",
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
                success: function (resp) {
                    if (resp.status == 'success') {
                        location.replace(_base_url_ + "admin/?page=purchase_order_cenit/view_po&id=" + resp.id);
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
                    $('html,body').animate({
                        scrollTop: 0
                    }, 'fast')
                }
            })
        })

        if ('<?php echo isset($id) && $id > 0 ?>' == 1) {
            calc()
            $('#supplier_id').trigger('change')
            //$('#supplier_id').attr('readonly', 'readonly')
            $('table#list tbody tr .rem_row').click(function () {
                rem($(this))
            })
        }
    })

    function rem(_this) {
        _this.closest('tr').remove()
        calc()
        if ($('table#list tbody tr').length <= 0)
            $('#supplier_id').removeAttr('readonly')

    }

    function calc() {
        var sub_total = 0;
        var grand_total = 0;
        var discount = 0;
        var tax = 0;
        $('table#list tbody input[name="total[]"]').each(function () {
            sub_total += parseFloat($(this).val())

        })
        $('table#list tfoot .sub-total').text(parseFloat(sub_total).toLocaleString('en-US', {
            style: 'decimal',
            maximumFractionDigit: 2
        }))
        var discount = sub_total * (parseFloat($('[name="discount_perc"]').val()) / 100)
        sub_total = sub_total - discount;
        var tax = sub_total * 0.16
        //var tax = sub_total * (parseFloat($('[name="tax_perc"]').val()) / 100)
        grand_total = sub_total + tax
        $('.discount').text(parseFloat(discount).toLocaleString('en-US', {
            style: 'decimal',
            maximumFractionDigit: 2
        }))
        $('[name="discount"]').val(parseFloat(discount))
        $('.tax').text(parseFloat(tax).toLocaleString('en-US', {
            style: 'decimal',
            maximumFractionDigit: 2
        }))
        $('[name="tax"]').val(parseFloat(tax))
        $('table#list tfoot .grand-total').text(parseFloat(grand_total).toLocaleString('en-US', {
            style: 'decimal',
            maximumFractionDigit: 2
        }))
        $('[name="amount"]').val(parseFloat(grand_total))

    }
</script>