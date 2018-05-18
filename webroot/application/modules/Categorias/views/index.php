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
            <th scope="row"><?php echo lang('index_categ_code_th');?></th>
            <th><?php echo lang('index_categ_name_th');?></th>
            <th><?php echo lang('index_categ_icon_th');?></th>
            <th><?php echo lang('index_categ_comments_th');?></th>
            <th><?php echo lang('index_categ_action_th');?></th>
          </tr>
        </thead>
        <tbody>
        	<?php foreach ($categories_list as $category):?>
          <tr>
            <td><?php echo $category->CodCategoria; ?></td>
            <td><?php echo htmlspecialchars($category->NomCategoria,ENT_QUOTES,'UTF-8'); ?></td>
            <td><?php echo $category->Icono; ?></td>
            <td><?php echo htmlspecialchars($category->Comentarios,ENT_QUOTES,'UTF-8'); ?></td>
            <td>
              <a href="<?php echo site_url('categorias/edit_category/').$category->id; ?>" class="btn btn-sm btn-primary m-b-10" role="button">Editar</a>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>