<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Site URL
 * Used when creating internal anchors, translates a uri into the current language
 */
function site_url($uri, $lang = FALSE) {
	$CI =& get_instance();
	$CI->load->config('language');
	
    
	if(!is_array($uri)) {
		$uri = explode('?', $uri);
		$query = isset($uri[1]) ? '?'.$uri[1] : '';
		$uri = explode('/', ltrim($uri[0], '/'));
	}
	
	if(!$lang)
    {
      $lang = $CI->config->item('language');
      $use_session = $CI->config->item('use_session');
      if(!$use_session)
      {
        // defined language is not supported or not specified, check for cookie
        $lang = $CI->input->cookie('pref_lang', true);
      }
      else
      {
        // defined language is not supported or not specified, check for session
        $lang = $CI->session->userdata('pref_lang');
      }
    }
    
	//$lang_code = array_search($lang, $CI->config->item('language_codes'));
	//var_dump($lang);
    array_unshift($uri, $lang);
	return $CI->config->site_url($uri).$query;
}

/**
 * Language Menu
 * Returns a unordered list of links to switch languages
 * @param	$class Class of the list 
 * @return	string $links The list of language links
 */
function language_menu($class = "") {
	$CI =& get_instance();
	$CI->load->config('language');
	$CI->load->helper('html');
	
	$languages = $CI->config->item('supported_languages');
	$page = $CI->uri->uri_string() ? $CI->uri->uri_string() : $CI->router->default_controller;
    if($page != $CI->router->default_controller)
    {
      $segment_array = $CI->uri->segment_array();
      unset($segment_array[1]);
      $page = implode("/", $segment_array);
    }
	$links = array();
	foreach($languages as $lang => $name)
	{
		if($lang != $CI->config->item('language'))
		{
			$links[] = anchor(site_url($page, $lang), $name);
		}
	}
	return ul($links, array('class'=>$class));
}

/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */