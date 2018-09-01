<?php
  if (isset($messages) && !empty($messages)) {
    foreach($messages as $type=>$msgs) {
      foreach($msgs as $msg) {
        echo "<div class='alert alert-$type' role='alert'>";
        echo $msg;
        echo "</div>";
      }
    } 
  }
?>

<div class="col-12">
  <div class="card">
    <div class="card-title">
      <h3>Información de las últimas facturas cargadas a DMS</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Punto de venta</th>
              <th>Factura</th>
              <th>Fecha</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($latest_invoices) && !empty($latest_invoices)) { ?>
            <?php foreach ($latest_invoices as $last_invoice): ?>
            <tr>
              <td><?php echo $last_invoice->punto_venta; ?></td>
              <td><?php echo $last_invoice->factura; ?></td>
              <td><?php echo $last_invoice->fecha; ?></td>
              <td><button type="button" class="btn btn-sm btn-primary" value="<?php echo $last_invoice->tipo; ?>">Seleccionar</button></td>
            </tr>
            <?php endforeach; ?>
            <?php } else { ?>
            <tr>
              <td colspan="4">No se obtuvieron resultados.</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>