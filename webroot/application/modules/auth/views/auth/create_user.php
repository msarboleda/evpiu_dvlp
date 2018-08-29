<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $message;?>
</div>
<div class="card">
  <div class="card-title">
    <p><?php echo lang('create_user_subheading');?></p>
  </div>
  <div class="card-body">
    <div class="basic-form">    
      <?php echo form_open("auth/create_user");?>
        <div class="form-group">
          <?php echo lang('create_user_fname_label', 'first_name');?> <br />
          <?php echo form_input($first_name, '', 'class="form-control"');?>
        </div>
        <div class="form-group">
          <?php echo lang('create_user_lname_label', 'last_name');?> <br />
          <?php echo form_input($last_name, '', 'class="form-control"');?>
        </div>
        <?php
          if ($identity_column !== 'email') {
            echo '<p>';
            echo lang('create_user_identity_label', 'identity');
            echo '<br />';
            echo form_error('identity');
            echo form_input($identity, '', 'class="form-control"');
            echo '</p>';
          }
        ?>
        <div class="form-group">
          <?php echo lang('create_user_company_label', 'company');?> <br/>
          <?php echo form_input($company, '', 'class="form-control"');?>
        </div>
        <div class="form-group">
          <?php echo lang('create_user_email_label', 'email');?> <br/>
          <?php echo form_input($email, '', 'class="form-control"');?>
        </div>
        <div class="form-group">
          <?php echo lang('create_user_phone_label', 'phone');?> <br />
          <?php echo form_input($phone, '', 'class="form-control"');?>
        </div>
        <div class="form-group">
          <?php echo lang('create_user_password_label', 'password');?> <br />
          <?php echo form_input($password, '', 'class="form-control"');?>
        </div>
        <div class="form-group">
          <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
          <?php echo form_input($password_confirm, '', 'class="form-control"');?>
        </div>
        <?php echo form_submit('submit', lang('create_user_submit_btn'), 'class="btn btn-primary"');?>
      <?php echo form_close();?>
    </div>
  </div>
</div>