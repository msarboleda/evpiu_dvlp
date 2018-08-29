<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $message;?>
</div>
<div class="card">
  <div class="card-title">
    <p><?php echo lang('enable_module_subheading');?></p>
  </div>
  <div class="card-body">
    <div class="basic-form">    
      <?php echo form_open(uri_string());?>
        <div class="form-group">
          <?php echo lang('enable_module_module_label', 'Modulo'); ?> <br />
          <?php echo form_dropdown($Modulo, $modules_select, '', 'class="form-control"'); ?>
        </div>
        <div class="form-group">
          <?php echo lang('enable_module_group_label', 'Grupo'); ?> <br />
          <?php echo form_dropdown($Grupo, $groups_select, '', 'class="form-control"'); ?>
        </div>
        <?php echo form_submit('submit', lang('enable_module_submit_btn'), 'class="btn btn-primary"');?>
      <?php echo form_close();?>
    </div>
  </div>
</div>