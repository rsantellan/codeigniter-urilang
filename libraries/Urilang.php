<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sets the language specified in the URI-string
 * as the system's default language during runtime.
 *
 * @author	Max Zender <maxzender@gmail.com>
 * @package CodeIgniter
 * @license MIT License
 */
class URILang {

	/**
	 * @var object The CI superobject
	 */
	protected $_ci;

	/**
	 * @var string The new default language's identifier
	 */
	protected $_lang = '';
	
	/**
	 * @var array The supported languages specified in the config file
	 */
	protected $_supported_langs = array();
	
	/**
	 * @var string The language specified in the URI
	 */
	protected $_uri_lang = '';
	
	
	/**
	 * The constructor
	 *
	 * Sets the new default language on initialization of this class
	 */
	public function __construct()
	{
		
		// load superclass
		$this->_ci =& get_instance();
		
		// get lang abbreviations from uri and config file
		$this->_supported_langs = $this->_ci->config->item('supported_languages');
		$this->_uri_lang = $this->_ci->uri->segment(1);
		
		// check if the language defined in the URI is supported / if it is defined
		if ($this->_lang_is_supported($this->_uri_lang))
		{
			// remove old cookie
			$this->_ci->input->set_cookie('pref_lang', null, -1);
			unset($_COOKIE['pref_lang']);
			
			// set the new one
			$this->_ci->input->set_cookie('pref_lang', $this->_uri_lang, $this->_ci->config->item('sess_expiration'));
			
			// set lang to load
			$this->_lang = $this->_uri_lang;
		}
		else
		{
			// defined language is not supported or not specified, check for cookie
			$pref_lang = $this->_ci->input->cookie('pref_lang', true);
			
			if ($pref_lang)
			{
				// cookie found, check if language is supported and pick default language if not
				$this->_lang = ($this->_lang_is_supported($pref_lang)) ? $pref_lang : $this->_get_default_lang();
			}
			else
			{
				// cookie not found, check browser language
				$browser_lang = substr(strtolower($this->_ci->input->server('HTTP_ACCEPT_LANGUAGE', true)), 0, 2);
				
				// if browser language is supported, use it. if not, choose default lang
				$this->_lang = ($this->_lang_is_supported($browser_lang)) ? $browser_lang : $this->_get_default_lang();
			}
		}
		
		// set the detected language as default
		$this->_ci->config->set_item('language', $this->_supported_langs[$this->_lang]);
		
		log_message('debug', "URILang Library Initialized");
	}
	
	/**
	 * Checks if the passed language identifier is supported
	 *
	 * @param	string	The language identifier to be verified
	 * @return	bool	Whether the language is supported or not
	 */
	protected function _lang_is_supported($lang)
	{
		return isset($this->_supported_langs[$lang]);
	}
	
	/**
	 * Determines the default language
	 *
	 * @return	string	The identifier of the default language
	 */
	protected function _get_default_lang()
	{
			// check if default language is specified in supported languages
		if (in_array($this->_ci->config->item('language'), $this->_supported_langs))
		{
			$default_lang = array_search($this->_ci->config->item('language'), $this->_supported_langs, true);
		}
		else
		{
			// fall back to english
			$default_lang = 'en';
		}
		
		return $default_lang;
	}
	
	/**
	 * Builds an array containing all supported languages (led by the current language),
	 * which can be used to build a language selector
	 * 
	 * @return	array	The array mentioned above
	 */
	public function get_lang_array()
	{
		$default_lang = array($this->_lang => $this->_supported_langs[$this->_lang]);
		return $default_lang + $this->_supported_langs;
	}

}

/* End of file */