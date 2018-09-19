<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('principal_card_title'); ?></h2>
      <h6 class="card-subtitle"><?php echo lang('principal_card_subtitle'); ?></h6>
      <div class="input-group input-group-default">
        <select class="form-control" id="search_plans">
          <option>...</option>
        </select>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <h2><?php echo lang('last_added_plans'); ?> <span class="badge badge-success">Nuevos</span></h2>
      <div class="row">
        <div class="col-md-2">
          <div class="card mb-4 box-shadow">
            <img class="card-img-top" src="<?php echo site_url('assets/dist/custom/icons/plan.svg'); ?>" alt="Card image cap">
            <div class="card-body">
              <div class="list-group">
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">Product name</h5>
                  <a class="btn btn-link" href="javascript:void(0);" role="button"><i class="fa fa-external-link"></i></a>
                </div>
                <small><i class="fa fa-barcode"></i> Product code</small>
                <small><i class="fa fa-user"></i> Author</small>
                <small><i class="fa fa-calendar"></i> Update date</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>