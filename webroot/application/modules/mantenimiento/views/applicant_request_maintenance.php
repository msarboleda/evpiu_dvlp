<div id="infoMessage" <?php echo (!isset($valid_errors)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
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
          <?php echo lang('new_rm_damage_date_label', 'damage_date'); ?> <span class="color-danger">*</span><br />
          <input type="text" class="form-control" id="damage_date" name="damage_date">
          <small class="text-muted"><?php echo lang('new_rm_damage_date_help'); ?></small>
        </div>
        <div class="form-group">
          <?php echo lang('new_rm_damage_time_label', 'damage_time'); ?> <span class="color-danger">*</span><br />
          <input type="time" class="form-control" id="damage_time" name="damage_time">
          <small class="text-muted"><?php echo lang('new_rm_damage_time_help'); ?></small>
        </div>
        <div class="form-group">
          <?php echo lang('new_rm_damaged_asset_label', 'damaged_asset'); ?> <span class="color-danger">*</span><br />
          <select class="form-control" id="damaged_asset" name="damaged_asset">
            <option></option>
          <?php
            if (isset($assets)) {
              if (!empty($assets)) {
                foreach ($assets as $asset): ?>
                  <option value="<?php echo $asset->CodigoActivo; ?>"><?php echo $asset->NombreActivo; ?></option>
          <?php
                endforeach;
              }
            } ?>
          </select>
          <small class="text-muted"><?php echo lang('new_rm_damaged_asset_help'); ?></small>
        </div>
        <div class="form-group">
          <?php echo lang('new_rm_damage_description_label', 'damage_description'); ?> <span class="color-danger">*</span><br />
          <textarea class="form-control" name="damage_description" rows="4"></textarea>
          <small class="text-muted"><?php echo lang('new_rm_damage_description_help'); ?></small>
        </div>
        <?php
          if (isset($assets_not_loaded)) {
            if ($assets_not_loaded === TRUE) {
              echo form_submit('submit', lang('new_rm_submit_button'), 'id="submit" class="btn btn-primary" disabled');
            }
          }

          if (isset($assets)) {
            echo form_submit('submit', lang('new_rm_submit_button'), 'id="submit" class="btn btn-primary"');
          }
        ?>
      <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>
