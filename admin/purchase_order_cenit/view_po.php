<?php
// Consulta principal
$qry = $conn->query("SELECT p.*, s.name as supplier, c.logo as logo_empresa, 
                            c.name as name_empresa, c.email, c.contact, c.address 
                     FROM purchase_order_list p 
                     INNER JOIN supplier_list s ON p.supplier_id = s.id 
                     LEFT JOIN company_list c ON p.id_company = c.id 
                     WHERE p.id = '{$_GET['id']}'");
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) $$k = $v;
}
?>
<div class="card card-outline card-primary">
  <div class="card-header">
    <h4 class="card-title">Información de la Cotización : <?php echo $po_code ?> - <?php echo $name_empresa ?></h4>
    <br><br>
    <div class="row">
      <div class="col-md-3"><label class="control-label text-info">OC</label><div><?php echo $oc ?? '' ?></div></div>
      <div class="col-md-3"><label class="control-label text-info">Proveedor</label><div><?php echo $supplier ?? '' ?></div></div>
      <div class="col-md-3"><label class="control-label text-info">No. Factura</label><div><?php echo $num_factura ?? '' ?></div></div>
      <div class="col-md-3"><label class="control-label text-info">Fecha de Carga al Portal</label><div><?php echo $date_carga_portal ?? '' ?></div></div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-3"><label class="control-label text-info">Fecha de Pago</label><div><?php echo $date_pago ?? '' ?></div></div>
      <div class="col-md-3"><label class="control-label text-info">Folio Fiscal</label><div><?php echo $folio_fiscal ?? '' ?></div></div>
      <div class="col-md-3"><label class="control-label text-info">Folio Comprobante de Pago</label><div><?php echo $folio_comprobante_pago ?? '' ?></div></div>
      <div class="col-md-3"><label class="control-label text-info">Pago en Efectivo</label><div><?php echo $pago_efectivo ?? '' ?></div></div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-3"><label class="control-label text-info">Cotización</label><div><?php echo $po_code ?? '' ?></div></div>
      <div class="col-md-3"><label class="control-label text-info">Fecha de Expedición</label><div><?php echo $date_exp ?? '' ?></div></div>
    </div>
    <br>
  </div>

  <div class="card-body" id="print_out">
    <div class="row mb-3">
      <div class="col-md-6">
        <img src="<?php echo validate_image($logo_empresa) ?>" style="max-height:80px;" alt="Logo">
        <p><strong><?php echo $name_empresa ?></strong><br>
        <?php echo $address ?><br>
        Tel: <?php echo $contact ?><br>
        Email: <?php echo $email ?></p>
      </div>
      <div class="col-md-6 text-end">
        <p><strong>Vendido a:</strong> <?php echo $cliente_cotizacion ?? '' ?><br>
        <strong>Fecha:</strong> <?php echo $date_exp ?? '' ?><br>
        <strong>OC:</strong> <?php echo $oc ?? '' ?></p>
      </div>
    </div>

    <table class="table table-striped table-bordered">
      <thead class="bg-navy text-light">
        <tr>
          <th class="text-center">Cant.</th>
          <th class="text-center">Unidad</th>
          <th class="text-center">Descripción</th>
          <th class="text-center">Precio por Unidad</th>
          <th class="text-center">Desc %</th>
          <th class="text-center">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $subtotal = 0;
        $qry_items = $conn->query("SELECT p.*, i.description 
                                   FROM po_items p 
                                   INNER JOIN item_list i ON p.item_id = i.id 
                                   WHERE p.po_id = '{$id}'");
        while ($row = $qry_items->fetch_assoc()):
            $line_total = ($row['price'] - ($row['price']*$row['discount']/100)) * $row['quantity'];
            $subtotal += $line_total;
        ?>
        <tr>
          <td class="text-center"><?php echo number_format($row['quantity'],2) ?></td>
          <td class="text-center"><?php echo $row['unit'] ?></td>
          <td><?php echo $row['description'] ?></td>
          <td class="text-end">$<?php echo number_format($row['price'],2) ?></td>
          <td class="text-end"><?php echo number_format($row['discount'],2) ?>%</td>
          <td class="text-end">$<?php echo number_format($line_total,2) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="5" class="text-end">Sub Total</th>
          <th class="text-end">$<?php echo number_format($subtotal,2) ?></th>
        </tr>
        <tr>
          <th colspan="5" class="text-end">Impuesto <?php echo $tax_perc ?? 0 ?>%</th>
          <th class="text-end">$<?php echo isset($tax)?number_format($tax,2):number_format($subtotal*($tax_perc??0)/100,2) ?></th>
        </tr>
        <tr>
          <th colspan="5" class="text-end">Total</th>
          <th class="text-end">
            $<?php
              $amt = isset($amount) ? $amount : ($subtotal + ($subtotal*($tax_perc??0)/100));
              echo number_format($amt,2);
            ?>
          </th>
        </tr>
      </tfoot>
    </table>

    <?php if(!empty($remarks)): ?>
      <p><strong>Observaciones:</strong> <?php echo $remarks ?></p>
    <?php endif; ?>
  </div>

  <div class="card-footer text-center">
    <a class="btn btn-primary" href="<?php echo base_url . '/admin?page=purchase_order_cenit/manage_po&id=' . $id ?>">Editar</a>
    <a class="btn btn-danger" href="<?php echo base_url . '/admin?page=purchase_order_cenit' ?>">Volver</a>
  </div>
</div>
