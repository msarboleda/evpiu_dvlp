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

<?php 
  if (!empty($invoices)) { ?>
<div class="card">
  <div class="card-title">
    <h2><?php echo 'Punto de venta: ' . $terminal_data->Nombre; ?> <span class="badge badge-primary"><?php echo $count_invoices; ?></span></h2>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <?php
        foreach ($invoices as $invoice):
          if ($invoice->anulada === TRUE) { ?>
            <h3>Factura #<?php echo $invoice->numero; ?> <span class="badge badge-danger"><?php echo 'Anulada'; ?></span></h3><hr>
      <?php 
            if (isset($invoice->void_invoice_status)) {
              if ($invoice->void_invoice_status === TRUE) { ?>
                <div class="alert alert-warning" role="alert">
                  <i class="fa fa-check"></i> <?php echo $invoice->void_invoice_msg; ?>
                </div>
      <?php
              } else { ?>
                <div class="alert alert-danger" role="alert">
                  <i class="fa fa-exclamation"></i> <?php echo $invoice->void_invoice_msg; ?>
                </div>
      <?php 
              } ?>
      <?php 
            } ?>
      <?php 
          } else { ?>
            <h3>Factura #<?php echo $invoice->numero; ?> <span class="badge badge-success"><?php echo 'No anulada'; ?></span></h3><hr>
      <?php 
          } ?>
      <?php 
          if (isset($invoice->nit_error)) { ?>
            <div class="alert alert-danger" role="alert">
              <i class="fa fa-exclamation"></i> <?php echo $invoice->nit_error; ?>
            </div>
      <?php 
            break; 
          } ?>
      <?php 
          if (isset($invoice->customer_not_created_on_dms_msg)) { ?>
            <div class="alert alert-danger" role="alert">
              <i class="fa fa-exclamation"></i> <?php echo $invoice->customer_not_created_on_dms_msg; ?>
            </div>
      <?php
            break; 
          } ?>
      <?php 
          if (isset($invoice->is_manual_invoice_status)) {
            if ($invoice->is_manual_invoice_status === TRUE) { ?>
              <div class="alert alert-danger" role="alert">
                <i class="fa fa-exclamation"></i> <?php echo $invoice->is_manual_invoice_msg; ?>
              </div>
      <?php 
              continue; ?>
      <?php 
            } ?>  
      <?php 
          } ?>
      <?php 
          if (isset($invoice->success_invoice_status)) {
            if ($invoice->success_invoice_status === TRUE) { ?>
              <div class="alert alert-success" role="alert">
                <i class="fa fa-check"></i> <?php echo $invoice->success_invoice_msg; ?>
              </div>
      <?php
            } else { ?>
              <div class="alert alert-danger" role="alert">
                <i class="fa fa-exclamation"></i> <?php echo $invoice->success_invoice_msg; ?>
              </div>
      <?php
            } ?>
      <?php
          } ?>

      <!-- Tabla de encabezado de factura -->
      <table class="table table-bordered">
        <thead>
          <tr class="table-active">
            <th>NIT</th>
            <th>Cliente</th>
            <th>Plazo</th>
            <th>Valor mercancía</th>
            <th>IVA</th>
            <?php 
              if ($invoice->rete_fuente > 0) { ?>
                <th>Retención Fuente</th>
            <?php 
              } ?>
            <th>Valor aplicado</th>
            <th>Descuento</th>
            <th>Total</th>
            <th>Vendedor</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            if ($invoice->anulada) { ?>
              <tr class="table-danger">
          <?php 
            } else { ?>
              <tr>
          <?php 
            } ?>
            <td><?php echo $invoice->nit; ?></td>
            <td><?php echo $invoice->cliente; ?></td>
            <td><?php echo $invoice->modelo; ?></td>
            <td style="text-align:right;"><?php echo '$' . number_format($invoice->valor_mercancia, 0, ',', '.'); ?></td>
            <td style="text-align:right;"><?php echo '$' . number_format($invoice->iva, 0, ',', '.'); ?></td>
            <?php if ($invoice->rete_fuente > 0) { ?>
            <td style="text-align:right;"><?php echo '$' . number_format($invoice->rete_fuente, 0, ',', '.'); ?></td>
            <?php } ?>
            <td style="text-align:right;"><?php echo '$' . number_format($invoice->valor_aplicado, 0, ',', '.'); ?></td>
            <td class="color-danger" style="text-align:right;"><?php echo '$' . number_format($invoice->descuento, 0, ',', '.'); ?></td>
            <td class="color-primary" style="text-align:right;"><?php echo '$' . number_format($invoice->valor_total, 0, ',', '.'); ?></td>
            <td>
            <?php 
              if (isset($invoice->nombre_vendedor_dms)) {
                echo $invoice->nombre_vendedor_dms;
              } 
            ?>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Fin de tabla de encabezado de factura -->

      <br>

      <?php 
        if ($invoice->anulada) { ?>
      <?php 
          continue; ?>
      <?php 
        } ?>
      <?php 
        if (isset($invoice->acc_imputation_status)) { ?>
      <?php 
          if ($invoice->acc_imputation_status === TRUE) { ?>
            <div class="alert alert-success" role="alert">
              <i class="fa fa-check"></i> <?php echo $invoice->acc_imputation_msg; ?>
            </div>
      <?php 
          } else { ?>
            <div class="alert alert-danger" role="alert">
              <i class="fa fa-exclamation"></i> <?php echo $invoice->acc_imputation_msg; ?>
            </div>
      <?php 
          } ?>
      <?php 
        } ?>

      <!-- Tabla de imputación contable -->
      <table class="table table-bordered">
        <thead>
          <tr class="table-active">
            <th colspan="7">Imputación contable</th>
          </tr>
          <tr>
            <th>Item</th>
            <th>Cuenta</th>
            <th>Valor</th>
            <th>Base</th>
          </tr>
        </thead>
        <tbody>
          <!-- Item 1 - Valor de la Mercancía -->
          <tr>
            <td>1 - Valor de la Mercancía</td>
            <td><?php echo $invoice->cuenta_venta; ?></td>
            <td class="color-danger"><?php echo '-$' . number_format($invoice->valor_mercancia, 0, ',', '.'); ?></td>
            <td>0</td>
          </tr>
          <!-- ./ Item 1 - Valor de la Mercancía -->
          <!-- Item 2 - Valor del IVA -->
          <tr>
            <td>2 - Valor del IVA</td>
            <td>24080507</td>
            <td class="color-danger"><?php echo '-$' . number_format($invoice->iva, 0, ',', '.'); ?></td>
            <td><?php echo '$' . number_format($invoice->valor_mercancia, 0, ',', '.'); ?></td>
          </tr>
          <!-- ./ Item 2 - Valor del IVA -->
          <!-- Item 3 - Valor Total -->
          <tr>
            <td>3 - Valor Total</td>
            <td><?php echo $invoice->cuenta_cobrar; ?></td>
            <td><?php echo '$' . number_format($invoice->valor_total, 0, ',', '.'); ?></td>
            <td>0</td>
          </tr>
          <!-- ./ Item 3 - Valor Total -->
          <!-- Item 4 - Retención en la Fuente -->
          <?php if ($invoice->rete_fuente > 0) { ?>
          <tr>
            <td>4 - Retención en la Fuente</td>
            <td>13551505</td>
            <td><?php echo '$' . number_format($invoice->rete_fuente, 0, ',', '.'); ?></td>
            <td><?php echo '$' . number_format($invoice->valor_mercancia, 0, ',', '.'); ?></td>
          </tr>
          <?php } ?>
          <!-- ./ Item 4 - Retención en la Fuente -->
        </tbody>
      </table>
      <!-- Fin de tabla de imputación contable -->
      <br>
      <?php 
        endforeach; ?>
    </div>
  </div>
</div>
<?php 
  } ?>