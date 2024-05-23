<?php
//$qry = $conn->query("SELECT p.*,s.name as supplier FROM purchase_order_list p inner join supplier_list s on p.supplier_id = s.id  where p.id = '{$_GET['id']}'");
$qry = $conn->query("SELECT p.*,s.name as supplier, c.logo as logo_empresa, c.name as name_empresa FROM purchase_order_list p inner join supplier_list s on p.supplier_id = s.id left join company_list c on p.id_company = c.id where p.id = '{$_GET['id']}'");

if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
}
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Información de la Cotización :
            <?php echo $po_code ?>
        </h4>
        <br><br>
        <div class="row">
            <div class="col-md-3">
                <label class="control-label text-info">OC</label>
                <div>
                    <?php echo isset($oc) ? $oc : '' ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="supplier_id" class="control-label text-info">Proveedor</label>
                    <div>
                        <?php echo isset($supplier) ? $supplier : '' ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <label class="control-label text-info">No. Factura</label>
                <div>
                    <?php echo isset($num_factura) ? $num_factura : '' ?>
                </div>
            </div>
            <div class="col-md-3">
                <label class="control-label text-info">Fecha de Carga al Portal</label>
                <div>
                    <?php echo isset($date_carga_portal) ? $date_carga_portal : '' ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <label class="control-label text-info">Fecha de Pago</label>
                <div>
                    <?php echo isset($date_pago) ? $date_pago : '' ?>
                </div>
            </div>
            <div class="col-md-3">
                <label class="control-label text-info">Folio Fiscal</label>
                <div>
                    <?php echo isset($folio_fiscal) ? $folio_fiscal : '' ?>
                </div>
            </div>
            <div class="col-md-3">
                <label class="control-label text-info">Folio Comprobante de Pago</label>
                <div>
                    <?php echo isset($folio_comprobante_pago) ? $folio_comprobante_pago : '' ?>
                </div>
            </div>
            <div class="col-md-3">
                <label class="control-label text-info">Pago en Efectivo</label>
                <div>
                    <?php echo isset($pago_efectivo) ? $pago_efectivo : '' ?>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <label class="control-label text-info">Cotización</label>
                <div>
                    <?php echo isset($po_code) ? $po_code : '' ?>
                </div>
            </div>
            <div class="col-md-3">
                <label class="control-label text-info">Fecha de Expedición: </label>
                <div>
                    <?php echo isset($date_exp) ? $date_exp : '' ?>
                </div>
            </div>
        </div>

        <br>
    </div>
    <div class="card-body" id="print_encabezado" >
        <div class="col-md-6">
            <label class="control-label text-info" style="color: #0B779E;">Vendido a: </label> 
            <?php echo isset($cliente_cotizacion) ? $cliente_cotizacion : '' ?>
        </div>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <br>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
                    <col width="10%">
                    <col width="10%">
                    <col width="30%">
                    <col width="15%">
                    <col width="15%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2">Cant.</th>
                        <th class="text-center py-1 px-2">Unidad</th>
                        <th class="text-center py-1 px-2">Descripción</th>
                        <th class="text-center py-1 px-2">Precio por Unidad</th>
                        <th class="text-center py-1 px-2">Desc</th>
                        <th class="text-center py-1 px-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    $qry = $conn->query("SELECT p.*,i.name,i.description FROM `po_items` p inner join item_list i on p.item_id = i.id where p.po_id = '{$id}'");
                    while ($row = $qry->fetch_assoc()):
                        $total += $row['total']
                            ?>
                        <tr>
                            <td class="py-1 px-2 text-center">
                                <?php echo number_format($row['quantity'], 2) ?>
                            </td>
                            <td class="py-1 px-2 text-center">
                                <?php echo ($row['unit']) ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['name'] ?> <br>
                                <?php echo $row['description'] ?>
                            </td>
                            <td class="py-1 px-2 text-right">
                                <?php echo number_format($row['price']) ?>
                            </td>
                            <td class="py-1 px-2 text-right">
                                <!--<php echo number_format($row['price'] * 0.16) ?>-->
                            </td>
                            <td class="py-1 px-2 text-right">
                                <?php echo number_format($row['total']) ?>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="5">Sub Total</th>
                        <th class="text-right py-1 px-2 sub-total">
                            <?php echo number_format($total, 2) ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="5">Descuento
                            <?php echo isset($discount_perc) ? $discount_perc : 0 ?>%
                        </th>
                        <th class="text-right py-1 px-2 discount">
                            <?php echo isset($discount) ? number_format($discount, 2) : 0 ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="5">Impuesto
                            <?php echo isset($tax_perc) ? $tax_perc : 0 ?>%
                        </th>
                        <th class="text-right py-1 px-2 tax">
                            <?php echo isset($tax) ? number_format($tax, 2) : 0 ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="5">Total</th>
                        <th class="text-right py-1 px-2 grand-total">
                            <?php echo isset($amount) ? number_format($amount, 2) : 0 ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks" class="text-info control-label">Observaciones</label>
                        <p>
                            <?php echo isset($remarks) ? $remarks : '' ?>
                        </p>
                    </div>
                </div>
                <?php if ($status > 0): ?>
                    <div class="col-md-6">
                        <span class="text-info">
                            <?php echo ($status == 2) ? "Recibido" : "Recibido Parcialmente" ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-info" type="button" id="print">Imprimir</button>
        <a class="btn btn-flat btn-primary"
            href="<?php echo base_url . '/admin?page=purchase_order_cenit/manage_po&id=' . (isset($id) ? $id : '') ?>">Editar</a>
        <a class="btn btn-flat btn-danger" href="<?php echo base_url . '/admin?page=purchase_order_cenit' ?>">Volver</a>
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
            <input type="hidden" name="iva[]">
            <input type="hidden" name="total[]">
        </td>
        <td class="py-1 px-2 text-center unit">
        </td>
        <td class="py-1 px-2 item">
        </td>
        <td class="py-1 px-2 text-right cost">
        </td>
        <td class="py-1 px-2 text-right total">
        </td>
    </tr>
</table>
<script>
    $(function () {
        $('#print').click(function () {
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
            _head.find('title').text("")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light bg-navy")

            var encabezado = $('#print_encabezado').clone()

            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">' +
                '<div class="col-1 text-center">' +
                '<img src="<?php echo validate_image($logo_empresa) ?>" width="1100px" height="120px" />' +
                '</div><hr/>')
            _el.append( 
                '<div class="d-flex justify-content-center">' +
                '<table class="demoTable" style="height: 54px; width: 672px;">' +
                '    <thead>' +
                '        <tr style="height: 18px;">' +
                '           <td style="height: 18px; width: 550px; font-size: 20px"><span style="color: #0B779E;"><?php echo $name_empresa ?></span></td>' +
                '            <td style="width: 135.21875px; height: 18px; text-align: right;"><span' +
                '                    style="color: #0B779E;">Fecha: </span></td>' +
                '            <td style="height: 18px; width: 146.234375px; text-align: left;"><?php echo isset($date_exp) ? $date_exp : '' ?></td>' +
                '        </tr>' +
                '    </thead>' +
                '    <tbody>' +
                '        <tr style="height: 36px;">' +
                '            <td style="height: 36px; width: 400px;"></td>' +
                '            <td style="width: 135.21875px; height: 36px; text-align: right;"><span' +
                '                    style="font-size: 16px;"><span style="color: #0B779E;">N°de factura :' +
                '                    </span></span></td>' +
                '            <td style="height: 36px; width: 146.234375px; text-align: left;"><?php echo isset($date_exp) ? $date_exp : '' ?></td>' +
                '        </tr>' +
                '    </tbody>' +
                '</table>' +
                '</div><hr/>')

            _el.append(encabezado.html())

            _el.append(
                //'<div class="card-body" id="print_encabezado">'+
                //'    <div class="col-md-6">'+
                //'        <label class="control-label text-info" style="color: #0B779E;">Vendido a: </label>'+
                //'        <?php echo isset($cliente_cotizacion) ? $cliente_cotizacion : '' ?>'+
                //'    </div>'+
                //'</div>'+
                '<div class="d-flex justify-content-center">' +
                '<table class="demoTable" style="height: 47px; width: 100%;">'+
                '    <thead>'+
                '        <tr style="height: 18px; background-color: #488BCE;">'+
                '            <td style="width: 166.734375px; height: 18px; border: white 3px solid; text-align: center">Método de pago</td>'+
                '            <td style="width: 168.484375px; height: 18px; border: white 3px solid; text-align: center">N° de cheque</td>'+
                '            <td style="width: 182.796875px; height: 18px; border: white 3px solid; text-align: center">Trabajo</td>'+
                '        </tr>'+
                '    </thead>'+
                '    <tbody>'+
                '        <tr style="height: 29px; background-color: #e7f2fd;">'+
                '            <td style="width: 166.734375px; height: 29px; border: white 3px solid; text-align: center"><?php echo isset($metodo_pago) ? $metodo_pago : '' ?></td>'+
                '            <td style="width: 168.484375px; height: 29px; border: white 3px solid; text-align: center"><?php echo isset($num_cheque) ? $num_cheque : '' ?></td>'+
                '            <td style="width: 182.796875px; height: 29px; border: white 3px solid; text-align: center"><?php echo isset($trabajo) ? $trabajo : '' ?></td>'+
                '        </tr>'+
                '    </tbody>'+
                '</table>'+
                '</div><hr/>'
            )

            _el.append(p.html())

                

            var nw = window.open("", "", "width=1200,height=900,left=250,location=no,titlebar=yes")
            nw.document.write(_el.html())
            nw.document.close()
            setTimeout(() => {
                nw.print()
                setTimeout(() => {
                    nw.close()
                    end_loader()
                }, 200);
            }, 500);
        })
    })
</script>