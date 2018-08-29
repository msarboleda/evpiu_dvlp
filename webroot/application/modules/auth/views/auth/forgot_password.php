<div id="main-wrapper">
  <div class="unix-login">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-4">
          <div class="login-content card">
            <div class="login-form">
              <h4><?php echo lang('forgot_password_heading');?></h4>
              <div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
                <?php echo $message;?>
              </div>
              <h5><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></h5>
              <br>
              <?php echo form_open("auth/forgot_password");?>
                <div class="form-group">
                  <label for="identity"><?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?></label> <br />
  								<?php echo form_input($identity, '', 'class="form-control"');?>
                </div>
                <?php echo form_submit('submit', lang('forgot_password_submit_btn'), 'class="btn btn-primary btn-flat m-b-30 m-t-30"');?>
              <?php echo form_close();?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>