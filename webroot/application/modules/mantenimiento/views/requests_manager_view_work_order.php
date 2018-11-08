<div <?php echo (!isset($valid_errors)) ? '' : 'class="alert alert-danger" role="alert"'; ?>>
  <?php echo $valid_errors;?>
</div>

<?php
  if (isset($app_messages)) {
    if (!empty($app_messages)) {
      foreach($app_messages as $message_type => $messages) {
        foreach($messages as $message) {
          echo "<div class='alert alert-$message_type' role='alert'>";
          echo $message;
          echo "</div>";
        }
      }
    }
  }
?>

<?php
if ($show_work_order) { ?>
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('vwo_basic_info_title'); ?></h2>
      <div class="table-responsive m-t-20">
        <table class="table" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th scope="row">Número</th>
              <th>Solicitud</th>
              <th>Encargado</th>
              <th>Tipo de Mantenimiento</th>
              <th>Costo Total</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $work_order->CodOt; ?></td>
              <td><a href="<?php echo site_url('mantenimiento/solicitudes/view_maint_request/' . $work_order->CodSolicitud); ?>" class="color-primary">Visualizar solicitud (<?php echo $work_order->CodSolicitud; ?>)</a></td>
              <td><?php echo $work_order->NomEncargado; ?></td>
              <td><?php echo $work_order->NomTipoMantenimiento; ?></td>
              <td>$<?php echo $work_order->Costo; ?></td>
              <td><?php echo $work_order->NomEstado; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('vwo_detailed_info_title'); ?></h2>
      <div class="table-responsive m-t-20">
        <table class="table" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Creada en</th>
              <th>Creada por</th>
              <th>Actualizada en</th>
              <th>Actualizada por</th>
              <th>Iniciada en</th>
              <th>Terminada en</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $work_order->BeautyCreationFullDate; ?></td>
              <td><?php echo $work_order->NomCreo; ?></td>
              <td><?php echo (!empty($work_order->BeautyUpdateFullDate)) ? $work_order->BeautyUpdateFullDate : '<span class="badge badge-warning"><i class="fa fa-warning"></i> Sin actualización</span>'; ?></td>
              <td><?php echo (!empty($work_order->NomActualizo)) ? $work_order->NomActualizo : '<span class="badge badge-warning"><i class="fa fa-warning"></i> Sin actualización</span>'; ?></td>
              <td><?php echo (!empty($work_order->BeautyStartFullDate)) ? $work_order->BeautyStartFullDate : '<span class="badge badge-warning"><i class="fa fa-warning"></i> Sin inicialización</span>'; ?></td>
              <td><?php echo (!empty($work_order->BeautyEndFullDate)) ? $work_order->BeautyEndFullDate : '<span class="badge badge-warning"><i class="fa fa-warning"></i> Sin finalización</span>'; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <?php
        if (in_array($work_order->CodEstado, $update_desc_allowed_states)) {
        echo form_open(uri_string(), 'id="update_description-form"'); ?>
        <div class="form-group m-t-20">
          <label for="wo_description"><?php echo lang('vwo_wo_description_label'); ?></label>
          <textarea class="form-control" name="wo_description" rows="5"><?php echo $work_order->Descripcion; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i> Actualizar descripción</button>
      <?php
        echo form_close();
        } else { ?>
        <div class="form-group m-t-20">
          <label for="wo_description"><?php echo lang('vwo_wo_description_label'); ?></label>
          <textarea class="form-control" name="wo_description" rows="5" readonly><?php echo $work_order->Descripcion; ?></textarea>
        </div>
      <?php
        } ?>
    </div>
  </div>
  <?php
    if (in_array($work_order->CodEstado, $assign_tasks_allowed_states)) { ?>
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('vwo_tasks_assigment_title'); ?></h2>
      <hr>
      <?php echo form_open(uri_string(), 'id="add_task-form"'); ?>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="work_type"><?php echo lang('vwo_work_type_label'); ?></label>
            <?php echo form_dropdown('work_type', $work_types, '', 'class="form-control"'); ?>
          </div>
          <div class="form-group col-md-4">
            <label for="task"><?php echo lang('vwo_task_label'); ?></label>
            <input type="text" class="form-control" name="task" placeholder="Describe la tarea a realizar...">
          </div>
          <div class="form-group col-md-4">
            <label for="maint_tech"><?php echo lang('vwo_maintenance_technician_label'); ?></label>
            <?php echo form_dropdown('maint_tech', $maint_techs, '', 'class="form-control"'); ?>
          </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-hand-pointer-o"></i> Asignar tarea</button>
      <?php echo form_close(); ?>
    </div>
  </div>
  <?php
    } ?>
  <?php
  if ($show_work_order_details) { ?>
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('vwo_assigned_tasks_title'); ?></h2>
      <table class="table" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Tipo de trabajo</th>
              <th>Tarea a realizar</th>
              <th>Técnico encargado</th>
              <th>Costo</th>
              <th>Fecha creación</th>
              <th>Fecha finalización</th>
              <th>Estado</th>
          <?php
            if ($work_order->CodEstado === $rep_conclusion_allowed_state) { ?>
              <th>Acciones</th>
          <?php
            } ?>
            </tr>
          </thead>
          <tbody>
          <?php
          foreach ($work_order_details as $wo_detail): ?>
            <tr>
              <td><?php echo $wo_detail->NomTipoTrabajo; ?></td>
              <td><?php echo $wo_detail->DescripcionTarea; ?></td>
              <td><?php echo $wo_detail->NomTecnico; ?></td>
              <td style="text-align: right;">$<?php echo $wo_detail->CostoMat; ?></td>
              <td><?php echo $wo_detail->BeautyCreationDate; ?></td>
          <?php
            if ($wo_detail->Finalizada === 1) { ?>
              <td><?php echo $wo_detail->BeautyEndDate; ?></td>
              <td><span class="badge badge-success"><i class="fa fa-check"></i> Completada</span></td>
          <?php
            } else { ?>
              <td></td>
              <td><span class="badge badge-danger"><i class="fa fa-times"></i> Pendiente</span></td>
          <?php
                if ($work_order->CodEstado === $rep_conclusion_allowed_state) { ?>
              <td><button type="button" class="btn btn-sm btn-primary report-conclusion" data-item="<?php echo $wo_detail->idItem; ?>"><i class="fa fa-commenting"></i> Reportar conclusión</button></td>
          <?php
                } else { ?>
              <td></td>
          <?php
                }
            } ?>
            </tr>
          <?php
          endforeach; ?>
          </tbody>
        </table>
    </div>
  </div>
  <?php
  } ?>
  <?php
    if (in_array($work_order->CodEstado, $show_actions_allowed_states)) { ?>
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('vwo_actions_title'); ?></h2>
      <hr>
      <?php
      if ($work_order->CodEstado === $start_wo_allowed_state) { ?>
      <button type="submit" class="btn btn-success start-work-order"><i class="fa fa-play"></i> Iniciar orden de trabajo</button>
      <?php
      } ?>
      <?php
      if ($work_order->CodEstado === $complete_wo_allowed_state) { ?>
      <button type="submit" class="btn btn-danger finish-work-order"><i class="fa fa-stop"></i> Finalizar orden de trabajo</button>
      <?php
      } ?>
    </div>
  </div>
  <?php
    } ?>
  <?php
  if ($show_work_order_historical) { ?>
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('vwo_historical_title'); ?></h2>
      <hr>
      <div class="profiletimeline">
      <?php
        foreach ($work_order_historical as $event): ?>
          <div class="sl-item">
            <div class="sl-left">
              <img src="<?php echo $this->config->item('assets_path').'themes/elaadmin/images/users/user.png'; ?>" alt="user" class="img-circle">
            </div>
            <div class="sl-right">
              <div>
                <a href="javascript:void(0);" class="color-primary"><?php echo $event->NomUsuario; ?></a> <span class="sl-date"><?php echo $event->BeautyEventDate; ?></span>
                <p>ha realizado un nuevo evento: <b><?php echo $event->NomConcepto; ?></b></p>
                <blockquote class="m-t-10">
                  <?php echo $event->DescEvento; ?>
                </blockquote>
              </div>
            </div>
          </div>
          <hr>
      <?php
        endforeach; ?>
      </div>
    </div>
  </div>
  <?php
  } ?>
</div>
<?php
} ?>
