<?php
  // Turn on output buffering
  ob_start(); 

  // Assign file paths to PHP constants
  define("PRIVATE_PATH", dirname(__FILE__));
  define("PROJECT_PATH", dirname(PRIVATE_PATH));
  define("PUBLIC_PATH", PROJECT_PATH . '/');

  // Assign the root URL to a PHP constant
  $public_end = strpos($_SERVER['SCRIPT_NAME'], '/') + 7;
  $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
  define("WWW_ROOT", $doc_root);

  // Get necessary functions
  require_once('functions.php');
  require_once('db_credential.php');
  require_once('database_functions.php');
  require_once('status_error_functions.php');
  require_once('validation_functions.php');
  
  // -> All classes in directory
  foreach(glob('classes/*.class.php') as $file) {
    require_once($file);
  }

  // Autoload class definitions
  function my_autoload($class) {
    if(preg_match('/\A\w+\Z/', $class)) {
      include('classes/' . $class . '.class.php');
    }
  }
  spl_autoload_register('my_autoload');
  
  // Load SimpleExcel Class
  require_once('SimpleExcel/SimpleExcel.php');

  // Connect to MySQL Database
  $database = db_connect();
  DatabaseObject::set_database($database);

  $session = new Session;
?>