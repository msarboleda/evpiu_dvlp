<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $message;?>
</div>
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Tabla de Requerimientos</h4>
      <h6 class="card-subtitle"><?php echo lang('index_subheading'); ?></h6>
      <div class="table-responsive m-t-40">
        <div class="form-group">
          <?php echo lang('index_status_filter_label', 'status_filter');?> <br />
          <?php echo form_dropdown($Estados, $status_reqs_select, '', 'class="form-control"'); ?>
        </div>
        <table id="requerimientos" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th scope="row">#</th>
              <th>Cliente</th>
              <th>Marca</th>
              <th>Producto</th>
              <th>Estado</th>
              <th>Creación</th>
              <th>Diseñador</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th scope="row">#</th>
              <th>Cliente</th>
              <th>Marca</th>
              <th>Producto</th>
              <th>Estado</th>
              <th>Creación</th>
              <th>Diseñador</th>
            </tr>
          </tfoot>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
                        