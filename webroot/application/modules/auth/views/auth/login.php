<div id="main-wrapper">
  <div class="unix-login">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-4">
          <div class="login-content card">
            <div class="login-form">
              <h4><?php echo lang('login_heading');?></h4>
              <div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
                <?php echo $message;?>
              </div>
              <h5><?php echo lang('login_subheading');?></h5>
              <br>
              <?php echo form_open("auth/login");?>
                <div class="form-group">
                  <?php echo lang('login_identity_label', 'identity');?>
                  <?php echo form_input($identity, '', 'type="email" class="form-control"');?>
                </div>
                <div class="form-group">
                  <?php echo lang('login_password_label', 'password');?>
                  <?php echo form_input($password, '', 'class="form-control"');?>
                </div>
                <div class="checkbox">
                  <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                  <?php echo lang('login_remember_label', 'remember');?>
                  <label class="pull-right"><a href="<?php echo site_url('auth/forgot_password'); ?>"><?php echo lang('login_forgot_password');?></a></label>
                </div>
                <?php echo form_submit('submit', lang('login_submit_btn'), 'class="btn btn-primary btn-flat m-b-30 m-t-30"');?>
              <?php echo form_close();?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>