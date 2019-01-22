<?php if (isset($messages)): ?>
  <?php if (!empty($messages)): ?>
    <?php foreach($messages as $msg_type => $message): ?>
      <?php foreach($message as $msg_content): ?>
        <div class="alert alert-<?php echo $msg_type; ?>" role="alert">
          <?php echo $msg_content; ?>
        </div>
      <?php endforeach; ?>
    <?php endforeach; ?>
  <?php endif; ?>
<?php endif; ?>
<?php if (!$customer_data_empty): ?>
  <div class="col-lg-12">
    <div class="row">
      <div class="col-lg-6">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-title">
              <h2>
                <?php echo $customer->NAME_23; ?>
                <?php if ($customer->STATUS_23 === 'R'): ?>
                <span class="badge badge-success"><?php echo $customer->R_STATUS_23; ?></span>
                <?php elseif ($customer->STATUS_23 === 'H'): ?>
                <span class="badge badge-danger"><?php echo $customer->R_STATUS_23; ?></span>
                <?php endif; ?>
              </h2>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4">
                  <small class="text-muted"><?php echo lang('customer_id_label'); ?></small><br>
                  <?php if (!empty($customer->CUSTID_23)): ?>
                  <?php echo $customer->CUSTID_23; ?>
                  <?php else: ?>
                  <span class="color-danger">Sin proporcionar</span><br>
                  <?php endif; ?>
                </div>
                <div class="col-lg-4">
                  <small class="text-muted"><?php echo lang('customer_nit_label'); ?></small><br>
                  <?php if (!empty($customer->UDFKEY_23)): ?>
                  <?php echo $customer->UDFKEY_23; ?>
                  <?php else: ?>
                  <span class="color-danger">Sin proporcionar</span><br>
                  <?php endif; ?>
                </div>
                <div class="col-lg-4">
                  <small class="text-muted"><?php echo lang('customer_type_label'); ?></small><br>
                  <?php if (!empty($customer->CUSTYP_23)): ?>
                  <?php echo $customer->CUSTYP_23; ?>
                  <?php else: ?>
                  <span class="color-danger">Sin proporcionar</span><br>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-title">
              <h2>Contacto</h2>
            </div>
            <div class="card-body">
              <div class="basic-form">
                <div class="row">
                  <div class="col-lg-6">
                    <small class="text-muted"><?php echo lang('customer_contact_label'); ?></small><br>
                    <?php if (!empty($customer->CNTCT_23)): ?>
                    <?php echo $customer->CNTCT_23; ?><br>
                    <?php else: ?>
                    <span class="color-danger">Sin proporcionar</span><br>
                    <?php endif; ?>
                  </div>
                  <div class="col-lg-6">
                    <small class="text-muted"><?php echo lang('customer_email_label'); ?></small><br>
                    <?php if (!empty($customer->EMAIL1_23)): ?>
                    <?php echo $customer->EMAIL1_23; ?><br>
                    <?php else: ?>
                    <span class="color-danger">Sin proporcionar</span><br>
                    <?php endif; ?>
                  </div>
                  <div class="col-lg-6">
                    <small class="text-muted"><?php echo lang('customer_phone_label'); ?></small><br>
                    <?php if (!empty($customer->PHONE_23)): ?>
                    <?php echo $customer->PHONE_23; ?><br>
                    <?php else: ?>
                    <span class="color-danger">Sin proporcionar</span><br>
                    <?php endif; ?>
                  </div>
                  <div class="col-lg-6">
                    <small class="text-muted"><?php echo lang('customer_mobile_label'); ?></small><br>
                    <?php if (!empty($customer->TELEX_23)): ?>
                    <?php echo $customer->TELEX_23; ?><br>
                    <?php else: ?>
                    <span class="color-danger">Sin proporcionar</span><br>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-title">
              <h2>Ubicación</h2>
            </div>
            <div class="card-body">
              <div class="basic-form">
                <div class="row">
                  <div class="col-lg-12">
                    <small class="text-muted">Dirección principal</small><br>
                    <?php if (!empty($customer->ADDR1_23)): ?>
                    <?php echo $customer->ADDR1_23; ?><br>
                    <?php else: ?>
                    <span class="color-danger">Sin proporcionar</span><br>
                    <?php endif; ?>
                    <?php if (!empty($customer->ADDR2_23)): ?>
                    <?php echo $customer->ADDR2_23; ?><br>
                    <?php else: ?>
                    <span class="color-danger">Sin proporcionar</span><br>
                    <?php endif; ?>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-lg-4">
                    <small class="text-muted">Ciudad</small><br>
                    <?php if (!empty($customer->ADDR1_23)): ?>
                    <?php echo $customer->CITY_23; ?>
                    <?php else: ?>
                    <span class="color-danger">Sin proporcionar</span><br>
                    <?php endif; ?>
                  </div>
                  <div class="col-lg-4">
                    <small class="text-muted">Departamento</small><br>
                    <?php if (!empty($customer->STATE_23)): ?>
                    <?php echo $customer->STATE_23; ?>
                    <?php else: ?>
                    <span class="color-danger">Sin proporcionar</span><br>
                    <?php endif; ?>
                  </div>
                  <div class="col-lg-4">
                    <small class="text-muted">País</small><br>
                    <?php if (!empty($customer->CNTRY_23)): ?>
                    <?php echo $customer->CNTRY_23; ?>
                    <?php else: ?>
                    <span class="color-danger">Sin proporcionar</span><br>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <?php echo form_open(uri_string(), 'id="update_customer-form"');?>
        <div class="card">
          <div class="card-title">
            <h2>Actualización de datos</h2>
          </div>
          <div class="card-body">
            <div class="basic-form">
              <div class="form-group">
                <?php echo form_label( lang('customer_email_label'), 'customer_email' ); ?> <small class="color-danger">*</small><br>
                <?php echo form_input( 'customer_email', $customer->EMAIL1_23 , 'class="form-control"' ); ?>
                <?php echo form_error( 'customer_email' ); ?>
              </div>
              <div class="form-group">
                <?php echo form_label( lang('customer_contact_label'), 'customer_contact' ); ?> <small class="text-muted">(opcional)</small><br>
                <?php echo form_input( 'customer_contact', $customer->CNTCT_23 , 'class="form-control"' ); ?>
                <?php echo form_error( 'customer_contact' ); ?>
                <small class="text-muted"><?php echo lang('customer_contact_help'); ?></small>
              </div>
              <div class="form-group">
                <?php echo form_label( lang('customer_phone_label'), 'customer_phone' ); ?> <small class="text-muted">(opcional)</small><br>
                <?php echo form_input( 'customer_phone', $customer->PHONE_23 , 'class="form-control"' ); ?>
                <?php echo form_error( 'customer_phone' ); ?>
              </div>
              <div class="form-group">
                <?php echo form_label( lang('customer_mobile_label'), 'customer_mobile' ); ?> <small class="color-danger">*</small><br>
                <?php echo form_input( 'customer_mobile', $customer->TELEX_23 , 'class="form-control"' ); ?>
                <?php echo form_error( 'customer_mobile' ); ?>
              </div>
              <?php echo form_submit( 'update_action', 'Actualizar', 'class="btn btn-primary"' ); ?>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
<?php endif; ?>
