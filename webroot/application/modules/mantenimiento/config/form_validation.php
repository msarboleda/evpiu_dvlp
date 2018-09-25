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
  )
);