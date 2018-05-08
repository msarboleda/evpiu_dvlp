<div class="card">
	<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
    <?php echo $message;?>
  </div>
  <div class="card-title">
		<p><?php echo lang('index_subheading');?></p>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
						<th><?php echo lang('index_fname_th');?></th>
						<th><?php echo lang('index_lname_th');?></th>
						<th><?php echo lang('index_email_th');?></th>
						<th><?php echo lang('index_groups_th');?></th>
						<th><?php echo lang('index_status_th');?></th>
						<th><?php echo lang('index_action_th');?></th>
          </tr>
        </thead>
        <tbody>
        	<?php foreach ($users as $user):?>
          <tr>
            <td><?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?></td>
            <td><?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?></td>
            <td><?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?></td>
            <td>
							<?php foreach ($user->groups as $group):?>
							<?php echo anchor("auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?><br/>
              <?php endforeach?>
						</td>
            <?php if ($user->active === 1) { ?>
            	<td><a href="<?php echo 'auth/deactivate/'.$user->id; ?>"><span class="badge badge-success"><?php echo lang('index_active_link'); ?></span></a></td>
          	<?php } else { ?>
            	<td><a href="<?php echo 'auth/activate/'.$user->id; ?>"><span class="badge badge-danger"><?php echo lang('index_inactive_link'); ?></span></a></td>
            <?php } ?>
            <td>
            	<a href="<?php echo 'auth/edit_user/'.$user->id; ?>" class="btn btn-sm btn-primary m-b-10" role="button">Editar</button>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>