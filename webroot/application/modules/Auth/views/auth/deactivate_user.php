<div class="card">
	<div class="card-title">
		<p><?php echo sprintf(lang('deactivate_subheading'), $user->username);?></p>
  </div>
  <div class="card-body">
    <div class="basic-form">    
			<?php echo form_open("auth/deactivate/".$user->id);?>
        <div class="form-group">
          <?php echo lang('deactivate_confirm_y_label', 'confirm');?>
			    <input type="radio" name="confirm" value="yes" checked="checked" />
			    <?php echo lang('deactivate_confirm_n_label', 'confirm');?>
			    <input type="radio" name="confirm" value="no" />
        </div>
        <?php echo form_hidden($csrf); ?>
  			<?php echo form_hidden(array('id'=>$user->id)); ?>
        <?php echo form_submit('submit', lang('deactivate_submit_btn'), 'class="btn btn-primary"');?>
      <?php echo form_close();?>
    </div>
  </div>
</div>