#CodeIgniter-URILang
Sets the language defined in the URI as the system's default language during runtime.

##How does it do this?
1. It checks for a language identifier in the URI. If that is found, a cookie containing that identifier is being set for later use (see next point).
2. If no language identifier is set, it checks for that cookie and uses the contained identifier.
3. If neither the identifier nor the cookie can be found, it checks for the browser's default language.
4. If even this fails, it falls back to English.

##Installation
Note that you should load URILang __before__ you use `$this->lang->line()` or `lang()` anywhere in your application!

If you use sparks:

    $this->load->spark('urilang/1.0.1');


If you don't use sparks:

    $this->load->library('urilang');


Then add the following routes to your config/routes.php:

    $route['(\w{2})/(.*)'] = '$2';
    $route['(\w{2})'] = $route['default_controller'];

Specify the supported languages in config/urilang.php:

    $config['supported_languages'] = array('en' => 'english', 'fr' => 'french', 'es' => 'spanish', 'de' => 'german');

Now load the language file just like you normally would and you're all set!

    $this->lang->load('your_language_file');
