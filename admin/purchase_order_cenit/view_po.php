<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$qry = $conn->query("
  SELECT p.*, s.name AS supplier, c.logo AS logo_empresa, 
         c.name AS name_empresa, c.email, c.contact, c.address 
  FROM purchase_order_list p 
  INNER JOIN supplier_list s ON p.supplier_id = s.id 
  LEFT JOIN company_list c ON p.id_company = c.id 
  WHERE p.id = {$id}
");
if ($qry && $qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) $$k = $v;
}
?>
<style>
  .card-title { font-weight:600; }
  th, td { vertical-align: middle !important; }
  thead th { background-color:#001f3f; color:white; }
  tfoot th { background:#f6f6f6; }
</style>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h4 class="card-title">
      Información de la Cotización: <?php echo $po_code ?? '' ?> - <?php echo $name_empresa ?? '' ?>
    </h4>
    <div class="mt-3 row">
      <div class="col-md-3"><label class="text-info">OC</label><div><?php echo $oc ?? '' ?></div></div>
      <div class="col-md-3"><label class="text-info">Proveedor</label><div><?php echo $supplier ?? '' ?></div></div>
      <div class="col-md-3"><label class="text-info">No. Factura</label><div><?php echo $num_factura ?? '' ?></div></div>
      <div class="col-md-3"><label class="text-info">Carga al Portal</label><div><?php echo $date_carga_portal ?? '' ?></div></div>
    </div>
    <div class="row mt-2">
      <div class="col-md-3"><label class="text-info">Fecha de Pago</label><div><?php echo $date_pago ?? '' ?></div></div>
      <div class="col-md-3"><label class="text-info">Folio Fiscal</label><div><?php echo $folio_fiscal ?? '' ?></div></div>
      <div class="col-md-3"><label class="text-info">Comprobante de Pago</label><div><?php echo $folio_comprobante_pago ?? '' ?></div></div>
      <div class="col-md-3"><label class="text-info">Pago en Efectivo</label><div><?php echo $pago_efectivo ?? '' ?></div></div>
    </div>
  </div>

  <div class="card-body" id="print_out">
    <div class="row mb-3">
      <div class="col-md-6">
        <img src="<?php echo validate_image($logo_empresa ?? '') ?>" style="max-height:80px;" alt="Logo">
        <p><strong><?php echo $name_empresa ?? '' ?></strong><br>
        <?php echo $address ?? '' ?><br>
        Tel: <?php echo $contact ?? '' ?><br>
        Email: <?php echo $email ?? '' ?></p>
      </div>
      <div class="col-md-6 text-end">
        <p><strong>Vendido a:</strong> <?php echo $cliente_cotizacion ?? '' ?><br>
        <strong>Fecha:</strong> <?php echo !empty($date_exp) ? date("d/m/Y", strtotime($date_exp)) : '' ?><br>
        <strong>OC:</strong> <?php echo $oc ?? '' ?></p>
      </div>
    </div>

    <table class="table table-striped table-bordered">
      <thead class="text-center">
        <tr>
          <th>Cant.</th>
          <th>Unidad</th>
          <th>Descripción</th>
          <th>Precio Unitario</th>
          <th>Desc %</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $subtotal = 0;
        $qry_items = $conn->query("
          SELECT p.*, i.description 
          FROM po_items p 
          INNER JOIN item_list i ON p.item_id = i.id 
          WHERE p.po_id = {$id}
        ");
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
        <tr><th colspan="5" class="text-end">Sub Total</th><th class="text-end">$<?php echo number_format($subtotal,2) ?></th></tr>
        <tr><th colspan="5" class="text-end">Impuesto <?php echo $tax_perc ?? 0 ?>%</th><th class="text-end">$<?php echo number_format($tax ?? ($subtotal*($tax_perc??0)/100),2) ?></th></tr>
        <tr><th colspan="5" class="text-end">Total</th><th class="text-end">$<?php echo number_format($amount ?? ($subtotal + ($subtotal*($tax_perc??0)/100)),2) ?></th></tr>
      </tfoot>
    </table>

    <?php if(!empty($remarks)): ?>
      <p><strong>Observaciones:</strong> <?php echo nl2br($remarks) ?></p>
    <?php endif; ?>
  </div>

  <div class="card-footer text-center">
    <a class="btn btn-primary" href="<?php echo base_url . '/admin?page=purchase_order_cenit/manage_po&id=' . $id ?>">Editar</a>
    <a class="btn btn-danger" href="<?php echo base_url . '/admin?page=purchase_order_cenit' ?>">Volver</a>
    <button class="btn btn-success" type="button" onclick="printDiv()">Imprimir / PDF</button>
  </div>
</div>

<script>
function printDiv(){
  const printContents = document.getElementById('print_out').innerHTML;
  const win = window.open('', '', 'width=900,height=650');
  win.document.write(`
    <html>
    <head>
      <title>Cotización</title>
      <link rel="stylesheet" href="<?php echo base_url ?>/plugins/bootstrap/css/bootstrap.min.css">
      <style>
        body{font-family:Arial,sans-serif;padding:20px;}
        th,td{border:1px solid #ccc;padding:6px;}
        th{background:#001f3f;color:white;}
        table{width:100%;border-collapse:collapse;}
        h4{text-align:center;margin-bottom:20px;}
      </style>
    </head>
    <body onload="window.print()">
      ${printContents}
    </body>
    </html>
  `);
  win.document.close();
}
</script>
