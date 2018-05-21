<?php

/**
 * Dinamic load CSS (header) and JSS (header/footer)
 * @author miguelra
 **/

define('DS', DIRECTORY_SEPARATOR);

if (!function_exists('_getConfig')) {
  function _getConfig() {
    $ci =& get_instance();
    $ci->load->config('load');
    $config = array();
    $config['path_base'] = $ci->config->item('path_base');
    
    return $config;
  }
}

if (!function_exists('add_js')) {
  function add_js($file = '') {
    $str = '';
    $ci = &get_instance();
    $ci->load->config('load');
    
    $footer_js  = $ci->config->item('footer_js');

    if (empty($file)) {
      return;
    }

    if (is_array($file)) {
      if (!is_array($file) && count($file) <= 0) {
        return;
      }

      foreach ($file AS $item) {
        $footer_js[] = $item;
      }

      $ci->config->set_item('footer_js',$footer_js);
    } else {
      $str = $file;
      $footer_js[] = $str;
      $ci->config->set_item('footer_js',$footer_js);
    }
  }
}

if (!function_exists('add_css')){
  function add_css($file = '') {
    $str = '';
    $ci = &get_instance();
    $ci->load->config('load');

    $header_css = $ci->config->item('header_css');

    if (empty($file)){
      return;
    }

    if (is_array($file)) {
      if(!is_array($file) && count($file) <= 0){
        return;
      }

      foreach($file AS $item){   
        $header_css[] = $item;
      }

      $ci->config->set_item('header_css',$header_css);
    } else {
      $str = $file;
      $header_css[] = $str;
      $ci->config->set_item('header_css',$header_css);
    }
  }
}

if (!function_exists('print_additional_css')) {
  function print_additional_css() {
    $str = '';
    $ci = &get_instance();
    $ci->load->config('load');

    $header_css = $ci->config->item('header_css') ? $ci->config->item('header_css') : array();
    $config = _getConfig();

    $path_css = $config['path_base'];

    foreach($header_css AS $file) {
      $str .= '<link href="'.site_url($path_css.DS.$file).'" rel="stylesheet">'."\n";
    }

    return $str;
  }
}

if (!function_exists('print_additional_js')) {
  function print_additional_js() {
    $str = '';
    $ci = &get_instance();
    $ci->load->config('load');

    $footer_js  = $ci->config->item('footer_js') ? $ci->config->item('footer_js') : array();
    $config = _getConfig();

    $path_js = $config['path_base'];

    foreach($footer_js AS $file){
        $str .= '<script src="'.site_url($path_js.DS.$file).'"></script>'."\n";
    }

    return $str;
  }
}