<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$route['(\w{2})/(.*)'] = '$2';
$route['(\w{2})'] = $route['default_controller'];
