<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $message;?>
</div>
<div class="card">
  <div class="card-body">
    <div class="basic-form">
      <?php echo form_open("auth/change_password");?>
          <div class="form-group">
            <?php echo lang('change_password_old_password_label', 'old_password');?> <br />
            <?php echo form_input($old_password, '', 'class="form-control"');?>
          </div>
          <div class="form-group">
            <label for="new_password"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label> <br/>
            <?php echo form_input($new_password, '', 'class="form-control"');?>
          </div>
          <div class="form-group">
            <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?> <br />
            <?php echo form_input($new_password_confirm, '', 'class="form-control"');?>
          </div>
          <div class="form-group">
            <?php echo form_input($user_id);?>
          </div>
          <?php echo form_submit('submit', lang('change_password_submit_btn'), 'class="btn btn-primary"');?>
      <?php echo form_close();?>
    </div>
  </div>
</div>
