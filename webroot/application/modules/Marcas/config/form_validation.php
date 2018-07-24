<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
  'marcas/new_mark' => array(
    array(
      'field' => 'Nombre',
      'label' => 'lang:NM_validation_name_label',
      'rules' => "trim|required|regex_match[#(^[A-Za-z])[\w\s\'\&\%\+\-\.\`\$\\\/\(\)]*$#]"
    ),
  ),
);