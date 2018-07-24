<?php
  if (isset($validation_errors)) {
    echo "<div class='alert alert-danger' role='alert'>";
    echo $validation_errors;
    echo "</div>";
  }

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
    <div class="card-body">
      <div class="basic-form">    
      <?php echo form_open(uri_string(), 'id="new_mark-form" novalidate');?>
        <input type="hidden" class="form-check-input" name="Generico" value="0">
        <div class="form-group">
          <?php echo lang('NM_name_label', 'Nombre'); ?> <br />
          <input type="text" name="Nombre" id="Nombre" class="form-control" required="required" pattern="(^[A-Za-z])[\w\s\'\&\%\+\-\.\`\$\\\/\(\)]*$">
          <small class="text-muted"><?php echo lang('NM_name_label_help'); ?></small>
          <div class="valid-feedback">
            <i class="fa fa-check" aria-hidden="true"></i>
          </div>
          <div class="invalid-feedback">
            <i class="fa fa-times" aria-hidden="true"></i> <?php echo lang('NM_required_name'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NM_comments_label', 'Comentarios'); ?> <br />
          <textarea name="Comentarios" id="Comentarios" class="form-control" placeholder="..."></textarea>
          <div class="valid-feedback">
            <i class="fa fa-check" aria-hidden="true"></i>
          </div>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="Generico" name="Generico" value="1">
          <label class="form-check-label" for="Generico"><?php echo lang('NM_is_generic_label'); ?></label><br>
          <small class="text-muted"><?php echo lang('NM_is_generic_label_help'); ?></small>
          <div class="valid-feedback">
            <i class="fa fa-check" aria-hidden="true"></i>
          </div>
        </div>
      <?php echo form_submit('submit', lang('NM_submit_button'), 'id="submit" class="btn btn-primary"');?>
      <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>