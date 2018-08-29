<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $message;?>
</div>
<div class="card">
  <div class="card-title">
		<p><?php echo lang('index_subheading');?></p>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
						<th><?php echo lang('groups_id_th');?></th>
						<th><?php echo lang('groups_name_th');?></th>
						<th><?php echo lang('groups_description_th');?></th>
						<th><?php echo lang('groups_action_th');?></th>
          </tr>
        </thead>
        <tbody>
        	<?php foreach ($groups as $group):?>
          <tr>
            <td scope="row"><?php echo $group->id; ?></td>
            <td><?php echo htmlspecialchars($group->name,ENT_QUOTES,'UTF-8');?></td>
            <td><?php echo htmlspecialchars($group->description,ENT_QUOTES,'UTF-8');?></td>
            <td><a href="<?php echo site_url('auth/edit_group/').$group->id; ?>" class="btn btn-sm btn-primary m-b-10" role="button">Editar</a></td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>