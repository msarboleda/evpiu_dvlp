<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $message;?>
</div>
<div class="card">
  <div class="card-title">
    <p><?php echo lang('edit_group_subheading');?></p>
  </div>
  <div class="card-body">
    <div class="basic-form">    
      <?php echo form_open(current_url());?>
        <div class="form-group">
          <?php echo lang('edit_group_name_label', 'group_name');?> <br />
          <?php echo form_input($group_name, '', 'class="form-control"');?>
        </div>
        <div class="form-group">
					<?php echo lang('edit_group_desc_label', 'description');?> <br />
          <?php echo form_input($group_description, '', 'class="form-control"');?>
        </div>
        <?php echo form_submit('submit', lang('edit_group_submit_btn'), 'class="btn btn-primary"');?>
      <?php echo form_close();?>
    </div>
  </div>
</div>