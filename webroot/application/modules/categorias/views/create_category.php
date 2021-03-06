<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $message;?>
</div>
<div class="card">
  <div class="card-title">
    <p><?php echo lang('create_category_subheading');?></p>
  </div>
  <div class="card-body">
    <div class="basic-form">    
      <?php echo form_open(uri_string());?>
        <div class="form-group">
          <?php echo lang('create_category_name_label', 'NomCategoria'); ?> <br />
          <?php echo form_input($NomCategoria, '', 'class="form-control"'); ?>
        </div>
        <div class="form-group">
          <?php echo lang('create_category_icon_label', 'Icono'); ?> <br />
          <?php echo form_input($Icono, '', 'class="form-control"'); ?>
        </div>
        <div class="form-group">
          <?php echo lang('create_category_comments_label', 'Comentarios'); ?> <br />
          <?php echo form_textarea($Comentarios, '', 'class="form-control"'); ?>
        </div>
        <?php echo form_submit('submit', lang('create_category_submit_btn'), 'class="btn btn-primary"');?>
      <?php echo form_close();?>
    </div>
  </div>
</div>