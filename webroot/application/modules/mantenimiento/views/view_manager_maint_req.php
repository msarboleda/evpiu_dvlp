<?php
  if (isset($app_errors)) {
    if (!empty($app_errors)) {
      foreach($app_errors as $type=>$msgs) {
        foreach($msgs as $msg) {
          echo "<div class='alert alert-$type' role='alert'>";
          echo $msg;
          echo "</div>";
        }
      }
    }
  }
?>

<?php
if (isset($maint_request)) { ?>
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('view_mr_card_first_title'); ?></h2>
      <div class="table-responsive m-t-20">
        <table class="table" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th scope="row">Código</th>
              <th>Activo</th>
              <th>Solicitante</th>
              <th>Fecha de Incidente</th>
              <th>Fecha de Solicitud</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $maint_request->CodSolicitud; ?></td>
              <td><?php echo $maint_request->NomActivo; ?></td>
              <td><?php echo $maint_request->NomSolicitante; ?></td>
              <td><?php echo $maint_request->BeautyDamageDate; ?></td>
              <td><?php echo $maint_request->BeautyRequestDate; ?></td>
              <td style="text-align: left;"><?php echo $maint_request->NomEstado; ?></td>
            </tr>
          </tbody>
        </table>

        <div class="form-group m-t-40">
          <label for="">Descripción de la solicitud</label>
          <textarea class="form-control" rows="4" readonly><?php echo $maint_request->Descripcion; ?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('view_mr_card_second_title'); ?></h2>
      <hr>
      <?php echo form_open(uri_string(), 'id="add_comments-form"'); ?>
      <div class="form-group">
        <textarea class="form-control" name="comments" rows="4" placeholder="<?php echo lang('view_mr_comments_placeholder'); ?>"></textarea>
      </div>
      <?php echo form_submit('submit', lang('view_mr_update_submit_button'), 'class="btn btn-primary"');?>
      <?php echo form_close();?>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('view_mr_card_fourth_title'); ?></h2>
      <hr>
      <?php
        if (isset($maint_request_history)) {
          if (is_array($maint_request_history)) { ?>
            <ul class="timeline">
      <?php
            foreach ($maint_request_history as $event): ?>
              <li>
                <div>
                  <h5><b><?php echo $event->NomConcepto; ?></b></h5>
                  <h6><i class="fa fa-user"></i> <?php echo $event->NomUsuario; ?></h6>
                  <h6><i class="fa fa-clock-o"></i> <?php echo $event->BeautyEventDate; ?></h6>
                  <?php echo $event->Descripcion; ?>
                </div>
              </li>
      <?php
            endforeach; ?>
            </ul>
      <?php
          }
        }

        if (isset($maint_request_history_error_message)) { ?>
          <div class="alert alert-danger" role="alert">
      <?php
            echo $maint_request_history_error_message; ?>
          </div>
      <?php
        } ?>
    </div>
  </div>
</div>
<?php
} ?>

<?php
if (isset($maint_request_not_exist_error)) { ?>
  <div class="alert alert-danger" role="alert">
<?php
    echo $maint_request_not_exist_error; ?>
  </div>
<?php
} ?>
