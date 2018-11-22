<div <?php echo (!isset($valid_errors)) ? '' : 'class="alert alert-danger" role="alert"'; ?>>
  <?php echo $valid_errors;?>
</div>

<?php
  if (isset($app_msgs)) {
    if (!empty($app_msgs)) {
      foreach($app_msgs as $type=>$msgs) {
        foreach($msgs as $msg) {
          echo "<div class='alert alert-$type' role='alert'>";
          echo $msg;
          echo "</div>";
        }
      }
    }
  }
?>

<div class="col-12">
  <div class="card">
    <div class="card-body">
      <div class="basic-form">
      <?php echo form_open(uri_string(), 'id="new_request-form"');?>
        <div class="form-group">
          <?php echo lang('planned_date_label'); ?> <span class="color-danger">*</span><br />
          <input type="text" class="form-control" id="planned_date" name="planned_date">
          <small class="text-muted"><?php echo lang('planned_date_help'); ?></small>
        </div>
        <div class="form-group">
          <?php echo lang('planned_time_label'); ?> <span class="color-danger">*</span><br />
          <input type="time" class="form-control" id="planned_time" name="planned_time">
          <small class="text-muted"><?php echo lang('planned_time_help'); ?></small>
        </div>
        <div class="form-group">
          <?php echo lang('planned_asset_label'); ?> <span class="color-danger">*</span><br />
          <select class="form-control" id="planned_asset" name="planned_asset">
            <option></option>
          <?php
            foreach ($assets as $asset): ?>
              <option value="<?php echo $asset->CodigoActivo; ?>"><?php echo $asset->NombreActivo; ?></option>
          <?php
            endforeach; ?>
          </select>
          <small class="text-muted"><?php echo lang('planned_asset_help'); ?></small>
        </div>
        <?php echo form_submit('submit', lang('plan_submit_button'), 'id="submit" class="btn btn-primary"'); ?>
      <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>
