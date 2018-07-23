<div class="col-12">
  <div class="card">
    <div class="card-body">
      <div class="basic-form">    
      <?php echo form_open(uri_string(), 'id="new_mark-form" novalidate');?>
        <div class="form-group">
          <?php echo lang('NM_name_label', 'Nombre'); ?> <br />
          <input type="text" name="Nombre" id="Nombre" class="form-control" required="required">
          <div class="invalid-feedback">
            <?php echo lang('NM_required_name'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NM_comments_label', 'Comentarios'); ?> <br />
          <textarea name="Comentarios" id="Comentarios" class="form-control" placeholder="..."></textarea>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="is_generic" name="is_generic" value="0">
          <label class="form-check-label" for="is_generic"><?php echo lang('NM_is_generic_label'); ?></label><br>
          <small class="text-muted"><?php echo lang('NM_is_generic_label_help'); ?></small>
        </div>
      <?php echo form_submit('submit', lang('NM_submit_button'), 'id="submit" class="btn btn-primary"');?>
      <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>