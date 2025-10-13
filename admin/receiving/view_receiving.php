<?php
// =============================
// VALIDAR EMPRESA
// =============================
$company_id = isset($_GET['company_id']) ? intval($_GET['company_id']) : 0;

// =============================
// VALIDAR RECEPCIÓN
// =============================
$qry = $conn->query("SELECT * FROM receiving_list where id = '{$_GET['id']}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
    if ($from_order == 1) {
        $po_qry = $conn->query("SELECT p.*,s.name as supplier FROM `purchase_order_list` p inner join `supplier_list` s on p.supplier_id = s.id where p.id= '{$form_id}' ");
        if ($po_qry->num_rows > 0) {
            foreach ($po_qry->fetch_array() as $k => $v) {
                if (!isset($$k))
                    $$k = $v;
            }
        }
    } else {
        $qry = $conn->query("SELECT b.*,s.name as supplier,p.po_code FROM back_order_list b inner join supplier_list s on b.supplier_id = s.id inner join purchase_order_list p on b.po_id = p.id  where b.id = '{$form_id}'");
        if ($qry->num_rows > 0) {
            foreach ($qry->fetch_array() as $k => $v) {
                if ($k == 'id')
                    $k = 'bo_id';
                if (!isset($$k))
                    $$k = $v;
            }
        }
    }
}
?>
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Cotización Aceptada: <?php echo $po_code ?></h4>
        <div>
            <button class="btn btn-flat btn-info btn-sm" type="button" id="print"><i class="fa fa-print"></i> Imprimir</button>
            <a class="btn btn-flat btn-warning btn-sm" href="<?php echo base_url . '/admin?page=receiving/manage_receiving&id=' . (isset($id) ? $id : '') . '&company_id=' . $company_id; ?>">
                <i class="fa fa-edit"></i> Editar
            </a>
            <a class="btn btn-flat btn-secondary btn-sm" href="<?php echo base_url . '/admin?page=purchase_order&company_id=' . $company_id; ?>">
                <i class="fa fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label text-info"># de la Cotización</label>
                    <div><?php echo isset($po_code) ? $po_code : '' ?></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_id" class="control-label text-info">Proveedor</label>
                        <div><?php echo isset($supplier) ? $supplier : '' ?></div>
                    </div>
                </div>
                <?php if (isset($bo_id)) : ?>
                    <div class="col-md-6">
                        <label class="control-label text-info">De la Cotización</label>
                        <div><?php echo isset($bo_code) ? $bo_code : '' ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <h4 class="text-info mt-4">Cotizaciones</h4>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
                    <col width="10%">
                    <col width="10%">
                    <col width="30%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr class="text-light bg-navy">
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
                    $qry = $conn->query("SELECT s.*,i.name,i.description FROM `stock_list` s inner join item_list i on s.item_id = i.id where s.id in ({$stock_ids})");
                    while ($row = $qry->fetch_assoc()) :
                        $total += $row['total']
                    ?>
                        <tr>
                            <td class="py-1 px-2 text-center"><?php echo number_format($row['quantity'], 2) ?></td>
                            <td class="py-1 px-2 text-center"><?php echo $row['unit'] ?></td>
                            <td class="py-1 px-2">
                                <?php echo $row['name'] ?> <br>
                                <small><?php echo $row['description'] ?></small>
                            </td>
                            <td class="py-1 px-2 text-right"><?php echo number_format($row['price']) ?></td>
                            <td class="py-1 px-2 text-right"><?php echo number_format($row['total']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Sub Total</th>
                        <th class="text-right py-1 px-2 sub-total"><?php echo number_format($total, 2) ?></th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Descuento <?php echo isset($discount_perc) ? $discount_perc : 0 ?>%</th>
                        <th class="text-right py-1 px-2 discount"><?php echo isset($discount) ? number_format($discount, 2) : 0 ?></th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Impuesto <?php echo isset($tax_perc) ? $tax_perc : 0 ?>%</th>
                        <th class="text-right py-1 px-2 tax"><?php echo isset($tax) ? number_format($tax, 2) : 0 ?></th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="4">Total</th>
                        <th class="text-right py-1 px-2 grand-total"><?php echo isset($amount) ? number_format($amount, 2) : 0 ?></th>
                    </tr>
                </tfoot>
            </table>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks" class="text-info control-label">Observaciones</label>
                        <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                    </div>
                </div>
                <?php if ($status > 0) : ?>
                    <div class="col-md-6 text-right">
                        <span class="badge badge-<?php echo ($status == 2) ? 'success' : 'warning'; ?>">
                            <?php echo ($status == 2) ? "Recibido" : "Parcialmente Recibido"; ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(function() {
    // === Botón Imprimir ===
    $('#print').click(function() {
        start_loader();
        var _el = $('<div>');
        var _head = $('head').clone();
        _head.find('title').text("Cotización Aceptada - Vista de Impresión");
        var p = $('#print_out').clone();
        p.find('tr.text-light').removeClass("text-light bg-navy");
        _el.append(_head);
        _el.append(`
            <div class="d-flex justify-content-center mb-2">
                <div class="col-1 text-right">
                    <img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px"/>
                </div>
                <div class="col-10">
                    <h4 class="text-center mb-0"><?php echo $_settings->info('name') ?></h4>
                    <h5 class="text-center mb-0">Cotización Aceptada</h5>
                </div>
            </div><hr/>
        `);
        _el.append(p.html());

        var nw = window.open("", "", "width=1200,height=900,left=250,location=no,titlebar=yes");
        nw.document.write(_el.html());
        nw.document.close();
        setTimeout(() => {
            nw.print();
            setTimeout(() => {
                nw.close();
                end_loader();
            }, 200);
        }, 500);
    });

    // === Eliminar Recepción (si aplicara desde modal o tabla futura) ===
    $('.delete_data').click(function() {
        _conf("¿Deseas eliminar esta recepción permanentemente?", "delete_receiving", [$(this).attr('data-id')]);
    });
});

function delete_receiving(id) {
    start_loader();
    $.ajax({
        url: _base_url_ + "classes/Master.php?f=delete_receiving",
        method: "POST",
        data: { id: id },
        dataType: "json",
        error: err => {
            console.error(err);
            alert_toast("Error al eliminar", 'error');
            end_loader();
        },
        success: function(resp) {
            if (resp.status === 'success') {
                // ✅ Redirigir con la empresa
                location.replace(_base_url_ + "admin/?page=receiving&company_id=" + resp.company_id);
            } else {
                alert_toast("No se pudo eliminar la recepción", 'error');
                end_loader();
            }
        }
    });
}
</script>
