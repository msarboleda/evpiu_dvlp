<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
  'activos/edit_asset' => array(
    array(
      'field' => 'cod_activo',
      'label' => 'lang:edit_asset_code_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'nom_activo',
      'label' => 'lang:edit_asset_name_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'clasif_sel',
      'label' => 'lang:edit_asset_classification_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'resp_sel',
      'label' => 'lang:edit_asset_responsible_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'est_sel',
      'label' => 'lang:edit_asset_state_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'plant_sel',
      'label' => 'lang:edit_asset_plant_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'prior_sel',
      'label' => 'lang:edit_asset_priority_label',
      'rules' => 'trim|required'
    )
  ),
  'activos/add_asset' => array(
    array(
      'field' => 'cod_activo',
      'label' => 'lang:add_asset_code_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'nom_activo',
      'label' => 'lang:add_asset_name_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'clasif_sel',
      'label' => 'lang:add_asset_classification_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'resp_sel',
      'label' => 'lang:add_asset_responsible_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'est_sel',
      'label' => 'lang:add_asset_state_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'plant_sel',
      'label' => 'lang:add_asset_plant_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'prior_sel',
      'label' => 'lang:add_asset_priority_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'ult_revis',
      'label' => 'lang:add_asset_last_revision_label',
      'rules' => 'trim|required'
    )
  ),
  'solicitudes/req_maintenance' => array(
    array(
      'field' => 'damage_date',
      'label' => 'lang:new_rm_damage_date_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'damage_time',
      'label' => 'lang:new_rm_damage_time_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'damaged_asset',
      'label' => 'lang:new_rm_damaged_asset_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'damage_description',
      'label' => 'lang:new_rm_damage_description_label',
      'rules' => 'trim|required'
    )
  )
);
