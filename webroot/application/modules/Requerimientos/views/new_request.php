<div id="infoMessage" <?php echo (!isset($message)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $message;?>
</div>
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <div class="basic-form">    
      <?php echo form_open_multipart(uri_string(), 'id="new_request-form" novalidate');?>
        <input type="hidden" class="form-check-input" name="requires_rendering" value="0">
        <input type="hidden" class="form-check-input" name="applied_art" value="0">
        <input type="hidden" name="origen" value="R">
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="enable_vendors">
          <label class="form-check-label" for="enable_vendors"><?php echo lang('NR_confirm_other_vendor_label'); ?></label> <br />
          <small class="text-muted"><?php echo lang('NR_confirm_other_vendor_label_help'); ?></small>
        </div>
        <div class="collapse" id="vendors_collapse">
          <div class="form-group">
            <?php echo lang('NR_other_vendor_label', 'Vendedor'); ?> <br />
            <?php echo form_dropdown(array('name' => 'Vendedor', 'id' => 'Vendedor'), $vendors_select, '', 'class="form-control"'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_customer_label', 'Cliente'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Cliente', 'id' => 'Cliente'), $customers_select, '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_customer_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_customer'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_mark_label', 'Marca'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Marca', 'id' => 'Marca'), array('' => 'Selecciona una Marca'), '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_mark_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('new_request_required_mark'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_param_label', 'Parametro'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Parametro', 'id' => 'Parametro'), $params_select, '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_param_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_param'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_line_label', 'Linea'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Linea', 'id' => 'Linea'), array('' => 'Selecciona una Línea'), '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_line_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_line'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_subline_label', 'Sublinea'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Sublinea', 'id' => 'Sublinea'), array('' => 'Selecciona una Sublínea'), '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_subline_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_subline'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_feature_label', 'Caracteristica'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Caracteristica', 'id' => 'Caracteristica'), array('' => 'Selecciona una Característica'), '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_feature_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_feature'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_material_label', 'Material'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Material', 'id' => 'Material'), array('' => 'Selecciona un Material'), '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_material_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_material'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_size_label', 'Tamano'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Tamano', 'id' => 'Tamano'), array('' => 'Selecciona un Tamaño'), '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_size_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_size'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_thickness_label', 'Espesor'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Espesor', 'id' => 'Espesor'), array('' => 'Selecciona un Espesor'), '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_thickness_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_thickness'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_relief_label', 'Relieve'); ?> <br />
          <?php echo form_dropdown(array('name' => 'Relieve', 'id' => 'Relieve'), array('' => 'Selecciona un Relieve'), '', 'class="form-control" required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_relief_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_relief'); ?>
          </div>
        </div>
        <div class="form-group">
          <?php echo lang('NR_attachments_label', 'Adjuntos'); ?> <br />
          <div class="container_file">
            <div class="content">
              <div class="box">
                <?php echo form_upload('supports[]', '', 'id="supports" class="inputfile inputfile-4" data-multiple-caption="{count} archivos seleccionados" multiple'); ?>
                <label for="supports">
                  <figure>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                      <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/>
                    </svg>
                  </figure>
                  <span>Seleccionar archivo(s)&hellip;</span>
                </label>
              </div>
            </div>
          </div>
          <small class="text-muted"><?php echo lang('NR_attachments_label_help'); ?></small>
        </div>
        <div class="form-group">
          <?php echo lang('NR_comments_label', 'Comentarios'); ?> <br />
          <?php echo form_textarea(array('name' => 'Comentarios', 'id' => 'Comentarios'), '', 'class="form-control" placeholder="..." required="required"'); ?>
          <small class="text-muted"><?php echo lang('NR_comments_label_help'); ?></small>
          <div class="invalid-feedback">
            <?php echo lang('NR_required_comments'); ?>
          </div>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="requires_rendering" name="requires_rendering" value="1">
          <label class="form-check-label" for="requires_rendering"><?php echo lang('NR_requires_rendering_label'); ?></label><br>
          <small class="text-muted"><?php echo lang('NR_requires_rendering_label_help'); ?></small>
        </div>
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="applied_art" name="applied_art" value="1">
          <label class="form-check-label" for="applied_art"><?php echo lang('NR_applied_art_label'); ?></label><br>
          <small class="text-muted"><?php echo lang('NR_applied_art_label_help'); ?></small>
        </div>
        <div class="collapse" id="base_product_collapse">
          <div class="form-group">
            <?php echo lang('NR_base_product_label', 'base_product'); ?> <br>
            <?php echo form_dropdown(array('name' => 'base_product', 'id' => 'base_product'), array('' => 'Selecciona un Producto Base'), '', 'class="form-control"'); ?>
            <small class="text-muted"><?php echo lang('NR_base_product_label_help'); ?></small>
            <div class="invalid-feedback">
              <?php echo lang('NR_required_base_product'); ?>
            </div>
          </div>
        </div>
        <?php echo form_submit('submit', lang('NR_submit_button'), 'id="submit" class="btn btn-primary"');?>
      <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>