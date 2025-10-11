<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$qry = $conn->query("
  SELECT p.*, s.name AS supplier, c.logo AS logo_empresa, 
         c.name AS name_empresa, c.email, c.contact, c.address 
  FROM purchase_order_list p 
  LEFT JOIN supplier_list s ON p.supplier_id = s.id 
  LEFT JOIN company_list c ON p.id_company = c.id 
  WHERE p.id = {$id}
");
if ($qry && $qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $v) $$k = $v;
} else {
    echo "<div class='alert alert-warning'>No se encontró la cotización.</div>";
    exit;
}
?>
<style>
  .card-title { font-weight:600; }
  th, td { vertical-align: middle !important; }
  thead th { background-color:#001f3f; color:white; text-align:center; }
  tfoot th { background:#f6f6f6; }
  .text-end { text-align: right !important; }
  .text-center { text-align: center !important; }
  .text-start { text-align: left !important; }
  .border-top-2 { border-top: 2px solid #001f3f !important; }
</style>

<div class="card card-outline card-primary">
  <div class="card-header">
    <h4 class="card-title">
      Información de la Cotización: <?php echo $po_code ?? '' ?> 
      <?php if (!empty($name_empresa)): ?> - <?php echo $name_empresa ?><?php endif; ?>
    </h4>
    <br>
    <div class="mt-3 row">
      <div class="col-md-3"><label class="text-info">Proveedor</label><div><?php echo $supplier ?? '—'; ?></div></div>
      <div class="col-md-3"><label class="text-info">OC</label><div><?php echo $oc ?? '—'; ?></div></div>
      <div class="col-md-3"><label class="text-info">No. Factura</label><div><?php echo $num_factura ?? '—'; ?></div></div>
      <div class="col-md-3"><label class="text-info">Carga al Portal</label><div><?php echo $date_carga_portal ?? '—'; ?></div></div>
    </div>
    <div class="row mt-2">
      <div class="col-md-3"><label class="text-info">Fecha de Pago</label><div><?php echo $date_pago ?? '—'; ?></div></div>
      <div class="col-md-3"><label class="text-info">Folio Fiscal</label><div><?php echo $folio_fiscal ?? '—'; ?></div></div>
      <div class="col-md-3"><label class="text-info">Comprobante de Pago</label><div><?php echo $folio_comprobante_pago ?? '—'; ?></div></div>
      <div class="col-md-3"><label class="text-info">Pago en Efectivo</label><div><?php echo $pago_efectivo ?? '—'; ?></div></div>
    </div>
  </div>

  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-6 text-end">
        <p>
          <strong>Vendido a:</strong> <?php echo $cliente_cotizacion ?? '' ?> &nbsp; | &nbsp;
          <strong>Fecha:</strong> <?php echo !empty($date_exp) ? date("d/m/Y", strtotime($date_exp)) : '—'; ?> &nbsp; | &nbsp;
          <strong>OC:</strong> <?php echo $oc ?? '—'; ?>
        </p>
      </div>
    </div>

    <!-- ================= TABLA DE PRODUCTOS ================= -->
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th style="width:8%">Cant.</th>
          <th style="width:10%">Unidad</th>
          <th style="width:40%">Descripción</th>
          <th style="width:12%">Precio Unitario</th>
          <th style="width:10%">Desc %</th>
          <th style="width:15%">Total</th>
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
          <td class="text-end"><?php echo number_format($row['quantity'],2) ?></td>
          <td class="text-center"><?php echo $row['unit'] ?></td>
          <td class="text-start"><?php echo $row['description'] ?></td>
          <td class="text-end">$<?php echo number_format($row['price'],2) ?></td>
          <td class="text-end"><?php echo number_format($row['discount'],2) ?>%</td>
          <td class="text-end">$<?php echo number_format($line_total,2) ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>

      <!-- ================= TOTALES ================= -->
      <tfoot>
        <tr>
          <th colspan="5" class="text-end">Sub Total</th>
          <th class="text-end">$<?php echo number_format($subtotal,2) ?></th>
        </tr>
        <tr>
          <th colspan="5" class="text-end">
            Descuento Total (<?php echo $discount_perc ?? 0 ?>%)
          </th>
          <th class="text-end">$<?php echo number_format($discount ?? 0,2) ?></th>
        </tr>
        <tr>
          <th colspan="5" class="text-end">
            Impuesto (<?php echo $tax_perc ?? 0 ?>%)
          </th>
          <th class="text-end">$<?php echo number_format($tax ?? 0,2) ?></th>
        </tr>
        <tr class="border-top-2">
          <th colspan="5" class="text-end">Total</th>
          <th class="text-end">$<?php echo number_format($amount ?? 0,2) ?></th>
        </tr>
      </tfoot>
    </table>

    <?php if(!empty($remarks)): ?>
      <p><strong>Observaciones:</strong> <?php echo nl2br($remarks) ?></p>
    <?php endif; ?>
  </div>

  <div class="card-footer text-center">
    <a class="btn btn-primary" href="<?php echo base_url . '/admin?page=purchase_order_cenit/manage_po&id=' . $id ?>">Editar</a>
    <a class="btn btn-danger" href="<?php echo base_url . '/admin?page=purchase_order_cenit' ?>">Volver</a>

    <!-- ✅ Se mantiene solo el botón Generar PDF -->
    <a class="btn btn-success" href="<?php echo base_url; ?>/admin/pdf/generate_po.php?id=<?php echo $id; ?>" target="_blank">
      Generar PDF
    </a>
  </div>
</div>
