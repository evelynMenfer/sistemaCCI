<?php
$qry = $conn->query("SELECT p.*,s.name as supplier, c.logo as logo_empresa, c.name as name_empresa, c.email, c.cperson FROM purchase_order_list p inner join supplier_list s on p.supplier_id = s.id left join company_list c on p.id_company = c.id where p.id = '{$_GET['id']}'");

if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
}
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Información de la Cotización :
            <?php echo $po_code ?> -
            <?php echo $name_empresa ?>
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
    <div class="card-body" id="print_encabezado">
        <div class="col-md-6">
            <!--class="control-label text-info"-->
            <label class="control-label" style="color: #9552BD;">Tiempo de entrega:</label>
            <!--<?php echo isset($cperson) ? $cperson : '' ?>-->
        </div>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <br>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="10%">
                    <col width="15%">
                    <col width="30%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <!--class="text-light bg-navy"-->
                    <tr class="text-light" style="background-color: #9552BD;">
                        <th class="text-center py-1 px-2">ART.</th>
                        <th class="text-center py-1 px-2">MARCA</th>
                        <th class="text-center py-1 px-2">MODELO</th>
                        <th class="text-center py-1 px-2">DESCRIPCION</th>
                        <th class="text-center py-1 px-2">UNIDAD</th>
                        <th class="text-center py-1 px-2">CANTIDAD</th>
                        <th class="text-center py-1 px-2">P.U.</th>
                        <th class="text-center py-1 px-2">IMPORTE</th>
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

                            </td>
                            <td class="py-1 px-2 text-center">

                            </td>
                            <td class="py-1 px-2 text-center">

                            </td>
                            <td class="py-1 px-2">
                                <?php echo $row['description'] ?>
                            </td>
                            <td class="py-1 px-2">
                                <?php echo ($row['unit']) ?>
                            </td>
                            <td class="py-1 px-2 text-center">
                                <?php echo number_format($row['quantity']) ?>
                            </td>
                            <td class="py-1 px-2 text-right">
                                $
                                <?php echo number_format($row['price'], 2) ?>
                            </td>
                            <td class="py-1 px-2 text-right">
                                $
                                <?php echo number_format($row['total'], 2) ?>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="7">SUBTOTAL</th>
                        <th class="text-right py-1 px-2 sub-total">
                            $
                            <?php echo number_format($total, 2) ?>
                        </th>
                    </tr>

                    <tr>
                        <th class="text-right py-1 px-2" colspan="7">I.V.A.16%
                            <!--<?php echo isset($tax_perc) ? $tax_perc : 0 ?>%-->
                        </th>
                        <th class="text-right py-1 px-2 tax">
                            $
                            <?php echo isset($tax) ? number_format($tax, 2) : 0 ?>
                        </th>
                    </tr>
                    <tr>
                        <th class="text-right py-1 px-2" colspan="7">Total</th>
                        <th class="text-right py-1 px-2 grand-total">
                            $
                            <?php echo isset($amount) ? number_format($amount, 2) : 0 ?>
                        </th>
                    </tr>
                    <!--<tr>
                        <th class="text-left py-1 px-1" colspan="6">
                            <label for="remarks" style="color: #9552BD;">Forma de Pago:</label>
                            <?php echo isset($metodo_pago) ? $metodo_pago : ''; ?>
                        </th>
                    </tr> 
                    <tr>
                        <th class="text-left py-1 px-1" colspan="6">
                            <label for="remarks" style="color: #9552BD;">Notas:</label>
                            <?php echo isset($remarks) ? $remarks : '' ?>
                        </th>
                    </tr>-->
                </tfoot>
            </table>

        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="remarks" style="color: #9552BD;">.</label>
                    <p>
                        <php echo isset($remarks) ? $remarks : '' ?>
                    </p>
                </div>
            </div>
            <!--   <php if ($status > 0): ?>
                    <div class="col-md-6">
                        <span class="text-info">
                            <php echo ($status == 2) ? "Recibido" : "Recibido Parcialmente" ?>
                        </span>
                    </div>
                <php endif; ?>
            </div>-->
        </div>
        <div class="col-md-12" style="color: #9552BD;">
                    <br><br>
                    <p  style="text-align: center;" >LA DISPONIBILIDAD DE LOS PRODUCTOS ES SALVO PREVIA VENTA, 
                    <br>PRECIOS SUJETOS A CAMBIO SIN PREVIO AVISO POR PARTE DEL FABRICANTE, 
                    <br>VIGENCIA DE COTIZACIÓN 15
                    <br>ATENCION A CLIENTES
                </p>
            </div>
        </div>
        <div class="card-footer py-1 text-center">
            <button class="btn btn-flat btn-info" type="button" id="print">Imprimir</button>
            <a class="btn btn-flat btn-primary"
                href="<?php echo base_url . '/admin?page=purchase_order_kalahari/manage_po&id=' . (isset($id) ? $id : '') ?>">Editar</a>
            <a class="btn btn-flat btn-danger"
                href="<?php echo base_url . '/admin?page=purchase_order_kalahari' ?>">Volver</a>
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
                    '<div class="col-1 text-center" >' +
                    '<img src="<?php echo validate_image($logo_empresa) ?>" width="1100px" height="150px"/>' +
                    '</div><hr/>')

                _el.append(
                    //'<div class="card-body" id="print_encabezado">'+
                    //'    <div class="col-md-6">'+
                    //'        <label class="control-label text-info" style="color: #0B779E;">Vendido a: </label>'+
                    //'        <?php echo isset($cliente_cotizacion) ? $cliente_cotizacion : '' ?>'+
                    //'    </div>'+
                    //'</div>'+
                    '<div class="d-flex justify-content-center">' +
                    '<table class="demoTable" style="height: 54px; width: 672px;">' +
                    '    <thead>' +
                    '        <tr style="height: 18px;">' +
                    '            <td style="height: 36px; width: 400px;"></td>' +
                    '            <td style="width: 300px; height: 15px; text-align: right;"><span' +
                    '                    style="font-size: 12px; color: #9552BD;">OAXACA DE JUÁREZ, OAXACA</span></td>' +
                    '        </tr>' +
                    '    </thead>' +
                    '</table>' +
                    '<br><br>' +
                    '<table>' +
                    '    <thead>' +
                    '        <tr style="height: 18px;">' +
                    '            <td style="width: 166.734375px; height: 18px; border: white 3px solid; text-align: center"></td>' +
                    '            <td style="width: 168.484375px; height: 18px; border: white 3px solid; text-align: center"></td>' +
                    '            <td style="width: 182.796875px; background-color: #9552BD; color: white; height: 18px; border: white 3px solid; text-align: center">FECHA</td>' +
                    '            <td style="width: 182.796875px; background-color: #9552BD; color: white; height: 18px; border: white 3px solid; text-align: center">FOLIO</td>' +
                    '        </tr>' +
                    '    </thead>' +
                    '    <tbody>' +
                    '        <tr style="height: 29px;">' +
                    '            <td style="width: 166.734375px; height: 29px; border: white 3px solid; text-align: center"></td>' +
                    '            <td style="width: 168.484375px; height: 29px; border: white 3px solid; text-align: center"></td>' +
                    '            <td style="width: 182.796875px; height: 29px; border: white 3px solid; text-align: center"><?php echo isset($date_exp) ? $date_exp : '' ?></td>' +
                    '            <td style="width: 182.796875px; height: 29px; border: white 3px solid; text-align: center"></td>' +
                    '        </tr>' +
                    '    </tbody>' +
                    '</table>' +
                    '</div><hr/>'
                )

                _el.append(encabezado.html())
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