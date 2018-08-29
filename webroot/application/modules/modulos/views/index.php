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
            <th scope="row"><?php echo lang('index_modl_code_th');?></th>
            <th style="width: 16.66%"><?php echo lang('index_modl_name_th');?></th>
            <th style="width: 16.66%"><?php echo lang('index_modl_desc_th');?></th>
            <th style="width: 16.66%"><?php echo lang('index_modl_ruta_th');?></th>
            <th style="width: 16.66%"><?php echo lang('index_modl_icon_th');?></th>
            <th><?php echo lang('index_modl_updt_date_th');?></th>
            <th><?php echo lang('index_modl_action_th');?></th>
          </tr>
        </thead>
        <tbody>
        	<?php foreach ($modulos_list as $modulo):?>
          <tr>
            <td><?php echo htmlspecialchars($modulo->CodModulo,ENT_QUOTES,'UTF-8');?></td>
            <td><?php echo htmlspecialchars($modulo->NomModulo,ENT_QUOTES,'UTF-8');?></td>
            <td><?php echo htmlspecialchars($modulo->Descripcion,ENT_QUOTES,'UTF-8');?></td>
            <td><?php echo $modulo->Ruta; ?></td>
            <td><?php echo $modulo->Icono; ?></td>
            <td><?php echo $modulo->FechaActualizacion; ?></td>
            <td>
            	<a href="<?php echo site_url('modulos/edit_module/').$modulo->id; ?>" class="btn btn-sm btn-primary m-b-10" role="button">Editar</a>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>