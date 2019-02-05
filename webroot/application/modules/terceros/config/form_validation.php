<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
  'clientes/update_customer' => array(
    array(
      'field' => 'customer_email',
      'label' => 'lang:fv_customer_email_label',
      'rules' => 'trim|required|valid_email|max_length[200]'
    ),
    array(
      'field' => 'customer_contact',
      'label' => 'lang:fv_customer_contact_label',
      'rules' => 'trim|max_length[20]'
    ),
    array(
      'field' => 'customer_phone',
      'label' => 'lang:fv_customer_phone_label',
      'rules' => 'trim|min_length[7]|max_length[20]'
    ),
    array(
      'field' => 'customer_mobile',
      'label' => 'lang:fv_customer_mobile_label',
      'rules' => 'trim|required|min_length[10]|max_length[20]'
    ),
    array(
      'field' => 'customer_address_one',
      'label' => 'lang:fv_customer_address_one_label',
      'rules' => 'trim|required|max_length[64]'
    ),
    array(
      'field' => 'customer_address_two',
      'label' => 'lang:fv_customer_address_two_label',
      'rules' => 'trim|max_length[64]'
    ),
  ),
);
