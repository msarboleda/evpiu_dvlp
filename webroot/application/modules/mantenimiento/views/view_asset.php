<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('view_asset_card_first_title'); ?></h2>
      <div class="table-responsive m-t-20">
        <table class="table" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th scope="row">Código</th>
              <th>Nombre</th>
              <th>Clasificación</th>
              <th>Responsable</th>
              <th>Estado</th>
              <th>Planta</th>
              <th>Prioridad</th>
              <th>Última Revisión</th>
              <th>Costo Mantenimiento</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $asset->CodActivo; ?></td>
              <td><?php echo $asset->NomActivo; ?></td>
              <td><?php echo $asset->NomClasificacion; ?></td>
              <td><?php echo $asset->NomResponsable; ?></td>
              <td><?php echo $asset->NomEstado; ?></td>
              <td><?php echo $asset->NomPlanta; ?></td>
              <td><?php echo $asset->NomPrioridad; ?></td>
              <td><?php echo $asset->UltimaRevision; ?></td>
              <td><?php echo '$ ' . $asset->CostoMantenimiento . ' COP'; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('view_asset_card_second_title'); ?></h2>
      <hr>
      <div class="form-group">
        <label for="">Ficha Técnica</label>
        <textarea class="form-control" rows="4" readonly><?php echo $asset->FichaTecnica; ?></textarea>
      </div>
      <div class="form-group">
        <label for="">Funcionalidad</label>
        <textarea class="form-control" rows="4" readonly><?php echo $asset->Funcionalidad; ?></textarea>
      </div>
      <h4><?php echo lang('view_asset_card_third_title'); ?></h4>
      <?php
        if (is_string($files)) { ?>
        <div class="alert alert-primary" role="alert"><?php echo $files; ?></div>
      <?php
        } ?>
      <div class="row">
      <?php
        if (is_array($files)) {
        $fq = 1;
        foreach ($files as $file): ?>
        <div class="col-md-2">
          <div class="card mb-4 box-shadow">
      <?php
            if ($file->Extension === '.pdf') { ?>
            <a target="_blank" href="<?php echo site_url('assets/uploads/Mantenimiento/Anexos/'. $file->CodActivo .'/' . $file->NomArchivo); ?>">
              <img src="<?php echo site_url('assets/dist/custom/icons/pdf.svg'); ?>" alt="">
            </a>
      <?php
            } else { ?>
            <a href="<?php echo site_url('assets/uploads/Mantenimiento/Anexos/'. $file->CodActivo .'/' . $file->NomArchivo); ?>" data-lightbox="attached">
              <img src="<?php echo site_url('assets/dist/custom/icons/image.svg'); ?>" alt="">
            </a>
      <?php
            } ?>
            <div class="card-body">
              <div class="list-group">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mt-4">Anexo <?php echo $fq; ?></h5>
                </div>
                <small><i class="fa fa-user"></i> Cargado por: <?php echo $file->Usuario; ?></small>
                <small><i class="fa fa-calendar"></i> Fecha de carga: <?php echo $file->FechaCreacion; ?></small>
              </div>
            </div>
          </div>
        </div>
      <?php
        $fq++;
        endforeach;
        } ?>
      </div>
    </div>
  </div>
</div>