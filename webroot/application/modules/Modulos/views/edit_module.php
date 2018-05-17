<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $message;?>
</div>
<div class="card">
  <div class="card-title">
    <p><?php echo lang('edit_module_subheading');?></p>
  </div>
  <div class="card-body">
    <div class="basic-form">    
      <?php echo form_open(uri_string());?>
        <div class="form-group">
          <?php echo lang('edit_module_code_label', 'CodModulo'); ?> <br />
          <?php echo form_input($CodModulo, '', 'class="form-control"'); ?>
        </div>
        <div class="form-group">
          <?php echo lang('edit_module_name_label', 'NomModulo');?> <br />
          <?php echo form_input($NomModulo, '', 'class="form-control"');?>
        </div>
         <div class="form-group">
          <?php echo lang('edit_module_description_label', 'Descripcion');?> <br />
          <?php echo form_textarea($Descripcion, '', 'class="form-control"');?>
        </div>
        <div class="form-group">
          <?php echo lang('edit_module_route_label', 'Ruta');?> <br />
          <?php echo form_input($Ruta, '', 'class="form-control"');?>
        </div>
        <div class="form-group">
          <?php echo lang('edit_module_icon_label', 'Icono');?> <br />
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="<?php echo $Icono['value']; ?>"></i></span>
            </div>
            <?php echo form_input($Icono, '', 'class="form-control"'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('edit_module_updt_date_label', 'FechaActualizacion'); ?>
          <?php echo form_input($FechaActualizacion, '', 'class="form-control"'); ?>
        </div>
        <?php echo form_submit('submit', lang('edit_module_submit_btn'), 'class="btn btn-primary"');?>
      <?php echo form_close();?>
    </div>
  </div>
</div>