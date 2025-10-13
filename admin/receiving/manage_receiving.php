<?php
// =============================
// VALIDAR ID DE EMPRESA
// =============================
$company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : 0;

// =============================
// CONSULTAS PRINCIPALES
// =============================
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT p.* FROM receiving_list p where p.id = '{$_GET['id']}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            $$k = $v;
        }
        if ($from_order == 1) {
            $qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_order_list p inner join supplier_list s on p.supplier_id = s.id  where p.id = '{$form_id}'");
            if ($qry->num_rows > 0) {
                foreach ($qry->fetch_array() as $k => $v) {
                    if ($k == 'id') $k = 'po_id';
                    if (!isset($$k)) $$k = $v;
                }
            }
        } else {
            $qry = $conn->query("SELECT b.*,s.name as supplier,p.po_code FROM back_order_list b inner join supplier_list s on b.supplier_id = s.id inner join purchase_order_list p on b.po_id = p.id  where b.id = '{$_GET['bo_id']}'");
            if ($qry->num_rows > 0) {
                foreach ($qry->fetch_array() as $k => $v) {
                    if ($k == 'id') $k = 'bo_id';
                    if (!isset($$k)) $$k = $v;
                }
            }
        }
    }
}
if (isset($_GET['po_id'])) {
    $qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_order_list p inner join supplier_list s on p.supplier_id = s.id  where p.id = '{$_GET['po_id']}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            if ($k == 'id') $k = 'po_id';
            $$k = $v;
        }
    }
}
if (isset($_GET['bo_id'])) {
    $qry = $conn->query("SELECT b.*,s.name as supplier,p.po_code FROM back_order_list b inner join supplier_list s on b.supplier_id = s.id inner join purchase_order_list p on b.po_id = p.id  where b.id = '{$_GET['bo_id']}'");
    if ($qry->num_rows > 0) {
        foreach ($qry->fetch_array() as $k => $v) {
            if ($k == 'id') $k = 'bo_id';
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
            <?php echo !isset($id) ? "Cotización Aceptada de " . $po_code : 'Actualizar Información de Cotización'; ?>
        </h4>
    </div>
    <div class="card-body">
        <form action="" id="receive-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <input type="hidden" name="from_order" value="<?php echo isset($bo_id) ? 2 : 1 ?>">
            <input type="hidden" name="form_id" value="<?php echo isset($bo_id) ? $bo_id : $po_id ?>">
            <input type="hidden" name="po_id" value="<?php echo isset($po_id) ? $po_id : '' ?>">
            <input type="hidden" name="company_id" value="<?php echo $company_id ?>">

            <div class="container-fluid">
                <div class="row">
                    <?php if (!isset($bo_id)) : ?>
                        <div class="col-md-6">
                            <label class="control-label text-info">Cotización</label>
                            <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($po_code) ? $po_code : '' ?>" readonly>
                        </div>
                    <?php else : ?>
                        <div class="col-md-6">
                            <label class="control-label text-info">OC</label>
                            <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($bo_code) ? $bo_code : '' ?>" readonly>
                        </div>
                    <?php endif; ?>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="supplier_id" class="control-label text-info">Proveedor</label>
                            <select id="supplier_id" name="supplier_id" class="custom-select select2">
                                <option <?php echo !isset($supplier_id) ? 'selected' : '' ?> disabled></option>
                                <?php
                                $supplier = $conn->query("SELECT * FROM `supplier_list` where status = 1 order by `name` asc");
                                while ($row = $supplier->fetch_assoc()) :
                                ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($supplier_id) && $supplier_id == $row['id'] ? "selected" : "" ?>><?php echo $row['name'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>
                <table class="table table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="10%">
                        <col width="10%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                        <tr class="text-light bg-navy">
                            <th class="text-center py-1 px-2"></th>
                            <th class="text-center py-1 px-2">Cant</th>
                            <th class="text-center py-1 px-2">Unidad</th>
                            <th class="text-center py-1 px-2">Producto</th>
                            <th class="text-center py-1 px-2">Precio</th>
                            <th class="text-center py-1 px-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        if (isset($po_id)) :
                            if (!isset($bo_id))
                                $qry = $conn->query("SELECT p.*,i.name,i.description FROM `po_items` p inner join item_list i on p.item_id = i.id where p.po_id = '{$po_id}'");
                            else
                                $qry = $conn->query("SELECT b.*,i.name,i.description FROM `bo_items` b inner join item_list i on b.item_id = i.id where b.bo_id = '{$bo_id}'");
                            while ($row = $qry->fetch_assoc()) :
                                $total += $row['total'];
                                $row['qty'] = $row['quantity'];
                                if (isset($stock_ids)) {
                                    $qty = $conn->query("SELECT * FROM `stock_list` where id in ($stock_ids) and item_id = '{$row['item_id']}'");
                                    $row['qty'] = $qty->num_rows > 0 ? $qty->fetch_assoc()['quantity'] : $row['qty'];
                                }
                        ?>
                                <tr>
                                    <td class="py-1 px-2 text-center">
                                        <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
                                    </td>
                                    <td class="py-1 px-2 text-center qty">
                                        <input type="number" name="qty[]" style="width:50px !important" value="<?php echo $row['qty']; ?>" max="<?php echo $row['quantity']; ?>" min="0">
                                        <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                                        <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
                                        <input type="hidden" name="oqty[]" value="<?php echo $row['quantity']; ?>">
                                        <input type="hidden" name="price[]" value="<?php echo $row['price']; ?>">
                                        <input type="hidden" name="total[]" value="<?php echo $row['total']; ?>">
                                    </td>
                                    <td class="py-1 px-2 text-center unit"><?php echo $row['unit']; ?></td>
                                    <td class="py-1 px-2 item"><?php echo $row['name']; ?><br><?php echo $row['description']; ?></td>
                                    <td class="py-1 px-2 text-right cost"><?php echo number_format($row['price']); ?></td>
                                    <td class="py-1 px-2 text-right total"><?php echo number_format($row['total']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Sub Total</th>
                            <th class="text-right py-1 px-2 sub-total">0</th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Descuento 
                                <input style="width:40px !important" name="discount_perc" type="number" min="0" max="100" value="<?php echo isset($discount_perc) ? $discount_perc : 0 ?>">%
                                <input type="hidden" name="discount" value="<?php echo isset($discount) ? $discount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 discount"><?php echo isset($discount) ? number_format($discount) : 0 ?></th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Impuesto 
                                <input style="width:40px !important" name="tax_perc" type="number" min="0" max="100" value="<?php echo isset($tax_perc) ? $tax_perc : 0 ?>">%
                                <input type="hidden" name="tax" value="<?php echo isset($tax) ? $tax : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 tax"><?php echo isset($tax) ? number_format($tax) : 0 ?></th>
                        </tr>
                        <tr>
                            <th class="text-right py-1 px-2" colspan="5">Total
                                <input type="hidden" name="amount" value="<?php echo isset($amount) ? $amount : 0 ?>">
                            </th>
                            <th class="text-right py-1 px-2 grand-total">0</th>
                        </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remarks" class="text-info control-label">Observaciones</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control rounded-0"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-primary" type="submit" form="receive-form">Guardar</button>
        <a class="btn btn-flat btn-danger" href="<?php echo base_url . 'admin/?page=purchase_order&company_id=' . $company_id ?>">Cancelar</a>
    </div>
</div>

<script>
$(function() {
    $('.select2').select2({
        placeholder: "Seleccione aquí",
        width: 'resolve',
    });

    // === GUARDAR RECEPCIÓN ===
    $('#receive-form').submit(function(e) {
        e.preventDefault();
        var _this = $(this);
        $('.err-msg').remove();
        start_loader();

        $.ajax({
            url: _base_url_ + "classes/Master.php?f=save_receiving",
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            dataType: 'json',
            error: err => {
                console.log(err);
                alert_toast("Ocurrió un error en el servidor", 'error');
                end_loader();
            },
            success: function(resp) {
                if (resp.status === 'success') {
                    // ✅ Redirigir manteniendo la empresa
                    location.replace(
                        _base_url_ + "admin/?page=receiving/view_receiving&id=" + resp.id + "&company_id=" + resp.company_id
                    );
                } else if (resp.status === 'failed' && resp.msg) {
                    var el = $('<div>').addClass("alert alert-danger err-msg").text(resp.msg);
                    _this.prepend(el);
                    el.show('slow');
                    end_loader();
                } else {
                    alert_toast("Ocurrió un error desconocido", 'error');
                    console.log(resp);
                    end_loader();
                }
                $('html,body').animate({ scrollTop: 0 }, 'fast');
            }
        });
    });

    // === CÁLCULOS DINÁMICOS ===
    if ('<?php echo (isset($id) && $id > 0) || (isset($po_id) && $po_id > 0) ?>' == 1) {
        calc();
        $('#supplier_id').attr('readonly', 'readonly');
        $('table#list tbody tr .rem_row').click(function() { rem($(this)); });
        $('[name="qty[]"], [name="discount_perc"], [name="tax_perc"]').on('input', calc);
    }
});

function rem(_this) {
    _this.closest('tr').remove();
    calc();
    if ($('table#list tbody tr').length <= 0)
        $('#supplier_id').removeAttr('readonly');
}

function calc() {
    var sub_total = 0, grand_total = 0, discount = 0, tax = 0;
    $('table#list tbody tr').each(function() {
        var qty = parseFloat($(this).find('[name="qty[]"]').val()) || 0;
        var price = parseFloat($(this).find('[name="price[]"]').val()) || 0;
        var total = qty * price;
        $(this).find('[name="total[]"]').val(total);
        $(this).find('.total').text(total.toLocaleString('es-MX', {minimumFractionDigits: 2}));
    });

    $('table#list tbody input[name="total[]"]').each(function() {
        sub_total += parseFloat($(this).val()) || 0;
    });

    $('.sub-total').text(sub_total.toLocaleString('es-MX', {minimumFractionDigits: 2}));

    discount = sub_total * (parseFloat($('[name="discount_perc"]').val()) / 100);
    sub_total -= discount;
    tax = sub_total * (parseFloat($('[name="tax_perc"]').val()) / 100);
    grand_total = sub_total + tax;

    $('.discount').text(discount.toLocaleString('es-MX', {minimumFractionDigits: 2}));
    $('[name="discount"]').val(discount);
    $('.tax').text(tax.toLocaleString('es-MX', {minimumFractionDigits: 2}));
    $('[name="tax"]').val(tax);
    $('.grand-total').text(grand_total.toLocaleString('es-MX', {minimumFractionDigits: 2}));
    $('[name="amount"]').val(grand_total);
}
</script>
