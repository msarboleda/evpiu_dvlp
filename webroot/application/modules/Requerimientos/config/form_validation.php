<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
  'requerimientos/new_request' => array(
    array(
      'field' => 'Cliente',
      'label' => 'lang:NR_validation_customer_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Marca',
      'label' => 'lang:NR_validation_mark_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Parametro',
      'label' => 'lang:NR_validation_param_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Linea',
      'label' => 'lang:NR_validation_line_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Sublinea',
      'label' => 'lang:NR_validation_subline_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Caracteristica',
      'label' => 'lang:NR_validation_feature_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Material',
      'label' => 'lang:NR_validation_material_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Tamano',
      'label' => 'lang:NR_validation_size_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Espesor',
      'label' => 'lang:NR_validation_thickness_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Relieve',
      'label' => 'lang:NR_validation_relief_label',
      'rules' => 'trim|required'
    ),
    array(
      'field' => 'Comentarios',
      'label' => 'lang:NR_validation_comments_label',
      'rules' => 'trim|required'
    ),
  ),
  'applied_art_cond_new_request' => array(
    array(
      'field' => 'base_product',
      'label' => 'lang:NR_validation_base_product_label',
      'rules' => 'trim|required',
    ),
  )
);

$config['new_request_with_applied_art_validation'] = array_merge($config['requerimientos/new_request'], $config['applied_art_cond_new_request']);