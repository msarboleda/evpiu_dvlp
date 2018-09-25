<div id="infoMessage" <?php echo (!isset($valid_errors)) ? '' : 'class="alert alert-primary" role="alert"'; ?>>
  <?php echo $valid_errors;?>
</div>

<?php
  if (isset($app_errors)) {
    if (!empty($app_errors)) {
      foreach($app_errors as $type=>$msgs) {
        foreach($msgs as $msg) {
          echo "<div class='alert alert-$type' role='alert'>";
          echo $msg;
          echo "</div>";
        }
      }
    }
  }
?>

<div class="col-12">
  <?php echo form_open_multipart(uri_string(), 'id="add_asset-form"');?>
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <h2><?php echo lang('add_asset_card_first_title'); ?></h2>
            <div class="form-group">
              <?php echo lang('add_asset_code_label', 'cod_activo'); ?>
              <input type="text" class="form-control" name="cod_activo">
            </div>
            <div class="form-group">
              <?php echo lang('add_asset_name_label', 'nom_activo'); ?>
              <input type="text" class="form-control" name="nom_activo">
            </div>
            <div class="form-group">
              <?php echo lang('add_asset_classification_label', 'clasif_sel'); ?>
              <select class="form-control" id="clasif_sel" name="clasif_sel">
                <option></option>
              <?php
                if (!empty($classifications)) {
                  foreach ($classifications as $classification): ?>
                    <option value="<?php echo $classification->idClasificacion; ?>"><?php echo $classification->Nombre; ?></option>
              <?php
                  endforeach;
                } ?>
              </select>
            </div>
            <div class="form-group">
              <?php echo lang('add_asset_responsible_label', 'resp_sel'); ?>
              <br>
              <select class="form-control" id="resp_sel" name="resp_sel">
                <option></option>
              <?php
                if (!empty($responsibles)) {
                  foreach ($responsibles as $responsible): ?>
                    <option value="<?php echo $responsible->username; ?>"><?php echo $responsible->Nombre; ?></option>
              <?php
                  endforeach;
                } ?>
              </select>
            </div>
            <div class="form-group">
              <?php echo lang('add_asset_state_label', 'est_sel'); ?>
              <br>
              <select class="form-control" id="est_sel" name="est_sel">
                <option></option>
              <?php
                if (!empty($states)) {
                  foreach ($states as $state): ?>
                    <option value="<?php echo $state->idEstado; ?>"><?php echo $state->NombreEstado; ?></option>
              <?php
                  endforeach;
                } ?>
              </select>
            </div>
            <div class="form-group">
              <?php echo lang('add_asset_plant_label', 'plant_sel'); ?>
              <br>
              <select class="form-control" id="plant_sel" name="plant_sel">
                <option></option>
              <?php
                if (!empty($plants)) {
                  foreach ($plants as $plant): ?>
                    <option value="<?php echo $plant->idPlanta; ?>"><?php echo $plant->NombrePlanta; ?></option>
              <?php
                  endforeach;
                } ?>
              </select>
            </div>
            <div class="form-group">
              <?php echo lang('add_asset_priority_label', 'prior_sel'); ?>
              <br>
              <select class="form-control" id="prior_sel" name="prior_sel">
                <option></option>
              <?php
                if (!empty($priorities)) {
                  foreach ($priorities as $priority): ?>
                    <option value="<?php echo $priority->idPrioridad; ?>"><?php echo $priority->NombrePrioridad; ?></option>
              <?php
                  endforeach;
                } ?>
              </select>
            </div>
            <div class="form-group">
              <?php echo lang('add_asset_last_revision_label', 'ult_revis'); ?>
              <input type="text" class="form-control" id="ult_revis" name="ult_revis">
            </div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card">
          <div class="card-body">
            <h2><?php echo lang('add_asset_card_second_title'); ?></h2>
            <div class="form-group">
              <?php echo lang('add_asset_data_sheet_label', 'ficha_tecnica'); ?>
              <textarea class="form-control" name="ficha_tecnica" rows="4"></textarea>
            </div>
            <div class="form-group">
              <?php echo lang('add_asset_functionality_label', 'funcionalidad'); ?>
              <textarea class="form-control" name="funcionalidad" rows="4"></textarea>
            </div>
            <div class="form-group">
              <?php echo lang('add_asset_attach_documents_label', 'attach'); ?>
              <div class="container_file">
                <div class="content">
                  <div class="box">
                    <?php echo form_upload('attach[]', '', 'id="attach" class="inputfile inputfile-4" data-multiple-caption="{count} archivos seleccionados" multiple'); ?>
                    <label for="attach">
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
            </div>
          </div>
          <div class="text-right">
          <?php echo form_submit('submit', lang('add_asset_submit_button'), 'id="submit" class="btn btn-primary"');?>
          </div>
        </div>
      </div>
    </div>
  <?php echo form_close();?>
</div>
